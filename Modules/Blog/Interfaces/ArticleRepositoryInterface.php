<?php

namespace Modules\Blog\Interfaces;

use Modules\User\Entities\User;

interface ArticleRepositoryInterface
{
    public function getArticleById(int $articleId);

    public function getUserArticles(User $user);

    public function getArticles(array $filters);

    public function getSimilarArticles(int $articleId, array $tags);

    public function getAllTags();

    public function storeArticle(array $payload);

    public function publishArticle(int $articleId);

    public function incrementArticleViewsCount(int $articleId);
}
