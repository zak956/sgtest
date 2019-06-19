<?php

declare(strict_types=1);

namespace App\Model;

use voku\helper\HtmlDomParser;

class ProductModel extends AbstractModel
{
    /** @var ProductDataModel */
    private $data;

    public function __construct(HtmlDomParser $dom)
    {
        parent::__construct($dom);

        $this->parseData();
    }

    public function isListPage(): bool
    {
        return false;
    }

    public function isProductPage(): bool
    {
        return true;
    }

    public function getData(): ProductDataModel
    {
        return $this->data;
    }

    private function parseData(): void
    {
        $this->data = new ProductDataModel();

        $this->data->setName($this->dom->findOne('h1.product-name[itemprop="name"]')->innertext);
        $this->data->setManufacturerSku($this->dom->findOne('meta[itemprop="sku"]')->getAttribute('content'));

        $images = [];
        $imageElements = $this->dom->find('section.product-main>div.product-gallery>div.product-gallery-image>img');
        foreach ($imageElements as $imageElement) {
            $images[] = $imageElement->getAttribute('src');
        }
        $this->data->setImages($images);

        $skus = [];
        $skusElements = $this->dom->find('input.product-sizes__input');
        foreach ($skusElements as $skusElement) {
            $sku = new ProductDataSkusModel();
            $sku->setSize($skusElement->getAttribute('data-size'));
            if ('false' === $skusElement->getAttribute('data-stock')) {
                $sku->setPrice('');
            } else {
                $sku->setPrice(ltrim($skusElement->getAttribute('data-price'), '$'));
            }

            $skus[] = $sku;
        }
        $this->data->setSkus($skus);
    }
}
