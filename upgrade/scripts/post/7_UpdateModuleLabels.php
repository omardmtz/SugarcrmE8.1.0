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
 * Update customized LBL_MODULE_NAMEs for consistency with 6.x
 */
class SugarUpgradeUpdateModuleLabels extends UpgradeScript
{
    public $order = 7001;
    public $type = self::UPGRADE_CUSTOM;

    /**
     * @see BR-3279
     */
    public function run()
    {
        if (empty($this->upgrader->state['mismatching_labels'])) {
            $this->log('Mismatching module labels were not registered by pre-upgrade script.');
            return;
        }

        foreach ($this->upgrader->state['mismatching_labels'] as $language => $modules) {
            foreach ($modules as $module => $label) {
                $this->log('Updating the label of the ' . $module . ' module to ' . $label . ' in ' . $language);
                ParserLabel::addLabels($language, array(
                    'LBL_MODULE_NAME' => $label,
                ), $module);
            }
        }
    }
}
