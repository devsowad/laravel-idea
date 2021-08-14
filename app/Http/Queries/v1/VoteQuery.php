<?php

namespace App\Http\Queries\v1;

use App\Models\Vote;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class VoteQuery extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(Vote::query());

        $this
            ->allowedFields([
                'id', 'idea_id', 'user_id', 'created_at',
            ])
            ->allowedIncludes('idea', 'user')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('idea_id'),
                AllowedFilter::exact('user_id'),
            ]);
    }
}
