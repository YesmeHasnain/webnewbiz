<?php

namespace App\Notifications;

use App\Models\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WebsiteBuildFailedNotification extends Notification
{
    use Queueable;

    public function __construct(public Website $website) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Website build failed')
            ->greeting("Hi {$notifiable->name},")
            ->line("Unfortunately, we encountered an issue while building your website **{$this->website->name}**.")
            ->line('Our system will attempt to resolve this automatically. You can also retry the build from your dashboard.')
            ->action('Go to Dashboard', route('dashboard'))
            ->line('We apologize for the inconvenience.');
    }
}
