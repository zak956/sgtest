<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Model\ProductDataModel;
use App\Model\ProductDataSkusModel;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class ProductDataModelTest extends TestCase
{
    /**
     * @covers \App\Model\ProductDataSkusModel
     * @dataProvider getSkuData
     *
     * @param $price
     * @param $size
     */
    public function testDataSkusModel($price, $size): void
    {
        $sku = new ProductDataSkusModel();
        $sku->setPrice($price);
        $sku->setSize($size);

        $this->assertSame($price, $sku->getPrice());
        $this->assertSame($size, $sku->getSize());
    }

    /**
     * @covers \App\Model\ProductDataModel
     * @dataProvider getData
     *
     * @param $name
     * @param $mansku
     * @param array $images
     * @param array $skus
     */
    public function testDataModel($name, $mansku, array $images, array $skus): void
    {
        $data = new ProductDataModel();
        $data->setName($name);
        $data->setManufacturerSku($mansku);
        $data->setImages($images);
        $skusList = [];
        foreach ($skus as $sku) {
            $skuItem = new ProductDataSkusModel();
            $skuItem->setPrice($sku[0]);
            $skuItem->setSize($sku[1]);

            $skusList[] = $skuItem;
        }
        $data->setSkus($skusList);

        $this->assertSame($name, $data->getName());
        $this->assertSame($mansku, $data->getManufacturerSku());
        $this->assertIsArray($data->getImages());
        $this->assertSame($images, $data->getImages());
        $this->assertIsArray($data->getSkus());
        foreach ($data->getSkus() as $skusModel) {
            $this->assertInstanceOf(ProductDataSkusModel::class, $skusModel);
        }
    }

    public function getSkuData()
    {
        return [
            ['100.00', '10'],
            ['110.00', '11'],
        ];
    }

    public function getData()
    {
        return [
            ['Test Name', 'testsku', ['img1.png', 'img2.png'], $this->getSkuData()],
        ];
    }
}
