<?php

return [
    'article' => \Modules\Blog\Entities\Article::class,
    'author' => \Modules\Book\Entities\Author::class,
    'book' => \Modules\Book\Entities\Book::class,
    'genre' => \Modules\Book\Entities\Genre::class,
    'group' => \Modules\Group\Entities\Group::class,
    'group_category' => \Modules\Group\Entities\GroupCategory::class,
    'publisher' => \Modules\Book\Entities\Publisher::class,
    'tag' => \App\Models\Tag::class,
    'user' => \Modules\User\Entities\User::class,
    'purchase' => \Modules\Purchase\Entities\Purchase::class,
    'transaction' => \Modules\Purchase\Entities\Transaction::class,
    'bookmark' => \Modules\Book\Entities\Bookmark::class,
    'book_read' => \Modules\Book\Entities\BookRead::class,
    'book_user_status' => \Modules\Book\Entities\BookUserStatus::class,
    'rating' => \Modules\Book\Entities\Rating::class,
    'black_list' => \Modules\Group\Entities\BlackList::class,
    'group_admin' => \Modules\Group\Entities\GroupAdmin::class,
    'membership' => \Modules\Group\Entities\Membership::class,
    'post' => \Modules\Post\Entities\Post::class,
    'post_like' => \Modules\Post\Entities\PostLike::class,
    'sms_token' => \Modules\User\Entities\SmsToken::class,
    'comment' => \App\Models\Comment::class,
    'complaint' => \Modules\User\Entities\Complaint::class,
    'admin' => \App\Models\AdminUser::class
];