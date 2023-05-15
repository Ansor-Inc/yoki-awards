<?php

namespace Modules\Blog\Actions;

use Modules\Blog\Enums\ArticleStatus;

class StoreArticleToDraft
{
    public function execute(array $payload)
    {
        $payload['status'] = ArticleStatus::DRAFT->value;

        $article = auth()->user()->articles()->create($payload);

        if (isset($payload['tags'])) {
            $article->syncTags($payload['tags']);
        }

        return $article;
    }
}
