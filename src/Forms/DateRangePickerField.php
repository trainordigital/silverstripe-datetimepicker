<?php

namespace Trainor\DateTimePicker\Forms;

use SilverStripe\Core\Convert;
use SilverStripe\Forms\CompositeField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\View\Requirements;
use Trainor\DateTimePicker\Forms\DatePickerField;
use InvalidArgumentException;

class DateRangePickerField extends CompositeField
{
    public function __construct($name, $titleOrField = null, $fields = null)
    {
        if (!is_string($name)) {
            throw new InvalidArgumentException('Invalid string parameter for $name');
        }

        // Get following arguments
        $fields = func_get_args();
        array_shift($fields);

        // Detect title from second argument, if it is a string
        if ($titleOrField && is_string($titleOrField)) {
            $title = $titleOrField;
            array_shift($fields);
        } else {
            $title = static::name_to_label($name);
        }

        // Remaining arguments are child fields
        if (count($fields) != 2) {
            throw new InvalidArgumentException('Invalid number of fields provided - must be 2');
        }

        $childFields = FieldList::create(
            DatePickerField::create($fields[0], '')->addExtraClass('date start')->setPlaceholder('Start Date'),
            LiteralField::create(null, '<span class="daterangepicker-separator">to</span>'),
            DatePickerField::create($fields[1], '')->addExtraClass('date end')->setPlaceholder('End Date')
        );

        parent::__construct($childFields);

        // Assign name and title (not assigned by parent constructor)
        $this->setName($name);
        $this->setTitle($title);
        $this->setID(Convert::raw2htmlid($name));
    }

    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }
}
