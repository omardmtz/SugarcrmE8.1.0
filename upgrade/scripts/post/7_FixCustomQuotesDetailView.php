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
 * Class SugarUpgradeFixCustomQuotesDetailView
 *
 * Remove $LAYOUT_OPTIONS in Quotes detailviewdefs, since it became obsolete from 6.6.x.
 * It could have trapped in custom defs and was not deleted properly earlier.
 */
class SugarUpgradeFixCustomQuotesDetailView extends UpgradeScript
{
    public $order = 7400;
    public $type = self::UPGRADE_CUSTOM;
    public $version = '7.5';

    public function run()
    {
        if (version_compare($this->from_version, '6.6.2', '>=')) {
            return;
        }

        $files = $this->getFilesToProcess();

        foreach ($files as $file) {
            if (!file_exists($file)) {
                return;
            }
            require $file;
            if (isset($viewdefs['Quotes']['DetailView']['templateMeta']['form']['links'])) {
                $data = $viewdefs['Quotes']['DetailView']['templateMeta']['form']['links'];
                if (count($data) == 1 && strpos($data[0], '$LAYOUT_OPTIONS')) {
                    unset($viewdefs['Quotes']['DetailView']['templateMeta']['form']['links']);
                } else {
                    foreach ($data as $i => $value) {
                        if (strpos($value, '$LAYOUT_OPTIONS')) {
                            // array_splice() for deleting and re-indexing.
                            array_splice($viewdefs['Quotes']['DetailView']['templateMeta']['form']['links'], $i, 1);
                        }
                    }
                }
            }

            $this->log("Removed obsolete LAYOUT_OPTIONS in $file");
            write_array_to_file("viewdefs", $viewdefs, $file);
        }
    }

    /**
     * Get list of files that should be processed
     *
     * @return array files
     */
    protected function getFilesToProcess()
    {
        return array(
            'custom/modules/Quotes/metadata/detailviewdefs.php',
        );
    }
}
