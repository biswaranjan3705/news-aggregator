<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsService;
use Illuminate\Support\Facades\Log;

class FetchNewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store latest news';

    protected $newsService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(NewsService $newsService)
    {
        parent::__construct();
        $this->newsService = $newsService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('News fetch command started.');

        try {
            $this->newsService->fetchNews();
            Log::info('News fetch command executed successfully.');
            $this->info('News fetched and stored successfully.');
        } catch (\Exception $e) {
            Log::error('News fetch error: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
