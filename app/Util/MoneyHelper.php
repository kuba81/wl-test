<?php

namespace App\Util;

use Money\Currencies\ISOCurrencies;
use Money\Money;
use Money\Parser\IntlMoneyParser;
use NumberFormatter;

class MoneyHelper
{
    public static function parseIntl(string $money): Money
    {
        $currencies = new ISOCurrencies();

        // TODO: consider moving to config
        $numberFormatter = new NumberFormatter('en_GB', NumberFormatter::CURRENCY);
        $moneyParser = new IntlMoneyParser($numberFormatter, $currencies);

        return $moneyParser->parse($money);
    }
}
