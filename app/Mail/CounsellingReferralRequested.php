<?php

namespace App\Mail;

use App\Models\Referral;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CounsellingReferralRequested extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Referral $referral)
    {
    }

    public function build(): self
    {
        $studentName = $this->referral->user?->name ?? 'Student';

        return $this
            ->subject("New counselling support request from {$studentName}")
            ->view('emails.counselling-referral-requested');
    }
}
