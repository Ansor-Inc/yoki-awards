<?php

namespace Modules\Book\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Book\Filters\QuoteFilter;
use Modules\User\Entities\User;

class Quote extends Model
{
    protected $fillable = [
        'book_id',
        'user_id',
        'body'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function scopeFilter(Builder $builder, array $filters)
    {
        (new QuoteFilter($builder))->apply($filters);
    }
}
