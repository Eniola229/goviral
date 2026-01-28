<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserLoggedIn extends Notification
{
    use Queueable;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Welcome back, ' . $notifiable->name,
            'link' => route('dashboard'),
            'icon' => 'log-in',
            'logged_in_at' => now()->toDateTimeString(),
        ];
    }
}