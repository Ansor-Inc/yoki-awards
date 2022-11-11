<?php

namespace Modules\Post\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Policies\PostPolicy;
use Illuminate\Http\Request;
use Modules\Group\Entities\Group;
use Modules\Post\Entities\Post;
use Modules\Post\Http\Requests\CreateGroupPostRequest;
use Modules\Post\Http\Requests\GetGroupPostsRequest;
use Modules\Post\Http\Requests\UpdateGroupPostRequest;
use Modules\Post\Repositories\Interfaces\GroupPostRepositoryInterface;
use Modules\Post\Transformers\PostResource;

class GroupPostController extends Controller
{
    protected GroupPostRepositoryInterface $groupPostRepository;

    public function __construct(GroupPostRepositoryInterface $groupPostRepository)
    {
        $this->groupPostRepository = $groupPostRepository;
    }

    public function index(Group $group, GetGroupPostsRequest $request)
    {
        /* @see AuthorizesGroupPostActions::getPosts() */
        $this->authorize('getPosts', $group);

        $posts = $this->groupPostRepository->getGroupPosts($group, $request->validated());

        return PostResource::collection($posts);
    }

    public function show(Post $post)
    {
        return PostResource::make($post);
    }

    public function create(Group $group, CreateGroupPostRequest $request)
    {
        /* @see AuthorizesGroupPostActions::createPost() */
        $this->authorize('createPost', $group);

        $payload = array_merge($request->validated(), ['user_id' => auth()->id()]);
        $post = $this->groupPostRepository->createPost($group, $payload);

        return $post ? response(['message' => 'Post created!', 'data' => PostResource::make($post)]) : $this->failed();
    }

    public function update(Post $post, UpdateGroupPostRequest $request)
    {
        /* @see PostPolicy::update() */
        $this->authorize('update', $post);

        $affectedRows = $this->groupPostRepository->updatePost($post, $request->validated());

        return $affectedRows > 0 ? response(['message' => 'Post updated!', 'data' => PostResource::make($post->refresh())]) : $this->failed();
    }

    public function delete(Post $post)
    {
        /* @see PostPolicy::delete() */
        $this->authorize('delete', $post);

        $deleted = $this->groupPostRepository->deletePost($post);

        return $deleted ? response(['message' => 'Deleted successfully!']) : $this->failed();
    }

    public function toggleLike(Post $post, Request $request)
    {
        $liked = $this->groupPostRepository->togglePostLike($post, $request->user());

        return response(['liked' => $liked]);
    }
}
