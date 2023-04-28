<?php

namespace Modules\Blog\Http\Controllers;

use Modules\Blog\Entities\Article;
use Modules\Blog\Http\Requests\GetArticleCommentsRequest;
use Modules\Blog\Http\Requests\StoreArticleCommentRequest;
use Modules\Blog\Interfaces\ArticleCommentRepositoryInterface;
use Modules\Comment\Transformers\CommentResource;

class ArticleCommentController
{
    public function __construct(protected ArticleCommentRepositoryInterface $blogCommentRepository)
    {
    }

    public function index(Article $article, GetArticleCommentsRequest $request)
    {
        $comments = $this->blogCommentRepository->getArticleComments($article, $request->validated());

        return CommentResource::collection($comments);
    }

    public function store(Article $article, StoreArticleCommentRequest $request)
    {
        $comment = $this->blogCommentRepository->storeArticleComment($article, array_merge($request->validated(), [
            'user_id' => auth()->id()
        ]));

        return response(['message' => 'Success!', 'comment' => CommentResource::make($comment)]);
    }
}
