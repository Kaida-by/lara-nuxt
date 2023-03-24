<?php

namespace App\Http\Resources;

use App\Http\Services\EntityHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'category_ids' => $this->resource->category_ids,
            'status_id' => $this->resource->status_id,
            'mainImageUrl' => EntityHelper::getUrlMainImageFromDescription($this->resource->description),
            'entityStatus' => $this->resource->entityStatus,
            'created_at' => $this->resource->created_at->format('d M H:i'),
            'user' => $this->resource->user,
        ];
    }
}
