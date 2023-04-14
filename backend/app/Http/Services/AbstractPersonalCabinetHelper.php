<?php

namespace App\Http\Services;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\EntityInterface;

abstract class AbstractPersonalCabinetHelper extends Controller
{
    protected function getAllEntities(EntityInterface $entity, int $entityType, int $entityStatus): mixed
    {
        return $entity::with([
            'images' => function($q) use ($entityType) {
                $q->where('entity_type_id', $entityType);
            }
        ])
            ->where(['status_id' => $entityStatus])
            ->where(['author_id' => $this->user()->profile->id])
            ->simplePaginate(config('data.count_entities_for_admin_page'));
    }

    protected function getOneEntity(EntityInterface $entity, int $id): mixed
    {
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
