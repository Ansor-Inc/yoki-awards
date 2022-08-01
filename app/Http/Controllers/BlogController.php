<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexBlog;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Tag;
use App\Repositories\Interfaces\BlogRepositoryInterface;
use Illuminate\Support\Facades\DB;

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

        return ArticleResource::collection($data);
    }

    public function show(int $id)
    {
        $article = $this->repository->getArticleById($id);

        if ($article === null) {
            return [
                'message' => "Not found!"
            ];
        }

        $similarArticles = $this->repository->getSimilarArticles($id);

        return [
            'article' => ArticleResource::make($article),
            'similar_articles' => ArticleResource::collection($similarArticles)
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
