<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Services\DecompressService;

class DecompressCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'decompress {input-file : the name/path of the file to decompress} 
    {decompression-key : the name/path of the decompression key} 
    {extension : the file extension of the output-file}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Decompresses the input file';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(DecompressService $decompress)
    {
        //
        $decompress->setFileExtension($this->argument('extension'));
        $decompress->decompressKey($this->argument('decompression-key'));
        $this->info("Found: " . pathinfo($this->argument('input-file'), PATHINFO_FILENAME) . "_decompress_key.ddc");
        $this->info("Found: " . pathinfo($this->argument('input-file'), PATHINFO_FILENAME)  . ".dzip");
        $decompress->decompress($this->argument('input-file'));
        $this->info(PHP_EOL . "Succesfully decompressed: " . pathinfo($this->argument('input-file'), PATHINFO_FILENAME)  . ".dzip" . " into: " . pathinfo($this->argument('input-file'), PATHINFO_FILENAME) . "." . $this->argument('extension'));
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
