<?php

namespace App\Entities;

enum BillingFrequency
{
    case Monthly;
    case Annual;

    public function adverb(): string
    {
        return match ($this) {
            self::Annual => 'annually',
            self::Monthly => 'monthly',
        };
    }
}
