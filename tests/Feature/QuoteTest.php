<?php

namespace Tests\Feature;

use Modules\Book\Database\factories\BookFactory;
use Tests\TestCase;

class QuoteTest extends TestCase
{
    public function test_book_has_quotes()
    {
        $book = BookFactory::new()->create();

        $this->get("/api/books/{$book->id}/quotes")
            ->assertOk();
    }

    public function test_user_can_create_quote_for_book()
    {
        $user = $this->signIn();
        $book = BookFactory::new()->create();

        $this->postJson("/api/books/{$book->id}/quotes", [
            'body' => 'test quote'
        ])->assertStatus(201);

        $this->assertDatabaseHas('quotes', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'body' => 'test quote'
        ]);
    }

    public function test_user_can_edit_quote_for_book()
    {
        $user = $this->signIn();
        $book = BookFactory::new()->create();

        $response = $this->postJson("/api/books/{$book->id}/quotes", ['body' => 'test quote'])
            ->assertStatus(201);

        $this->putJson("/api/quotes/{$response['data']['id']}", ['body' => 'test quote edited'])
            ->assertOk();

        $this->assertDatabaseHas('quotes', [
            'id' => $response['data']['id'],
            'user_id' => $user->id,
            'book_id' => $book->id,
            'body' => 'test quote edited'
        ]);
    }
}
