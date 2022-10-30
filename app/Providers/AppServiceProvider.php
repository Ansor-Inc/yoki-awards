<?php

namespace App\Providers;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Modules\Billing\Entities\Purchase;
use Modules\Billing\Entities\Transaction;
use Modules\Blog\Entities\Article;
use Modules\Book\Entities\Author;
use Modules\Book\Entities\Book;
use Modules\Book\Entities\Bookmark;
use Modules\Book\Entities\BookRead;
use Modules\Book\Entities\BookUserStatus;
use Modules\Book\Entities\Genre;
use Modules\Book\Entities\Publisher;
use Modules\Book\Entities\Rating;
use Modules\Group\Entities\BlackList;
use Modules\Group\Entities\Group;
use Modules\Group\Entities\GroupAdmin;
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

        Relation::morphMap(Config::get('morph-relation-mapping'));
    }
}
