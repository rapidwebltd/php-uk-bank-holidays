<?php
namespace RapidWeb\UkBankHolidays\Objects;

class UkBankHoliday
{
    public $title;
    public $date;
    public $notes;

    public function __construct($title,$date,$notes)
    {
     $this->title = $title;
     $this->date = $date;
     $this->notes = $notes;
    }
}
?>
