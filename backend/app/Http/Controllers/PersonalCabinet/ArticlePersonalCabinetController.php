<?php
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\PersonalCabinet;

use App\Data\RequestData\ArticleData;
use App\Enums\EntityCategory;
use App\Enums\EntityName;
use App\Enums\EntityStatus;
use App\Enums\EntityType;
use App\Events\Notifications;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Services\EntityHelper;
use App\Http\Services\UploadImagesService;
use App\Models\Article;
use App\Models\User;
use App\Notifications\CreateEntityNotification;
use App\Notifications\UpdateEntityNotification;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RuntimeException;
use Throwable;
use function response;

class ArticlePersonalCabinetController extends Controller
{
    protected Article $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function getMyArticles(): AnonymousResourceCollection
    {
        $articles = Article::with([
            'images' => function($q) {
                $q->where('entity_type_id', EntityType::Article->value);
            }
        ])
            ->where(['status_id' => EntityStatus::Active->value])
            ->where(['author_id' => $this->user()->profile->id])
            ->simplePaginate(10);

        return ArticleResource::collection($articles);
    }

    /**
     * @throws Throwable
     */
    public function store(ArticleData $data): JsonResponse
    {
        $this->article->title = $data->title;
        $this->article->description = $data->description;
        $this->article->author_id = $this->user()->profile->id;
        $this->article->entity_type_id = EntityType::Article->value;
        $this->article->category_id = EntityCategory::Article->value;
        $this->article->status_id = EntityStatus::UnderModeration->value;

        try {
            $this->article->save();
            $currentId = Article::latest()->first()->id ?? 0;

            if (request()->files->get('file')) {
                try {
                    UploadImagesService::save(
                        EntityType::Article->value,
                        $currentId,
                        request()->files->get('file')
                    );
                } catch (RuntimeException|Exception $exception) {
                    Article::destroy($currentId);

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

            /** @var User $user */
            $user = $this->user();
            $user->notify(new CreateEntityNotification(EntityName::Article->value, $this->article->title));
            event(new Notifications($user->id));

            return response()->json([
                'success' => true,
                'article' => $this->article
            ]);
        } catch (Exception $exception) {
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
            },
            'categories'
        ])
            ->where(['id' => $id])
            ->get();

        if (count($article) > 0) {
            return response()->json([
                'success' => true,
                'data' => $article
            ]);
        }

        return response()->json([
            'success' => false,
            'data' => $article
        ], 404);
    }

    /**
     * @throws Throwable
     */
    public function update(ArticleData $data, Article $article): JsonResponse
    {
        $tags = EntityHelper::getTagsFromDescription($data->description);
        $usedImagesUuid = UploadImagesService::getUsedImagesUuidFromHTMLTags($tags);
        UploadImagesService::removeUnusedImages($article, $usedImagesUuid);

        $categories = request('categories');
        $categoryIds = EntityHelper::getCategoriesIdFromCategoryArray($categories);

        try {
            $article->update([
                'title' => $data->title,
                'description' => $data->description,
                'author_id' => $this->user()->profile->id,
                'entity_type_id' => EntityType::Article->value,
                'category_id' => EntityType::Article->value,
                'status_id' => EntityStatus::UnderModeration->value,
            ]);

            $article->categories()->sync($categoryIds);

            UploadImagesService::upload(
                request()->files->get('file'),
                EntityType::Article->value,
                $article->id,
                1,
                true,
            );

            /** @var User $user */
            $user = $this->user();
            $user->notify(new UpdateEntityNotification(EntityName::Article->value, $article->title));
            event(new Notifications($user->id));

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => true,
                'data' => $exception->getMessage()
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => true,
                'data' => $e->getMessage()
            ]);
        }
    }

    /**
     * @return JsonResponse
     */
    public function createTemporaryArticle(): JsonResponse
    {
        $this->article->title = '';
        $this->article->description = '';
        $this->article->author_id = $this->user()->profile->id;
        $this->article->entity_type_id = EntityType::Article->value;
        $this->article->category_id = EntityType::Article->value;
        $this->article->status_id = EntityStatus::UnderModeration->value;

        try {
            $this->article->save();

            return response()->json([
                'success' => true,
                'data' => $this->article->id
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }
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
}
