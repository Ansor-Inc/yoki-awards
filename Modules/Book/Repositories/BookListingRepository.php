<?php

namespace Modules\Book\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\Book\Entities\Book;
use Modules\Book\Interfaces\BookListingRepositoryInterface;

class BookListingRepository implements BookListingRepositoryInterface
{
    public function getBooks(array $filters)
    {
        $query = $this->getListingQuery()->filter($filters);

        return isset($filters['per_page']) ? $query->paginate($filters['per_page']) : $query->get();
    }

    public function getSimilarBooks(Book $book, int|null $limit)
    {
        return $this->getListingQuery()
            ->whereNot('id', $book->id)
            ->where('genre_id', $book->genre_id)
            ->when(!is_null($limit), fn($query) => $query->limit($limit))
            ->get();
    }

    public function getSavedBooks(int|null $perPage)
    {
        $query = $this->getListingQuery()
            ->whereHas('bookUserStatuses',
                fn($query) => $query->where(['user_id' => Auth::id(), 'bookmarked' => true])
            );

        return is_null($perPage) ? $query->get() : $query->paginate($perPage);
    }

    public function search(string $query)
    {
        return Book::query()
            ->onlyListingFields()
            ->addSelect('publisher_id')
            ->with(['author:id,firstname,lastname', 'publisher:id,title'])
            ->where('title', 'like', "%{$query}%")
            ->orWhereHas('author', function (Builder $builder) use ($query) {
                $builder->where('firstname', 'like', "%{$query}%")
                    ->orWhere('lastname', 'like', "%{$query}%");
            })
            ->get();
    }

    protected function getListingQuery()
    {
        return Book::query()
            ->onlyListingFields()
            ->withAvg('ratings as rating', 'rating')
            ->with('author:id,firstname,lastname');
    }
}
