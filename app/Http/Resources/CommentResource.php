<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $date = Carbon::parse($this->date)->diffForHumans();

        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'date' => $date,
            'comment' => $this->resource->comment,
            'published' => $this->resource->published,
            'blog_id' => $this->resource->blog->id,
        ];
    }
}
