<?php

namespace Tests\Unit\Services;

use App\Entities\BillingFrequency;
use App\Entities\Product;
use App\Services\ProductService;
use App\Services\ProductSources\ProductSourceInterface;
use Money\Money;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{

    public function testShouldSortProductsFromSourceByAnnualPriceDesc()
    {
        $source = $this->createMock(ProductSourceInterface::class);
        $source
            ->expects($this->once())
            ->method('getProducts')
            ->willReturn(collect([
                $this->generateProduct(
                    title: '£600 per year', price: Money::GBP(600), frequency: BillingFrequency::Annual,
                ),
                $this->generateProduct(
                    title: '£1000 per year', price: Money::GBP(1000), frequency: BillingFrequency::Annual,
                ),
                $this->generateProduct(
                    title: '£780 per year (charged monthly)', price: Money::GBP(65), frequency: BillingFrequency::Monthly,
                ),
            ]));

        $service = new ProductService($source);

        $products = $service->getSortedByPriceDesc();

        $this->assertEquals([
            '£1000 per year',
            '£780 per year (charged monthly)',
            '£600 per year'
        ], $products->pluck('option')->all());
    }

    private function generateProduct(string $title, Money $price, BillingFrequency $frequency): Product
    {
        $product = new Product();

        $product->option = $title;
        $product->price = $price;
        $product->frequency = $frequency;

        return $product;
    }
}
