<?php

namespace Modules\Book\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\User\Entities\User;

class UserReadBooksCountChanged
{
    use SerializesModels, Dispatchable, InteractsWithSockets;

    public User $reader;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
