<?php

use PHPUnit\Framework\TestCase;
use RapidWeb\UkBankHolidays\Exceptions\InvalidLocationException;
use RapidWeb\UkBankHolidays\Factories\UkBankHolidayFactory;

final class HolidayRetrievalByLocationTest extends TestCase
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
        $this->checkHolidaysArrayFormat(UkBankHolidayFactory::getAll('england-and-wales'));
    }

    public function testScotland()
    {
        $this->checkHolidaysArrayFormat(UkBankHolidayFactory::getAll('scotland'));
    }

    public function testNorthernIreland()
    {
        $this->checkHolidaysArrayFormat(UkBankHolidayFactory::getAll('northern-ireland'));
    }

    public function testInvalidLocation()
    {
        $this->expectException(InvalidLocationException::class);
        $this->checkHolidaysArrayFormat(UkBankHolidayFactory::getAll('rensid-flapsop'));
    }
}
