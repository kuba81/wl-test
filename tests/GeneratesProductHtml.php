<?php

namespace Tests;

trait GeneratesProductHtml
{
    public function generateHtmlForProduct(
        ?string $title = 'Dummy title',
        ?string $frequency = 'Per Month',
        ?string $name = 'Dummy name',
        ?string $description = 'Dummy description',
        ?string $price = '£10.00',
        ?string $discount = null
    ): string {
        $priceHtml = sprintf('<span class="price-big">%s</span><br>(inc. VAT)<br>%s', $price, $frequency);

        if ($discount) {
            $priceHtml .= sprintf('<p style="color: red">Save %s on the monthly price</p>', $discount);
        }

        return trim(<<<HTML
            <div class="package featured-right">
                <div class="header dark-bg">
                    <h3>$title</h3>
                </div>
                <div class="package-features">
                    <ul>
                        <li>
                            <div class="package-name">$description</div>
                        </li>
                        <li>
                            <div class="package-description">$name</div>
                        </li>
                        <li>
                            <div class="package-price">$priceHtml</div>
                        </li>
                        <li>
                            <div class="package-data">Annual - Data &amp; SMS Service Only</div>
                        </li>
                    </ul>
                </div>
            </div>
        HTML);
    }
}
