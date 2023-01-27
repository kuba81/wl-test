<?php

namespace Tests\Unit\Services;

use App\Services\ItemParser;
use PHPUnit\Framework\TestCase;

class ItemParserTest extends TestCase
{
    public function testShouldCorrectlyExtractDataFromAnnualSubscription(): void
    {
        $html = <<<HTML
            <div class="package featured-right">
                <div class="header dark-bg">
                    <h3>My annual test package title</h3>
                </div>
                <div class="package-features">
                    <ul>
                        <li>
                            <div class="package-name">My annual test package name</div>
                        </li>
                        <li>
                            <div class="package-description">My annual test package description.</div>
                        </li>
                        <li>
                            <div class="package-price"><span class="price-big">£66.00</span><br>(inc. VAT)<br>Per Year
                                <p style="color: red">Save £5.86 on the monthly price</p>
                            </div>
                        </li>
                        <li>
                            <div class="package-data">Annual - Data &amp; SMS Service Only</div>
                        </li>
                    </ul>
                </div>
            </div>
        HTML;

        $parser = new ItemParser();
        $product = $parser->parse($html);

        $this->assertEquals('My annual test package title', $product->option);
        $this->assertEquals('My annual test package name', $product->title);
        $this->assertEquals('My annual test package description.', $product->description);

        $this->assertEquals('£66.00', $product->price);
        $this->assertEquals('£5.86', $product->discount);
    }
    public function testShouldCorrectlyExtractDataFromMontlySubscription(): void
    {
        $html = <<<HTML
            <div class="package featured-right">
                <div class="header dark-bg">
                    <h3>My monthly test package title</h3>
                </div>
                <div class="package-features">
                    <ul>
                        <li>
                            <div class="package-name">My monthly test package name</div>
                        </li>
                        <li>
                            <div class="package-description">My monthly test package description.</div>
                        </li>
                        <li>
                            <div class="package-price"><span class="price-big">£16.00</span><br>(inc. VAT)<br>Per Month</div>
                        </li>
                        <li>
                            <div class="package-data">Annual - Data &amp; SMS Service Only</div>
                        </li>
                    </ul>
                </div>
            </div>
        HTML;

        $parser = new ItemParser();
        $product = $parser->parse($html);

        $this->assertEquals('My monthly test package title', $product->option);
        $this->assertEquals('My monthly test package name', $product->title);
        $this->assertEquals('My monthly test package description.', $product->description);

        $this->assertEquals('£16.00', $product->price);
        $this->assertNull($product->discount);
    }
}
