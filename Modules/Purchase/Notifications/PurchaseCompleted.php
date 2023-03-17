<?php

namespace Modules\Purchase\Notifications;

use App\UserNotificationPayload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Notification;
use Modules\Purchase\Entities\Purchase;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class PurchaseCompleted extends Notification
{
    use Queueable;

    public function __construct(protected Purchase $purchase)
    {
    }

    public function via(Authenticatable $notifiable): array
    {
        return [FcmChannel::class, 'database'];
    }

    public function toFcm(Authenticatable $notifiable): FcmMessage
    {
        return FcmMessage::create()
            ->setNotification(notification: FcmNotification::create()
                ->setTitle($this->title())
                ->setBody($this->body())
                ->setImage(UserNotificationPayload::APP_LOGO));
    }

    public function toArray(Authenticatable $notifiable): array
    {
        return (new UserNotificationPayload())
            ->title($this->title())
            ->body($this->body())
            ->success()
            ->toArray();
    }

    private function title(): string
    {
        return "Kitob sotib olindi!";
    }

    private function body(): string
    {
        return "{$this->purchase->book_data['title']} nomli kitob sotib olindi!";
    }
}
