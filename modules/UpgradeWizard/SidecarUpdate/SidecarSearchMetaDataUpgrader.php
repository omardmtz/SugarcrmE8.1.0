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
require_once 'SidecarAbstractMetaDataUpgrader.php';

class SidecarSearchMetaDataUpgrader extends SidecarAbstractMetaDataUpgrader
{
    /**
     * Should we delete pre-upgrade files?
     * Not deleting searchviews since we may need them for popups in subpanels driven by BWC module.
     * See BR-1044
     * @var bool
     */
    public $deleteOld = false;

    /**
     * Handles the actual upgrading for search metadata. This process is much
     * simpler in that no manipulation of defs is necessary. We simply move the
     * file contents into place in the new structure.
     *
     * @return bool
     */
    public function upgrade() {
        if (file_exists($this->fullpath)) {
            // Save the new file and report it
            return $this->handleSave();
        }

        return false;
    }

    /**
     * Does nothing for search since search is simply a file move.
     */
    public function convertLegacyViewDefsToSidecar() {}

    /**
     * Simply gets the current file contents
     *
     * @return string
     */
    public function getNewFileContents($viewname = null)
    {
        return file_get_contents($this->fullpath);
    }
}