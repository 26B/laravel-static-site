<?php

namespace TwentySixB\LaravelStaticSite\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Process;

class Assets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'burn:assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->line('Using disk: ' . config('static-site.storage'));

        try {

            // Use this in Laravel 10.
            // https://laravel.com/docs/10.x/processes

            /*
            $this->line('Building assets');
            $result = Process::run('yarn run build');
            return $result->output();
            */

            $this->copyAssets(config('static-site.storage'));

        } catch(\Exception $e) {

            $this->error('Unable to copy');
            $this->info($e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    protected function copyAssets(string $toDisk) {

        // Default vite path, should be customizable.
        $disk = Storage::build([
            'driver' => 'local',
            'root' => public_path('build'),
        ]);
        $files = $disk->allFiles('/');

        foreach ($files as $filePath) {

            $this->line(__('Processing :file', ['file' => $filePath]));

            $file = $disk->get($filePath);
            Storage::disk($toDisk)->put($filePath, $file, 'public');
        }

        $this->info(__('Copied :count files', ['count' => count($files)]));
        return Command::SUCCESS;
    }
}
