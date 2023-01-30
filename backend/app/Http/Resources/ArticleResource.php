<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'category_ids' => $this->resource->category_ids,
            'status_id' => $this->resource->status_id,
            'images' => $this->resource->images,
            'entityStatus' => $this->resource->entityStatus,
            'created_at' => $this->resource->created_at->format('d M H:i'),
        ];
    }
}
