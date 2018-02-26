<?php

namespace Trainor\DateTimePicker\Forms;

use SilverStripe\ORM\FieldType\DBDate;
use SilverStripe\View\Requirements;
use Trainor\DateTimePicker\Forms\DatePickerField;

class DatePickerField_Readonly extends DatePickerField
{
    protected $readonly = true;

    public function Field($properties = [])
    {
        Requirements::css('trainordigital/datetimepicker: client/css/datetimepicker.css');

        if ($this->Value()) {
            $val = DBDate::create_field('Date', $this->Value())->Nice();
        } else {
            $val = '<i>(not set)</i>';
        }

        return "<span class=\"readonly\" id=\"" . $this->ID() . "\">$val</span>";
    }
}
