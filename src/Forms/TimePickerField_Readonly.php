<?php

namespace Trainor\DateTimePicker\Forms;

use SilverStripe\ORM\FieldType\DBTime;
use SilverStripe\View\Requirements;
use Trainor\DateTimePicker\Forms\TimePickerField;

class TimePickerField_Readonly extends TimePickerField
{
    protected $readonly = true;

    public function Field($properties = [])
    {
        Requirements::css('trainordigital/datetimepicker: client/css/datetimepicker.css');

        if ($this->Value()) {
            $val = DBTime::create_field('Time', $this->Value())->Format('h:mm a');
        } else {
            $val = '<i>(not set)</i>';
        }

        return "<span class=\"readonly\" id=\"" . $this->ID() . "\">$val</span>";
    }
}

