<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Services;

use App\Events\Notifications;
use App\Http\Interfaces\EntityInterface;
use App\Models\Image;
use App\Notifications\DeleteEntityNotification;
use App\Notifications\PublishEntityNotification;
use App\Notifications\UpdateEntityNotification;
use Illuminate\Database\Eloquent\Collection;
use Exception;
use Illuminate\Http\JsonResponse;

abstract class AbstractAdminEntityHelper
{
    /**
     * @param EntityInterface $entity
     * @param int $categoryId
     * @return mixed
     */
    protected function getAllEntityData(EntityInterface $entity, int $categoryId): mixed
    {
        if ($categoryId === 0) {
            return $entity::simplePaginate(config('data.count_entities_for_admin_page'));
        }

        return $entity::whereHas('categories', static function ($q) use ($categoryId) {
            $q->where('category_id', $categoryId);
        })
            ->simplePaginate(config('data.count_entities_for_admin_page'));
    }

    /**
     * @param EntityInterface $entity
     * @param int $id
     * @return mixed
     */
    protected function getOneEntityData(EntityInterface $entity, int $id): mixed
    {
        return $entity::with([
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
    }

    /**
     * @param EntityInterface $entity
     * @param int $id
     * @param int $status
     * @param string $entityName
     * @return void
     */
    protected function approveEntity(EntityInterface $entity, int $id, int $status, string $entityName): void
    {
        $newEntity = $entity::find($id);
        $newEntity->status_id = $status;
        $user = $newEntity->user;

        $newEntity->update();

        if ($newEntity->status_id === 1) {
            $user->notify(new PublishEntityNotification($entityName, $newEntity->title));
        } else {
            $user->notify(new UpdateEntityNotification($entityName, $newEntity->title));
        }

        event(new Notifications($newEntity->user()->first()->id));
    }

    /**
     * @param EntityInterface $entity
     * @param int $id
     * @param string $entityName
     * @return JsonResponse
     */
    protected function deleteEntity(EntityInterface $entity, int $id, string $entityName): JsonResponse
    {
        $newEntity = $entity::with([
            'images',
            'user'
        ])
            ->where(['id' => $id])
            ->first();

        try {
            if (isset($newEntity) && $newEntity->images instanceof Collection) {
                foreach ($newEntity->images as $image) {
                    $image = Image::find($image['id']);

                    if ($image['is_local'] === 1) {
                        unlink(public_path() . '/' . $image['src']);
                    }

                    Image::destroy(['id' => $image['id']]);
                }

                $newEntity->user()->first()?->notify(new DeleteEntityNotification($entityName, $newEntity->title));
                event(new Notifications($newEntity->user()->first()->id));

                $entity::destroy($id);

                return response()->json([
                    'success' => true,
                ]);
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
