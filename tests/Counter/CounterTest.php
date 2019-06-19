<?php

declare(strict_types=1);

namespace App\Tests\Counter;

use App\Counter\AbstractCounter;
use App\Counter\Factory\CounterFactory;
use App\Counter\PageCounter;
use App\Counter\ProductCounter;
use App\Model\AbstractModel;
use App\Model\PageRequestModel;
use App\Model\ProductRequestModel;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class CounterTest extends TestCase
{
    /**
     * @covers \App\Counter\AbstractCounter
     * @covers \App\Counter\AbstractCounter::free
     * @covers \App\Counter\AbstractCounter::getInstance
     * @covers \App\Counter\AbstractCounter::isFirstItem
     * @covers \App\Counter\AbstractCounter::setNotFirst
     */
    public function testCounter(): void
    {
        AbstractCounter::free();
        $counter = ProductCounter::getInstance(1);
        $this->assertInstanceOf(ProductCounter::class, $counter);
        $this->assertTrue($counter->isFirstItem());

        $counter->setNotFirst();
        $this->assertFalse($counter->isFirstItem());

        $counter = PageCounter::getInstance(1);
        $this->assertNotInstanceOf(PageCounter::class, $counter);

        AbstractCounter::free();
        $counter = PageCounter::getInstance(1);
        $this->assertInstanceOf(PageCounter::class, $counter);
        $this->assertTrue($counter->isFirstItem());

        $counter->setNotFirst();
        $this->assertFalse($counter->isFirstItem());
    }

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

    /**
     * @covers \App\Counter\AbstractCounter
     * @covers \App\Counter\AbstractCounter::isEnough
     * @covers \App\Counter\PageCounter::addItem
     * @covers \App\Counter\PageCounter::isLinkEnough
     * @covers \App\Counter\ProductCounter
     */
    public function testPageCounter(): void
    {
        AbstractCounter::free();
        $counter = CounterFactory::getCounter(AbstractModel::TYPE_PAGE, 3);

        $link = new PageRequestModel('test.com');
        $this->assertFalse($counter->isLinkEnough($link));
        $counter->addItem($link);
        $this->assertFalse($counter->isLinkEnough($link));
        $counter->addItem($link);
        $this->assertFalse($counter->isLinkEnough($link));
        $counter->addItem($link);
        $this->assertTrue($counter->isLinkEnough($link));
    }

    /**
     * @covers \App\Counter\AbstractCounter
     * @covers \App\Counter\AbstractCounter::__clone
     * @covers \App\Counter\AbstractCounter::__construct
     */
    public function testSingletonCounter(): void
    {
        $this->expectException(\Error::class);
        new ProductCounter();
        $this->expectException(\Error::class);
        new PageCounter();

        $counter = PageCounter::getInstance(1);
        $this->expectException(\Error::class);
        $c = clone $counter;
    }
}
