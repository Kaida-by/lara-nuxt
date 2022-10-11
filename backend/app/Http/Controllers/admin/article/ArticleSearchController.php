<?php

namespace App\Http\Controllers\admin\article;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ArticleSearchController
{
    public function search(Request $request): AnonymousResourceCollection
    {
        $articles = Article::search($request->get('query'))->get();

        return ArticleResource::collection($articles);
    }
}
