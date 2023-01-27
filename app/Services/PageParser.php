<?php

namespace App\Services;

use App\Entities\Product;
use App\Services\Contracts\ItemParserInterface;
use App\Services\Contracts\PageParserInterface;
use App\Services\Exceptions\ParseException;
use DOMDocument;
use DOMElement;
use DOMXPath;

class PageParser implements PageParserInterface
{
    public function __construct(private ItemParserInterface $itemParser)
    {
    }

    /**
     * @return Product[]
     * @throws ParseException
     */
    public function parse(string $html): array
    {
        $domDocument = (new DOMDocument());
        $domDocument->loadHTML('<?xml encoding="utf-8">' . $html);

        $xpath = new DOMXPath($domDocument);

        // although this path looks a bit convoluted it is the only way to ensure that classes like “package-something”
        // are not found as well
        $path = "//div[contains(concat(' ', normalize-space(@class), ' '), ' package ')]";

        /** @var DOMElement[] $productNodes */
        $productNodes = $xpath->query($path);

        $products = [];

        foreach ($productNodes as $productNode) {
            $productHtml = $domDocument->saveHTML($productNode);
            $products[] = $this->itemParser->parse($productHtml);
        }

        return $products;
    }
}
