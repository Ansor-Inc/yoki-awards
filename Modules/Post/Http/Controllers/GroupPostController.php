<?php

namespace Modules\Post\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Post;
use Illuminate\Http\Request;
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
        $this->authorize('seePosts', $group);
        $posts = $this->groupPostRepository->getGroupPosts($group, $request->validated());

        return PostResource::collection($posts);
    }

    public function create(Group $group, CreateGroupPostRequest $request)
    {
        $this->authorize('createPost', $group);
        $post = $this->groupPostRepository->createPost($group, $request->validated());

        return $post ? response(['message' => 'Post created!', 'data' => PostResource::make($post)]) : $this->failed();
    }

    public function update(Post $post, UpdateGroupPostRequest $request)
    {
        $this->authorize('updatePost', [$post->group, $post]);
        $affectedRows = $this->groupPostRepository->updatePost($post, $request->validated());

        return $affectedRows > 0 ? response(['message' => 'Post updated!', 'data' => PostResource::make($post->refresh())]) : $this->failed();
    }

    public function delete(Post $post)
    {
        //$this->authorize('deletePost', [$group, $post]);
        $deleted = $this->groupPostRepository->deletePost($post);

        return $deleted ? response(['message' => 'Deleted successfully!']) : $this->failed();
    }

    public function toggleLike(Post $post, Request $request)
    {
        $liked = $this->groupPostRepository->togglePostLike($post, $request->user());

        return response(['liked' => $liked]);
    }
}
