<?php

namespace Modules\Blog\Repositories\Interfaces;

use Modules\Blog\Entities\Article;

interface BlogRepositoryInterface
{
    public function getArticles(array $filters);

    public function getSimilarArticles(Article $article);

    public function getAllTags();
}