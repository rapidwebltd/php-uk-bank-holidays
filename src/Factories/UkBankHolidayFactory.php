<?php

namespace RapidWeb\UkBankHolidays\Factories;

use RapidWeb\UkBankHolidays\Objects\DataRetrievers\GovUkDataRetriever;

abstract class UkBankHolidayFactory
{
    private static $dataRetriver;
    private static $dates = [];

    private static function setUpDataRetriever()
    {
        self::$dataRetriver = new GovUkDataRetriever();
    }

    public static function getAll($location = 'england-and-wales')
    {
        if (empty(self::$dates[$location]) === false) {
            return self::$dates[$location];
        }
        self::setUpDataRetriever();
        self::$dates[$location] = self::$dataRetriver->retrieve($location);

        return self::$dates[$location];
    }

    public static function getByMonth($year, $month, $location = 'england-and-wales')
    {
        $dates = self::getAll($location);
        if (!isset($dates[$year][$month])) {
            return [];
        }

        return $dates[$year][$month];
    }

    public static function getByDate($year, $month, $day, $location = 'england-and-wales')
    {
        $dates = self::getByMonth($year, $month, $location);
        if (!isset($dates[$day])) {
            return [];
        }

        return $dates[$day];
    }
}
