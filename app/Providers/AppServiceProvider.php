<?php

namespace App\Providers;

use App\Services\ProductService;
use App\Services\ProductSources\ProductSourceInterface;
use App\Services\ProductSources\Scrapper\Contracts\ItemParserInterface;
use App\Services\ProductSources\Scrapper\Contracts\PageParserInterface;
use App\Services\ProductSources\Scrapper\ItemParser;
use App\Services\ProductSources\Scrapper\PageParser;
use App\Services\ProductSources\Scrapper\Scrapper;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(PageParser::class)->needs(ItemParserInterface::class)->give(ItemParser::class);
        $this->app->when(Scrapper::class)->needs(PageParserInterface::class)->give(PageParser::class);

        $this->app
            ->when(ProductService::class)
            ->needs(ProductSourceInterface::class)
            // TODO: ideally this should use some sort of a source factory, especially once another source
            //       is added
            ->give(Scrapper::class);

    }
}
