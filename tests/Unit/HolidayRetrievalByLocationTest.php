<?php

use PHPUnit\Framework\TestCase;
use RapidWeb\UkBankHolidays\Exceptions\InvalidLocationException;
use RapidWeb\UkBankHolidays\Factories\UkBankHolidayFactory;
use RapidWeb\UkBankHolidays\Tests\Support\BankHolidayFixture;
use RapidWeb\UkBankHolidays\Tests\Support\InMemoryCacheDriver;

final class HolidayRetrievalByLocationTest extends TestCase
{
    private function useFixtureCache()
    {
        UkBankHolidayFactory::setCacheDriver(new InMemoryCacheDriver([
            'GovUkBankHolidays' => BankHolidayFixture::data(),
        ]));
    }

    private function checkHolidaysArrayFormat($holidays)
    {
        $this->assertTrue(is_array($holidays), 'Holidays should be an array.');
        $this->assertTrue(count($holidays) > 0, 'Holidays array should have at least 1 value.');

        $holiday = $holidays[0];

        $this->assertEquals('RapidWeb\UkBankHolidays\Objects\UkBankHoliday', get_class($holiday));
    }

    public function testEnglandWales()
    {
        $this->useFixtureCache();
        $this->checkHolidaysArrayFormat(UkBankHolidayFactory::getAll('england-and-wales'));
    }

    public function testScotland()
    {
        $this->useFixtureCache();
        $this->checkHolidaysArrayFormat(UkBankHolidayFactory::getAll('scotland'));
    }

    public function testNorthernIreland()
    {
        $this->useFixtureCache();
        $this->checkHolidaysArrayFormat(UkBankHolidayFactory::getAll('northern-ireland'));
    }

    public function testInvalidLocation()
    {
        $this->useFixtureCache();
        $this->expectException(InvalidLocationException::class);
        $this->checkHolidaysArrayFormat(UkBankHolidayFactory::getAll('rensid-flapsop'));
    }

    public function testCustomCacheDriverCanBeSupplied()
    {
        $this->useFixtureCache();
        $holidays = UkBankHolidayFactory::getAll('scotland');

        $this->assertCount(1, $holidays);
        $this->assertSame('2017-01-02', $holidays[0]->date);
    }
}
