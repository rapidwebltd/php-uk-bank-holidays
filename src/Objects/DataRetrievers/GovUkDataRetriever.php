<?php
namespace RapidWeb\UkBankHolidays\Objects\DataRetrievers;

use RapidWeb\UkBankHolidays\Objects\UkBankHoliday;
use RapidWeb\UkBankHolidays\Objects\CacheDrivers\RWFileCacheDriver;
use RapidWeb\UkBankHolidays\Objects\CacheDrivers\LaravelCacheDriver;
use Exception;

class GovUkDataRetriever
{

  private $cache = null;
  private $cacheKey = 'GovUkBankHolidays';
  private $cacheExpiry = '+1 month';

  private function setupCache()
  {
    if (class_exists('Illuminate\Support\Facades\Cache')) {
        $this->cache = new LaravelCacheDriver;
    } else {
      $this->cache = new RWFileCacheDriver;
    }
  }

  private function retrieve($location)
  {
    $this->setupCache();
      
    if (!($data = $this->cache->get($this->cacheKey))) {

        $retrievedData = file_get_contents("https://www.gov.uk/bank-holidays.json");

        if (!$retrievedData) {
            throw new Exception('Unable to retrieve JSON data.');
        }

        $data = json_decode($retrievedData,true);

        if (!$data) {
            throw new Exception('Unable to decode JSON data.');
        }

        $this->cache->set($this->cacheKey, $data, strtotime($this->cacheExpiry));

    }

    if (!isset($data[$location]) || !isset($data[$location]['events'])) {
        throw new Exception('Unable to locate events for the specified location.');
    }

    $bankHolidayDates = array();

    foreach($data[$location]['events'] as $holidayDate){
           $bankHolidayDate = new UkBankHoliday($holidayDate['title'],$holidayDate['date'],$holidayDate['notes']);
           $bankHolidayDates[] = $bankHolidayDate;
    }

    return $bankHolidayDates;
  }

  public function retrieveEnglandAndWales()
  {
      return $this->retrieve("england-and-wales");
  }

  public function retrieveScotland()
  {
      return $this->retrieve("scotland");
  }

  public function retrieveNorthernIreland()
  {
      return $this->retrieve("northern-ireland");
  }

}
