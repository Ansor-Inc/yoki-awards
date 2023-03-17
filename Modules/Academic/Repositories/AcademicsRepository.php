<?php

namespace Modules\Academic\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Academic\Interfaces\AcademicsRepositoryInterface;
use Modules\User\Entities\User;
use Modules\User\Enums\UserDegree;

class AcademicsRepository implements AcademicsRepositoryInterface
{
    public function getAcademics(string $degree, int|null $perPage): Collection|LengthAwarePaginator|array
    {
        $query = User::query()
            ->where('degree', $degree)
            ->select('id', 'avatar', 'fullname')
            ->withCount('readBooks as read_books_count')
            ->withSum('readBooks as read_books_page_count', 'page_count');

        return isset($perPage) ? $query->paginate($perPage) : $query->limit(100)->get();
    }

    public function getDegrees(): \Illuminate\Support\Collection
    {
        return collect(UserDegree::cases())->filter(fn($degree) => $degree != UserDegree::USER);
    }
}
