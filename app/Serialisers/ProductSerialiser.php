<?php

namespace App\Serialisers;

use App\Entities\Product;
use App\Util\MoneyHelper;

// TODO: ideally this should not be static and it should be defined by an interface so that we can later verify
//       that the CLI command delegates rendering correctly
class ProductSerialiser
{
    public static function toArray(Product $product): array
    {
        return [
            'option' => $product->option,
            'title' => $product->title,
            'description' => $product->description,
            'price' => $product->price->jsonSerialize(),
            'priceFormatted' => MoneyHelper::toIntl($product->price),
            'billingFrequency' => $product->frequency->adverb(),
            'pricePerAnnum' => $product->getPricePerAnnum()->jsonSerialize(),
            'pricePerAnnumFormatted' => MoneyHelper::toIntl($product->getPricePerAnnum()),
            'pricePerMonth' => $product->getPricePerMonth()->jsonSerialize(),
            'pricePerMonthFormatted' => MoneyHelper::toIntl($product->getPricePerMonth()),
            'discountPerAnnum' => $product->discount?->jsonSerialize(),
            'discountPerAnnumFormatted' => $product->discount ? MoneyHelper::toIntl($product->discount) : null,
        ];
    }
}
