<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\EntityInterface;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Services\UploadImagesService;
use App\Models\Article;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArticleController
{
    protected Article $article;

    const ENTITY_TYPE = 2;
    const CATEGORY = 2;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function showAll(): AnonymousResourceCollection
    {
        $articles = Article::with([
            'images' => function($q) {
                $q->where('entity_type_id', self::ENTITY_TYPE);
            }
        ])
            ->where(['status_id' => Article::ENTITY_STATUS_ACTIVE])
            ->simplePaginate(10);

        return ArticleResource::collection($articles);
    }

    public function show(Article $article): Response
    {
        return response([
            'article' => $article,
        ]);
    }


    public function destroy($id): RedirectResponse
    {
        Article::where('id', $id)->delete();

        Image::where('entity_type_id', self::ENTITY_TYPE)
            ->where('entity_id', $id)
            ->delete();

        return redirect()->route('article.index');
    }
}
