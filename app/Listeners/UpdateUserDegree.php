<?php

namespace App\Listeners;

use Modules\User\Providers\BookMarkedAsCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
    public function handle(BookMarkedAsCompleted $event)
    {
        
    }
}
