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
$result = $GLOBALS['db']->query('SELECT o.sales_stage as name, count( o.sales_stage ) as value
FROM opportunities o
WHERE deleted =0
GROUP BY o.sales_stage');
while($row = $GLOBALS['db']->fetchByAssoc($result)){
    $data[] = $row;
}