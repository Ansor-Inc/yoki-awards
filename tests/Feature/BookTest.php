<?php

namespace Tests\Feature;

use Illuminate\Testing\Fluent\AssertableJson;
use Modules\Book\Database\factories\BookFactory;
use Modules\User\Database\factories\UserFactory;
use Tests\TestCase;

class BookTest extends TestCase
{
    public function test_user_can_retrieve_books(): void
    {
        BookFactory::new()->create();

        $response = $this->getJson('/api/books');

        $response->assertStatus(200);
    }

    public function test_user_can_retrieve_latest_books()
    {
        $response = $this->getJson('/api/books?latest&limit=6');
        $response->assertStatus(200);
    }

    public function test_user_can_search_books_by_title_and_by_authors_firstname_lastname()
    {
        $book = BookFactory::new()->create();

        $this->getJson("/api/books/search?query={$book->title}")
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json->has('data', 1, fn($json) => $json->where('title', $book->title)->etc()));

        $this->getJson("/api/books/search?query={$book->author->firstname}")
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json->has('data', 1, fn($json) => $json->where('title', $book->title)->etc()));

        $this->getJson("/api/books/search?query={$book->author->lastname}")
            ->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json->has('data', 1, fn($json) => $json->where('title', $book->title)->etc()));
    }

    public function test_user_cannot_access_paid_book_without_buying_it()
    {
        $user = $this->createUser();
        $book = BookFactory::new()->create(['is_free' => false]);

        $this->assertFalse($book->isBoughtBy($user));
    }

    public function test_foreign_users_cannot_see_paid_books()
    {
        $user = $this->createUser();
        $book = BookFactory::new()->create(['is_free' => false]);
        $localIp = '185.139.137.51';
        $foreignIp = '69.162.81.155';

        $this->withServerVariables(['HTTP_DO_CONNECTING_IP' => $foreignIp])
            ->actingAs($user)
            ->getJson("/api/books/{$book->id}")
            ->assertStatus(404);

        $this->withServerVariables(['HTTP_DO_CONNECTING_IP' => $localIp])
            ->actingAs($user)
            ->getJson("/api/books/{$book->id}")
            ->assertStatus(200);
    }

    private function createUser()
    {
        return UserFactory::new()->create();
    }
}
