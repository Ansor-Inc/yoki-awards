<?php

namespace App\Providers;

use App\Providers\BookMarkedAsCompleted;
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
     * @param  \App\Providers\BookMarkedAsCompleted  $event
     * @return void
     */
    public function handle(BookMarkedAsCompleted $event)
    {
        //
    }
}
