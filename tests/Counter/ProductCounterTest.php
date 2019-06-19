<?php

declare(strict_types=1);

namespace App\Tests\Counter;

use App\Counter\AbstractCounter;
use App\Counter\Factory\CounterFactory;
use App\Model\AbstractModel;
use App\Model\ProductRequestModel;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class ProductCounterTest extends TestCase
{
    /**
     * @covers \App\Counter\AbstractCounter
     * @covers \App\Counter\AbstractCounter::isEnough
     * @covers \App\Counter\ProductCounter
     * @covers \App\Counter\ProductCounter::addItem
     * @covers \App\Counter\ProductCounter::isLinkEnough
     */
    public function testProductCounter(): void
    {
        AbstractCounter::free();
        $counter = CounterFactory::getCounter(AbstractModel::TYPE_PRODUCT, 3);

        $link = new ProductRequestModel('test.com');
        $this->assertFalse($counter->isLinkEnough($link));
        $counter->addItem($link);
        $this->assertFalse($counter->isLinkEnough($link));
        $counter->addItem($link);
        $this->assertFalse($counter->isLinkEnough($link));
        $counter->addItem($link);
        $this->assertTrue($counter->isLinkEnough($link));
    }
}
