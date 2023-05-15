<?php

namespace Modules\Blog\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as CollectionAlias;
use Illuminate\Support\Facades\DB;
use Modules\Blog\Entities\Article;
use Modules\Blog\Interfaces\ArticleRepositoryInterface;
use Modules\User\Entities\User;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function getArticles(array $filters)
    {
        $query = Article::query()
            ->filter($filters)
            ->latest();

        return isset($filters['per_page']) ? $query->paginate($filters['per_page']) : $query->get();
    }

    public function getSimilarArticles(Article $article): Collection|array
    {
        return Article::query()
            ->whereNot('id', $article->id)
            ->whereHas('tags', fn($query) => $query->whereIn('name', $article->tags->pluck('name')->toArray()))
            ->limit(4)
            ->get();
    }

    public function getAllTags(): CollectionAlias
    {
        return app(Article::class)->getModelTags();
    }

    public function incrementArticleViewsCount(int $articleId): Model|Collection|Builder|array|null
    {
        $article = Article::query()->findOrFail($articleId);
        $article->increment('views');
        return $article;
    }

    public function storeArticle(array $payload)
    {
        return Article::query()->create($payload);
    }

    public function getUserArticles(User $user, array $filters)
    {
        $query = $user->articles()
            ->withoutGlobalScopes()
            ->filter($filters);

        return isset($filters['per_page']) ? $query->paginate($filters['per_page']) : $query->get();
    }

    public function updateArticle(Article $article, array $payload): void
    {
        DB::transaction(function () use ($article, $payload) {
            $article->update($payload);
            if (isset($payload['tags'])) {
                $article->syncTags($payload['tags']);
            }
        });
    }
}
