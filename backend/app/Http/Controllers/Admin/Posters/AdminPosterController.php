<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Admin\Posters;

use App\Data\ResourceData\PosterData;
use App\Enums\EntityCategory;
use App\Enums\EntityName;
use App\Enums\EntityType;
use App\Events\Notifications;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminEntityRequest;
use App\Http\Services\EntityHelper;
use App\Models\Image;
use App\Models\Poster;
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

class AdminPosterController extends Controller
{
    public function showAll(): DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $posters = Poster::with([
            'images' => function($q) {
                $q->where('entity_type_id', EntityType::Poster->value);
            },
            'entityStatus',
            'user'
        ])
            ->simplePaginate(10);

        return PosterData::collection($posters);
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
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => PosterData::from($poster)
        ]);
    }

    /**
     * @param int $id
     * @param AdminEntityRequest $request
     * @return void
     */
    public function approve(int $id, AdminEntityRequest $request): void
    {
        /** @var Poster $poster */
        $poster = Poster::find($id);
        $poster->status_id = !$request['checked'] ? 2 : 1;
        /** @var User $user */
        $user = $poster->user;

        $poster->update();

        if ($poster->status_id === 1) {
            $user->notify(new PublishEntityNotification(EntityName::Poster-> value, $poster->title));
        } else {
            $user->notify(new UpdateEntityNotification(EntityName::Poster-> value, $poster->title));
        }

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

                $poster->user()->first()?->notify(new DeleteEntityNotification(EntityName::Poster-> value, $poster->title));
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
