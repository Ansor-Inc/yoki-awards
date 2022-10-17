<?php

namespace Modules\User\Console;

use Illuminate\Console\Command;
use Modules\Book\Listeners\UpdateUserDegree;
use Modules\User\Jobs\UpdateUsersDegreeJob;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdateUsersDegreeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'user:update-degree';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update users degree.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        UpdateUsersDegreeJob::dispatch();

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
