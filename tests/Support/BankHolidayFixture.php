<?php

namespace RapidWeb\UkBankHolidays\Tests\Support;

abstract class BankHolidayFixture
{
    public static function data()
    {
        $event = [
            'title' => 'New Year’s Day',
            'date' => '2017-01-02',
            'notes' => 'Substitute day',
        ];

        return [
            'england-and-wales' => ['events' => [$event]],
            'scotland' => ['events' => [$event]],
            'northern-ireland' => ['events' => [$event]],
        ];
    }
}
