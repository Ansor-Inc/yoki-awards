<?php

namespace Modules\Blog\Repositories;

use Modules\Blog\Entities\Article;
use Modules\Blog\Interfaces\ArticleCommentRepositoryInterface;

class ArticleCommentRepository implements ArticleCommentRepositoryInterface
{

    public function getArticleComments(Article $article, array $filters)
    {
        $query = $article
            ->comments()
            ->with(['user:id,fullname,avatar', 'userLike'])
            ->withCount(['likes', 'dislikes'])
            ->latest()
            ->filter($filters);

        return isset($filters['per_page']) ? $query->paginate($filters['per_page']) : $query->get();
    }

    public function storeArticleComment(Article $article, array $payload)
    {
        return $article->comments()->create($payload);
    }
}
