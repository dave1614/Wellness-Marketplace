<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MerchantRegistered extends Notification
{
    use Queueable;

    public User $user;

    public $greeting;
    public $subject;
    public $first_message;
    public $closing_message;
    public $action_button;

    public $from;
    public $to;

    public $sms_cost;


    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;

        $this->from = env('APP_NAME');
        $this->to = $user->name;

        $this->greeting = 'Good day ' . $user->name;
        $this->subject = 'Merchant Registration Confirmation';
        $this->first_message = 'You have just registered your merchant account with us. Click the button below to access your dashboard.';

        $this->closing_message = 'Thank you for using ' . env('APP_NAME') . '!';
        $this->action_button = [['Dashboard', url(route('admin_or_merchant.dashboard'))]];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $methods = ['database'];
        if ($notifiable->email_enabled) {
            $methods[] = 'mail';
        }

        // if ($notifiable->sms_enabled && $notifiable->sms_credits >= $this->sms_cost) {

        //     $this->user->sms_credits = $notifiable->sms_credits - $this->sms_cost;
        //     $this->user->save();
        //     $methods[] = 'vonage';
        // }
        return $methods;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting($this->greeting)
            ->subject($this->subject)
            ->line($this->first_message)
            ->action($this->action_button[0][0], $this->action_button[0][1])
            ->line($this->closing_message);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'from' => $this->from,
            'to' => $this->to,
            'greeting' => $this->greeting,
            'subject' => $this->subject,
            'first_message' => $this->first_message,
            'action_button' => $this->action_button,
            'closing_message' => $this->closing_message,
        ];
    }
}
