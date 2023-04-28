<?php

namespace Tests\Feature;

use Database\Seeders\UserRolesAndPermissionsSeeder;
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

    public function test_ordinary_user_cannot_create_article()
    {
        $this->signIn();

        $payload = [
            'title' => 'title',
            'body' => 'body'
        ];

        $this->postJson('/api/articles', $payload)->assertStatus(403);

        $this->assertDatabaseMissing('articles', $payload);
    }

    public function test_user_with_role_amateur_blogger_can_create_article()
    {
        $user = $this->signIn();

        $this->seed(UserRolesAndPermissionsSeeder::class);

        $user->assignRole('amateur_blogger');

        $payload = [
            'title' => 'title',
            'body' => 'body'
        ];

        $response = $this->postJson('/api/articles', $payload)
            ->assertStatus(200);

        $this->postJson("/api/articles/{$response['article']['id']}/publish")
            ->assertStatus(403);

        $this->assertDatabaseHas('articles', array_merge($payload, [
            'published' => false
        ]));
    }

    public function test_user_with_role_blogger_can_publish_article()
    {
        $user = $this->signIn();

        $this->seed(UserRolesAndPermissionsSeeder::class);

        $user->assignRole('blogger');

        $payload = [
            'title' => 'title',
            'body' => 'body'
        ];

        $response = $this->postJson('/api/articles', $payload)->assertStatus(200);
        $this->postJson("/api/articles/{$response['article']['id']}/publish");

        $this->assertDatabaseHas('articles', array_merge($payload, [
            'published' => true
        ]));
    }

    public function test_user_can_retrieve_his_articles()
    {
        $user = $this->signIn();

        $user->articles()->create(['title' => 'test', 'body' => 'body']);

        $response = $this->getJson('/api/me/articles')
            ->assertOk();

        $response->assertJson([
            'data' => [
                ['title' => 'test', 'excerpt' => 'body', 'published' => false]
            ]
        ]);
    }

}
