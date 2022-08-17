<?php

namespace Modules\Book\Repositories;

use App\Models\Book;
use Modules\Book\Enums\BookType;
use Modules\Book\Repositories\Interfaces\BookSectionsRepositoryInterface;
use Modules\Book\Transformers\BookResource;

class BookSectionsRepository implements BookSectionsRepositoryInterface
{
    public function getTrendingBooks()
    {
        return Book::query()
            ->onlyListingFields()
            ->where('book_type', BookType::E_BOOK)
            ->whereRelation('tags', 'name', 'trending')
            ->limit(4)
            ->get();
    }

    public function getTrendingAudioBooks()
    {
        return Book::query()
            ->onlyListingFields()
            ->where('book_type', BookType::AUDIO_BOOK)
            ->whereRelation('tags', 'name', 'trending')
            ->limit(4)
            ->get();
    }

    public function getSpecialBooks()
    {
        return Book::query()
            ->onlyListingFields()
            ->whereRelation('tags', 'name', 'special')
            ->limit(2)
            ->get();
    }


    public function getAcademicsBooks()
    {
        return Book::query()
            ->onlyListingFields()
            ->limit(4)
            ->get();
    }

    public function getBooksBySections()
    {
        return [
            'Trenddagi kitoblar' => BookResource::collection($this->getTrendingBooks()),
            'Siz uchun maxsus' => BookResource::collection($this->getSpecialBooks()),
            'Trenddagi audio kitoblar' => BookResource::collection($this->getTrendingAudioBooks()),
            'Akademiklar tanlovi' => BookResource::collection($this->getAcademicsBooks())
        ];
    }
}