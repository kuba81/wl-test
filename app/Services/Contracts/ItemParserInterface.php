<?php

namespace App\Services\Contracts;

use App\Entities\Product;
use App\Services\Exceptions\ParseException;

interface ItemParserInterface
{
    /**
     * @throws ParseException
     */
    public function parse(string $html): Product;
}
