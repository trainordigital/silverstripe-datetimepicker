<?php

namespace Trainor\DateTimePicker\Forms;

use SilverStripe\Core\Convert;
use SilverStripe\Forms\TimeField;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\View\Requirements;
use Trainor\DateTimePicker\Forms\TimePickerField_Readonly;

class TimePickerField extends TimeField
{
    protected $placeholder = 'Time';

    public function Field($properties = [])
    {
        $this->addExtraClass('datetimepicker-time-field');
        $this->setAttribute('placeholder', $this->placeholder);

        Requirements::css('trainordigital/datetimepicker: client/thirdparty/jquery.timepicker.min.css');
        Requirements::css('trainordigital/datetimepicker: client/css/datetimepicker.css');
        Requirements::javascript('trainordigital/datetimepicker: client/thirdparty/moment.min.js');
        Requirements::javascript('trainordigital/datetimepicker: client/thirdparty/jquery.timepicker.min.js');
        Requirements::javascript('trainordigital/datetimepicker: client/javascript/datetimepicker.js');

        return parent::Field($properties);
    }

    public function setPlaceholder($placeholder = null)
    {
        if (!is_null($placeholder)) {
            $this->placeholder = $placeholder;
        }

        return $this;
    }

    public function Value()
    {
        return $this->value;
    }

    public function getAttributes()
    {
        $attributes = parent::getAttributes();

        $attributes['type'] = 'hidden';
        $attributes['class'] = 'datetimepicker-time-value';

        return $attributes;
    }

    public function getPickerAttributesHTML()
    {
        $attributes = parent::getAttributes();

        $exclude = ['type', 'name', 'id', 'value', 'lang'];
        $toDataAttrs = ['min', 'max'];

        $attributes = array_filter($attributes, function ($v) {
            return ($v || $v === 0 || $v === '0');
        });

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
        $field = $this->castedCopy(TimePickerField_Readonly::class);
        $field->setValue($this->dataValue());
        $field->setReadonly(true);

        return $field;
    }
}

