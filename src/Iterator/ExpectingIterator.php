<?php

declare(strict_types=1);

namespace App\Iterator;

use Iterator;

class ExpectingIterator implements Iterator
{
    /**
     * @var Iterator
     */
    private $inner;
    private $wasValid;

    public function __construct(Iterator $inner)
    {
        $this->inner = $inner;
    }

    public function next(): void
    {
        if (!$this->wasValid && $this->valid()) {
            // Just do nothing, because the inner iterator has became valid.
        } else {
            $this->inner->next();
        }

        $this->wasValid = $this->valid();
    }

    public function current()
    {
        return $this->inner->current();
    }

    public function rewind(): void
    {
        $this->inner->rewind();

        $this->wasValid = $this->valid();
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
