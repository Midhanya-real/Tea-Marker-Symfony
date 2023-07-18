<?php

namespace App\Services\EntityBuilderService;

use App\Repository\ProductRepository;
use App\Services\ProductFilterService\Entity\Filter;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class FilterBuilder
{
    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    private function getPriceBorders(ProductRepository $productRepository): array
    {
        return $productRepository->findByBordersPrice();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    private function getWeightBorders(ProductRepository $productRepository): array
    {
        return $productRepository->findByBordersWeight();
    }

    private function getEntity(array $priceBorders, array $weightBorders): Filter
    {
        $filter = new Filter();

        $filter->setMinPrice($priceBorders['min_price']);
        $filter->setMaxPrice($priceBorders['max_price']);
        $filter->setMinWeight($weightBorders['min_weight']);
        $filter->setMaxWeight($weightBorders['max_weight']);

        return $filter;
    }

    public function build(ProductRepository $productRepository): Filter
    {
        $priceBorders = $this->getPriceBorders($productRepository);
        $weightBorders = $this->getWeightBorders($productRepository);

        return $this->getEntity(priceBorders: $priceBorders, weightBorders: $weightBorders);
    }
}