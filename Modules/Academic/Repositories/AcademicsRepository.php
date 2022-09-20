<?php

namespace Modules\Academic\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Academic\Repositories\Interfaces\AcademicsRepositoryInterface;
use Modules\User\Enums\UserDegree;

class AcademicsRepository implements AcademicsRepositoryInterface
{
    public function getAcademics(string $degree, int|null $perPage = 0)
    {
        $query = User::query()
            ->where('degree', $degree)
            ->select('id', 'avatar', 'fullname')
            ->withCount('readBooks as read_books_count')
            ->withSum('readBooks as read_books_page_count', 'page_count');

        return $perPage === 0 ? $query->limit(100)->get() : $query->paginate($perPage);
    }

    public function getDegrees()
    {
        return [
            [
                'title' => 'GENIYLAR',
                'book_read_count' => '50+',
                'degree' => UserDegree::GENIUS->value,
                'academics_count' => User::query()->where('degree', UserDegree::GENIUS->value)->count(),
            ],
            [
                'title' => 'OLIMLAR',
                'book_read_count' => '25-49',
                'degree' => UserDegree::SCIENTIST->value,
                'academics_count' => User::query()->where('degree', UserDegree::SCIENTIST->value)->count(),
            ],
            [
                'title' => 'AQLLILAR',
                'book_read_count' => '1-24',
                'degree' => UserDegree::CLEVER->value,
                'academics_count' => User::query()->where('degree', UserDegree::CLEVER->value)->count(),
            ]
        ];
    }
}