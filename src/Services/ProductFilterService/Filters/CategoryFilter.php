<?php

namespace App\Services\ProductFilterService\Filters;

use App\config\Enums\FilterParameters;
use App\Services\ProductFilterService\Entity\Filter;
use Doctrine\ORM\QueryBuilder;

class CategoryFilter
{
    public function handle(Filter $filters, QueryBuilder $builder, string $alias, array $parameters): QueryBuilder
    {
        return $filters->getCategories()
            ? $builder->andWhere($alias)
                ->setParameter(FilterParameters::Categories->value, $parameters)
            : $builder;
    }
}