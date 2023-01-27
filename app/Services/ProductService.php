<?php

namespace App\Services;

use App\Entities\Product;
use App\Services\ProductSources\ProductSourceInterface;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;

readonly class ProductService
{
    public function __construct(private ProductSourceInterface $source)
    {
    }


    /**
     * @return Collection<int, Product>
     * @throws RequestException
     */
    public function getSortedByPriceDesc(): Collection
    {
        $products = $this->source->getProducts();

        $sortedProducts = $products->sortByDesc(
            fn (Product $product): int => $product->getPricePerAnnum()->getAmount()
        );

        return $sortedProducts->values();
    }
}
