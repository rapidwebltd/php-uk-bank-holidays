<?
namespace RapidWeb\UkBankHolidays\Factories;

use exception;
use RapidWeb\UkBankHolidays\Objects\DataRetrievers\GovUkDataRetriever;

abstract class UkBankHolidayFactory
{
    private static $dataRetriver;

    private static function setUpDataRetriever()
    {
     self::$dataRetriver  = new GovUkDataRetriever();
    }

    public static function getAll($location = "england-wales")
    {
        self::setUpDataRetriever();
        switch($location)
        {
            case"england-wales":
                $dates = self::$dataRetriver->retrieveEnglandAndWales();
            break;

            case"scotland":
                $dates = self::$dataRetriver->retrieveScotland();
            break;

            case"northern-ireland":
                $dates = self::$dataRetriver->retrieveNorthernIreland();
            break;

            default:
            throw new exception("Specified Location is invalid");
            break;
        }
        return $dates;

    }
    public static function getByMonth($year,$month,$location = "england-wales")
    {
        $dates = self::getAll($location);
        $dateRange = array();
        foreach($dates as $date){
            if(date("Y",strtotime($date->date)) == $year && date("m",strtotime($date->date)) == $month){
                $dateRange[] = $date;
            }
        }
        return $dateRange;
    }
    public static function getByDate($year,$month,$day,$location = "england-wales")
    {
      $dates = self::getByMonth($year,$month,$location);
      $dateRange = array();
         foreach($dates as $date){
            if(date("d",strtotime($date->date)) == $day){
                $dateRange[] = $date;
            }
        }
      return $dateRange;

    }
    
}
