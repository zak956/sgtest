<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Counter\Factory\CounterFactory;
use App\Decorator\JsonArrayItemDecorator;
use App\Model\AbstractModel;
use App\Model\Factory\ModelFactory;
use App\Model\PageModel;
use App\Model\PageRequestModel;
use App\Model\ProductDataModel;
use App\Model\ProductModel;
use App\Model\ProductRequestModel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use voku\helper\HtmlDomParser;

/**
 * @coversNothing
 */
class ModelDecoratorTest extends TestCase
{
    /**
     * @covers \App\Decorator\JsonArrayItemDecorator
     * @covers \App\Decorator\JsonArrayItemDecorator::__construct
     * @covers \App\Decorator\JsonArrayItemDecorator::getData
     * @covers \App\Model\Factory\ModelFactory
     * @covers \App\Model\Factory\ModelFactory::factory
     * @covers \App\Model\PageModel
     * @covers \App\Model\PageModel::getData
     * @covers \App\Model\PageModel::getNewLinks
     * @covers \App\Model\PageModel::isListPage
     * @covers \App\Model\PageModel::isProductPage
     *
     * @dataProvider getPageHtmlData
     *
     * @param mixed $html
     */
    public function testPageDecorator($html): void
    {
        $counter = CounterFactory::getCounter(AbstractModel::TYPE_PAGE, 1);
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $request = new PageRequestModel('www.test.com');
        $factory = new ModelFactory(new HtmlDomParser());
        $model = $factory->factory($request, $html);
        $this->assertTrue($model->isListPage());
        $this->assertFalse($model->isProductPage());
        $this->assertInstanceOf(PageModel::class, $model);
        $this->assertNull($model->getData());

        $decorator = new JsonArrayItemDecorator($model, $serializer, $counter);

        $this->assertInstanceOf(JsonArrayItemDecorator::class, $decorator);

        $this->assertStringMatchesFormat('', $decorator->getData());

        $this->assertIsArray($model->getNewLinks());
        $this->assertCount(1, $model->getNewLinks());
    }

    /**
     * @covers \App\Decorator\JsonArrayItemDecorator
     * @covers \App\Decorator\JsonArrayItemDecorator::__construct
     * @covers \App\Decorator\JsonArrayItemDecorator::getData
     * @covers \App\Model\AbstractModel
     * @covers \App\Model\AbstractModel::getNewLinks
     * @covers \App\Model\Factory\ModelFactory
     * @covers \App\Model\Factory\ModelFactory::factory
     * @covers \App\Model\ProductModel
     * @covers \App\Model\ProductModel::getData
     * @covers \App\Model\ProductModel::isListPage
     * @covers \App\Model\ProductModel::isProductPage
     *
     * @dataProvider getProductHtmlData
     *
     * @param mixed $html
     * @param mixed $expected
     */
    public function testProductDecorator($html, $expected): void
    {
        $counter = CounterFactory::getCounter(AbstractModel::TYPE_PRODUCT, 1);
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $request = new ProductRequestModel('www.test.com');
        $factory = new ModelFactory(new HtmlDomParser());
        $model = $factory->factory($request, $html);
        $this->assertFalse($model->isListPage());
        $this->assertTrue($model->isProductPage());
        $this->assertInstanceOf(ProductModel::class, $model);
        $this->assertInstanceOf(ProductDataModel::class, $model->getData());

        $decorator = new JsonArrayItemDecorator($model, $serializer, $counter);

        $this->assertInstanceOf(JsonArrayItemDecorator::class, $decorator);

        $output = $decorator->getData();

        $this->assertIsString('', $output);
        $this->assertJsonStringEqualsJsonString($output, $expected);

        $this->assertIsArray($model->getNewLinks());
        $this->assertCount(0, $model->getNewLinks());
    }

    public function getPageHtmlData()
    {
        return [
            ['<html><head></head><body><a class="next-link" href="www.test.com"></a></body></html>'],
        ];
    }

    public function getProductHtmlData()
    {
        return [
            [
                '<html><head></head><body><meta itemprop="sku" content="testsku"><h1 class="product-name" itemprop="name">Test Name</h1><section class="product-main"><div class="product-gallery"><div class="product-gallery-image"><img src="img1.png"><img src="img2.png"></div></div></section><input class="product-sizes__input" data-size="10" data-stock="true" data-price="$100.00"><input class="product-sizes__input" data-size="11" data-stock="true" data-price="$100.00"><input class="product-sizes__input" data-size="12" data-stock="false" data-price="$100.00"></body></html>',
                '{
    "name": "Test Name",
    "manufacturerSku": "testsku",
    "images": [
        "img1.png",
        "img2.png"
    ],
    "skus": [
        {
            "size": "10",
            "price": "100.00"
        },
        {
            "size": "11",
            "price": "100.00"
        },
        {
            "size": "12",
            "price": ""
        }
    ]
}
',
            ],
        ];
    }
}
