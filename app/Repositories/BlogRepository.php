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
        $query = Article::query()->withoutEagerLoads()->filter($filters);

        return isset($filters['per_page']) ? $query->paginate($filters['per_page']) : $query->get();
    }

    public function getSimilarArticles($article)
    {
        return Article::query()
            ->whereNot('id', $article->id)
            ->whereHas('tags', fn($query) => $query->whereIn('name', $article->tags->pluck('name')))
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