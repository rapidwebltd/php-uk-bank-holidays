<?php

namespace RapidWeb\UkBankHolidays\Objects\DataRetrievers;

use Exception;
use RapidWeb\UkBankHolidays\Exceptions\InvalidLocationException;
use RapidWeb\UkBankHolidays\Interfaces\CacheDriverInterface;
use RapidWeb\UkBankHolidays\Interfaces\DataRetrieverInterface;
use RapidWeb\UkBankHolidays\Objects\CacheDrivers\DOFileCacheDriver;
use RapidWeb\UkBankHolidays\Objects\CacheDrivers\LaravelCacheDriver;
use RapidWeb\UkBankHolidays\Objects\UkBankHoliday;

class GovUkDataRetriever implements DataRetrieverInterface
{
    private $cache = null;
    private $cacheKey = 'GovUkBankHolidays';
    private $acceptableLocations = ['england-and-wales', 'scotland', 'northern-ireland'];

    public function __construct()
    {
        $this->setupCache();
    }

    private function setupCache()
    {
        if (class_exists('Illuminate\Support\Facades\Cache')) {
            $this->setCacheDriver(new LaravelCacheDriver());
        } else {
            $this->setCacheDriver(new DOFileCacheDriver());
        }
    }

    public function setCacheDriver(CacheDriverInterface $cache)
    {
        $this->cache = $cache;
    }

    public function retrieve($location)
    {
        if (!in_array($location, $this->acceptableLocations)) {
            throw new InvalidLocationException('Invalid location specified. Acceptable locations: '.implode(', ', $this->acceptableLocations));
        }

        if (!($data = $this->cache->get($this->cacheKey))) {
            $retrievedData = file_get_contents('https://www.gov.uk/bank-holidays.json');

            if (!$retrievedData) {
                throw new Exception('Unable to retrieve JSON data.');
            }

            $data = json_decode($retrievedData, true);

            if (!$data) {
                throw new Exception('Unable to decode JSON data.');
            }

            $this->cache->set($this->cacheKey, $data);
        }

        if (!isset($data[$location]) || !isset($data[$location]['events'])) {
            throw new Exception('Unable to locate events for the specified location.');
        }

        $bankHolidayDates = [];

        foreach ($data[$location]['events'] as $holidayDate) {
            $bankHolidayDate = new UkBankHoliday($holidayDate['title'], $holidayDate['date'], $holidayDate['notes']);
            $bankHolidayDates[] = $bankHolidayDate;
        }

        return $bankHolidayDates;
    }
}
