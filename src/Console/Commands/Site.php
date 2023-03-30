<?php

namespace TwentySixB\LaravelStaticSite\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class Site extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'burn:site';

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
        Http::macro('project', fn () =>
            Http::baseUrl(config('app.url'))
                ->withOptions([
                    'verify' => false,
                    'timeout' => 3,
                    'connect_timeout' => 3,
                    'stream' => false, // TODO: Test this.
                ])
        );

        $this->line('Using disk: ' . config('static-site.storage'));
        $disk = Storage::disk(config('static-site.storage'));

        // TODO: Either erase all files and folders here or make a separate command for that.

        $routes = config('static-site.routes', []);

        try {
            foreach ($routes as $route => $filename) {

                if (is_numeric($route)) {
                    $route = $filename;
                }

                $this->line(__('Processing :url', ['url' => $route]));

                /** @var \Illuminate\Http\Client\Response $response */
                $response = Http::project()->get($route);
                $response->throw();

                $body = $response->body();

                $disk->put($filename, $body);
            }

        } catch(\Exception $e) {

            $this->error('Unable to fetch: ' . $route);
            $this->info($e->getMessage());
            return Command::FAILURE;
        }

        $this->info(__('Burned :count pages', ['count' => count($routes)]));
        return Command::SUCCESS;
    }
}
