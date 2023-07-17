<?php

namespace App\Services\ProductFilterService\Entity;

class Filter
{
    private array $categories = [];

    private array $brands = [];

    private array $types = [];

    private array $weights = [];

    private ?string $minPrice = null;

    private ?string $maxPrice = null;

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

    public function getMinPrice(): string
    {
        return $this->minPrice;
    }

    public function getMaxPrice(): string
    {
        return $this->maxPrice;
    }

    public function setMinPrice(string $price): static
    {
        $this->minPrice = $price;

        return $this;
    }

    public function setMaxPrice(string $price): static
    {
        $this->maxPrice = $price;

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