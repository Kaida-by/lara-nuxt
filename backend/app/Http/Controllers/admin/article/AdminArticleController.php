<?php

namespace App\Http\Controllers\admin\article;

use App\Http\Requests\AdminArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Image;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class AdminArticleController
{
    const CATEGORY = 2;
    const ENTITY_TYPE = 2;

    protected Article $article;

    /**
     * AdminArticleController constructor.
     * @param Article $article
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function showAll(): AnonymousResourceCollection
    {
        $articles = Article::with([
            'images' => function($q) {
                $q->where('entity_type_id', self::ENTITY_TYPE);
            },
            'entityStatus'
        ])
            ->simplePaginate(10);

        return ArticleResource::collection($articles);
    }

    /**
     * @return JsonResponse
     */
    public function getCategories(): JsonResponse
    {
        $categories = DB::table('categories', 'cat')
            ->select(['cat.id', 'cat.title', DB::raw('count(category_id) as cat')])
            ->leftJoin('article_category', 'cat.id', '=', 'category_id')
            ->where('category_type_id', self::CATEGORY)
            ->groupBy(['category_id', 'cat.id', 'cat.title'])
            ->get();

        return \response()->json([
            'success' => true,
            'categories' => $categories
        ], 200);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        $article = Article::with([
            'user' => function($q) {
                $q->with(['profile']);
            },
            'entityStatus',
            'images' => function($q) {
                $q->orderBy('order');
            }
        ])
            ->where(['id' => $id])
            ->get();

        return \response()->json([
            'success' => true,
            'data' => $article
        ]);
    }

    public function approve(int $id, AdminArticleRequest $request)
    {
        $article = Article::find($id);

        $article->status_id = $request['checked'] == false ? 2 : 1;

        $article->update();
    }

    public function delete(int $id): JsonResponse
    {
        $article = Article::with([
            'images',
        ])
            ->where(['id' => $id])
            ->limit(1)
            ->get();

        DB::beginTransaction();

        try {
            if (isset($article[0]) && $article[0]->images instanceof Collection) {
                foreach ($article[0]->images as $image) {
                    $image = Image::find($image['id']);

                    if ($image['is_local'] === 1) {
                        unlink(public_path() . '/../' . $image['src']);
                    }

                    Image::destroy(['id' => $image['id']]);
                }
                Article::destroy($id);
                DB::commit();

                return \response()->json([
                    'success' => true,
                ], 204);
            } else {
                return \response()->json([
                    'success' => false,
                    'errors' => [
                        'text' => [
                            'Something went wrong.'
                        ]
                    ]
                ], 500);
            }
        } catch (\Exception $exception) {
            DB::rollBack();

            return \response()->json([
                'success' => false,
                'errors' => [
                    'text' => [
                        $exception->getMessage()
                    ]
                ]
            ], 500);
        }
    }
}
