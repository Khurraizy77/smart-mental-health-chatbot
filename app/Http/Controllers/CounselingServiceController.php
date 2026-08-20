<?php

namespace App\Http\Controllers;

use App\Mail\CounsellingReferralRequested;
use App\Models\CounselingService;
use App\Models\Referral;
use App\Models\WellbeingAssessment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CounselingServiceController extends Controller
{
    public function index(): View
    {
        $services = CounselingService::latest()->get();

        $referrals = Referral::with('service')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $assessments = WellbeingAssessment::where('user_id', Auth::id())
            ->latest()
            ->limit(5)
            ->get();

        $questions = WellbeingAssessmentController::questions();

        return view('counseling.index', compact('services', 'referrals', 'assessments', 'questions'));
    }

    public function requestReferral(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_id' => ['required', 'exists:counseling_services,service_id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $referral = DB::transaction(function () use ($validated): Referral {
                return Referral::create([
                    'user_id' => Auth::id(),
                    'service_id' => $validated['service_id'],
                    'status' => 'pending',
                    'notes' => $validated['notes'] ?? null,
                ]);
            });
        } catch (\Throwable $exception) {
            Log::error('Referral request failed.', [
                'user_id' => Auth::id(),
                'message' => $exception->getMessage(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'service_id' => 'The referral request could not be submitted. Please try again.',
                ]);
        }

        $emailSent = $this->emailCounsellor($referral);

        return redirect()
            ->route('counseling.index')
            ->with(
                $emailSent ? 'success' : 'warning',
                $emailSent
                    ? 'Counselling request submitted and emailed to the counsellor. They can contact you to arrange an appointment.'
                    : 'Counselling request submitted. The counsellor email could not be sent yet, but your request is saved for admin follow-up.'
            );
    }

    private function emailCounsellor(Referral $referral): bool
    {
        $referral->load(['service', 'user.studentProfile']);
        $counsellorEmail = $referral->service?->counsellor_email;

        if (blank($counsellorEmail)) {
            Log::warning('Counselling referral has no counsellor email configured.', [
                'referral_id' => $referral->referral_id,
                'service_id' => $referral->service_id,
            ]);

            return false;
        }

        try {
            Mail::to($counsellorEmail)->send(new CounsellingReferralRequested($referral));

            return true;
        } catch (\Throwable $exception) {
            Log::error('Counselling referral email failed.', [
                'referral_id' => $referral->referral_id,
                'counsellor_email' => $counsellorEmail,
                'message' => $exception->getMessage(),
            ]);

            return false;
        }
    }
}
