# WL coding test implementation

## Requirements
This project requires PHP 8.2 with the following extensions:
- php-intl
- php-mbstring
- php-json
- php-tokenizer
- php-xml

You will also need composer2 installed on your system.

## Setup
To set up the project, run `composer install` from the root directory.

## Running
To execute the fetch command, run `php wl-test fetch-products`

## Testing
Tests can be run with `./vendor/bin/phpunit`. If you want to see the test descriptions,
run `./vendor/bin/phpunit --testdox`.

## Implementation details
I chose [Laravel-Zero](https://laravel-zero.com/) as the framework and used a fairly standard directory structure.

I have added tests and the coverage is 93% (only the exception logic around HTML-parsing errors is untested).

### Comments
Although the command works as expected, the implementation is very brittle as it relies on poorly formatted
HTML code that can change at any time, and this will not be caught by any of the tests as none of them make
real HTTP requests. The acceptance test for the command uses a snapshot of the HTML code, but it will need to be manually updated
if the HTML structure is changed at source.

I had to make some assumptions as the specification is not clear, particularly around the handling of monthly
subscriptions. The requirements say that all products should be listed and that they should be ordered by the
annual price with the discount included, but those properties are not available on monthly subscriptions, so I made
the following changes/assumptions:
- The `price` property will always represent the billing amount. I have included `billingFrequency` as well to indicate
  whether the plan is billed monthly or annually.
- I have added `pricePerMonth` and `pricePerAnnum` to reflect the real cost of the subscription. For annual plans
  `pricePerAnnum` will be equal to `price`, and `pricePerMonth` will be the annual price divided by 12. For monthly
  subscriptions `pricePerAnnum` will be equal to `price` multiplied by 12, while `pricePerMonth`
  will be equal to `price`.
- Sorting was done on the `pricePerAnnum` property.
- All prices will be represented using the money pattern, i.e. they will be objects with two fields:
  `amount` (represents the value in the currency’s minor unit) and `currency` (the currency ISO symbol).
  For example, £12.99 will be represented as `{amount: "1299", currency: "GBP"}`. All price fields will
  have a corresponding `*Formatted` (e.g. `discountFormatted`) which will contain a localised,
  human-readable representation of the monetary value, e.g. `£12.99`.

I have kept the commits relatively small and atomic, and some commit messages will contain extra details regarding
implementation etc.

Finally, I have taken some shortcuts I would never do in a real application. For example, for simplicity and speed
I declared all properties in Product as public and I have not added any logic to ensure that those properties
are even initialised, which would likely become an issue if the application started to grow. I have also left
some TODOs in code, I was going to address them but decided not to do it in the interest of time.
