# PHP UK Bank Holidays

[![Build Status](https://travis-ci.org/rapidwebltd/php-uk-bank-holidays.svg?branch=master)](https://travis-ci.org/rapidwebltd/php-uk-bank-holidays)
[![Coverage Status](https://coveralls.io/repos/github/rapidwebltd/php-uk-bank-holidays/badge.svg?branch=master)](https://coveralls.io/github/rapidwebltd/php-uk-bank-holidays?branch=master)
[![StyleCI](https://styleci.io/repos/84812494/shield?branch=master)](https://styleci.io/repos/84812494)

This library lets developers easily retrieve UK bank holiday details. Holidays can be retrieved for 
England & Wales, Scotland, and Northern Ireland. Information about these holidays can optionally be
restricted by month or date.

## Installation
To install, just run the following composer command.

`composer require rapidwebltd/php-uk-bank-holidays`

Remember to include the `vendor/autoload.php` file if your framework does not do this for you.

## Usage

Retrieving bank holidays for a specific date can be done as follows. By default this will bring
back holidays from England & Wales.

```php
use RapidWeb\UkBankHolidays\Factories\UkBankHolidayFactory;

$holidays = UkBankHolidayFactory::getByDate(2017, 01, 2);

var_dump($holidays);
```

### UKBankHoliday object

All `UkBankHolidayFactory` methods will return an array of `UkBankHoliday` objects. The following
snippet shows an example object for the 2017 New Year's Day bank holiday, which occurs on the 2nd 
of January 2017.

```php
array(1) {
  [0]=>
  object(RapidWeb\UkBankHolidays\Objects\UkBankHoliday)#46 (4) {
    ["title"]=>
    string(16) "New Year’s Day"
    ["date"]=>
    string(10) "2017-01-02"
    ["notes"]=>
    string(14) "Substitute day"
  }
}
```

This object contains a title for the holiday, the date it occurs, and government provided notes
regarding the holiday, if available. All of these are publicly accessible.

### Location restriction

If you wish to retrieve bank holidays from Scotland or Northern Ireland, make use of the 4th optional
argument, as follows.

```php
$englandWalesHolidays = UkBankHolidayFactory::getByDate(2017, 01, 2, 'england-and-wales');
$scotlandHolidays = UkBankHolidayFactory::getByDate(2017, 01, 2, 'scotland');
$northernIrelandHolidays = UkBankHolidayFactory::getByDate(2017, 01, 2, 'northern-ireland');

$allHolidays = array_merge($englandWalesHolidays, $scotlandHolidays, $northernIrelandHolidays);
```

### Date-based restrictions

You can use this library to retrieve all holidays or alternatively holidays restricted by month or date.
This is easily done using the `getAll`, `getByMonth` and `getByDate` methods respectively.

See the following examples.

```php
$allHolidays = UkBankHolidayFactory::getAll();
$januaryHolidays = UkBankHolidayFactory::getByMonth(2017, 01);
$newYearsHolidays = UkBankHolidayFactory::getByDate(2017, 01, 2);
```

All of these methods accept an additional optional argument to specify the location of the holidays you wish to
retrieve. For more details, see the location restriction section above.
