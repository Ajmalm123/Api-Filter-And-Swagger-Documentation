<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\User\UserResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * @var Post $resource
     */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $response = parent::toArray($request);
        $includes = (array)$request->input('include', []);
        if (in_array('author', $includes)) {
            $response['author'] = new UserResource($this->resource->author);
        }
        if (in_array('updater', $includes)) {
            $response['updater'] = new UserResource($this->resource->updater);
        }

        return $response;
    }
}
