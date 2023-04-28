<?php

namespace Tests\Feature;

use Modules\Blog\Database\factories\ArticleFactory;
use Tests\TestCase;

class ArticleCommentsTest extends TestCase
{
    public function test_users_can_retrieve_article_comments()
    {
        $user = $this->signIn();

        $article = ArticleFactory::new()->create();

        $article->comments()->create([
            'body' => 'test comment',
            'user_id' => $user->id
        ]);

        $this->get("/api/articles/{$article->id}/comments")
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'body',
                        'user' => ['id', 'fullname', 'avatar'],
                        'created_date',
                        'created_at',
                        'created_at_human_readable',
                        'reaction' => ['likes_count', 'dislikes_count', 'has_liked', 'has_disliked']
                    ]
                ]
            ]);
    }

    public function test_user_can_comment_on_article()
    {
        $user = $this->signIn();

        $article = ArticleFactory::new()->create();

        $this->postJson("/api/articles/{$article->id}/comments", [
            'body' => 'comment'
        ])->assertOk();

        $this->assertDatabaseHas('comments', [
            'body' => 'comment',
            'user_id' => $user->id,
            'commentable_type' => 'article',
            'commentable_id' => $article->id
        ]);
    }


}
