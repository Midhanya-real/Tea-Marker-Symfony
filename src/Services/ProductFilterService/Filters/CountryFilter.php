<?php

namespace App\Services\ProductFilterService\Filters;

use App\Services\ProductFilterService\Entity\Filter;
use Doctrine\ORM\QueryBuilder;

class CountryFilter
{
    public function handle(Filter $filters, QueryBuilder $builder, string $alias): QueryBuilder
    {
        return $filters->getCountries()
            ? $builder->andWhere($alias)
            : $builder;
    }
}