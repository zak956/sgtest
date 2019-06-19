<?php

declare(strict_types=1);

namespace App\Counter;

use App\Counter\Contract\CounterInterface;
use App\Model\AbstractModel;
use App\Model\Contract\RequestModelInterface;
use App\Model\PageRequestModel;

final class PageCounter extends AbstractCounter implements CounterInterface
{
    public function addItem(RequestModelInterface $item): void
    {
        $this->counter += $item instanceof PageRequestModel ? 1 : 0;
    }

    public function isLinkEnough(RequestModelInterface $link): bool
    {
        return $this->isEnough() && AbstractModel::TYPE_PAGE === $link->getType();
    }
}
