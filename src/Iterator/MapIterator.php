<?php

declare(strict_types=1);

namespace App\Iterator;

use Iterator;

class MapIterator implements Iterator
{
    /**
     * @var Iterator
     */
    private $inner;
    private $handler;

    public function __construct(Iterator $inner, callable $handler)
    {
        $this->inner = $inner;
        $this->handler = $handler;
    }

    public function next(): void
    {
        $this->inner->next();
    }

    public function current()
    {
        return \call_user_func($this->handler, $this->inner->current(), $this->inner);
    }

    public function rewind(): void
    {
        $this->inner->rewind();
    }

    public function key()
    {
        return $this->inner->key();
    }

    public function valid()
    {
        return $this->inner->valid();
    }
}
