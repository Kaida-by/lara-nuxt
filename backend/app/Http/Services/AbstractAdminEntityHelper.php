<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Services;

use App\Contracts\HasPhone;
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
    protected function getAllEntityData(EntityInterface $entity, int $categoryId): mixed
    {
        if ($categoryId === 0) {
            if ($entity instanceof HasPhone) {
                return $entity::whereHas('phone')
                    ->where('title', '!=', '')
                    ->simplePaginate(config('data.count_entities_for_admin_page'));
            }

            return $entity::where('title', '!=', '')->simplePaginate(config('data.count_entities_for_admin_page'));
        }

        return $entity::whereHas('categories', static function ($q) use ($categoryId) {
            $q->where('category_id', $categoryId);
        })
            ->where('title', '!=', '')
            ->simplePaginate(config('data.count_entities_for_admin_page'));
    }

    protected function getOneEntityData(EntityInterface $entity, int $id, string $entityType): mixed
    {
        if ($entity instanceof HasPhone) {
            return $entity::with([
                'user' => function($q) {
                    $q->with(['profile']);
                },
                'entityStatus',
                'phone' => function($q) use ($entityType) {
                    $q->where(['entity_type_id' => $entityType]);
                }
            ])
                ->where(['id' => $id])
                ->firstOrFail();
        }
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
