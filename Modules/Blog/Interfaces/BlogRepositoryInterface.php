<?php

namespace Modules\Blog\Interfaces;

interface BlogRepositoryInterface
{
    public function getArticleById(int $articleId);

    public function getArticles(array $filters);

    public function getSimilarArticles(int $articleId, array $tags);

    public function getAllTags();

    public function incrementArticleViewsCount(int $articleId);
}
