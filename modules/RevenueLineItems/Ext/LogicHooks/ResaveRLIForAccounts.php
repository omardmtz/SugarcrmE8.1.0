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
 * Resave RLI bean after the account_link relationship is removed. This will cause the RLI to pick up
 * the account from it's associated Opportunity through sugarlogic
 */
$hook_array['after_relationship_delete'][] = array(
    1,
    'afterRelationshipDelete',
    'modules/RevenueLineItems/RevenueLineItemHooks.php',
    'RevenueLineItemHooks',
    'afterRelationshipDelete',
);
