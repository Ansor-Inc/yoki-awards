<?php

namespace Tests\Feature;

use Database\Seeders\UserRolesAndPermissionsSeeder;
use Modules\Blog\Database\factories\BlogFactory;
use Spatie\Permission\Models\Permission;
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
