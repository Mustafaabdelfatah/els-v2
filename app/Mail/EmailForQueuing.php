<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EmailForQueuing extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $userData;
    protected $page;
    protected $data;
    protected $msg;

    public function __construct($userData,$page,$data,$msg)
    {
        $this->userData = $userData;
        $this->page = $page;
        $this->data = $data;
        $this->msg = $msg;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        log::info(['message is :' => $this->msg]);
        return $this->from(env('MAIL_FROM_ADDRESS','m.hamdi@wakeb.tech'), env('MAIL_FROM_NAME','legal Department'))
            ->subject('subject from legal department')
            ->view('mails.email',['userData' => $this->userData , 'page' => $this->page ,'data' => $this->data ,'msg' => $this->msg]);
    }
}
