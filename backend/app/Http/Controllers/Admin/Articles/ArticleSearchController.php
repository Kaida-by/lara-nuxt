<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Admin\Articles;

use App\Data\ResourceData\ArticleData;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class ArticleSearchController extends Controller
{
    public function search(Request $request): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $articles = Article::search($request->get('query'))->get();

        return ArticleData::collection($articles);
    }
}
