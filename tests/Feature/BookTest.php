<?php

namespace Tests\Feature;

use Illuminate\Testing\Fluent\AssertableJson;
use Modules\Book\Database\factories\BookFactory;
use Modules\Book\Entities\Book;
use Modules\Book\Transformers\BookListingResource;
use Modules\User\Database\factories\UserFactory;
use Tests\TestCase;

class BookTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_retrieve_books(): void
    {
        $book = BookFactory::new()->create();

        $response = $this->get('/api/books');

        $response->assertStatus(200);
    }

    public function test_user_can_search_books_by_title()
    {
        $book = $this->createBook();

        $response = $this->get("/api/books/search?query={$book->title}");

        $response->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json->has('data', 1, fn($json) => $json->where('title', $book->title)->etc()));
    }

    public function test_user_can_search_books_by_author_firstname()
    {
        $book = $this->createBook();

        $response = $this->get("/api/books/search?query={$book->author->firstname}");

        $response->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json->has('data', 1, fn($json) => $json->where('title', $book->title)->etc()));
    }

    public function test_user_can_search_books_by_author_lastname()
    {
        $book = $this->createBook();

        $response = $this->get("/api/books/search?query={$book->author->lastname}");

        $response->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json->has('data', 1, fn($json) => $json->where('title', $book->title)->etc()));
    }

    public function test_user_cannot_access_paid_book_without_buying()
    {
        $user = $this->createUser();
        $book = BookFactory::new()->create(['is_free' => false]);

        $this->assertFalse($book->isBoughtBy($user));


    }

    public function createBook()
    {
        return BookFactory::new()->create();
    }

    private function createUser()
    {
        return UserFactory::new()->create();
    }
}
