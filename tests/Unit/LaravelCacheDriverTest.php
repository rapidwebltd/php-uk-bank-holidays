<?php

use PHPUnit\Framework\TestCase;
use RapidWeb\UkBankHolidays\Factories\UkBankHolidayFactory;

final class LaravelCacheDriverTest extends TestCase
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
        require_once __DIR__.'/includes/MockLaravelCache/Cache.php';
        $this->checkHolidaysArrayFormat(UkBankHolidayFactory::getAll('england-and-wales'));
    }
}
