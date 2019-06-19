<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\Contract\ParsedModelInterface;
use voku\helper\HtmlDomParser;

abstract class AbstractModel implements ParsedModelInterface
{
    public const TYPE_PRODUCT = 'product';
    public const TYPE_PAGE = 'page';
    protected $dom;

    public function __construct(HtmlDomParser $dom)
    {
        $this->dom = $dom;
    }

    public function getNewLinks(): array
    {
        return [];
    }
}
