<?php

namespace Tests\Unit\Services;

use App\Entities\Product;
use App\Services\Contracts\ItemParserInterface;
use App\Services\PageParser;
use PHPUnit\Framework\TestCase;
use Tests\GeneratesProductHtml;

class PageParserTest extends TestCase
{
    use GeneratesProductHtml;

    public function testShouldFindAllProductsWithinGivenHtmlAndParseThemUsingItemParser(): void
    {
        // the actual values don’t matter, we provide different values here so that we can easily check
        // that PageParser delegated to ItemParserInterface correctly
        $htmlProducts = [
            $this->generateHtmlForProduct(title: 'Dummy product 1', frequency: 'Per Month'),
            $this->generateHtmlForProduct(title: 'Dummy product 2', frequency: 'Per Year'),
            $this->generateHtmlForProduct(title: 'Dummy product 3', frequency: 'Per Year', discount: '£21.99'),
        ];

        $itemParser = $this->createMock(ItemParserInterface::class);
        $itemParser
            ->expects($this->exactly(3))
            ->method('parse')
            ->withConsecutive(
                [$this->stringContains($htmlProducts[0])],
                [$this->stringContains($htmlProducts[1])],
                [$this->stringContains($htmlProducts[2])],
            )
            ->willReturnOnConsecutiveCalls(
                $product1 = new Product(),
                $product2 = new Product(),
                $product3 = new Product(),
            );

        $parser = new PageParser($itemParser);

        $combinedHtml = '<div class="should-work-when-wrapped">' . implode('', $htmlProducts) . '</div>';
        $products = $parser->parse($combinedHtml);

        // at this point we just care that PageParser requested each item to be parsed and that it aggregated
        // and returned the results
        $this->assertCount(3, $products);

        $this->assertSame($product1, $products[0]);
        $this->assertSame($product2, $products[1]);
        $this->assertSame($product3, $products[2]);
    }
}
