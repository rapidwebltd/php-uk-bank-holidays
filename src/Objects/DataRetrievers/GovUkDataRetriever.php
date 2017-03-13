<?
namespace RapidWeb\UkBankHolidays\Objects\DataRetrievers;

use RapidWeb\UkBankHolidays\Objects\UkBankHoliday;
use Exception;

class GovUkDataRetriever
{
  private function retrieve($location)
  {
    $retrievedData = file_get_contents("https://www.gov.uk/bank-holidays.json");

    if (!$retrievedData) {
        throw new Exception('Unable to retrieve JSON data.');
    }

    $data = json_decode($retrievedData,true);

    if (!$data) {
        throw new Exception('Unable to decode JSON data.');
    }

    if (!isset($data[$location]) || !isset($data[$location]['events'])) {
        throw new Exception('Unable to locate events for the specified location.');
    }

    $bankHolidayDates = array();

    foreach($data[$location]['events'] as $holidayDate){
           $bankHolidayDate = new UkBankHoliday($holidayDate['title'],$holidayDate['date'],$holidayDate['notes'],$holidayDate['bunting']);
           $bankHolidayDates[] = $bankHolidayDate;
    }

    return $bankHolidayDates;
  }

  public function retrieveEnglandAndWales()
  {
      return $this->retrieve("england-and-wales");
  }

  public function retrieveScotland()
  {
      return $this->retrieve("scotland");
  }

  public function retrieveNorthernIreland()
  {
      return $this->retrieve("northern-ireland");
  }

}