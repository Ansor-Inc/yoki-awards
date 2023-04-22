<?php

namespace Modules\Blog\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Blog\Entities\Article;
use Modules\Blog\Interfaces\BlogRepositoryInterface;
use Modules\User\Entities\User;

class BlogRepository implements BlogRepositoryInterface
{
    public function getArticleById(int $articleId): Model|Collection|Builder|array|null
    {
        return Article::query()->findOrFail($articleId);
    }

    public function getArticles(array $filters)
    {
        $query = Article::query()->withoutEagerLoads()->filter($filters);

        return isset($filters['per_page']) ? $query->paginate($filters['per_page']) : $query->get();
    }

    public function getSimilarArticles(int $articleId, array $tags): Collection|array
    {
        return Article::query()
            ->whereNot('id', $articleId)
            ->whereHas('tags', fn($query) => $query->whereIn('name', $tags))
            ->limit(4)
            ->get();
    }

    public function getAllTags(): \Illuminate\Support\Collection
    {
        return DB::table('taggables')
            ->join('tags', 'tags.id', '=', 'taggables.tag_id')
            ->where('taggable_type', Article::class)
            ->select('name')
            ->distinct()
            ->pluck('name');
    }

    public function incrementArticleViewsCount(int $articleId): Model|Collection|Builder|array|null
    {
        $article = $this->getArticleById($articleId);
        $article->increment('views');
        return $article;
    }

    public function storeArticle(array $payload)
    {
        return Article::query()->create($payload);
    }

    public function publishArticle(int $articleId)
    {
        DB::table('articles')->where('id', $articleId)->update(['published' => true]);
    }

    public function getUserArticles(User $user)
    {
        return $user->articles()->withoutGlobalScopes()->get();
    }
}
