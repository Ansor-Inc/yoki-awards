<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\Tag;
use App\Repositories\Interfaces\BlogRepositoryInterface;
use Illuminate\Support\Facades\DB;

class BlogRepository implements BlogRepositoryInterface
{

    public function getArticles(array $filters)
    {
        $query = Article::query()->filter($filters);

        if (isset($filters['limit'])) {
            $data = $query->paginate($filters['limit']);
        } else {
            $data = $query->get();
        }

        return $data;
    }

    public function getArticleById(int $id)
    {
        return Article::query()->find($id);
    }

    public function getSimilarArticles(int $id)
    {
        $article = $this->getArticleById($id);
        $tags = $article->tags->pluck('name')->toArray();

        return Article::query()
            ->whereNot('id', $article->id)
            ->whereHas('tags', fn($query) => $query->whereIn('name', $tags))
            ->limit(4)
            ->get();
    }

    public function getAllTags()
    {
        return DB::table('taggables')
            ->join('tags', 'tags.id', '=', 'taggables.tag_id')
            ->where('taggable_type', Article::class)
            ->select('name')
            ->distinct()
            ->pluck('name');
    }
}