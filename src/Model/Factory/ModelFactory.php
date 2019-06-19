<?php

declare(strict_types=1);

namespace App\Model\Factory;

use App\Model\AbstractModel;
use App\Model\Contract\ParsedModelInterface;
use App\Model\Contract\RequestModelInterface;
use App\Model\PageModel;
use App\Model\ProductModel;
use voku\helper\HtmlDomParser;

class ModelFactory
{
    private $parser;

    /**
     * ModelFactory constructor.
     *
     * @param HtmlDomParser $parser
     */
    public function __construct(HtmlDomParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param RequestModelInterface $request
     * @param string                $html
     *
     * @return null|ParsedModelInterface
     */
    public function factory(RequestModelInterface $request, string $html): ?ParsedModelInterface
    {
        $dom = $this->parser::str_get_html($html);

        if (AbstractModel::TYPE_PRODUCT === $request->getType()) {
            return new ProductModel($dom);
        }

        if (AbstractModel::TYPE_PAGE === $request->getType()) {
            return new PageModel($dom);
        }

        return null;
    }
}
