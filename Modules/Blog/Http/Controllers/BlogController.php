<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Blog\Http\Requests\IndexBlog;
use Modules\Blog\Interfaces\BlogRepositoryInterface;
use Modules\Blog\Transformers\ArticleListingResource;
use Modules\Blog\Transformers\ArticleResource;

class BlogController extends Controller
{
    public function __construct(protected BlogRepositoryInterface $repository)
    {
    }

    public function index(IndexBlog $request): AnonymousResourceCollection
    {
        $data = $this->repository->getArticles($request->validated());

        return ArticleListingResource::collection($data);
    }

    public function show(int $articleId): array
    {
        $article = $this->repository->getArticleById($articleId);

        $similarArticles = $this->repository->getSimilarArticles($articleId, $article->tags->pluck('name')->toArray());

        return [
            'article' => ArticleResource::make($article),
            'similar_articles' => ArticleListingResource::collection($similarArticles)
        ];
    }

    public function incrementViewsCount(int $articleId): ArticleResource
    {
        $article = $this->repository->incrementArticleViewsCount($articleId);

        return ArticleResource::make($article);
    }

    public function tags()
    {
        return $this->repository->getAllTags();
    }
}
