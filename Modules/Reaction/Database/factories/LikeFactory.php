<?php

namespace Modules\Reaction\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Blog\Entities\Article;
use Modules\Comment\Database\factories\CommentFactory;
use Modules\Comment\Entities\Comment;
use Modules\Reaction\Entities\Like;
use Modules\User\Database\factories\UserFactory;

class LikeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Like::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => UserFactory::new()->create()->id,
            'likeable_type' => 'comment',
            'likeable_id' => CommentFactory::new()->create()->id,

        ];
    }
}

