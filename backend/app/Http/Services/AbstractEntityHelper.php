<?php

namespace App\Http\Services;

use App\Http\Interfaces\EntityInterface;

abstract class AbstractEntityHelper
{
    /**
     * @param EntityInterface $entity
     * @param int $count
     * @param int $entityType
     * @param int $entityStatus
     * @return mixed
     */
    protected function getAllEntityData(EntityInterface $entity, int $count, int $entityType, int $entityStatus): mixed
    {
        if ($count && $count <= config('data.count_entities_for_entity_page')) {
            return $entity::with([
                'images' => function($q) use ($entityType) {
                    $q->where('entity_type_id', $entityType);
                }
            ])
                ->where(['status_id' => $entityStatus])
                ->orderBy('created_at', 'DESC')
                ->simplePaginate($count);
        }

        return $entity::with([
            'images' => function($q) use ($entityType) {
                $q->where('entity_type_id', $entityType);
            }
        ])
            ->where(['status_id' => $entityStatus])
            ->orderBy('created_at', 'DESC')
            ->simplePaginate(config('data.count_entities_for_main_page'));
    }

    /**
     * @param EntityInterface $entity
     * @param int $id
     * @param int $entityType
     * @return mixed
     */
    protected function getOneEntityData(EntityInterface $entity, int $id, int $entityType): EntityInterface
    {
        return $entity::with([
            'user' => function($q) use ($entityType) {
                $q->with([
                    'profile' => function ($q) use ($entityType) {
                        $q->with([
                            'images' => function ($q) use ($entityType) {
                                $q->where(['entity_type_id' => $entityType]);
                            }
                        ]);
                    }
                ]);
            },
            'entityStatus',
            'images' => function($q) use ($entityType) {
                $q->where(['entity_type_id' => $entityType]);
                $q->orderBy('order');
            },
        ])
            ->whereHas('phone', function($q) use ($entityType) {
                    $q->where(['entity_type_id' => $entityType]);
                }
            )
            ->where(['id' => $id])
            ->first();
    }
}
