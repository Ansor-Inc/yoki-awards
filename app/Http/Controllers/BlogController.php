<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexBlog;
use App\Http\Resources\ArticleListingResource;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Repositories\Interfaces\BlogRepositoryInterface;

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
