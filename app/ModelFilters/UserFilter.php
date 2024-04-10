<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class UserFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [
        'posts' => [
            'post_search' => 'search', // Filter by relation Post
        ]
    ];

    /**
     * Set the status condition for the UserFilter.
     *
     * @param int $value The value to match for the status condition.
     * @return \App\UserFilter The UserFilter instance.
     */
    public function status(int $value): UserFilter
    {
        return $this->where('status', $value);
    }

    public function search(string $value): UserFilter
    {
        return $this->where(function ($query) use ($value) {
            $query->where('name', 'like', "%{$value}%")
                ->orWhere('email', 'like', "%{$value}%");
        });
    }
}
