<?php

namespace App\Services\ProductFilterService\Filters;

use App\config\Enums\FilterParameters;
use App\Services\ProductFilterService\Entity\Filter;
use Doctrine\ORM\QueryBuilder;

class WeightFilter
{
    public function handle(Filter $filters, QueryBuilder $builder, string $alias, array $parameters): QueryBuilder
    {
        return $filters->getMinWeight() || $filters->getMaxWeight()
            ? $builder->andWhere($alias)
                ->setParameter(FilterParameters::MinWeight->value, $parameters['min'])
                ->setParameter(FilterParameters::MaxWeight->value, $parameters['max'])
            : $builder;
    }
}