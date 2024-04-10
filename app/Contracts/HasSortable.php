<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Trait Sortable
 * @package App\Contracts
 */
trait HasSortable
{
    /**
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public function scopeSort(Builder $query, Request $request): Builder
    {
        $sortables = data_get($this, 'sortables', []);
        $customSortables = data_get($this, 'customSortables', []);
        $sort = $request->get('sort_column');
        $direction = $request->get('sort_direction', 'desc');

        if (!$sort || !$direction || !in_array($direction, ['asc', 'desc'])) {
            return $query;
        }

        if (in_array($sort, $sortables)) {
            return $query->orderBy($sort, $direction);
        }

        if (in_array($sort, $customSortables) && method_exists(__CLASS__, 'customSort')) {
            return $this->customSort($query, $sort, $direction);
        }

        return $query;
    }
}
