<?php

namespace Modules\Book\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Book\Events\UserReadBooksCountChanged;
use Modules\User\Entities\User;

class BookRead extends Model
{
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::deleted(function ($bookRead) {
            UserReadBooksCountChanged::dispatch($bookRead->user);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
