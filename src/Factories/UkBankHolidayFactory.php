<?php

namespace RapidWeb\UkBankHolidays\Factories;

use RapidWeb\UkBankHolidays\Objects\DataRetrievers\GovUkDataRetriever;

abstract class UkBankHolidayFactory
{
    private static $dataRetriver;

    private static function setUpDataRetriever()
    {
        self::$dataRetriver = new GovUkDataRetriever();
    }

    public static function getAll($location = 'england-and-wales')
    {
        self::setUpDataRetriever();
        $dates = self::$dataRetriver->retrieve($location);

        return $dates;
    }

    public static function getByMonth($year, $month, $location = 'england-and-wales')
    {
        $dates = self::getAll($location);
        $dateRange = [];
        foreach ($dates as $date) {
            if (date('Y', strtotime($date->date)) == $year && date('m', strtotime($date->date)) == $month) {
                $dateRange[] = $date;
            }
        }

        return $dateRange;
    }

    public static function getByDate($year, $month, $day, $location = 'england-and-wales')
    {
        $dates = self::getByMonth($year, $month, $location);
        $dateRange = [];
        foreach ($dates as $date) {
            if (date('d', strtotime($date->date)) == $day) {
                $dateRange[] = $date;
            }
        }

        return $dateRange;
    }
}
