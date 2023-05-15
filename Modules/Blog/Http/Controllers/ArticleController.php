<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Blog\Entities\Article;
use Modules\Blog\Http\Requests\GetArticlesRequest;
use Modules\Blog\Interfaces\ArticleRepositoryInterface;
use Modules\Blog\Transformers\ArticleListingResource;
use Modules\Blog\Transformers\ArticleResource;

class ArticleController extends Controller
{
    public function __construct(protected ArticleRepositoryInterface $repository)
    {
    }

    public function index(GetArticlesRequest $request): AnonymousResourceCollection
    {
        $data = $this->repository->getArticles($request->validated());

        return ArticleListingResource::collection($data);
    }

    public function show(Article $article): array
    {
        $article->load('userLike')
            ->loadCount('likes', 'dislikes', 'comments');

        $similarArticles = $this->repository->getSimilarArticles($article);

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
