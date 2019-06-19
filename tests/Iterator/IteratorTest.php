<?php

declare(strict_types=1);

namespace App\Tests\Iterator;

use App\Iterator\ExpectingIterator;
use App\Iterator\MapIterator;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class IteratorTest extends TestCase
{
    /**
     * @covers \App\Iterator\ExpectingIterator
     * @covers \App\Iterator\ExpectingIterator::key
     * @covers \App\Iterator\ExpectingIterator::valid
     * @covers \App\Iterator\MapIterator
     * @covers \App\Iterator\MapIterator::key
     * @covers \App\Iterator\MapIterator::valid
     */
    public function testIterator(): void
    {
        $iterator = new MapIterator(new \ArrayIterator(['item1']), function ($data, $array): void {
            $array[] = 'item2';
        });

        $this->assertTrue($iterator->valid());
        $this->assertSame(0, $iterator->key());
        $iterator->next();
        $this->assertFalse($iterator->valid());
        $iterator->rewind();

        $generator = new ExpectingIterator($iterator);
        $this->assertTrue($generator->valid());
        $this->assertSame(0, $generator->key());
        $generator->next();
        $this->assertTrue($generator->valid());
        $generator->next();
        $this->assertFalse($generator->valid());
    }
}
