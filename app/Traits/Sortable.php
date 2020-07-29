<?php

namespace App\Traits;

trait Sortable
{
    public function scopeSortByArray($query, array $sorting, array $default = ['id', 'desc'])
    {
        if ($sorting['column'] && $sorting['order']) {
            return $query->orderBy($sorting['column'], $sorting['order']);
        }

        return $query->orderBy($default[0], $default[1]);
    }
}