<?php

namespace Modules\Blog\Interfaces;

use Modules\Blog\Entities\Article;

interface ArticleCommentRepositoryInterface
{
    public function getArticleComments(Article $article, array $filters);

    public function storeArticleComment(Article $article, array $payload);
}
