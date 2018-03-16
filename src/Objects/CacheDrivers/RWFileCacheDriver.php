<?php

namespace RapidWeb\UkBankHolidays\Objects\CacheDrivers;

use rapidweb\RWFileCache\RWFileCache;
use RapidWeb\UkBankHolidays\Interfaces\CacheDriverInterface;
use rapidweb\RWFileCachePSR6\CacheItemPool;

class RWFileCacheDriver implements CacheDriverInterface
{
    const CACHE_EXPIRY_IN_SECONDS = 60*60*24*30; // 30 days

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
    }

    public function get($key)
    {
        return $this->cache->getItem($key)->get();
    }
}
