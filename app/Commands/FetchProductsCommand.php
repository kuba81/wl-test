<?php

namespace App\Commands;

use App\Entities\Product;
use App\Serialisers\ProductSerialiser;
use App\Services\ProductService;
use Exception;
use LaravelZero\Framework\Commands\Command;

class FetchProductsCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'fetch-products';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Fetches products and displays them by price (most expensive first)';

    public function handle(ProductService $productService)
    {
        try {
            $products = $productService->getSortedByPriceDesc();
        } catch (Exception $e) {
            $this->output->error('An unexpected exception occurred' . PHP_EOL . $e);

            return 1;
        }

        $json = json_encode(
            $products->map(fn (Product $product) => ProductSerialiser::toArray($product)),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        $this->output->write($json);

        return 0;
    }
}
