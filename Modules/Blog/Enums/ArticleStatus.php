<?php

namespace Modules\Blog\Enums;

enum ArticleStatus: string
{
    case PUBLISHED = 'APPROVED';
    case PENDING_APPROVAL = 'PENDING_APPROVAL';
    case REJECTED = 'REJECTED';
    case DRAFT = 'DRAFT';
}
