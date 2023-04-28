<?php

namespace Modules\Comment\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Blog\Database\factories\ArticleFactory;
use Modules\User\Database\factories\UserFactory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Comment\Entities\Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'body' => $this->faker->sentence(),
            'user_id' => UserFactory::new()->create()->id,
            'commentable_type' => 'article',
            'commentable_id' => 116
        ];
    }
}

