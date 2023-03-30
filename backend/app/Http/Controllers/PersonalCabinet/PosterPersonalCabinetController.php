<?php
/** @noinspection PhpPossiblePolymorphicInvocationInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\PersonalCabinet;

use App\Events\Notifications;
use App\Http\Controllers\Controller;
use App\Http\Requests\PosterRequest;
use App\Http\Resources\PosterResource;
use App\Http\Services\EntityHelper;
use App\Http\Services\UploadImagesService;
use App\Models\Poster;
use App\Models\User;
use App\Notifications\CreateEntityNotification;
use App\Notifications\UpdateEntityNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
            ->where(['status_id' => EntityHelper::ENTITY_STATUS_ACTIVE])
            ->where(['author_id' => $this->user()->profile->id])
            ->simplePaginate(10);

        return PosterResource::collection($posters);
    }

    /**
     * @throws Throwable
     */
    public function store(PosterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $this->poster->title = $validatedData['title'];
        $this->poster->description = $validatedData['description'];
        $timestamp = strtotime($request->date);
        $this->poster->date = date('d/m/Y H:i:s', $timestamp);
        $this->poster->price = $validatedData['price'];
        $this->poster->author_id = $this->user()->profile->id;
        $this->poster->entity_type_id = self::ENTITY_TYPE;
        $this->poster->status_id = EntityHelper::ENTITY_STATUS_UNDER_MODERATION;

        try {
            $this->poster->save();
            $currentId = Poster::latest()->first()->id ?? 0;

            if ($request->files->get('files')) {
                try {
                    UploadImagesService::save(
                        self::ENTITY_TYPE,
                        $currentId,
                        $request->files->get('files')
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
                'poster' => $this->poster
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
            }
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
    public function update(PosterRequest $request, Poster $poster): JsonResponse
    {
        $tags = EntityHelper::getTagsFromDescription($request->description);
        $usedImagesUuid = UploadImagesService::getUsedImagesUuidFromHTMLTags($tags);
        UploadImagesService::removeUnusedImages($poster, $usedImagesUuid);
        $timestamp = strtotime($request->date);
        $dateTime = new \Illuminate\Support\Carbon($timestamp);

        try {
            $poster->update([
                'title' => $request->title,
                'description' => $request->description,
                'date' => $dateTime,
                'price' => $request->price,
                'author_id' => $this->user()->profile->id,
                'entity_type_id' => self::ENTITY_TYPE,
                'status_id' => EntityHelper::ENTITY_STATUS_UNDER_MODERATION,
            ]);

            UploadImagesService::upload(
                $request->files->get('file'),
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
                'data' => $request['images']
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
        $this->poster->status_id = EntityHelper::ENTITY_STATUS_UNDER_MODERATION;

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
}
