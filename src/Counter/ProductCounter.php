<?php

declare(strict_types=1);

namespace App\Counter;

use App\Counter\Contract\CounterInterface;
use App\Model\AbstractModel;
use App\Model\Contract\RequestModelInterface;
use App\Model\ProductRequestModel;

final class ProductCounter extends AbstractCounter implements CounterInterface
{
    public function addItem(RequestModelInterface $item): void
    {
        $this->counter += $item instanceof ProductRequestModel ? 1 : 0;
    }

    public function isLinkEnough(RequestModelInterface $link): bool
    {
        return $this->isEnough() && AbstractModel::TYPE_PRODUCT === $link->getType();
    }
}
