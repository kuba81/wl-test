<?php

namespace App\Services;

use App\Entities\BillingFrequency;
use App\Entities\Product;
use App\Services\Contracts\ItemParserInterface;
use App\Services\Exceptions\ParseException;
use DOMDocument;
use DOMXPath;

class ItemParser implements ItemParserInterface
{
    private DOMXPath $xpath;
    private Product $product;

    /**
     * @throws ParseException
     */
    public function parse(string $html): Product
    {
        $domDocument = (new DOMDocument());
        $domDocument->loadHTML('<?xml encoding="utf-8">' . $html);

        $this->xpath = new DOMXPath($domDocument);

        $this->product = new Product();

        $this->product->option = $this->extractTextFromPath('//*[contains(@class, "header")]/h3');
        $this->product->title = $this->extractTextFromPath('//*[contains(@class, "package-name")]');
        $this->product->description = $this->extractTextFromPath('//*[contains(@class, "package-description")]');

        $this->parsePricing();

        return $this->product;
    }

    /**
     * @throws ParseException
     */
    private function extractTextFromPath(string $path): string
    {
        $domElement = $this->xpath->query($path);

        $textContent = $domElement->item(0)->textContent;

        if (empty($textContent)) {
            $this->throwParseError();
        }

        return $textContent ?? $this->throwParseError();
    }

    /**
     * @throws ParseException
     */
    private function parsePricing(): void
    {
        $priceData = $this->extractTextFromPath('//*[contains(@class, "package-price")]');

        $priceRegex = '#(?<price>[^(]*+)\([^\)]*+\)Per (?P<frequency>(Month|Year))(?:.*?Save (?P<discount>[^ ]++))?#s';

        if (!preg_match($priceRegex, $priceData, $matches)) {
            $this->throwParseError();
        }

        $this->product->price = $matches['price'];
        $this->product->discount = $matches['discount'] ?? null;

        $this->product->frequency = match ($matches['frequency']) {
            'Month' => BillingFrequency::Monthly,
            'Year' => BillingFrequency::Annual,
            default => $this->throwParseError(),
        };
    }

    /**
     * @throws ParseException
     */
    private function throwParseError(): void
    {
        throw new ParseException('Error parsing HTML');
    }
}
