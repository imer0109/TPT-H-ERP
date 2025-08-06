<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $date = Carbon::parse($this->resource->date)->translatedFormat('d F Y à H\h i');

        return [
            'id' => $this->resource->id,
            'date' => $date,
            'type' => $this->resource->type,
            'description' => $this->resource->description,
            'user' => $this->user,

        ];
    }
}
