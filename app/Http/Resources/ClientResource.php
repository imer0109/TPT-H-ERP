<?php

namespace App\Http\Resources;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'comment' => $this->resource->comment,
            'site' => $this->resource->site,
            'published' => $this->resource->published,
            'logo' => new FileResource(File::query()->where('owner_id', $this->resource->id)->first())
        ];
    }
}
