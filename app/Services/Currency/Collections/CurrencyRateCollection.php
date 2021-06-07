<?php

namespace App\Services\Currency\Collections;

use App\Services\Currency\Models\CurrencyRate;

class CurrencyRateCollection implements \Iterator, \Countable
{
    private $collection = [];

    public function add(CurrencyRate $currencyRate): CurrencyRateCollection
    {
        $this->collection[$currencyRate->charCodeUpper()] = $currencyRate;

        return $this;
    }

    /**
     * @return CurrencyRate[]
     */
    public function all(): array
    {
        return $this->collection;
    }

    public function count(): int
    {
        return $this->count();
    }

    public function getByCharCode(string $charCode): ?CurrencyRate
    {
        return $this->collection[$charCode] ?? null;
    }

    public function getCurrencyCharCodes(): array
    {
        return array_unique(array_keys($this->collection));
    }

    public function current()
    {
        return current($this->collection);
    }

    public function next()
    {
        return next($this->collection);
    }

    public function key(): string
    {
        return (string)key($this->collection);
    }

    public function valid(): bool
    {
        return key($this->collection) !== null;
    }

    public function rewind()
    {
        reset($this->collection);
    }
}
