<?php

namespace App\Http\Controllers;

use App\Enums\PostStatus;
use App\Http\Requests\Post\PostChangeStatusRequest;
use App\Http\Requests\Post\PostCreateRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\Post\PostResourceCollection;
use App\Models\Post;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class PostController extends Controller
{
    /**
     * PostController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show','store']);
    }

    /**
     * List of posts
     *
     * @OA\Get(
     *     path="/api/posts",
     *     operationId="/api/posts(GET)",
     *     summary="List of posts",
     *     tags={"Posts"},
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
     *         description="Posts per page",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="keys",
     *         in="query",
     *         description="Post keys",
     *         required=false,
     *         @OA\Schema(type="string", format="", example="")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by name and content",
     *         required=false,
     *         @OA\Schema(type="string", example="")
     *     ),
     *     @OA\Parameter(
     *         name="published_gte",
     *         in="query",
     *         description="Publish date is greater than",
     *         required=false,
     *         @OA\Schema(type="string", example="")
     *     ),
     *     @OA\Parameter(
     *         name="published_lte",
     *         in="query",
     *         description="Publish date less than",
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
     *         @OA\Schema(type="array", @OA\Items(type="string", example="author"))
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Return list of posts",
     *         @OA\JsonContent(example="")
     *     )
     * )
     *
     * Возвращает список адресов персоны
     * @param Request $request
     * @return PostResourceCollection
     * @throws AuthorizationException
     */
    public function index(Request $request): PostResourceCollection
    {
        $models = Post::query()->filter($request->all())->sort($request)->paginate($request->query('per_page'));

        return new PostResourceCollection($models);
    }

    /**
     * @OA\Post (
     *      path="/api/posts",
     *      operationId="/api/posts(POST)",
     *      summary="Create post",
     *      tags={"Posts"},
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/PostCreateRequest")
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="Return model of new post",
     *          @OA\JsonContent(example="")
     *      ),
     *      @OA\Response(
     *          response="422",
     *          description="Validation errors",
     *          @OA\JsonContent(example="")
     *      )
     * )
     * @param PostCreateRequest $request
     * @return PostResource
     */
    public function store(PostCreateRequest $request): PostResource
    {
        $model = Post::query()->create($request->validated());

        return new PostResource($model);
    }

    /**
     * Show the specified resource from storage.
     *
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     operationId="/api/posts/{id}(GET)",
     *     summary="Get post by id",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Post id",
     *         required=true,
     *         @OA\Schema(type="string", format="")
     *     ),
     *     @OA\Parameter(
     *          name="include",
     *          in="query",
     *          description="Includes",
     *          required=false,
     *          @OA\Schema(type="array", @OA\Items(type="string", example="author"))
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Get exists post",
     *     ),
     *     @OA\Response(response="401",description="Unauthorized"),
     *     @OA\Response(response="404",description="Not found"),
     * )
     * @param Post $post
     * @return PostResource
     */
    public function show(Post $post): PostResource
    {
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     operationId="/api/posts/{id}(DELETE)",
     *     summary="Delete post",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Post id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Delete exists post",
     *         @OA\JsonContent(example="")
     *     ),
     * )
     * @param Post $announcement
     * @return JsonResponse
     */
    public function destroy(Post $announcement): JsonResponse
    {
        $announcement->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Change status
     *
     * @OA\Patch(
     *     path="/api/post-change-status/{id}",
     *     operationId="/api/post-change-status/{id}(PATCH)",
     *     summary="Change post status",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Post id",
     *         required=true,
     *         @OA\Schema(type="string", format="")
     *     ),
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/PostChangeStatusRequest")
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Change post status",
     *         @OA\JsonContent(example="")
     *     )
     * )
     * @param PostChangeStatusRequest $request
     * @param Post $post
     * @return PostResource
     */
    public function changeStatus(PostChangeStatusRequest $request, Post $post,): PostResource
    {
        $post->update(
            [...$request->validated(), 'published_at' => $request['status'] === PostStatus::Published ? now() : null]
        );

        return new PostResource($post);
    }

    /**
     * @OA\Put (
     *      path="/api/posts/{id}",
     *      operationId="/api/posts/{id}(PUT)",
     *      summary="Update post",
     *      tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Post id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(ref="#/components/schemas/PostUpdateRequest")
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
     * @param PostUpdateRequest $request
     * @param Post $post
     * @return PostResource
     */
    public function update(PostUpdateRequest $request, Post $post): PostResource
    {
        $post->update($request->validated());

        return new PostResource($post);
    }
}
