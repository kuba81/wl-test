<?php

namespace App\Services\Contracts;

use App\Entities\Product;
use App\Services\Exceptions\ParseException;

interface PageParserInterface
{
    /**
     * @return Product[]
     * @throws ParseException
     */
    public function parse(string $html): array;
}
