<?php

namespace Tests\Feature;

use Modules\Blog\Entities\Article;
use Modules\Blog\Enums\ArticleStatus;
use Modules\User\Enums\UserRole;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserArticlesTest extends TestCase
{
    public function test_ordinary_user_cannot_create_article()
    {
        $this->signIn();

        $payload = [
            'title' => 'title',
            'body' => 'body',
            'group_link' => 'link'
        ];

        $this->postJson('/api/articles', $payload)->assertStatus(403);

        $this->assertDatabaseMissing('articles', $payload);
    }

    public function test_user_with_role_approved_blogger_can_create_article()
    {
        $user = $this->signIn();

        $role = Role::create(['name' => UserRole::APPROVED_BLOGGER->value, 'guard_name' => 'sanctum']);
        $user->assignRole($role);

        $payload = [
            'title' => 'title',
            'body' => 'body'
        ];

        $tags = ['tags' => ['tag1', 'tag2']];

        $response = $this->postJson('/api/articles', array_merge($payload, $tags))
            ->assertStatus(200);

        $this->assertDatabaseHas('articles', array_merge($payload, [
            'status' => ArticleStatus::PUBLISHED->value
        ]));

        $article = Article::query()->find($response['data']['article']['id']);

        $this->assertEquals($tags['tags'], $article->tags->pluck('name')->toArray());
    }

    public function test_user_with_role_blogger_can_create_article()
    {
        $user = $this->signIn();

        $role = Role::create(['name' => UserRole::BLOGGER->value, 'guard_name' => 'sanctum']);
        $user->assignRole($role);

        $payload = [
            'title' => 'title',
            'body' => 'body',
            'group_link' => 'link'
        ];

        $this->postJson('/api/articles', $payload)
            ->assertStatus(200);

        $this->assertDatabaseHas('articles', array_merge($payload, [
            'status' => ArticleStatus::PENDING_APPROVAL->value
        ]));
    }

    public function test_user_can_retrieve_his_articles()
    {
        $user = $this->signIn();

        $user->articles()->create(['title' => 'test', 'body' => 'body', 'status' => ArticleStatus::PENDING_APPROVAL->value]);

        $response = $this->getJson('/api/me/articles')
            ->assertOk();

        $response->assertJsonStructure([
            'data' => [
                ['title', 'created_at', 'status']
            ]
        ]);
    }

    public function test_user_can_see_his_article()
    {
        $user = $this->signIn();

        $payload = [
            'title' => 'test',
            'body' => 'body',
            'group_link' => 'link',
            'status' => ArticleStatus::PENDING_APPROVAL->value
        ];

        $article = $user->articles()->create($payload);

        $this->getJson("/api/me/articles/{$article->id}")
            ->assertOk()
            ->assertJsonStructure(['data' => ['id', 'title', 'body', 'tags', 'created_at', 'group_link']]);
    }

    public function test_user_can_save_article_to_draft()
    {
        $user = $this->signIn();
        $role = Role::create(['name' => UserRole::BLOGGER->value, 'guard_name' => 'sanctum']);
        $user->assignRole($role);

        $payload = [
            'title' => 'title',
            'body' => 'body',
            'group_link' => 'link'
        ];

        $tags = ['tags' => ['tag1', 'tag2']];

        $response = $this->postJson('/api/articles/draft', array_merge($payload, $tags))
            ->assertStatus(200);

        $this->assertDatabaseHas('articles', array_merge($payload, [
            'status' => ArticleStatus::DRAFT->value
        ]));

        $article = Article::withoutGlobalScopes()->find($response['data']['article']['id']);
        $this->assertEquals($tags['tags'], $article->tags->pluck('name')->toArray());
    }

    public function test_user_can_edit_his_articles()
    {
        $user = $this->signIn();

        $payload = [
            'title' => 'test',
            'body' => 'test body',
            'group_link' => 'link',
            'status' => ArticleStatus::PENDING_APPROVAL->value
        ];

        $tags = ['tags' => ['tag1', 'tag2']];

        $article = $user->articles()->create(array_merge($payload, $tags));

        $this->putJson("/api/articles/{$article->id}/edit", [
            'title' => 'updated'
        ])->assertOk();

        $this->signIn();

        $this->putJson("/api/articles/{$article->id}/edit", [
            'title' => 'updated'
        ])->assertForbidden();

        $this->assertDatabaseHas('articles', [
            'title' => 'updated'
        ]);
    }

    public function test_user_can_delete_their_articles()
    {
        $user = $this->signIn();

        $payload = [
            'title' => 'test',
            'body' => 'body',
            'group_link' => 'link',
            'status' => ArticleStatus::PENDING_APPROVAL->value
        ];

        $article = $user->articles()->create($payload);

        $this->assertDatabaseHas('articles', $payload);

        $this->deleteJson("/api/articles/{$article->id}")
            ->assertOk();

        $this->assertDatabaseMissing('articles', $payload);
    }
}
