<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Blog\Actions\StoreArticleAction;
use Modules\Blog\Actions\StoreArticleToDraft;
use Modules\Blog\Entities\Article;
use Modules\Blog\Http\Requests\GetUserArticlesRequest;
use Modules\Blog\Http\Requests\StoreArticleRequest;
use Modules\Blog\Http\Requests\UpdateArticleRequest;
use Modules\Blog\Interfaces\ArticleRepositoryInterface;
use Modules\Blog\Transformers\ArticleListingResource;
use Modules\Blog\Transformers\ArticleResource;

class UserArticleController extends Controller
{
    public function __construct(protected ArticleRepositoryInterface $repository)
    {
    }

    public function index(GetUserArticlesRequest $request)
    {
        $articles = $this->repository->getUserArticles(auth()->user(), $request->validated());

        return ArticleListingResource::collection($articles);
    }

    public function show(Article $articleWithoutScopes)
    {
        $this->authorize('own', $articleWithoutScopes);

        return ArticleResource::make($articleWithoutScopes);
    }

    public function store(StoreArticleRequest $request, StoreArticleAction $storeArticleAction)
    {
        $article = $storeArticleAction->execute($request->validated());

        return $this->success(['article' => ArticleResource::make($article)]);
    }

    public function update(Article $articleWithoutScopes, UpdateArticleRequest $request)
    {
        $this->repository->updateArticle($articleWithoutScopes, $request->validated());

        return $this->success();
    }

    public function destroy(Article $articleWithoutScopes)
    {
        $this->authorize('own', $articleWithoutScopes);

        $articleWithoutScopes->delete();

        return $this->success();
    }

    public function saveToDraft(StoreArticleRequest $request, StoreArticleToDraft $storeArticleToDraft)
    {
        $article = $storeArticleToDraft->execute($request->validated());

        return $this->success(['article' => ArticleResource::make($article)]);
    }
}
