<?php

declare(strict_types=1);

namespace App\Tests\Counter;

use App\Counter\AbstractCounter;
use App\Counter\Factory\CounterFactory;
use App\Counter\PageCounter;
use App\Counter\ProductCounter;
use App\Model\AbstractModel;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class CounterFactoryTest extends TestCase
{
    /**
     * @covers \App\Counter\Factory\CounterFactory
     * @covers \App\Counter\Factory\CounterFactory::getCounter
     */
    public function testGetCounter(): void
    {
        $counter = CounterFactory::getCounter(AbstractModel::TYPE_PRODUCT, 5);
        $this->assertInstanceOf(ProductCounter::class, $counter, 'Wrong counter class!');

        AbstractCounter::free();

        $counter = CounterFactory::getCounter(AbstractModel::TYPE_PAGE, 5);
        $this->assertInstanceOf(PageCounter::class, $counter, 'Wrong counter class!');
    }
}
