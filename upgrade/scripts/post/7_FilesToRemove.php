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
 * Class SugarUpgradeFilesToRemove
 *
 * Deletes the difference between the current files.md5 (upgrade to version) and the previous
 * one (upgrade from version).
 */
class SugarUpgradeFilesToRemove extends UpgradeScript
{
    public $order = 7000;
    public $type = self::UPGRADE_CORE;

    public function run()
    {
        $filesToRemove = "{$this->context['extract_dir']}/filesToRemove.json";

        if (!file_exists($filesToRemove)) {
            $this->log("Skipping script due to missing 'filesToRemove.json'");
            return;
        }

        $contents = file_get_contents($filesToRemove);

        if (empty($contents)) {
            $this->log("Skipping script due to empty contents in 'filesToRemove.json'");
            return;
        }

        $files = json_decode($contents);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($files)) {
            $this->log("Skipping script due to invalid JSON in 'filesToRemove.json'");
            return;
        }

        $this->upgrader->fileToDelete($files, $this);
    }
}
