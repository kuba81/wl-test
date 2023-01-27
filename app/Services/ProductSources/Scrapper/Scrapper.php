<?php

namespace App\Services\ProductSources\Scrapper;

use App\Entities\Product;
use App\Services\ProductSources\ProductSourceInterface;
use App\Services\ProductSources\Scrapper\Contracts\PageParserInterface;
use App\Services\ProductSources\Scrapper\Exceptions\ParseException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

readonly class Scrapper implements ProductSourceInterface
{
    private array $options;

    public function __construct(private PageParserInterface $parser)
    {
        $this->options = config('sources.scrapper');
    }

    /**
     * @return Collection<int, Product>
     * @throws ParseException
     * @throws RequestException
     * @noinspection PhpDocRedundantThrowsInspection
     */
    public function getProducts(): Collection
    {
        $html = Http::get($this->options['url'])->body();

        return collect($this->parser->parse($html));
    }
}
