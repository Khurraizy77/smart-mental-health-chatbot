<?php

namespace App\Http\Controllers;

use App\Models\WellbeingAssessment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminAssessmentController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'priority' => ['nullable', 'in:Normal,Moderate,High,Urgent,pending'],
            'status' => ['nullable', 'in:pending,reviewed,flagged,closed'],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $assessments = WellbeingAssessment::with(['user.studentProfile', 'reviewer'])
            ->when($filters['priority'] ?? null, function ($query, string $priority): void {
                if ($priority === 'pending') {
                    $query->whereNull('ai_priority_level');

                    return;
                }

                $query->where('ai_priority_level', $priority);
            })
            ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('review_status', $status))
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->whereHas('user', function ($userQuery) use ($search): void {
                    $userQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('studentProfile', fn ($studentQuery) => $studentQuery->where('matric_no', 'like', "%{$search}%"));
                });
            })
            ->orderByRaw("
                CASE
                    WHEN ai_priority_level = 'Urgent' THEN 1
                    WHEN urgent_support = 1 THEN 2
                    WHEN ai_priority_level = 'High' THEN 3
                    WHEN review_status = 'flagged' THEN 4
                    WHEN ai_priority_level = 'Moderate' THEN 5
                    ELSE 6
                END
            ")
            ->latest('created_at')
            ->paginate(10);

        return view('admin.assessments.index', [
            'assessments' => $assessments->withQueryString(),
            'filters' => $filters,
        ]);
    }

    public function update(Request $request, WellbeingAssessment $assessment): RedirectResponse
    {
        $validated = $request->validate([
            'review_status' => ['required', 'in:pending,reviewed,flagged,closed'],
            'counsellor_notes' => ['nullable', 'string', 'max:1500'],
        ]);

        $reviewData = $validated;

        if (in_array($validated['review_status'], ['reviewed', 'closed'], true)) {
            $reviewData['reviewed_by'] = Auth::id();
            $reviewData['reviewed_at'] = now();
        }

        if (in_array($validated['review_status'], ['pending', 'flagged'], true)) {
            $reviewData['reviewed_by'] = null;
            $reviewData['reviewed_at'] = null;
        }

        $assessment->update($reviewData);

        return redirect()
            ->back()
            ->with('success', 'Assessment review updated.');
    }
}
