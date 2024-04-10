<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class PostFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    /**
     * Search for posts that have a title containing the given value.
     *
     * @param string $value The value to search for.
     * @return \App\ModelFilters\PostFilter Returns a PostFilter instance for further filtering.
     */
    public function search(string $value): PostFilter
    {
        return $this->where(function ($query) use ($value) {
            $query->where('title', 'like', "%{$value}%")
                ->orWhere('content', 'like', "%{$value}%");
        });
    }

    /**
     * Filter posts by status.
     *
     * @param int $value The status value to filter by.
     * @return \App\ModelFilters\PostFilter Returns a PostFilter instance for further filtering.
     */
    public function status(int $value): PostFilter
    {
        return $this->where('status', $value);
    }

    /**
     * Filter posts based on a list of keys.
     *
     * @param array $value An array of keys to filter by.
     * @return \App\ModelFilters\PostFilter Returns a PostFilter instance for further filtering.
     */
    public function keys(array $value): PostFilter
    {
        return $this->whereIn('id', $value);
    }

    /**
     * Filter the posts to only include those that have a published date greater than or equal to the given value.
     *
     * @param string $value The value representing the minimum published date.
     * @return \App\ModelFilters\PostFilter Returns a PostFilter instance for further filtering.
     */
    public function publishedGte(string $value): PostFilter
    {
        return $this->whereDate('published_at', '>=', $value);
    }

    /**
     * Get the posts that were published on or before the given date.
     *
     * @param string $value The maximum date for the published_at column.
     * @return \App\ModelFilters\PostFilter Returns a PostFilter instance for further filtering.
     */
    public function publishedLte(string $value): PostFilter
    {
        return $this->whereDate('published_at', '<=', $value);
    }
}
