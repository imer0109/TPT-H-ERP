<?php

namespace App\Http\Resources;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'category' => $this->resource->category,
            'created_at' => $this->resource->created_at,
            'site' => $this->resource->site,
            'description' => $this->resource->description,
            'functionality' => $this->resource->functionality,
            'published' => $this->resource->published,
            'portfolio' => $this->resource->portfolio,
            'images' => FileResource::collection(File::query()->where('owner_id', $this->resource->id)->orderBy('index')->get()),
        ];
    }
}
