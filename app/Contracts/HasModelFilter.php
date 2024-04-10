<?php

namespace App\Contracts;

use Illuminate\Support\Str;

/**
 *
 */
trait HasModelFilter
{
    /**
     * @return string
     */
    public function modelFilter(): string
    {
        $class = Str::replace('Models', 'ModelFilters', __CLASS__ . 'Filter');
        return $this->provideFilter($class);
    }
}
