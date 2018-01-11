<?php

use PHPUnit\Framework\TestCase;

use RapidWeb\UkBankHolidays\Factories\UkBankHolidayFactory;

final class BasicUsageTest extends TestCase
{
    public function testGetNewYearsDay()
    {
        $holidays = UkBankHolidayFactory::getByDate(2017, 01, 2);

        $this->assertTrue(is_array($holidays), 'Holidays should be an array.');
        $this->assertEquals(1, count($holidays), 'Holidays array should have 1 value.');

        $holiday = $holidays[0];

        $this->assertEquals('RapidWeb\UkBankHolidays\Objects\UkBankHoliday', get_class($holiday));

        $this->assertEquals('New Year’s Day', $holiday->title);
        $this->assertEquals('2017-01-02', $holiday->date);
        $this->assertEquals('Substitute day', $holiday->notes);

    }

}