<?php

namespace App\Jobs;

use App\Notifications\MailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\EmailForQueuing;
use Illuminate\Support\Facades\Log;
use Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $userData;
    protected $message;
    protected $page;
    protected $data;

    public function __construct($userData,$page,$data,$message = '')
    {
        $this->page = $page;
        $this->data = $data;
        $this->userData = $userData;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new EmailForQueuing($this->userData,$this->page,$this->data,$this->message);
        Mail::to($this->userData['email'])->send($email);
    }
}
