<?
namespace RapidWeb\UkBankHolidays\Objects;

class UkBankHoliday
{
    public $title;
    public $date;
    public $notes;
    public $bunting;

    public function __construct($title,$date,$notes,$bunting)
    {
     $this->title = $title;
     $this->date = $date;
     $this->notes = $notes;
     $this->bunting = $bunting;
    }
}
?>