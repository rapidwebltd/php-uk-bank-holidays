<?php

namespace RapidWeb\UkBankHolidays\Objects\CacheDrivers;

use DivineOmega\DOFileCachePSR6\CacheItemPool;
use RapidWeb\UkBankHolidays\Interfaces\CacheDriverInterface;

class DOFileCacheDriver implements CacheDriverInterface
{
    const CACHE_EXPIRY_IN_SECONDS = 2592000; // 30 days

    private $cache = null;

    public function __construct()
    {
        $this->cache = new CacheItemPool();
        $this->cache->changeConfig(
            [
                'cacheDirectory'  => '/tmp/php-uk-bank-holidays-cache/',
                'gzipCompression' => true,
            ]
            );
    }

    public function set($key, $value)
    {
        $cacheItem = $this->cache->getItem($key);
        $cacheItem->set($value);
        $cacheItem->expiresAfter(self::CACHE_EXPIRY_IN_SECONDS);
        $this->cache->save($cacheItem);
    }

    public function get($key)
    {
        return $this->cache->getItem($key)->get();
    }
}
