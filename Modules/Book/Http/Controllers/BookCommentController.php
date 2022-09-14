<?php

namespace Modules\Book\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Comment;
use Illuminate\Http\Request;
use Modules\Book\Http\Requests\StoreBookCommentRequest;
use Modules\Book\Http\Requests\UpdateBookCommentRequest;
use Modules\Book\Transformers\CommentResource;

class BookCommentController extends Controller
{
    public function index(Book $book, Request $request)
    {
        $query = $book->comments()
            ->latest()
            ->whereNull('reply_id');

        if ($request->has('per_page')) {
            $comments = $query->paginate($request->input('per_page'));
        } else {
            $comments = $query->get();
        }

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
