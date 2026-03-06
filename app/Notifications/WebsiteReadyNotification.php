<?php

namespace App\Notifications;

use App\Models\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WebsiteReadyNotification extends Notification
{
    use Queueable;

    public function __construct(public Website $website) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $siteUrl = $this->website->url ?: 'http://localhost/' . $this->website->subdomain;

        return (new MailMessage)
            ->subject("Your website {$this->website->name} is live!")
            ->greeting("Great news, {$notifiable->name}!")
            ->line("Your website **{$this->website->name}** has been built and is now live.")
            ->action('View Your Website', $siteUrl)
            ->line('You can manage your website from the dashboard, including accessing WordPress Admin.')
            ->line('Thank you for using Webnewbiz!');
    }
}
