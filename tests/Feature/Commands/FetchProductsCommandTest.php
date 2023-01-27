<?php

namespace Tests\Feature\Commands;

use Illuminate\Foundation\Testing\Concerns\InteractsWithConsole;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FetchProductsCommandTest extends TestCase
{
    use InteractsWithConsole;

    public function testShouldFetchProductsFromWebsiteAndRenderThemInJsonFormatFromMostExpensiveFirst(): void
    {
        Http::preventStrayRequests();

        $responseHtml = file_get_contents(base_path('resources/products-snapshot.html'));

        Http::fake([
            'https://wltest.dns-systems.net/' => Http::response($responseHtml)
        ]);

        $this->withoutMockingConsoleOutput()->artisan('fetch-products');
        $output = Artisan::output();

        $outputAsArray = json_decode($output, true);

        $this->assertIsArray($outputAsArray);
        $this->assertCount(6, $outputAsArray);

        $this->assertEquals([
            'Optimum: 2 GB Data - 12 Months',
            'Optimum: 24GB Data - 1 Year',
            'Standard: 1GB Data - 12 Months',
            'Standard: 12GB Data - 1 Year',
            'Basic: 500MB Data - 12 Months',
            'Basic: 6GB Data - 1 Year',
        ], Arr::pluck($outputAsArray, 'option'));
    }
}
