<?php

namespace Modules\Book\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Book\Entities\Book;
use Modules\Book\Http\Requests\StoreBookCommentRequest;
use Modules\Book\Http\Requests\UpdateBookCommentRequest;
use Modules\Book\Transformers\CommentResource;
use Modules\Comment\Entities\Comment;

class BookCommentController extends Controller
{
    public function index(Book $book, Request $request)
    {
        $query = $book->comments()
            ->whereNull('reply_id')
            ->with(['descendants', 'user:id,fullname,avatar'])
            ->latest();

        $comments = $request->has('per_page')
            ? $query->paginate($request->input('per_page'))
            : $query->get();


        return CommentResource::collection($comments);
    }

    public function store(Book $book, StoreBookCommentRequest $request)
    {
        $comment = $book->comments()->create($request->validated());

        return response()->json([
            'message' => 'Comment created successfully!',
            'comment' => CommentResource::make($comment)
        ]);
    }

    public function update(Comment $comment, UpdateBookCommentRequest $request)
    {
        $this->authorize('update', $comment);
        $comment->update($request->validated());

        return response()->json([
            'message' => 'Comment updated successfully!',
            'comment' => CommentResource::make($comment)
        ]);
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully!',
        ]);
    }
}
