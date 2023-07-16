<?php

namespace App\Services\ProductFilterService\Filters;

use App\Services\ProductFilterService\Entity\Filter;
use Doctrine\ORM\QueryBuilder;

class CategoryFilter
{
    public function handle(Filter $filters, QueryBuilder $builder, string $alias): QueryBuilder
    {
        return $filters->getCategories()
            ? $builder->andWhere($alias)
            : $builder;
    }
}