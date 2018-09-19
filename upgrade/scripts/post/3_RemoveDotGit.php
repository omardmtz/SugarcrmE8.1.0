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
 * Schedule .git directories for removal
 */
class SugarUpgradeRemoveDotGit extends UpgradeScript
{
    /*
     * execution order
     */
    public $order = 3010;

    /*
     * upgrade type
     */
    public $type = self::UPGRADE_CORE;

    /**
     * Remove .git directories
     */
    public function run()
    {
        $git_dirs = array(
            'vendor/ruflin/elastica/.git',
            'vendor/onelogin/php-saml/.git',
        );

        foreach ($git_dirs as $git_dir) {
            if (file_exists($git_dir)) {
                $this->log("Removing directory: $git_dir");
                if ($this->removeDir($git_dir)) {
                    $this->log("Finished removing $git_dir");
                }
            }
        }
    }
}
