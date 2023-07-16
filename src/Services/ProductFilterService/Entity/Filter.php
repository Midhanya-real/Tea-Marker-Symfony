<?php

namespace App\Services\ProductFilterService\Entity;

class Filter
{
    private array $categories = [];

    private array $brands = [];

    private array $types = [];

    private array $weights = [];

    private array $prices = [];

    private array $countries = [];

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): static
    {
        $this->categories = $categories;

        return $this;
    }

    public function getBrands(): array
    {
        return $this->brands;
    }

    public function setBrands(array $brands): static
    {
        $this->brands = $brands;

        return $this;
    }

    public function getTypes(): array
    {
        return $this->types;
    }

    public function setTypes(array $types): static
    {
        $this->types = $types;

        return $this;
    }

    public function getWeights(): array
    {
        return $this->weights;
    }

    public function setWeights(array $weights): static
    {
        $this->weights = $weights;

        return $this;
    }

    public function getPrices(): array
    {
        return $this->prices;
    }

    public function setPrices(array $prices): static
    {
        $this->prices = $prices;

        return $this;
    }

    public function getCountries(): array
    {
        return $this->countries;
    }

    public function setCountries(array $countries): static
    {
        $this->countries = $countries;

        return $this;
    }
}