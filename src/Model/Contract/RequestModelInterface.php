<?php

declare(strict_types=1);

namespace App\Model\Contract;

interface RequestModelInterface
{
    public function __construct(string $url);

    public function getUrl(): string;

    public function getType(): string;
}
