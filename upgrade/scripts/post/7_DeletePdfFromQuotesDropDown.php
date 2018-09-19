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
 * Class DeletePdfFromQuotesDropDown
 *
 * Remove "Print as PDF" in Quotes detailviewdefs from 6.5.17 to 7.5.
 *
 * CRYS-441 - https://sugarcrm.atlassian.net/browse/CRYS-441
 */
class SugarUpgradeDeletePdfFromQuotesDropDown extends UpgradeScript
{
    public $order = 7401;
    public $type = self::UPGRADE_CUSTOM;
    public $version = '7.5';

    public function run()
    {
        if (version_compare($this->from_version, '7.0', '>=')) {
            return;
        }

        $files = $this->getFilesToProcess();
        foreach ($files as $file) {
            if (!file_exists($file)) {
                return;
            }
            $needUpdate = false;
            require $file;
            if (isset($viewdefs['Quotes']['DetailView']['templateMeta']['form']['buttons'])) {
                $source = $viewdefs['Quotes']['DetailView']['templateMeta']['form']['buttons'];
                foreach ($source as $i => $item) {
                    if (isset($item['customCode']) &&
                        strpos($item['customCode'], 'LBL_VIEW_PDF_BUTTON_LABEL') !== false
                    ) {
                        unset($source[$i]);
                        $needUpdate = true;
                    }
                }
                if ($needUpdate) {
                    if ($source) {
                        $viewdefs['Quotes']['DetailView']['templateMeta']['form']['buttons'] = array_values($source);
                    } else {
                        unset($viewdefs['Quotes']['DetailView']['templateMeta']['form']['buttons']);
                    }
                }
            }
            if ($needUpdate) {
                $this->backupFile($file);
                $this->log("Removed Print as PDF link in $file");
                write_array_to_file("viewdefs", $viewdefs, $file);
            }
        }
    }

    protected function getFilesToProcess()
    {
        return array(
            'custom/modules/Quotes/metadata/detailviewdefs.php',
        );
    }
}
