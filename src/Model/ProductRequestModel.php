<?php

declare(strict_types=1);

namespace App\Model;

class ProductRequestModel extends AbstractRequestModel
{
    public function __construct($url)
    {
        parent::__construct($url);
        $this->type = AbstractModel::TYPE_PRODUCT;
    }
}
