<?php

namespace Modules\Group\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\Group\Entities\Group;

class GroupCreated extends Notification
{
    use Queueable;

    protected Group $group;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'body' => '',
        ];
    }
}
