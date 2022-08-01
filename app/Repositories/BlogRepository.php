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

        return $article->tags
            ->map(fn($tag) => $tag->articles()->limit(4)->get())
            ->collapse();
    }

    public function getAllTags()
    {
        $tagIds = DB::table('taggables')->where('taggable_type', Article::class)
            ->get('tag_id')
            ->pluck('tag_id');

        return Tag::query()->whereIn('id', $tagIds)->get('name')->pluck('name');
    }
}