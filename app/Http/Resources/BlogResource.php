<?php

namespace App\Http\Resources;

use App\Models\Comment;
use App\Models\File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {   

        $created_at = Carbon::parse($this->resource->created_at)->translatedFormat('d F Y à H\h i');
        $updated_at = Carbon::parse($this->resource->updated_at)->translatedFormat('d F Y à H\h i');

        return [
            "id" => $this->resource->id,
            "type" => $this->resource->type,
            "title" => $this->resource->title,
            "description" => $this->resource->description,
            "author" => $this->resource->author,
            "content" => $this->resource->content,
            "comments" => CommentResource::collection($this->resource->comments()->orderBy('date', 'desc')->get()),
            "created_at" => $created_at,
            "updated_at" => $updated_at,
            "published" => $this->resource->published,
            'images' => FileResource::collection(File::query()->where('owner_id', $this->resource->id)->orderBy('index')->get()),
        ];
    }
}
