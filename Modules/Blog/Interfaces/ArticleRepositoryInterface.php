<?php

namespace Modules\Blog\Interfaces;

use Modules\Blog\Entities\Article;
use Modules\User\Entities\User;

interface ArticleRepositoryInterface
{
    public function getUserArticles(User $user, array $filters);

    public function getArticles(array $filters);

    public function getSimilarArticles(Article $article);

    public function getAllTags();

    public function storeArticle(array $payload);

    public function updateArticle(Article $article, array $payload);

    public function incrementArticleViewsCount(int $articleId);
}
