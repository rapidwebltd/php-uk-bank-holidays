<?php

use PHPUnit\Framework\TestCase;
use RapidWeb\UkBankHolidays\Objects\CacheDrivers\DOFileCacheDriver;
use RapidWeb\UkBankHolidays\Objects\DataRetrievers\GovUkDataRetriever;
use RapidWeb\UkBankHolidays\Factories\UkBankHolidayFactory;
use RapidWeb\UkBankHolidays\Tests\Support\BankHolidayFixture;

final class LaravelCacheDriverTest extends TestCase
{
    private function checkHolidaysArrayFormat($holidays)
    {
        $this->assertTrue(is_array($holidays), 'Holidays should be an array.');
        $this->assertTrue(count($holidays) > 0, 'Holidays array should have at least 1 value.');

        $holiday = $holidays[0];

        $this->assertEquals('RapidWeb\UkBankHolidays\Objects\UkBankHoliday', get_class($holiday));
    }

    public function testEnglandWales()
    {
        require_once __DIR__.'/includes/MockLaravelCache/Cache.php';
        \Illuminate\Support\Facades\Cache::setFacadeRoot(new \stdClass());
        \Illuminate\Support\Facades\Cache::setItems([
            'GovUkBankHolidays' => BankHolidayFixture::data(),
        ]);
        UkBankHolidayFactory::setCacheDriver(null);

        $this->checkHolidaysArrayFormat(UkBankHolidayFactory::getAll('england-and-wales'));

        \Illuminate\Support\Facades\Cache::setFacadeRoot(null);
    }

    public function testUnbootedFacadeFallsBackToFileCache()
    {
        require_once __DIR__.'/includes/MockLaravelCache/Cache.php';
        \Illuminate\Support\Facades\Cache::setFacadeRoot(null);

        $retriever = new GovUkDataRetriever();
        $property = new \ReflectionProperty($retriever, 'cache');
        $property->setAccessible(true);

        $this->assertInstanceOf(DOFileCacheDriver::class, $property->getValue($retriever));
    }
}
