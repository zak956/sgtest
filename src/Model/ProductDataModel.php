<?php

declare(strict_types=1);

namespace App\Model;

class ProductDataModel
{
    /** @var string */
    private $name;
    /** @var string */
    private $manufacturerSku;
    /** @var string[] */
    private $images;
    /** @var ProductDataSkusModel[] */
    private $skus;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getManufacturerSku(): string
    {
        return $this->manufacturerSku;
    }

    /**
     * @param string $manufacturerSku
     */
    public function setManufacturerSku(string $manufacturerSku): void
    {
        $this->manufacturerSku = $manufacturerSku;
    }

    /**
     * @return string[]
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @param string[] $images
     */
    public function setImages(array $images): void
    {
        $this->images = $images;
    }

    /**
     * @return ProductDataSkusModel[]
     */
    public function getSkus(): array
    {
        return $this->skus;
    }

    /**
     * @param ProductDataSkusModel[] $skus
     */
    public function setSkus(array $skus): void
    {
        $this->skus = $skus;
    }
}
