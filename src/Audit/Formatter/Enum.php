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

class Enum implements Formatter
{

    public function formatRows(array &$rows)
    {
        array_walk($rows, function (&$row) {
            if (in_array($row['data_type'], ['enum', 'multienum'])) {
                if (!is_null($row['before'])) {
                    $row['before'] = explode(',', str_replace('^', '', $row['before']));
                }
                if (!is_null($row['after'])) {
                    $row['after'] = explode(',', str_replace('^', '', $row['after']));
                }
            }
        });
    }
}
