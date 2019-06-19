<?php

declare(strict_types=1);

namespace App\Counter\Factory;

use App\Counter\Contract\CounterInterface;
use App\Counter\PageCounter;
use App\Counter\ProductCounter;
use App\Model\AbstractModel;

class CounterFactory
{
    public static function getCounter(string $type, int $limit): ?CounterInterface
    {
        $counter = null;
        switch ($type) {
            case AbstractModel::TYPE_PAGE:
                $counter = PageCounter::getInstance($limit);

                break;
            case AbstractModel::TYPE_PRODUCT:
                $counter = ProductCounter::getInstance($limit);

                break;
        }

        return $counter;
    }
}
