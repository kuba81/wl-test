<?php

namespace Tests\Integration\Services\ProductSources\Scrapper;

use App\Services\ProductSources\Scrapper\Scrapper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Tests\GeneratesProductHtml;
use Tests\TestCase;

class ScrapperTest extends TestCase
{
    use GeneratesProductHtml;

    public function testShouldRequestDataFromHtmlClientAndReturnParsedResults(): void
    {
        Http::preventStrayRequests();

        $productHtml1 = $this->generateHtmlForProduct(title: 'Product 1');
        $productHtml2 = $this->generateHtmlForProduct(title: 'Product 2');

        $html = $productHtml1 . $productHtml2;

        Http::fake([
            'https://my-fake-source.com' => Http::response($html)
        ]);

        config()->set('sources.scrapper.url', 'https://my-fake-source.com');

        /** @var Scrapper $scrapper */
        $scrapper = app(Scrapper::class);
        $products = $scrapper->getProducts();

        $this->assertInstanceOf(Collection::class, $products);
        $this->assertEquals('Product 1', $products->get(0)->option);
        $this->assertEquals('Product 2', $products->get(1)->option);
    }
}
