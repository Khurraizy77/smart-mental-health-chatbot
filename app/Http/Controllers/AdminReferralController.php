<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminReferralController extends Controller
{
    public function index(): View
    {
        $referrals = Referral::with(['user.studentProfile', 'service'])
            ->latest()
            ->paginate(10);

        return view('admin.referrals.index', compact('referrals'));
    }

    public function update(Request $request, Referral $referral): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,contacted,completed,cancelled'],
        ]);

        $referral->update($validated);

        return redirect()
            ->route('admin.referrals.index')
            ->with('success', 'Referral status updated.');
    }
}
