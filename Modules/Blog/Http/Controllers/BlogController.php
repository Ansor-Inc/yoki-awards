<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Blog\Entities\Article;
use Modules\Blog\Http\Requests\IndexBlog;
use Modules\Blog\Repositories\Interfaces\BlogRepositoryInterface;
use Modules\Blog\Transformers\ArticleListingResource;
use Modules\Blog\Transformers\ArticleResource;

class BlogController extends Controller
{
    protected BlogRepositoryInterface $repository;

    public function __construct(BlogRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(IndexBlog $request)
    {
        $data = $this->repository->getArticles($request->validated());

        return ArticleListingResource::collection($data);
    }

    public function show(Article $article)
    {
        $article->load('tags:name');

        $similarArticles = $this->repository->getSimilarArticles($article);

        return [
            'article' => ArticleResource::make($article),
            'similar_articles' => ArticleListingResource::collection($similarArticles)
        ];
    }

    public function incrementViewsCount(Article $article)
    {
        $article->increment('views');

        return ArticleResource::make($article);
    }

    public function tags()
    {
        return $this->repository->getAllTags();
    }
}
