<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Jobs\SendEmail;
use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class MailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $page;
    protected $data;
    protected $message;

    public function __construct($page = '', $data = [], $message = '')
    {
        $this->page = $page;
        $this->data = $data;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
//        return explode(',', $notifiable->notification_perefared);
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(env('MAIL_FROM_ADDRESS', 'noreplay@ncms.sa'), env('MAIL_FROM_NAME', 'NCMS - Legal Department'))
            ->subject('subject from legal department')
            ->view('mails.email', ['userData' => $notifiable, 'page' => $this->page, 'data' => $this->data, 'msg' => $this->message]);
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable): array
    {
        $data = [
            'title'            => $this->page,
            'message'          => $this->message,
            'from_user_name'   => $this->data['user']['name'],
            'from_user_email'  => $this->data['user']['email'],
            'from_user_avatar' => $this->data['user']['avatar'],
            'id'               => $notifiable->id,
            'name'             => $notifiable->name,
            'email'            => $notifiable->email,
            'phone'            => $notifiable->phone,
            'type'             => $notifiable->type,
        ];
        return $data;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
