<?php

namespace Modules\Book\Repositories;

use App\Models\Book;
use Modules\Book\Repositories\Interfaces\BookRepositoryInterface;

class BookRepository implements BookRepositoryInterface
{
    public function getBooks(array $filters)
    {
        $query = $this->getListingQuery()->filter($filters);

        return isset($filters['per_page']) ? $query->paginate($filters['per_page']) : $query->limit(100)->get();
    }

    public function getBookById(int $id)
    {
        return $this->getBookQuery()->findOrFail($id);
    }

    public function getBookWithVariants(int $id)
    {
        $book = Book::query()->select('id', 'title')->findOrFail($id);

        return $this->getBookQuery()->where('title', 'like', "%{$book->title}%")->get();
    }

    public function getSimilarBooks(Book $book, $limit = 0)
    {
        return $this->getListingQuery()
            ->where('genre_id', $book->genre_id)
            ->when($limit > 0, fn($query) => $query->limit($limit))
            ->get();
    }

    public function getSavedBooks(int|null $perPage = 0)
    {
        $query = $this->getListingQuery()
            ->whereHas('bookUserStatuses', function ($query) {
                $query->where(['user_id' => auth()->id(), 'bookmarked' => true]);
            });

        return $perPage == 0 ? $query->get() : $query->paginate($perPage);
    }

    public function search(string $query)
    {
        return Book::query()
            ->onlyListingFields()
            ->addSelect('publisher_id')
            ->with(['author:id,firstname,lastname', 'publisher:id,title'])
            ->where('title', 'like', "%{$query}%")
            ->get();
    }

    protected function getBookQuery()
    {
        return Book::query()
            ->select(['id', 'title', 'description', 'language', 'page_count', 'publication_date', 'price', 'compare_price', 'is_free', 'book_type', 'publisher_id', 'genre_id', 'author_id', 'voice_director'])
            ->withAvg('bookUserStatuses as rating', 'rating')
            ->withCount(['bookUserStatuses as vote_count' => fn($query) => $query->whereNotNull('rating')])
            ->with(['author:id,firstname,lastname,about,copyright', 'publisher:id,title', 'genre:id,title', 'tags:name', 'currentUserStatus']);
    }

    protected function getListingQuery()
    {
        return Book::query()
            ->onlyListingFields()
            ->withAvg('bookUserStatuses as rating', 'rating')
            ->with('author:id,firstname,lastname');
    }
}