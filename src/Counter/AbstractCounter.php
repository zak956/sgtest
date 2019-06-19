<?php

declare(strict_types=1);

namespace App\Counter;

use App\Counter\Contract\CounterInterface;

abstract class AbstractCounter implements CounterInterface
{
    protected static $instance;
    protected $counter;
    protected $limit;
    protected $first;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public function isFirstItem(): bool
    {
        return $this->first;
    }

    public function setNotFirst(): void
    {
        $this->first = false;
    }

    public static function getInstance(int $limit): CounterInterface
    {
        if (null === static::$instance) {
            static::$instance = new static();

            static::$instance->counter = 0;
            static::$instance->limit = $limit;
            static::$instance->first = true;
        }

        return static::$instance;
    }

    public static function free(): void
    {
        static::$instance = null;
    }

    protected function isEnough(): bool
    {
        return $this->counter >= $this->limit;
    }
}
