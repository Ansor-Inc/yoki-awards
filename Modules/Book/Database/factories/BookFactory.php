<?php

namespace Modules\Book\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Book\Enums\BookStatus;
use Modules\Book\Enums\BookType;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Book\Entities\Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title(),
            'description' => $this->faker->text(),
            'language' => $this->faker->languageCode(),
            'page_count' => $this->faker->numberBetween(1, 300),
            'publication_date' => $this->faker->date(),
            'price' => $this->faker->randomNumber(5),
            'compare_price' => $this->faker->randomNumber(4),
            'is_free' => $this->faker->boolean(),
            'status' => BookStatus::APPROVED->value,
            'book_type' => array_rand([BookType::AUDIO_BOOK->value, BookType::E_BOOK]),
            'voice_director' => $this->faker->name(),
            'author_id' => AuthorFactory::new()->create()
        ];
    }
}

