<?php

namespace Modules\Post\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Modules\Book\Transformers\CommentResource;
use Modules\Post\Http\Requests\CreatePostCommentRequest;
use Modules\Post\Http\Requests\GetPostCommentsRequest;
use Modules\Post\Http\Requests\UpdatePostCommentRequest;
use Modules\Post\Repositories\Interfaces\PostCommentRepositoryInterface;

class PostCommentController extends Controller
{
    protected PostCommentRepositoryInterface $commentRepository;

    public function __construct(PostCommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function index(Post $post, GetPostCommentsRequest $request)
    {
        $comments = $this->commentRepository->getPostComments($post, $request->validated());

        return CommentResource::collection($comments);
    }

    public function create(Post $post, CreatePostCommentRequest $request)
    {
        $comment = $this->commentRepository->createPostComment($post, $request->validated());

        return $comment ? response(['message' => 'Comment created!', 'data' => CommentResource::make($comment)]) : $this->failed();
    }

    public function update(Comment $comment, UpdatePostCommentRequest $request)
    {
        $this->authorize('updatePostComment', $comment);
        $affectedRows = $this->commentRepository->updatePostComment($comment, $request->validated());

        return $affectedRows > 0
            ? response(['message' => 'Comment updated!', 'data' => CommentResource::make($comment->refresh())])
            : $this->failed();
    }

    public function delete(Comment $comment)
    {
        $this->authorize('deletePostComment', $comment);
        $deleted = $this->commentRepository->deletePostComment($comment);

        return $deleted
            ? response(['message' => 'Comment deleted!'])
            : $this->failed();
    }
}