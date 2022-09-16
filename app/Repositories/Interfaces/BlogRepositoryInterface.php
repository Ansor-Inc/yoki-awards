<?php

namespace App\Repositories\Interfaces;

use App\Models\Article;

interface BlogRepositoryInterface
{
    public function getArticles(array $filters);

    public function getSimilarArticles(Article $article);

    public function getAllTags();
}