<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to Webnewbiz!')
            ->greeting("Hi {$notifiable->name},")
            ->line('Welcome to Webnewbiz! We\'re excited to help you build your online presence with AI.')
            ->line('Here\'s how to get started:')
            ->line('1. Describe your business in a few sentences')
            ->line('2. Choose a style and customize your preferences')
            ->line('3. Let our AI build your professional website in minutes')
            ->action('Build Your First Website', route('builder.index'))
            ->line('If you have any questions, we\'re here to help!');
    }
}
