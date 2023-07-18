<?php

namespace App\Services\ProductFilterService\Filters;

use App\config\Enums\FilterParameters;
use App\Services\ProductFilterService\Entity\Filter;
use Doctrine\ORM\QueryBuilder;

class PriceFilter
{
    public function handle(Filter $filters, QueryBuilder $builder, string $alias, array $parameters): QueryBuilder
    {
        return $filters->getMinPrice() || $filters->getMaxPrice()
            ? $builder->andWhere($alias)
                ->setParameter(FilterParameters::MinPrice->value, $parameters['min'])
                ->setParameter(FilterParameters::MaxPrice->value, $parameters['max'])
            : $builder;
    }
}