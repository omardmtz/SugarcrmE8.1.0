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
 * After we update the relationship of an opportunity, we need to resave so the worksheet gets updated as well.
 */
$hook_array['after_relationship_add'][] = array(
    10,
    'fixWorksheetAccountAssignment',
    'modules/Opportunities/OpportunityHooks.php',
    'OpportunityHooks',
    'fixWorksheetAccountAssignment',
);
