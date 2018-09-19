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
 * Backup all files that are going to be overwritten to
 *  upload/upgrades/backup/UPGRADE_NAME-restore
 */
class SugarUpgradeBackupFiles extends UpgradeScript
{
    public $order = 100;
    public $type = self::UPGRADE_CORE;

    public function run()
    {
        if(empty($this->manifest['copy_files']['from_dir'])) {
            return;
        }
        if(isset($this->context['backup']) && !$this->context['backup']) {
            // backup disabled by option
            $this->log("**** Backup disabled by config");
            return;
        }
        $zip_from_dir = $this->context['extract_dir']."/".$this->manifest['copy_files']['from_dir'];

        $files = $this->findFiles($zip_from_dir);
        $this->log("**** Backup started");
        foreach($files as $file) {
            if(!$this->backupFile($file)) {
                $this->log("FAILED to back up $file");
            }
        }

        $this->log("**** Backup complete");
    }
}
