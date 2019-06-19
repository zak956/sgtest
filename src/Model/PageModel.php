<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\Contract\RequestModelInterface;

class PageModel extends AbstractModel
{
    public function getNewLinks(): array
    {
        $result = $this->getProductsLinks();
        $nextPageLink = $this->getNextPageLink();
        if (null !== $nextPageLink) {
            $result[] = $nextPageLink;
        }

        return $result;
    }

    public function isListPage(): bool
    {
        return true;
    }

    public function isProductPage(): bool
    {
        return false;
    }

    public function getData(): void
    {
    }

    private function getNextPageLink(): ?RequestModelInterface
    {
        $nextButton = $this->dom->findOne('a.next-link');
        $href = $nextButton->getAttribute('href');

        if ($href) {
            return new PageRequestModel($href);
        }

        return null;
    }

    private function getProductsLinks(): array
    {
        $result = [];
        $productLinks = $this->dom->find('li[itemtype="http://schema.org/Product"]>a.product-image');
        foreach ($productLinks as $productLink) {
            $result[] = new ProductRequestModel($productLink->getAttribute('href'));
        }

        return $result;
    }
}
