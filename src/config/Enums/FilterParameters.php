<?php

namespace App\config\Enums;

enum FilterParameters: string
{
    case Categories = 'categories';
    case Brands = 'brands';
    case Types = 'types';
    case Countries = 'countries';
    case Weights = 'weights';
    case Prices = 'prices';
}
