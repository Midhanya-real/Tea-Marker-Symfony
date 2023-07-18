<?php

namespace App\Services\ProductFilterService\Filters;

use App\config\Enums\FilterParameters;
use App\Services\ProductFilterService\Entity\Filter;
use Doctrine\ORM\QueryBuilder;

class CountryFilter
{
    public function handle(Filter $filters, QueryBuilder $builder, string $alias, array $parameters): QueryBuilder
    {
        return $filters->getCountries()
            ? $builder->andWhere($alias)
                ->setParameter(FilterParameters::Countries->value, $parameters)
            : $builder;
    }
}