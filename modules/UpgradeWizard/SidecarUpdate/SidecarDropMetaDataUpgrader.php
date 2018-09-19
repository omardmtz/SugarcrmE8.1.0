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
// This will need to be pathed properly when packaged
require_once 'modules/UpgradeWizard/SidecarUpdate/SidecarAbstractMetaDataUpgrader.php';

class SidecarDropMetaDataUpgrader extends SidecarAbstractMetaDataUpgrader
{
    public function convertLegacyViewDefsToSidecar()
    {
    }

    public function upgrade()
    {
        // does nothing, the upgrade driver then will just delete the old file
        return true;
    }
}
