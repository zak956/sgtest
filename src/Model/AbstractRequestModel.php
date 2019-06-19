<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\Contract\RequestModelInterface;

abstract class AbstractRequestModel implements RequestModelInterface
{
    protected $url;
    protected $type;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}
