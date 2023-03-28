<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Admin\Posters;

use App\Events\Notifications;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminEntityRequest;
use App\Http\Resources\PosterResource;
use App\Http\Services\EntityHelper;
use App\Models\Image;
use App\Models\Poster;
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

class AdminPosterController extends Controller
{
    public const ENTITY_NAME = 'poster';

    public function showAll(): AnonymousResourceCollection
    {
        $posters = Poster::with([
            'images' => function($q) {
                $q->where('entity_type_id', EntityHelper::TYPE_POSTERS);
            },
            'entityStatus',
            'user'
        ])
            ->simplePaginate(10);

        return PosterResource::collection($posters);
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

        return response()->json([
            'success' => true,
            'data' => $poster
        ]);
    }

    public function approve(int $id, AdminEntityRequest $request): void
    {
        /** @var Poster $poster */
        $poster = Poster::find($id);
        $poster->status_id = !$request['checked'] ? 2 : 1;
        /** @var User $user */
        $user = $poster->user;

        $poster->update();

        $user->notify(new PublishEntityNotification(self::ENTITY_NAME, $poster->title));
        event(new Notifications($poster->user()->first()->id));
    }

    /**
     * @throws Throwable
     */
    public function delete(int $id): JsonResponse
    {
        /** @var Poster $poster */
        $poster = Poster::with([
            'images',
            'user'
        ])
            ->where(['id' => $id])
            ->first();

        DB::beginTransaction();

        try {
            if (isset($poster) && $poster->images instanceof Collection) {
                foreach ($poster->images as $image) {
                    $image = Image::find($image['id']);

                    if ($image['is_local'] === 1) {
                        unlink(public_path() . '/' . $image['src']);
                    }

                    Image::destroy(['id' => $image['id']]);
                }

                $poster->user()->first()?->notify(new DeleteEntityNotification(self::ENTITY_NAME, $poster->title));
                event(new Notifications($poster->user()->first()->id));

                Poster::destroy($id);
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
