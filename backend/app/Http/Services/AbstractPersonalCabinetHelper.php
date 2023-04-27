<?php

namespace App\Http\Services;

use App\Contracts\HasPhone;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\EntityInterface;

abstract class AbstractPersonalCabinetHelper extends Controller
{
    protected function getAllEntities(EntityInterface $entity, int $entityType, int $entityStatus): mixed
    {
        if ($entity instanceof HasPhone) {
            return $entity::whereHas('phone', function($q) use ($entityType) {
                    $q->where(['entity_type_id' => $entityType]);
                }
            )
                ->where('title', '!=', '')
                ->where(['author_id' => $this->user()->profile->id])
                ->simplePaginate(config('data.count_entities_for_admin_page'));
        }

        return $entity::with([
            'images' => function($q) use ($entityType) {
                $q->where('entity_type_id', $entityType);
            }
        ])
            ->where('title', '!=', '')
            ->where(['author_id' => $this->user()->profile->id])
            ->simplePaginate(config('data.count_entities_for_admin_page'));
    }

    protected function getOneEntity(EntityInterface $entity, int $id, int $entityType): mixed
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
            },
            'categories'
        ])
            ->where(['id' => $id])
            ->firstOrFail();
    }
}
