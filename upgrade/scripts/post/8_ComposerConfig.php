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
 *
 * Handle Composer configuration in post step. If the pre step validated
 * to proceed with a custom Composer configuration, we need to put it back
 * in place during the post step overwriting the stock composer files.
 *
 */
class SugarUpgradeComposerConfig extends UpgradeScript
{
    /**
     * {@inheritDoc}
     */
    public $order = 8000;

    /**
     * {@inheritDoc}
     */
    public $version = '7.6.0';

    /**
     * {@inheritDoc}
     * Does not run on db-only updates
     */
    public $type = self::UPGRADE_CORE;

    /**
     * {@inheritDoc}
     */
    public function run()
    {
        if (empty($this->state['composer_custom'])) {
            $this->log("Nothing to do, sticking with the shipped composer files");
        } else {
            foreach ($this->state['composer_custom'] as $file) {
                if (!$this->restoreFile($file)) {
                    return $this->error("Cannot restore composer file '$file'", true);
                }
            }
        }
    }

    /**
     * Restore validate composer file over stock one
     * @param string $file
     * @return boolean
     */
    protected function restoreFile($file)
    {
        $validFile = $file . '.valid';
        if (file_exists($validFile)) {
            $this->upgrader->state['files_to_delete'][] = $validFile;
            $this->log("Restoring custom composer file '$validFile' to '$file'");
            return copy($validFile, $file);
        }
        return false;
    }
}
