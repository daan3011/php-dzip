<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class VersionCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'version';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Outputs the installed version of dzip and php';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dzipVersion = "0.1.0";
        $phpVersion = shell_exec("php -v");
        $this->info("dzip version: $dzipVersion");
        $this->info("php version: $phpVersion");
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
