<?php

namespace Modules\Post\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Modules\Book\Transformers\CommentResource;
use Modules\Comment\Entities\Comment;
use Modules\Post\Entities\Post;
use Modules\Post\Http\Requests\ComplainToCommentRequest;
use Modules\Post\Http\Requests\CreatePostCommentRequest;
use Modules\Post\Http\Requests\GetPostCommentsRequest;
use Modules\Post\Http\Requests\UpdatePostCommentRequest;
use Modules\Post\Notifications\UserComplainedAboutPostComment;
use Modules\Post\Repositories\Interfaces\PostCommentRepositoryInterface;

class PostCommentController extends Controller
{
    protected PostCommentRepositoryInterface $commentRepository;

    public function __construct(PostCommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function index(Post $post, GetPostCommentsRequest $request): AnonymousResourceCollection
    {
        $comments = $this->commentRepository->getPostComments($post, $request->validated());

        return CommentResource::collection($comments);
    }

    public function create(Post $post, CreatePostCommentRequest $request): Response|Application|ResponseFactory
    {
        $this->authorize('createPostComment', [Comment::class, $post]);

        $comment = $this->commentRepository->createPostComment($post, $request->validated());

        return $comment ? response(['message' => 'Comment created!', 'data' => CommentResource::make($comment)]) : $this->failed();
    }

    public function update(Comment $comment, UpdatePostCommentRequest $request): Response|Application|ResponseFactory
    {
        $this->authorize('update', $comment);

        $affectedRows = $this->commentRepository->updatePostComment($comment, $request->validated());

        return $affectedRows > 0
            ? response(['message' => 'Comment updated!', 'data' => CommentResource::make($comment->refresh())])
            : $this->failed();
    }

    public function delete(Comment $comment): Response|Application|ResponseFactory
    {
        $this->authorize('delete', $comment);

        $deleted = $this->commentRepository->deletePostComment($comment);

        return $deleted
            ? response(['message' => 'Comment deleted!'])
            : $this->failed();
    }

    public function complain(Comment $comment, ComplainToCommentRequest $request)
    {
        $this->authorize('complain', $comment);

        $complaint = $this->commentRepository->complain($comment, $request->input('body'));

        if ($complaint) {
            Notification::send(AdminUser::all(), new UserComplainedAboutPostComment($complaint));
            return response(['message' => 'Complaint sent successfully!']);
        }

        return $this->failed();
    }
}
