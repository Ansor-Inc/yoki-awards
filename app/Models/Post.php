<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'degree_scope' => 'array'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function scopeFilter($query)
    {

    }

    public function scopeApplyCurrentUserDegreeScopeFilter($query)
    {
        $query->whereJsonContains('degree_scope', auth()->user()->degree);
    }
}
