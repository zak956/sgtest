<?php

declare(strict_types=1);

namespace App\Model\Contract;

use App\Decorator\Contract\OutputInterface;

interface ParsedModelInterface extends OutputInterface
{
    public function getNewLinks(): array;

    public function isListPage(): bool;

    public function isProductPage(): bool;
}
