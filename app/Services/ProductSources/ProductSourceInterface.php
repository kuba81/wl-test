<?php

namespace App\Services\ProductSources;

use App\Entities\Product;
use App\Services\ProductSources\Scrapper\Exceptions\ParseException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;

interface ProductSourceInterface
{
    /**
     * @return Collection<int, Product>
     * @throws ParseException
     * @throws RequestException
     */
    public function getProducts(): Collection;
}
