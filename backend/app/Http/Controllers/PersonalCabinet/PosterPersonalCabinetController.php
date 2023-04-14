<?php
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\PersonalCabinet;

use App\Data\RequestData\PosterData;
use App\Data\ResourceData\PosterData as PosterDataResource;
use App\Enums\EntityCategory;
use App\Enums\EntityStatus;
use App\Enums\EntityType;
use App\Events\Notifications;
use App\Http\Services\AbstractPersonalCabinetHelper;
use App\Http\Services\EntityHelper;
use App\Http\Services\UploadImagesService;
use App\Models\Poster;
use App\Models\User;
use App\Notifications\UpdateEntityNotification;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;
use Throwable;
use function response;

class PosterPersonalCabinetController extends AbstractPersonalCabinetHelper
{
    public const ENTITY_TYPE = 9;
    public const ENTITY_NAME = 'poster';

    protected Poster $poster;

    public function __construct(Poster $poster)
    {
        $this->poster = $poster;
    }

    public function getMyPosters(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $posters = $this->getAllEntities(new Poster(), EntityType::Poster->value, EntityStatus::Active->value);

        return PosterDataResource::collection($posters);
    }

    public function edit(int $id): PosterDataResource
    {
        $poster = $this->getOneEntity(new Poster(), $id);

        return PosterDataResource::from($poster);
    }

    public function update(PosterData $data, Poster $poster): JsonResponse
    {
        $tags = EntityHelper::getTagsFromDescription($data->description);
        $usedImagesUuid = UploadImagesService::getUsedImagesUuidFromHTMLTags($tags);
        UploadImagesService::removeUnusedImages($poster, $usedImagesUuid);
        $timestamp = strtotime($data->date);

        $dateTime = new Carbon($timestamp);

        $categoryIds = EntityHelper::getCategoriesIdFromCategoryArray($data->categoryIds);

        try {
            $poster->update([
                'title' => $data->title,
                'description' => $data->description,
                'date' => $dateTime,
                'price' => $data->price,
                'author_id' => $this->user()->profile->id,
                'entity_type_id' => self::ENTITY_TYPE,
                'status_id' => EntityStatus::UnderModeration->value,
            ]);

            $poster->categories()->sync($categoryIds);

            UploadImagesService::upload(
                request()->files->get('file'),
                self::ENTITY_TYPE,
                $poster->id,
                1,
                true,
                true
            );

            /** @var User $user */
            $user = Auth::user();
            $user->notify(new UpdateEntityNotification(self::ENTITY_NAME, $poster->title));
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

    public function createTemporaryPoster(): JsonResponse
    {
        $this->poster->title = '';
        $this->poster->description = '';
        $this->poster->author_id = $this->user()->profile->id;
        $this->poster->entity_type_id = self::ENTITY_TYPE;
        $this->poster->status_id = EntityStatus::UnderModeration->value;

        try {
            $this->poster->save();

            return response()->json([
                'success' => true,
                'data' => $this->poster->id
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
        $categories = EntityHelper::getCategories($categoryId, 'poster_category');

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }
}
