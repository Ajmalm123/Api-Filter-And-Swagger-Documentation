<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      required={"title"},
 *      schema="PostCreateRequest",
 *      @OA\Property(property="title", type="string", example="", description="Post title"),
 *      @OA\Property(property="content", type="string", example="", description="Post content"),
 *      @OA\Property(property="status", type="int", example="0", description="Post status"),
 * )
 */
class PostCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'status' => 'nullable|integer|between:0,1',
        ];
    }
}
