<?php

namespace RapidWeb\UkBankHolidays\Interfaces;

interface DataRetrieverInterface
{
    public function setCacheDriver(CacheDriverInterface $cache);

    public function retrieve($location);
}
