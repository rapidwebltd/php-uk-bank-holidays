<?php

namespace RapidWeb\UkBankHolidays\Interfaces;

interface CacheDriverInterface
{
    public function set($key, $value);
    public function get($key);
}