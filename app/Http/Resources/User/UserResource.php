<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Post\PostResourceCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @var User $resource
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
        if (in_array('posts', $includes)) {
            $response['posts'] = new PostResourceCollection($this->resource->posts);
        }

        return $response;
    }
}
