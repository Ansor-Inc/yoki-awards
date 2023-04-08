<?php

namespace Modules\Book\Providers;

use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\ServiceProvider;
use Modules\Book\Interfaces\BookListingRepositoryInterface;
use Modules\Book\Interfaces\BookQuoteRepositoryInterface;
use Modules\Book\Interfaces\BookRepositoryInterface;
use Modules\Book\Interfaces\GenreRepositoryInterface;
use Modules\Book\Interfaces\PublisherRepositoryInterface;
use Modules\Book\Repositories\BookListingRepository;
use Modules\Book\Repositories\BookQuoteRepository;
use Modules\Book\Repositories\BookRepository;
use Modules\Book\Repositories\GenreRepository;
use Modules\Book\Repositories\PublisherRepository;

class BookServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Book';

    protected string $moduleNameLower = 'book';

    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->bind(PublisherRepositoryInterface::class, PublisherRepository::class);
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
        $this->app->bind(BookListingRepositoryInterface::class, BookListingRepository::class);
        $this->app->bind(GenreRepositoryInterface::class, GenreRepository::class);
        $this->app->bind(BookQuoteRepositoryInterface::class, BookQuoteRepository::class);
    }

    public function provides()
    {
        return [];
    }
}
