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

    public function __construct(CacheDriverInterface $cache = null)
    {
        if ($cache) {
            $this->setCacheDriver($cache);
        } else {
            $this->setupCache();
        }
    }

    private function setupCache()
    {
        $laravelCacheFacade = 'Illuminate\Support\Facades\Cache';

        if (class_exists($laravelCacheFacade)
            && method_exists($laravelCacheFacade, 'getFacadeRoot')
            && $laravelCacheFacade::getFacadeRoot()
        ) {
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
            $retrievedData = $this->retrieveRemoteData();

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

    protected function retrieveRemoteData()
    {
        $url = 'https://www.gov.uk/bank-holidays.json';

        if (function_exists('curl_init')) {
            $curl = curl_init($url);
            curl_setopt_array($curl, [
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_USERAGENT => 'rapidwebltd/php-uk-bank-holidays',
            ]);

            $data = curl_exec($curl);
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($data !== false && $statusCode >= 200 && $statusCode < 300) {
                return $data;
            }

            return false;
        }

        $context = stream_context_create([
            'http' => [
                'header' => "User-Agent: rapidwebltd/php-uk-bank-holidays\r\n",
                'timeout' => 30,
            ],
        ]);

        return @file_get_contents($url, false, $context);
    }
}
