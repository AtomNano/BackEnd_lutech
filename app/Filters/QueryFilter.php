<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class QueryFilter
{
    /** @var array */
    protected $filters;

    /** @var Builder */
    protected $builder;

    /**
     * @param array $filters Pre-validated filters
     */
    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    /**
     * Apply the filters to the builder.
     */
    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->filters() as $name => $value) {
            if (method_exists($this, $name)) {
                if (!is_null($value) && $value !== '') {
                    call_user_func_array([$this, $name], [$value]);
                }
            }
        }

        return $this->builder;
    }

    /**
     * Get all filters.
     */
    public function filters()
    {
        return $this->filters;
    }
}
