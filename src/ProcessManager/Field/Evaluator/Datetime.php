<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

namespace Sugarcrm\Sugarcrm\ProcessManager\Field\Evaluator;

/**
 * Datetime evaluator object. Handles evaluation for date, time, datetime and
 * datetimecombo fields
 * @package ProcessManager
 */
class Datetime extends Base implements EvaluatorInterface
{
    /**
     * Maps methods to field type since this object is reused across all date and
     * time fields
     * @var array
     */
    protected $dateMethodMap = [
        'date' => 'fromIsoDate',
        'time' => 'fromIsoTime',
        'datetimecombo' => 'fromIso',
        'datetime' => 'fromIso',
    ];

    /**
     * Maps TimeDate object methods to field type
     * @var array
     */
    protected $compareMethodMap = [
        'date' => 'asDbDate',
        'time' => 'asDbTime',
        'datetimecombo' => 'asDb',
        'datetime' => 'asDb',
    ];

    /**
     * TimeDate object
     * @var TimeDate
     */
    protected $timedate;

    /**
     * Default field type
     * @var string
     */
    protected $fieldType = 'datetime';

    /**
     * @inheritDoc
     */
    public function init(\SugarBean $bean, $name, array $data)
    {
        parent::init($bean, $name, $data);

        // For date time fields we need to know this
        if (isset($bean->field_defs[$name]['type'])) {
            $this->fieldType = $bean->field_defs[$name]['type'];
        }
    }

    /**
     * @inheritDoc
     */
    public function hasChanged()
    {
        // Only do the check if we have what is needed
        if ($this->isCheckable()) {
            // Get the TimeDate object
            $timedate = $this->getTimedateObject();

            // Gets the method needed for this field type.
            // datetimecombo - ISO-8601 format: 2016-03-24T14:00:00-07:00
            // date - ISO-8601 format: 2012-10-11
            // time - ISO-8601 format: 13:01:45-0700
            $method = $this->getDateMethod();

            // Get the left side check, or what was supplied. Supplied value will
            // be in ISO-8601 format: 2016-03-24T14:00:00-07:00. Result of this
            // call will be in DB format:
            // datetime - 2016-03-24 14:27
            // date - 2016-03-24
            // time - 14:27:00 adjusted for offsets
            $input = $timedate->$method($this->data[$this->name]);
            $input = $this->getComparisonValue($input);

            // Get the right side check, or what was from the bean. Values from
            // beans will be in the user's format:  2016//03/24 14:27. There are
            // cases where this returns null. In that case, just fall back to the
            // bean value.
            $original = $timedate->fromUser($this->bean->{$this->name}, $this->getCurrentUser());
            if ($original) {
                $original = $this->getComparisonValue($original);
            } else {
                $original = $this->bean->{$this->name};
            }

            // Send back the comparison result
            return $input != $original;
        }

        return false;
    }

    /**
     * Gets a TimeDate object
     * @return TimeDate
     */
    protected function getTimedateObject()
    {
        if (empty($this->timedate)) {
            $this->timedate = \TimeDate::getInstance();
        }

        return $this->timedate;
    }

    /**
     * Takes a SugarDateTime object and pushes it through TimeDate to get a
     * string value for comparison
     * @param  \SugarDateTime $datetime SugarDateTime object
     * @return string
     */
    protected function getComparisonValue(\SugarDateTime $datetime)
    {
        $method = $this->getTimedateMethod();
        return $this->getTimedateObject()->$method($datetime);
    }

    /**
     * Gets a date method method for this field type
     * @return string
     */
    protected function getDateMethod()
    {
        if (isset($this->dateMethodMap[$this->fieldType])) {
            return $this->dateMethodMap[$this->fieldType];
        }

        return $this->dateMethodMap['datetime'];
    }

    /**
     * Gets a method to be called on a TimeDate object for a given field type
     * @return string
     */
    protected function getTimedateMethod()
    {
        if (isset($this->compareMethodMap[$this->fieldType])) {
            return $this->compareMethodMap[$this->fieldType];
        }

        return $this->compareMethodMap['datetime'];
    }
}
