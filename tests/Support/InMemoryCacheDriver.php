<?php

namespace RapidWeb\UkBankHolidays\Tests\Support;

use RapidWeb\UkBankHolidays\Interfaces\CacheDriverInterface;

class InMemoryCacheDriver implements CacheDriverInterface
{
    private $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function set($key, $value)
    {
        $this->items[$key] = $value;
    }

    public function get($key)
    {
        return isset($this->items[$key]) ? $this->items[$key] : null;
    }
}
