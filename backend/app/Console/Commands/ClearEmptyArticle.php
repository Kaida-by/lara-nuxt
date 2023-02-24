<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

class ClearEmptyArticle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear-empty-article';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Empty articles will be cleared';

    /**
     * @return void
     */
    public function handle(): void
    {
        $count = 0;

        Article::chunk(500, function ($articles) use ($count) {
            /** @var Article $article */
            foreach ($articles as $article) {
                if (!$article->title || !$article->description) {
                    $article->delete();
                    ++$count;
                }
            }

            $this->info('Removed ' . $count . ' articles');
        });
    }
}
