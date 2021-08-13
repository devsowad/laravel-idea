<?php

namespace App\Http\Queries\v1;

use App\Models\Idea;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;

class IdeaQuery extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(Idea::query());

        $this
            ->allowedFields([
                'title', 'spam_reports', 'created_at', 'slug', 'description', 'id',
                'category_id', 'category.id', 'category.name',
                'status_id', 'status.id', 'status.name',
                'user_id', 'user.id', 'user.name', 'user.email',
                'comments.body', 'comments.created_at', 'comments.id', 'comments.idea_id', 'comments.spam_reports',
                'comments.user_id', 'comments.user.name', 'comments.user.id',
            ])
            ->allowedIncludes([
                'category', 'status', 'user', 'comments', 'comments.user', 'votes',
                AllowedInclude::count('comments'),
                AllowedInclude::count('votes'),
            ])
            ->defaultSorts('-created_at')
            ->allowedSorts([
                'title', 'spam_reports', 'created_at',
            ])
            ->allowedFilters([
                'title', AllowedFilter::exact('spam_reports'),
                'category.name',
                AllowedFilter::exact('status.name'),
            ]);
    }
}
