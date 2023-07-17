<?php

namespace App\Services\ProductFilterService\Filters;

use App\config\Enums\FilterParameters;
use App\Services\ProductFilterService\Entity\Filter;
use Doctrine\ORM\QueryBuilder;

class PriceFilter
{
    public function handle(Filter $filters, QueryBuilder $builder, string $alias, array $parameters): QueryBuilder
    {
        return $filters->getMinPrice()
            ? $builder->andWhere($alias)
                ->setParameter(FilterParameters::Min->value, $parameters['min'])
                ->setParameter(FilterParameters::Max->value, $parameters['max'])
            : $builder;
    }
}