<?php

namespace App\Entities;

class Product
{
    public string $option;
    public string $title;
    public string $description;

    // TODO consider introducing a price dto that would take currency and value in minor unit, e.g.
    //      (2217, 'GBP') for £22.17
    public string $price;
    public ?string $discount = null;
    public BillingFrequency $frequency;
}
