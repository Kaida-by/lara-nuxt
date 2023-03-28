<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Admin\Articles;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ArticleSearchController extends Controller
{
    public function search(Request $request): AnonymousResourceCollection
    {
        $articles = Article::search($request->get('query'))->get();

        return ArticleResource::collection($articles);
    }
}
