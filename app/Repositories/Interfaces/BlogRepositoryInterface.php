<?php

namespace App\Repositories\Interfaces;

interface BlogRepositoryInterface
{
    public function getArticles(array $filters);

    public function getArticleById(int $id);

    public function getSimilarArticles(int $id);

    public function getAllTags();
}