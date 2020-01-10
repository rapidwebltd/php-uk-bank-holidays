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
        $currYear = date('Y');
        $this->assertTrue(is_array($holidays[$currYear]));
        $months = end($holidays[$currYear]);
        $this->assertTrue(is_array($months));
        $days = end($months);
        $this->assertTrue(is_array($days));
        $day = end($days);
        
        $this->assertEquals('RapidWeb\UkBankHolidays\Objects\UkBankHoliday', get_class($day));
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
