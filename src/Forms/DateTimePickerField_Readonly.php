<?php

namespace Trainor\DateTimePicker\Forms;

use SilverStripe\ORM\FieldType\DBDate;
use SilverStripe\View\Requirements;
use Trainor\DateTimePicker\Forms\DateTimePickerField;

class DateTimePickerField_Readonly extends DateTimePickerField
{
    protected $readonly = true;

    public function Field($properties = [])
    {
        Requirements::css('trainordigital/datetimepicker: client/css/datetimepicker.css');

        if ($this->Value()) {
            $val = DBDate::create_field('Datetime', $this->Value())->Nice();

            // Dumb hack to remove seconds
            $val = str_replace(':00 ', ' ', $val);
        } else {
            $val = '<i>(not set)</i>';
        }

        return "<span class=\"readonly\" id=\"" . $this->ID() . "\">$val</span>";
    }
}
