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
use function Clue\StreamFilter\fun;

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
            ->orderBy('created_at', 'DESC')
            ->simplePaginate(4);

        return ArticleResource::collection($articles);
    }

    public function showOne(Article $article): JsonResponse
    {
        $article = Article::with([
            'user' => function($q) {
                $q->with([
                    'profile' => function ($p) {
                        $p->with([
                            'images' => function ($pi) {
                                $pi->where(['entity_type_id' => 3]);
                            }
                        ]);
                    }
                ]);
            },
            'entityStatus',
            'images' => function($q) {
                $q->where(['entity_type_id' => self::ENTITY_TYPE]);
                $q->orderBy('order');
            }
        ])
            ->where(['id' => $article->id])
            ->get();

        return \response()->json([
            'success' => true,
            'data' => $article
        ]);
    }
}
