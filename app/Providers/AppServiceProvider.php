<?php

namespace App\Providers;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Modules\Blog\Entities\Article;
use Modules\Book\Entities\Author;
use Modules\Book\Entities\Book;
use Modules\Book\Entities\Genre;
use Modules\Book\Entities\Publisher;
use Modules\Group\Entities\Group;
use Modules\Group\Entities\GroupCategory;
use Modules\User\Entities\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        $this->setMorphMapping();
    }

    protected function setMorphMapping()
    {
        Relation::morphMap([
            'article' => Article::class,
            'author' => Author::class,
            'book' => Book::class,
            'genre' => Genre::class,
            'group' => Group::class,
            'group_category' => GroupCategory::class,
            'publisher' => Publisher::class,
            'tag' => Tag::class,
            'user' => User::class
        ]);
    }
}
