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
 * Post upgrade script which moves unsupported views to Disabled directory
 * It covers cases hasCustomViews and hasCustomViewsModDir from HealthCheck
 */
class SugarUpgradeDisableViews extends UpgradeScript
{
    public $order = 7120;
    public $type = self::UPGRADE_ALL;

    /**
     * Method which moves files to Disabled directory
     */
    public function run()
    {
        if (empty($this->state['healthcheck'])) {
            return;
        }

        $files = array();

        foreach ($this->state['healthcheck'] as $meta) {
            if (empty($meta['report'])) {
                continue;
            }
            if ($meta['report'] != 'hasCustomViews' && $meta['report'] != 'hasCustomViewsModDir') {
                continue;
            }

            foreach ($meta['params'][1] as $file) {
                $files[$file] = dirname($file) . '/Disabled/' . basename($file);
            }
        }

        if ($files) {
            $this->renameDisabled($files);
        }


    }

    public function renameDisabled($files)
    {
        foreach ($files as $fileFrom => $fileTo) {
            sugar_mkdir(dirname($fileTo), null, true);
            sugar_rename($fileFrom, $fileTo);
            $this->log('Custom view ' . $fileFrom . ' should be disabled (moved to ' . $fileTo . ')');
        }
    }
}
