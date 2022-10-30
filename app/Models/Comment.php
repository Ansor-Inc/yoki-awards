<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Comment extends Model
{
    use HasFactory;
    use HasRecursiveRelationships;

    protected $guarded = ['id'];

    public function getParentKeyName()
    {
        return 'reply_id';
    }

    public function replies()
    {
        return $this->hasMany(self::class, 'reply_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commentable()
    {
        return $this->morphTo();
    }
}
