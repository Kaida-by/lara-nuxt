<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\admin\article;

use App\Events\Notifications;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Services\EntityHelper;
use App\Models\Article;
use App\Models\Image;
use App\Models\User;
use App\Notifications\DeleteEntityNotification;
use App\Notifications\PublishEntityNotification;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Throwable;

use function response;

class AdminArticleController extends Controller
{
    public const CATEGORY = 2;
    public const ENTITY_NAME = 'article';

    public function showAll(): AnonymousResourceCollection
    {
        $articles = Article::with([
            'images' => function($q) {
                $q->where('entity_type_id', EntityHelper::TYPE_ARTICLE);
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
            ->select(['cat.id', 'cat.title', 'cat.slug', DB::raw('count(category_id) as cat')])
            ->leftJoin('article_category', 'cat.id', '=', 'category_id')
            ->where('category_type_id', self::CATEGORY)
            ->groupBy(['category_id', 'cat.id', 'cat.title'])
            ->get();

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
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

        return response()->json([
            'success' => true,
            'data' => $article
        ]);
    }

    public function approve(int $id, AdminArticleRequest $request): void
    {
        /** @var Article $article */
        $article = Article::find($id);
        $article->status_id = !$request['checked'] ? 2 : 1;
        /** @var User $user */
        $user = $article->user;

        $article->update();

        $user->notify(new PublishEntityNotification(self::ENTITY_NAME, $article->title));
        event(new Notifications($article->user()->first()->id));
    }

    /**
     * @throws Throwable
     */
    public function delete(int $id): JsonResponse
    {
        /** @var Article $article */
        $article = Article::with([
            'images',
            'user'
        ])
            ->where(['id' => $id])
            ->first();

        DB::beginTransaction();

        try {
            if (isset($article) && $article->images instanceof Collection) {
                foreach ($article->images as $image) {
                    $image = Image::find($image['id']);

                    if ($image['is_local'] === 1) {
                        unlink(public_path() . '/../' . $image['src']);
                    }

                    Image::destroy(['id' => $image['id']]);
                }

                $article->user()->first()?->notify(new DeleteEntityNotification(self::ENTITY_NAME, $article->title));
                event(new Notifications($article->user()->first()->id));

                Article::destroy($id);
                DB::commit();


                return response()->json([
                    'success' => true,
                ], 204);
            }

            return response()->json([
                'success' => false,
                'errors' => [
                    'text' => [
                        'Something went wrong.'
                    ]
                ]
            ], 500);
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json([
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
