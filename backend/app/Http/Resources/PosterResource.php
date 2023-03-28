<?php

namespace App\Http\Resources;

use App\Http\Services\EntityHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PosterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'date' => $this->resource->date,
            'price' => $this->resource->price,
            'status_id' => $this->resource->status_id,
            'mainImageUrl' => EntityHelper::getUrlMainImageFromDescription($this->resource->description),
            'entityStatus' => $this->resource->entityStatus,
            'created_at' => $this->resource->created_at->format('d M H:i'),
            'user' => $this->resource->user,
        ];
    }
}
