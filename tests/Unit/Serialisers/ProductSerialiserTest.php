<?php

namespace Tests\Unit\Serialisers;

use App\Entities\BillingFrequency;
use App\Entities\Product;
use App\Serialisers\ProductSerialiser;
use Illuminate\Support\Arr;
use Money\Money;
use PHPUnit\Framework\TestCase;

class ProductSerialiserTest extends TestCase
{
    public function testShouldCorrectlyMapProductBilledAnnually(): void
    {
        $product = new Product();
        $product->option = 'Annual product';
        $product->description = 'Annual product description';
        $product->title = 'Annual product name';
        $product->frequency = BillingFrequency::Annual;
        $product->discount = Money::GBP(1250);
        $product->price = Money::GBP(18850);

        $expectedArray = [
            'option' => 'Annual product',
            'title' => 'Annual product name',
            'description' => 'Annual product description',
            'price' => [
                'amount' => '18850',
                'currency' => 'GBP',
            ],
            'priceFormatted' => '£188.50',
            'billingFrequency' => 'annually',
            'pricePerAnnum' => [
                'amount' => '18850',
                'currency' => 'GBP',
            ],
            'pricePerAnnumFormatted' => '£188.50',
            'pricePerMonth' => [
                'amount' => '1571',
                'currency' => 'GBP',
            ],
            'pricePerMonthFormatted' => '£15.71',
            'discountPerAnnum' => [
                'amount' => '1250',
                'currency' => 'GBP',
            ],
            'discountPerAnnumFormatted' => '£12.50',
        ];

        // limit to currently existing keys to avoid fragile test
        $keysToCompare = array_keys($expectedArray);

        $this->assertEquals($expectedArray, Arr::only(ProductSerialiser::toArray($product), $keysToCompare));
    }
    public function testShouldCorrectlyMapProductBilledMonthly(): void
    {
        $product = new Product();
        $product->option = 'Monthly product';
        $product->description = 'Monthly product description';
        $product->title = 'Monthly product name';
        $product->frequency = BillingFrequency::Monthly;
        $product->discount = null;
        $product->price = Money::GBP(1350);

        $expectedArray = [
            'option' => 'Monthly product',
            'title' => 'Monthly product name',
            'description' => 'Monthly product description',
            'price' => [
                'amount' => '1350',
                'currency' => 'GBP',
            ],
            'priceFormatted' => '£13.50',
            'billingFrequency' => 'monthly',
            'pricePerAnnum' => [
                'amount' => '16200',
                'currency' => 'GBP',
            ],
            'pricePerAnnumFormatted' => '£162.00',
            'pricePerMonth' => [
                'amount' => '1350',
                'currency' => 'GBP',
            ],
            'pricePerMonthFormatted' => '£13.50',
            'discountPerAnnum' => null,
            'discountPerAnnumFormatted' => null,
        ];

        // limit to currently existing keys to avoid fragile test
        $keysToCompare = array_keys($expectedArray);

        $this->assertEquals($expectedArray, Arr::only(ProductSerialiser::toArray($product), $keysToCompare));
    }
}
