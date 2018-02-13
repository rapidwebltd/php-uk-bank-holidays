<?php

use PHPUnit\Framework\TestCase;
use RapidWeb\UkBankHolidays\Factories\UkBankHolidayFactory;

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
        $this->checkHolidaysArrayFormat(UkBankHolidayFactory::getAll('england-and-wales'));
    }
}
