<?php

namespace Modules\Book\Listeners;

use Modules\User\Enums\UserDegree;

class UpdateUserDegree
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $bookReadCount = $event->reader->readBooks()->count();

        $event->reader->update([
            'degree' => UserDegree::getDegreeFromBookCount($bookReadCount)
        ]);
    }
}
