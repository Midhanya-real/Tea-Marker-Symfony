<?php

namespace App\Services\ProductFilterService;

use App\Services\ProductFilterService\Entity\Filter;
use App\Services\ProductFilterService\Filters\BrandFilter;
use App\Services\ProductFilterService\Filters\CategoryFilter;
use App\Services\ProductFilterService\Filters\CountryFilter;
use App\Services\ProductFilterService\Filters\PriceFilter;
use App\Services\ProductFilterService\Filters\TypeFilter;
use App\Services\ProductFilterService\Filters\WeightFilter;
use Doctrine\ORM\QueryBuilder;

class FilterService
{
    private ?QueryBuilder $queryBuilder = null;
    private ?Filter $filters = null;

    public function __construct(
        private readonly CategoryFilter $categoryFilter,
        private readonly BrandFilter    $brandFilter,
        private readonly CountryFilter  $countryFilter,
        private readonly PriceFilter    $priceFilter,
        private readonly TypeFilter     $typeFilter,
        private readonly WeightFilter   $weightFilter,
    )
    {
    }

    public function setQueryBuilder(QueryBuilder $builder): static
    {
        $this->queryBuilder = $builder;

        return $this;
    }

    public function setFilter(Filter $filters): static
    {
        $this->filters = $filters;

        return $this;
    }

    public function getCategory(string $alias, array $parameters): static
    {
        $this->categoryFilter->handle($this->filters, $this->queryBuilder, $alias, $parameters);

        return $this;
    }

    public function getBrand(string $alias, array $parameters): static
    {
        $this->brandFilter->handle($this->filters, $this->queryBuilder, $alias, $parameters);

        return $this;
    }

    public function getCountry(string $alias, array $parameters): static
    {
        $this->countryFilter->handle($this->filters, $this->queryBuilder, $alias, $parameters);

        return $this;
    }

    public function getPrice(string $alias, array $parameters): static
    {
        $this->priceFilter->handle($this->filters, $this->queryBuilder, $alias, $parameters);

        return $this;
    }

    public function getType(string $alias, array $parameters): static
    {
        $this->typeFilter->handle($this->filters, $this->queryBuilder, $alias, $parameters);

        return $this;
    }

    public function getWeight(string $alias, array $parameters): static
    {
        $this->weightFilter->handle($this->filters, $this->queryBuilder, $alias, $parameters);

        return $this;
    }

    public function getFilters(): QueryBuilder
    {
        return $this->queryBuilder;
    }
}