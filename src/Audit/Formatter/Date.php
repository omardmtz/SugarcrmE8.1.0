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
namespace Sugarcrm\Sugarcrm\Audit\Formatter;

use Sugarcrm\Sugarcrm\Audit\Formatter;

class Date implements Formatter
{
    private $timedate;

    public function __construct(\TimeDate $timedate = null)
    {
        $this->timedate = $timedate ?: \TimeDate::getInstance();
    }

    public function formatRows(array &$rows)
    {
        array_walk($rows, function (&$row) {
            if (in_array($row['data_type'], ['date', 'datetime'])) {
                $row['before'] = $this->formatDateTime($row['before'], $row['data_type']);
                $row['after'] = $this->formatDateTime($row['after'], $row['data_type']);
            }
        });
    }

    private function formatDateTime($value, $type)
    {
        if ($value) {
            $obj = $this->timedate->fromDbType($value, $type);
            $value = $this->timedate->asIso($obj);
        }

        return $value;
    }
}
