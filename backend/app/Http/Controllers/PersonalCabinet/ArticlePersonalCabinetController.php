<?php
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\PersonalCabinet;

use App\Data\RequestData\ArticleData;
use App\Data\ResourceData\ArticleData as ArticleDataResource;
use App\Enums\EntityCategory;
use App\Enums\EntityName;
use App\Enums\EntityStatus;
use App\Enums\EntityType;
use App\Events\Notifications;
use App\Http\Services\AbstractPersonalCabinetHelper;
use App\Http\Services\EntityHelper;
use App\Http\Services\UploadImagesService;
use App\Models\Article;
use App\Models\User;
use App\Notifications\UpdateEntityNotification;
use Exception;
use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;
use Throwable;
use function response;

class ArticlePersonalCabinetController extends AbstractPersonalCabinetHelper
{
    protected Article $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function getMyArticles(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $articles = $this->getAllEntities(new Article(), EntityType::Article->value, EntityStatus::Active->value);

        return ArticleDataResource::collection($articles);
    }

    public function edit(int $id): ArticleDataResource
    {
        $article = $this->getOneEntity(new Article(), $id);

        return ArticleDataResource::from($article);
    }

    public function update(ArticleData $data, Article $article): JsonResponse
    {
        $tags = EntityHelper::getTagsFromDescription($data->description);
        $usedImagesUuid = UploadImagesService::getUsedImagesUuidFromHTMLTags($tags);
        UploadImagesService::removeUnusedImages($article, $usedImagesUuid);

        $categoryIds = EntityHelper::getCategoriesIdFromCategoryArray($data->categoryIds);

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
