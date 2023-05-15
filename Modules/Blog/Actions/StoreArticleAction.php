<?php

namespace Modules\Blog\Actions;

use Modules\Blog\Enums\ArticleStatus;
use Modules\User\Enums\UserRole;

class StoreArticleAction
{
    public function execute(array $payload)
    {
        $payload['status'] = auth()->user()->hasRole(UserRole::APPROVED_BLOGGER->value)
            ? ArticleStatus::PUBLISHED->value
            : ArticleStatus::PENDING_APPROVAL->value;

        $article = auth()->user()->articles()->create($payload);

        if (isset($payload['tags'])) {
            $article->syncTags($payload['tags']);
        }

        return $article;
    }
}
