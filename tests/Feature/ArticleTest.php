<?php

namespace Tests\Feature;

use Modules\Blog\Database\factories\ArticleFactory;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    public function test_user_can_retrieve_articles()
    {
        ArticleFactory::new()->create();

        $this->get('/api/articles')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [['id', 'title', 'excerpt', 'views', 'created_at']]
            ]);
    }

    public function test_user_can_retrieve_article()
    {
        $user = $this->signIn();

        $article = ArticleFactory::new()->create();

        $user->like($article);

        $response = $this->get("/api/articles/{$article->id}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'article' => [
                    'id',
                    'title',
                    'body',
                    'views',
                    'tags',
                    'created_at',
                    'comments_count',
                    'reaction' => ['likes_count', 'dislikes_count', 'has_liked', 'has_disliked']
                ],
                'similar_articles' => []
            ]);

        $this->assertEquals(1, $response['article']['reaction']['likes_count']);
        $this->assertEquals(0, $response['article']['reaction']['dislikes_count']);

        $user->dislike($article);
        $this->assertEquals(0, $article->likes()->count());
        $this->assertEquals(1, $article->dislikes()->count());
    }

    public function test_user_can_increment_blog_views_count()
    {
        $article = ArticleFactory::new()->create();

        $this->put("/api/articles/{$article->id}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'title', 'body', 'views', 'tags', 'created_at'],
            ]);

        $this->assertEquals(1, $article->refresh()->views);
    }


}
