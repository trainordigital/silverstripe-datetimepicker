<?php

namespace Trainor\DateTimePicker\Forms;

use SilverStripe\Core\Convert;
use SilverStripe\Forms\DatetimeField;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\View\Requirements;
use Trainor\DateTimePicker\Forms\DatePickerField;
use Trainor\DateTimePicker\Forms\DateTimePickerField_Readonly;
use Trainor\DateTimePicker\Forms\TimePickerField;

class DateTimePickerField extends DatetimeField
{
    protected $datePlaceholder = 'Date';
    protected $timePlaceholder = 'Time';

    public function Field($properties = [])
    {
        $this->addExtraClass('datetimepicker-datetime-field');

        Requirements::css('trainordigital/datetimepicker: client/thirdparty/jquery.timepicker.min.css');
        Requirements::css('trainordigital/datetimepicker: client/thirdparty/pikaday.css');
        Requirements::css('trainordigital/datetimepicker: client/css/datetimepicker.css');
        Requirements::javascript('trainordigital/datetimepicker: client/thirdparty/moment.min.js');
        Requirements::javascript('trainordigital/datetimepicker: client/thirdparty/jquery.timepicker.min.js');
        Requirements::javascript('trainordigital/datetimepicker: client/thirdparty/pikaday-modified.js');
        Requirements::javascript('trainordigital/datetimepicker: client/javascript/datetimepicker.js');

        return parent::Field($properties);
    }

    public function setPlaceholder($datePlaceholder = null, $timePlaceholder = null)
    {
        if (!is_null($datePlaceholder)) {
            $this->datePlaceholder = $datePlaceholder;
        }

        if (!is_null($timePlaceholder)) {
            $this->timePlaceholder = $timePlaceholder;
        }

        return $this;
    }

    public function getAttributes()
    {
        $attributes = parent::getAttributes();

        $attributes['type'] = 'hidden';
        $attributes['class'] = 'datetimepicker-datetime-value';

        return $attributes;
    }

    public function getDatePickerAttributesHTML()
    {
        $attributes = parent::getAttributes();

        $exclude = ['type', 'name', 'id', 'value', 'lang'];
        $toDataAttrs = ['min', 'max'];

        $attributes = array_filter($attributes, function ($v) {
            return ($v || $v === 0 || $v === '0');
        });

        $attributes['class'] .= ' date';
        $attributes['placeholder'] = $this->datePlaceholder;

        $parts = [];

        foreach ($attributes as $name => $value) {
            if (!in_array($name, $exclude)) {
                if ($value === true) {
                    $parts[] = sprintf('%s="%s"', $name, $name);
                } else {
                    if (in_array($name, $toDataAttrs)) {
                        $parts[] = sprintf('data-%s="%s"', $name, Convert::raw2att($value));
                    }
                    else {
                        $parts[] = sprintf('%s="%s"', $name, Convert::raw2att($value));
                    }
                }
            }
        }

        return DBField::create_field('HTMLFragment', join(' ', $parts));
    }

    public function getTimePickerAttributesHTML()
    {
        $attributes = parent::getAttributes();

        $exclude = ['type', 'name', 'id', 'value', 'lang'];
        $toDataAttrs = ['min', 'max'];

        $attributes = array_filter($attributes, function ($v) {
            return ($v || $v === 0 || $v === '0');
        });

        $attributes['class'] .= ' time';
        $attributes['placeholder'] = $this->timePlaceholder;

        $parts = [];

        foreach ($attributes as $name => $value) {
            if (!in_array($name, $exclude)) {
                if ($value === true) {
                    $parts[] = sprintf('%s="%s"', $name, $name);
                } else {
                    if (in_array($name, $toDataAttrs)) {
                        $parts[] = sprintf('data-%s="%s"', $name, Convert::raw2att($value));
                    }
                    else {
                        $parts[] = sprintf('%s="%s"', $name, Convert::raw2att($value));
                    }
                }
            }
        }

        return DBField::create_field('HTMLFragment', join(' ', $parts));
    }

    public function performReadonlyTransformation()
    {
        $field = $this->castedCopy(DateTimePickerField_Readonly::class);
        $field->setValue($this->dataValue());
        $field->setReadonly(true);

        return $field;
    }
}
