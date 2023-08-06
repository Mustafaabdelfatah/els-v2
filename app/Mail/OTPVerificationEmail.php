<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OTPVerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $name;
    public $resetLink;


    /**
     * Create a new message instance.
     */
    public function __construct($otp,$name,$resetLink)
    {
        $this->otp = $otp;
        $this->name = $name;
        $this->resetLink = $resetLink;

    }

    /**
     * Get the message envelope.
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Your Ligel Dashboard Reset Code',
        );
    }

    public function build() {
        return $this->subject('OTP Verification')->view('mails.otp');
    }


}
