<?php

namespace Tests\Feature;

use Modules\Blog\Database\factories\BlogFactory;
use Tests\TestCase;

class BlogTest extends TestCase
{
    public function test_user_can_retrieve_articles()
    {
        BlogFactory::new()->create();

        $this->get('/api/articles')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [['id', 'title', 'excerpt', 'views', 'created_at']]
            ]);
    }

    public function test_user_can_retrieve_article()
    {
        $article = BlogFactory::new()->create();

        $this->get("/api/articles/{$article->id}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'article' => ['id', 'title', 'body', 'views', 'tags', 'created_at'],
                'similar_articles' => []
            ]);
    }

    public function test_user_can_increment_blog_views_count()
    {
        $article = BlogFactory::new()->create();

        $this->put("/api/articles/{$article->id}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'title', 'body', 'views', 'tags', 'created_at'],
            ]);

        $this->assertEquals(1, $article->refresh()->views);
    }

}
