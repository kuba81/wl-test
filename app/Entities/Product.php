<?php

namespace App\Entities;

use Money\Money;

class Product
{
    public string $option;
    public string $title;
    public string $description;

    public Money $price;
    public ?Money $discount = null;
    public BillingFrequency $frequency;
}
