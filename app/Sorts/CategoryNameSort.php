<?php

namespace App\Sorts;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class CategoryNameSort implements Sort
{
    public function __invoke(Builder $query, bool $descending)
    {
        $query
            ->join('category', 'category.id', '=', 'ideas.category_id')
            ->orderBy('category.name', $descending ? 'desc' : 'asc');

        // $direction = $descending ? 'DESC' : 'ASC';

        // $query->orderByRaw("LENGTH(`{$property}`) {$direction}");
    }
}
