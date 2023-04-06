<?php
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\PersonalCabinet;

use App\Data\RequestData\PosterData;
use App\Enums\EntityCategory;
use App\Enums\EntityStatus;
use App\Events\Notifications;
use App\Http\Controllers\Controller;
use App\Http\Resources\PosterResource;
use App\Http\Services\EntityHelper;
use App\Http\Services\UploadImagesService;
use App\Models\Poster;
use App\Models\User;
use App\Notifications\CreateEntityNotification;
use App\Notifications\UpdateEntityNotification;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use RuntimeException;
use Throwable;
use function response;

class PosterPersonalCabinetController extends Controller
{
    public const ENTITY_TYPE = 9;
    public const ENTITY_NAME = 'poster';

    protected Poster $poster;

    public function __construct(Poster $poster)
    {
        $this->poster = $poster;
    }

    public function getMyPosters(): AnonymousResourceCollection
    {
        $posters = Poster::with([
            'images' => function($q) {
                $q->where('entity_type_id', self::ENTITY_TYPE);
            }
        ])
            ->where(['status_id' => EntityStatus::Active->value])
            ->where(['author_id' => $this->user()->profile->id])
            ->simplePaginate(10);

        return PosterResource::collection($posters);
    }

    /**
     * @throws Throwable
     */
    public function store(PosterData $data): JsonResponse
    {
        $this->poster->title = $data->title;
        $this->poster->description = $data->description;
        $timestamp = strtotime($data->date);
        $this->poster->date = date('d/m/Y H:i:s', $timestamp);
        $this->poster->price = $data->price;
        $this->poster->author_id = $this->user()->profile->id;
        $this->poster->entity_type_id = self::ENTITY_TYPE;
        $this->poster->status_id = EntityStatus::UnderModeration->value;

        try {
            $this->poster->save();
            $currentId = Poster::latest()->first()->id ?? 0;

            if (request()->files->get('files')) {
                try {
                    UploadImagesService::save(
                        self::ENTITY_TYPE,
                        $currentId,
                        request()->files->get('files')
                    );
                } catch (RuntimeException|Exception $exception) {
                    Poster::destroy($currentId);

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
            $user = Auth::user();
            $user->notify(new CreateEntityNotification(self::ENTITY_NAME, $this->poster->title));
            event(new Notifications($user->id));

            return response()->json([
                'success' => true,
                'poster' => $data
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
        $poster = Poster::with([
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

        if (count($poster) > 0) {
            return response()->json([
                'success' => true,
                'data' => $poster
            ]);
        }

        return response()->json([
            'success' => false,
            'data' => $poster
        ], 404);
    }

    /**
     * @throws Throwable
     */
    public function update(PosterData $data, Poster $poster): JsonResponse
    {
        $tags = EntityHelper::getTagsFromDescription($data->description);
        $usedImagesUuid = UploadImagesService::getUsedImagesUuidFromHTMLTags($tags);
        UploadImagesService::removeUnusedImages($poster, $usedImagesUuid);
        $timestamp = strtotime($data->date);

        $dateTime = new Carbon($timestamp);

        $categoryIds = EntityHelper::getCategoriesIdFromCategoryArray($data->categories);

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

    /**
     * @return JsonResponse
     */
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


    /**
     * @return JsonResponse
     */
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
