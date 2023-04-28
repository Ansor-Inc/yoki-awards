<?php

namespace Tests\Feature;

use Modules\Blog\Database\factories\ArticleFactory;
use Tests\TestCase;

class ReactionTest extends TestCase
{
    public function test_user_can_toggle_like()
    {
        $user = $this->signIn();

        $article = ArticleFactory::new()->create();

        $this->post('/api/likes/toggle', [
            'likeable_type' => $article->getMorphClass(),
            'likeable_id' => $article->id
        ])->assertOk()
            ->assertJson([
                'has_liked' => true
            ]);

        $this->assertTrue($article->isLikedBy($user));

        $this->post('/api/likes/toggle', [
            'likeable_type' => $article->getMorphClass(),
            'likeable_id' => $article->id
        ])->assertOk()
            ->assertJson([
                'has_liked' => false
            ]);;

        $this->assertFalse($article->isLikedBy($user));
    }

    public function test_user_can_toggle_dislike()
    {
        $user = $this->signIn();

        $article = ArticleFactory::new()->create();

        $this->post('/api/dislikes/toggle', [
            'dislikeable_type' => $article->getMorphClass(),
            'dislikeable_id' => $article->id
        ])->assertOk()
            ->assertJson([
                'has_disliked' => true
            ]);

        $this->assertTrue($article->isDisLikedBy($user));

        $this->post('/api/dislikes/toggle', [
            'dislikeable_type' => $article->getMorphClass(),
            'dislikeable_id' => $article->id
        ])->assertOk()
            ->assertJson([
                'has_disliked' => false
            ]);;

        $this->assertFalse($article->isDisLikedBy($user));
    }

    public function test_user_cannot_like_and_dislike_at_the_same_time()
    {
        $user = $this->signIn();

        $article = ArticleFactory::new()->create();

        $this->post('/api/likes/toggle', [
            'likeable_type' => $article->getMorphClass(),
            'likeable_id' => $article->getKey()
        ])->assertOk();

        $this->assertTrue($article->isLikedBy($user));

        $this->post('/api/dislikes/toggle', [
            'dislikeable_type' => $article->getMorphClass(),
            'dislikeable_id' => $article->getKey()
        ])->assertOk();

        $this->assertTrue($article->isDisLikedBy($user));
        $this->assertFalse($article->isLikedBy($user));
    }
}
