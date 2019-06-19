<?php

declare(strict_types=1);

namespace App\Tests\Scraper;

use App\Counter\AbstractCounter;
use App\Counter\Factory\CounterFactory;
use App\Model\AbstractModel;
use App\Scraper\SGScraper;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @coversNothing
 */
class ScraperTest extends TestCase
{
    const FILENAME = './test_output.json';

    /**
     * @covers \App\Scraper\SGScraper
     * @covers \App\Scraper\SGScraper::run
     * @dataProvider getData
     *
     * @param $type
     * @param $limit
     * @param $file
     */
    public function testScraper($type, $limit, $file): void
    {
        $output = new StreamOutput(fopen(self::FILENAME, 'w', false));

        $mock = new MockHandler([
            new Response(200, [], '<html><head></head><body><li><li itemtype="http://schema.org/Product"><a class="product-image" href="www.test.com/item/1"></a></li><li itemtype="http://schema.org/Product"><a class="product-image" href="www.test.com/item/2"></a></li></ul><a class="next-link" href="www.test.com/page/2"></a></body></html>'),
            new Response(200, [], '<html><head></head><body><meta itemprop="sku" content="testsku"><h1 class="product-name" itemprop="name">Test Name</h1><section class="product-main"><div class="product-gallery"><div class="product-gallery-image"><img src="img1.png"><img src="img2.png"></div></div></section><input class="product-sizes__input" data-size="10" data-stock="true" data-price="$100.00"><input class="product-sizes__input" data-size="11" data-stock="true" data-price="$100.00"><input class="product-sizes__input" data-size="12" data-stock="false" data-price="$100.00"></body></html>'),
            new Response(200, [], '<html><head></head><body><meta itemprop="sku" content="testsku"><h1 class="product-name" itemprop="name">Test Name</h1><section class="product-main"><div class="product-gallery"><div class="product-gallery-image"><img src="img1.png"><img src="img2.png"></div></div></section><input class="product-sizes__input" data-size="10" data-stock="true" data-price="$100.00"><input class="product-sizes__input" data-size="11" data-stock="true" data-price="$100.00"><input class="product-sizes__input" data-size="12" data-stock="false" data-price="$100.00"></body></html>'),
            new Response(200, [], '<html><head></head><body><li><li itemtype="http://schema.org/Product"><a class="product-image" href="www.test.com/item/3"></a></li><li itemtype="http://schema.org/Product"><a class="product-image" href="www.test.com/item/4"></a></li></ul></body></html>'),
            new Response(200, [], '<html><head></head><body><meta itemprop="sku" content="testsku"><h1 class="product-name" itemprop="name">Test Name</h1><section class="product-main"><div class="product-gallery"><div class="product-gallery-image"><img src="img1.png"><img src="img2.png"></div></div></section><input class="product-sizes__input" data-size="10" data-stock="true" data-price="$100.00"><input class="product-sizes__input" data-size="11" data-stock="true" data-price="$100.00"><input class="product-sizes__input" data-size="12" data-stock="false" data-price="$100.00"></body></html>'),
            new Response(200, [], '<html><head></head><body><meta itemprop="sku" content="testsku"><h1 class="product-name" itemprop="name">Test Name</h1><section class="product-main"><div class="product-gallery"><div class="product-gallery-image"><img src="img1.png"><img src="img2.png"></div></div></section><input class="product-sizes__input" data-size="10" data-stock="true" data-price="$100.00"><input class="product-sizes__input" data-size="11" data-stock="true" data-price="$100.00"><input class="product-sizes__input" data-size="12" data-stock="false" data-price="$100.00"></body></html>'),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        AbstractCounter::free();
        $counter = CounterFactory::getCounter($type, $limit);

        $scraper = new SGScraper(
            $client,
            $serializer,
            'test.com',
            2,
            $counter
        );

        $scraper->run($output);

        $this->assertJsonFileEqualsJsonFile($file, self::FILENAME);
    }

    public function getData()
    {
        return [
            [AbstractModel::TYPE_PRODUCT, 3, './tests/data/test_product_3.json'],
            [AbstractModel::TYPE_PAGE, 2, './tests/data/test_page_2.json'],
        ];
    }
}
