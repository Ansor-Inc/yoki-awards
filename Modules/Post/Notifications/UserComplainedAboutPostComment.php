<?php

namespace Modules\Post\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\User\Entities\Complaint;

class UserComplainedAboutPostComment extends Notification
{
    use Queueable;

    protected Complaint $complaint;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Complaint $complaint)
    {
        $this->complaint = $complaint;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'body' => view('post::user-complain-notification', [
                'user' => $this->complaint->owner,
                'complaint' => $this->complaint
            ])->render()
        ];
    }
}
