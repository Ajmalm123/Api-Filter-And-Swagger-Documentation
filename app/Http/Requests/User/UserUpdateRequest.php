<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      required={"name", },
 *      schema="UserUpdateRequest",
 *      @OA\Property(property="name", type="string", example="", description="User name"),
 *      @OA\Property(property="email", type="string", example="", description="User email"),
 *      @OA\Property(property="password", type="string", example="0", description="User password"),
 * )
 */
class UserUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|max:255',
        ];
    }
}
