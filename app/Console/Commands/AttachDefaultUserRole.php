<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Modules\User\Entities\User;

class AttachDefaultUserRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:attach-default-role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attach default user role to all users inside database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        User::query()->chunk(100, function (Collection $users) {
            //Todo attach default role
        });
        return self::SUCCESS;
    }
}
