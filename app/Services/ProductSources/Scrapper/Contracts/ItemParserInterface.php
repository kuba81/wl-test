<?php

namespace App\Services\ProductSources\Scrapper\Contracts;

use App\Entities\Product;
use App\Services\ProductSources\Scrapper\Exceptions\ParseException;

interface ItemParserInterface
{
    /**
     * @throws ParseException
     */
    public function parse(string $html): Product;
}
