<?php

namespace App\Providers;

use App\Policies\CommentPolicy;
use App\Policies\GroupPolicy;
use App\Policies\PostPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Sanctum\Sanctum;
use Modules\Comment\Entities\Comment;
use Modules\Group\Entities\Group;
use Modules\Post\Entities\Post;
use Modules\User\Entities\PersonalAccessToken;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Group::class => GroupPolicy::class,
        Comment::class => CommentPolicy::class,
        Post::class => PostPolicy::class,
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
