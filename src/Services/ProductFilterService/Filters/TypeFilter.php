<?php

namespace App\Services\ProductFilterService\Filters;

use App\Services\ProductFilterService\Entity\Filter;
use Doctrine\ORM\QueryBuilder;

class TypeFilter
{
    public function handle(Filter $filters, QueryBuilder $builder, string $alias): QueryBuilder
    {
        return $filters->getTypes()
            ? $builder->andWhere($alias)
            : $builder;
    }
}