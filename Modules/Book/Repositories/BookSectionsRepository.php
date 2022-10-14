<?php

namespace Modules\Book\Repositories;

use Modules\Book\Entities\Book;
use Modules\Book\Enums\BookType;
use Modules\Book\Repositories\Interfaces\BookSectionsRepositoryInterface;
use Modules\Book\Transformers\BookListingResource;

class BookSectionsRepository implements BookSectionsRepositoryInterface
{
    public function getTrendingBooks()
    {
        return $this->getListingQuery()
            ->where('book_type', BookType::E_BOOK)
            ->whereRelation('tags', 'name', 'trending')
            ->limit(4)
            ->get();
    }

    public function getTrendingAudioBooks()
    {
        return $this->getListingQuery()
            ->where('book_type', BookType::AUDIO_BOOK)
            ->whereRelation('tags', 'name', 'trending')
            ->limit(4)
            ->get();
    }

    public function getSpecialBooks()
    {
        return $this->getListingQuery()
            ->addSelect('publisher_id')
            ->with('publisher:id,title')
            ->inRandomOrder()
            ->limit(2)
            ->get();
    }

    public function getAcademicsBooks()
    {
        return $this->getListingQuery()
            ->addSelect('price')
            ->inRandomOrder()
            ->limit(3)
            ->get();
    }

    public function getBooksBySections()
    {
        return [
            [
                'title' => 'Trenddagi kitoblar',
                'books' => BookListingResource::collection($this->getTrendingBooks())
            ],
            [
                'title' => 'Siz uchun maxsus',
                'books' => BookListingResource::collection($this->getSpecialBooks())
            ],
            [
                'title' => 'Trenddagi audio kitoblar',
                'books' => BookListingResource::collection($this->getTrendingAudioBooks())
            ],
            [
                'title' => 'Akademiklar tanlovi',
                'books' => BookListingResource::collection($this->getAcademicsBooks())
            ]
        ];
    }

    protected function getListingQuery()
    {
        return Book::query()
            ->onlyListingFields()
            ->withAvg('bookUserStatuses as rating', 'rating')
            ->with('author:id,firstname,lastname');
    }
}