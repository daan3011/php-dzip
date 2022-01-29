<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Services\CompressService;
use SebastianBergmann\CodeCoverage\Report\PHP;


class CompressCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'compress {input-file : the name/path of the file to compress}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Compresses the input file';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(CompressService $compress)
    {
        //
        if ($this->confirm('Are you sure you want to compress: ' . $this->argument('input-file'))) {
            $compress->compress($this->argument('input-file'));
            $this->info(PHP_EOL . "Succesfully compressed: " . $this->argument('input-file'));
            $this->info("Created: " . pathinfo($this->argument('input-file'), PATHINFO_FILENAME) . "_decompress_key.ddc");
            $this->info("Created: " . pathinfo($this->argument('input-file'), PATHINFO_FILENAME)  . ".dzip");
        }
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
