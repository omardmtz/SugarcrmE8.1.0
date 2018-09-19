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

/**
 * Helper component for formatting dates on BWC views
 */
class ViewDateFormatter
{
    /**
     * Formats a value of a given type from DB format to the user-preferred one
     *
     * @param string $type Field type
     * @param mixed $value DB-formatted value
     * @return string
     */
    public static function format(string $type, $value)
    {
        $timeDate = TimeDate::getInstance();

        switch ($type) {
            case 'date':
                if ($timeDate->check_matching_format($value, TimeDate::DB_DATE_FORMAT)) {
                    $dateTime = $timeDate->fromDbDate($value);
                    $value = $timeDate->asUserDate($dateTime);
                }

                break;

            case 'datetime':
            case 'datetimecombo':
                if ($timeDate->check_matching_format($value, TimeDate::DB_DATETIME_FORMAT)) {
                    $dateTime = $timeDate->fromDb($value);
                    $value = $timeDate->asUser($dateTime);
                }

                break;
        }

        return $value;
    }
}
