<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserChangeStatusRequest;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserResourceCollection;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    /**
     * List of users
     *
     * @OA\Get(
     *     path="/api/users",
     *     operationId="/api/users(GET)",
     *     summary="List of users",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="sort_column",
     *         in="query",
     *         description="Sort column",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_direction",
     *         in="query",
     *         description="Sort direction",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Users per page",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="keys",
     *         in="query",
     *         description="User keys",
     *         required=false,
     *         @OA\Schema(type="string", format="", example="")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by name and email",
     *         required=false,
     *         @OA\Schema(type="string", example="")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Status",
     *         required=false,
     *         @OA\Schema(type="int", example="")
     *     ),
     *     @OA\Parameter(
     *         name="include",
     *         in="query",
     *         description="Includes",
     *         required=false,
     *         @OA\Schema(type="array", @OA\Items(type="string", example="posts"))
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Return list of users",
     *         @OA\JsonContent(example="")
     *     )
     * )
     *
     * Возвращает список адресов персоны
     * @param Request $request
     * @return UserResourceCollection
     * @throws AuthorizationException
     */
    public function index(Request $request): UserResourceCollection
    {
        $models = User::query()->filter($request->all())->sort($request)->paginate($request->query('per_page'));

        return new UserResourceCollection($models);
    }

    /**
     * @OA\Post (
     *       path="/api/users",
     *       operationId="/api/users(POST)",
     *       summary="Create user",
     *       tags={"Users"},
     *       security={{"bearerAuth":{}}},
     *       @OA\RequestBody(
     *           required=true,
     *           @OA\JsonContent(ref="#/components/schemas/UserCreateRequest")
     *       ),
     *       @OA\Response(
     *           response="200",
     *           description="Return model of new user",
     *           @OA\JsonContent(example="")
     *       ),
     *       @OA\Response(
     *           response="422",
     *           description="Validation errors",
     *           @OA\JsonContent(example="")
     *       )
     *  )
     * @param UserCreateRequest $request
     * @return UserResource
     */
    public function store(UserCreateRequest $request): UserResource
    {
        $model = User::query()->create($request->validated());

        return new UserResource($model);
    }

    /**
     * Show the specified resource from storage.
     *
     * @OA\Get(
     *     path="/api/users/{id}",
     *     operationId="/api/users/{id}(GET)",
     *     summary="Get user by id",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(type="string", format="")
     *     ),
     *     @OA\Parameter(
     *          name="include",
     *          in="query",
     *          description="Includes",
     *          required=false,
     *          @OA\Schema(type="array", @OA\Items(type="string", example="posts"))
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Get exists post",
     *     ),
     *     @OA\Response(response="401",description="Unauthorized"),
     *     @OA\Response(response="404",description="Not found"),
     * )
     * @param User $user
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     operationId="/api/users/{id}(DELETE)",
     *     summary="Delete user",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Delete exists post",
     *         @OA\JsonContent(example="")
     *     ),
     * )
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Change status
     *
     * @OA\Patch(
     *     path="/api/user-change-status/{id}",
     *     operationId="/api/user-change-status/{id}(PATCH)",
     *     summary="Change user status",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(type="string", format="")
     *     ),
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/UserChangeStatusRequest")
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Change post status",
     *         @OA\JsonContent(example="")
     *     )
     * )
     * @param UserChangeStatusRequest $request
     * @param User $user
     * @return UserResource
     */
    public function changeStatus(UserChangeStatusRequest $request, User $user): UserResource
    {
        $user->update($request->validated());

        return new UserResource($user);
    }

    /**
     * @OA\Put (
     *      path="/api/users/{id}",
     *      operationId="/api/users/{id}(PUT)",
     *      summary="Update user",
     *      tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(ref="#/components/schemas/UserUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Return post model",
     *         @OA\JsonContent(example="")
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation errors",
     *         @OA\JsonContent(example="")
     *     )
     * )
     * @param UserUpdateRequest $request
     * @param User $user
     * @return UserResource
     */
    public function update(UserUpdateRequest $request, User $user): UserResource
    {
        $user->update($request->validated());

        return new UserResource($user);
    }
}
