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
 * Searches through the installed relationships to find broken self referencing one-to-many relationships
 * (wrong field used in the subpanel, and the left link not marked as left)
 */
class SugarUpgradeUpgradeCustomRels extends UpgradeScript
{
    public $order = 1200;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        global $modules_exempt_from_availability_check;
        $modules_exempt_from_availability_check = array();
        require_once('modules/Administration/upgrade_custom_relationships.php');
        upgrade_custom_relationships();
    }
}
