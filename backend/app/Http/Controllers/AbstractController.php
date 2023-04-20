<?php

/** @noinspection CallableParameterUseCaseInTypeContextInspection */

namespace App\Http\Controllers;

use App\Contracts\HasPhone;
use App\Http\Interfaces\EntityInterface;

abstract class AbstractController
{
    protected function getAll(EntityInterface $entity, int $count, int $entityType, int $entityStatus): mixed
    {
        if ($count && $count > config('data.count_entities_for_entity_page')) {
            $count = config('data.count_entities_for_main_page');
        }

        if ($entity instanceof HasPhone) {
            return $entity::where(['status_id' => $entityStatus])
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
            ->simplePaginate($count);
    }

    protected function getOne(EntityInterface $entity, int $id, int $entityType): EntityInterface
    {
        if ($entity instanceof HasPhone) {
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
                'phone' => function($q) use ($entityType) {
                    $q->where(['entity_type_id' => $entityType]);
                }
            ])
                ->where(['id' => $id])
                ->first();
        }

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
            ->where(['id' => $id])
            ->first();
    }
}
