<?php

declare(strict_types=1);

namespace App\Counter\Contract;

use App\Model\Contract\RequestModelInterface;

interface CounterInterface
{
    public function addItem(RequestModelInterface $item);

    public function isFirstItem(): bool;

    public function setNotFirst(): void;

    public function isLinkEnough(RequestModelInterface $link): bool;
}
