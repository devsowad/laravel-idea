<?php

namespace App\Http\Queries\v1;

use App\Models\Comment;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CommentQuery extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(Comment::query());

        $this
            ->allowedFields([
                'id', 'body', 'spam_reports', 'created_at', 'updated_at',
                'user_id', 'user.id', 'user.name', 'user.email',
                'idea_id', 'idea.slug', 'idea.id',
            ])
            ->allowedIncludes([
                'user', 'idea',
            ])
            ->defaultSorts('-created_at')
            ->allowedSorts([
                'spam_reports', 'created_at',
            ])
            ->allowedFilters([
                AllowedFilter::exact('spam_reports'),
                AllowedFilter::exact('idea_id'),
                AllowedFilter::exact('idea.slug'),
            ]);
    }
}
