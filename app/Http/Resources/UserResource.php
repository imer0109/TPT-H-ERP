<?php

namespace App\Http\Resources;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'lastName' => $this->resource->lastName,
            'firstName' => $this->resource->firstName,
            'email' => $this->resource->email,
            'phone' => $this->resource->phone,
            'role' => $this->resource->role,
            'token' => $this->resource->token,
            'profil' => new FileResource(File::query()->where('owner_id', $this->resource->id)->first())
        ];
    }
}
