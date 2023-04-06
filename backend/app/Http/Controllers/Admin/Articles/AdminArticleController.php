<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Admin\Articles;

use App\Data\ResourceData\ArticleData;
use App\Enums\EntityCategory;
use App\Enums\EntityName;
use App\Enums\EntityType;
use App\Events\Notifications;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminEntityRequest;
use App\Http\Services\EntityHelper;
use App\Models\Article;
use App\Models\Image;
use App\Models\User;
use App\Notifications\DeleteEntityNotification;
use App\Notifications\PublishEntityNotification;
use App\Notifications\UpdateEntityNotification;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;
use Throwable;

use function response;

class AdminArticleController extends Controller
{
    public function showAll(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $articles = Article::with([
            'images' => function($q) {
                $q->where('entity_type_id', EntityType::Article->value);
            },
            'entityStatus',
            'user'
        ])
            ->simplePaginate(10);

        return ArticleData::collection($articles);
    }

    /**
     * @return JsonResponse
     */
    public function getCategories(): JsonResponse
    {
        $categoryId = (int) request('categoryId') ?: EntityCategory::Article->value;
        $categories = EntityHelper::getCategories($categoryId, 'article_category');

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
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => ArticleData::from($article)
        ]);
    }

    /**
     * @param int $id
     * @param AdminEntityRequest $request
     * @return void
     */
    public function approve(int $id, AdminEntityRequest $request): void
    {
        /** @var Article $article */
        $article = Article::find($id);
        $article->status_id = !$request['checked'] ? 2 : 1;
        /** @var User $user */
        $user = $article->user;

        $article->update();

        if ($article->status_id === 1) {
            $user->notify(new PublishEntityNotification(EntityName::Article-> value, $article->title));
        } else {
            $user->notify(new UpdateEntityNotification(EntityName::Article-> value, $article->title));
        }

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
                        unlink(public_path() . '/' . $image['src']);
                    }

                    Image::destroy(['id' => $image['id']]);
                }

                $article->user()->first()?->notify(new DeleteEntityNotification(EntityName::Article-> value, $article->title));
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
