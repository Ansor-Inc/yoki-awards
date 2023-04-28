<?php

namespace Modules\Comment\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Comment\Entities\Comment;
use Modules\Comment\Http\Requests\GetCommentsRequest;
use Modules\Comment\Http\Requests\StoreCommentRequest;
use Modules\Comment\Http\Requests\UpdateCommentRequest;
use Modules\Comment\Interfaces\CommentRepositoryInterface;
use Modules\Comment\Transformers\CommentResource;

class CommentController extends Controller
{
    public function __construct(protected CommentRepositoryInterface $commentRepository)
    {
    }

    public function index(GetCommentsRequest $request)
    {
        $comments = $this->commentRepository->getComments($request->validated());

        return CommentResource::collection($comments);
    }

    public function show(Comment $comment)
    {
        return CommentResource::make($comment);
    }

    public function store(StoreCommentRequest $request)
    {
        $comment = $this->commentRepository->storeComment($request->validated());

        return response(['message' => 'success', 'data' => CommentResource::make($comment)]);
    }

    public function update(Comment $comment, UpdateCommentRequest $request)
    {
        $updated = $this->commentRepository->updateComment($comment, $request->validated());

        return $updated ? response(['message' => 'success']) : $this->failed();
    }

    public function destroy(Comment $comment)
    {
        $this->commentRepository->deleteComment($comment);

        return response(['message' => 'success']);
    }
}
