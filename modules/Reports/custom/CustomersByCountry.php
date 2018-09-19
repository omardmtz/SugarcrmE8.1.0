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
$result = $GLOBALS['db']->query('SELECT accounts.billing_address_country as country, COUNT( accounts.billing_address_country ) as amount
FROM opportunities
RIGHT JOIN accounts_opportunities ao ON ao.opportunity_id = opportunities.id
AND ao.deleted =0
RIGHT JOIN accounts ON accounts.id = ao.account_id
AND accounts.deleted =0
WHERE opportunities.sales_stage =  \'Closed Won\'
AND opportunities.deleted =0 GROUP BY accounts.billing_address_country');
while($row = $GLOBALS['db']->fetchByAssoc($result)){
    $data[] = $row;
}