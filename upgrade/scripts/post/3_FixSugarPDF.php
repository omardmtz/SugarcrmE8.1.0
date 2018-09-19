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
 * Fix sugarPDF configs that could be broken by move to vendor/
 * @see BR-1557
 */
class SugarUpgradeFixSugarPDF extends UpgradeScript
{
    public $order = 3000;
    public $type = self::UPGRADE_CUSTOM;

    protected $config_keys = array("K_PATH_MAIN", "K_PATH_URL", "K_PATH_FONTS");

    public function run()
    {
        // clear fontlist cache file first
        $fontManager = new FontManager();
        $fontManager->clearCachedFile();

        if (!version_compare($this->from_version, '7.2.1', '<')) {
            // only needed for upgrades from pre-7.2.1
            return;
        }

        if(!file_exists("custom/include/Sugarpdf/sugarpdf_default.php")) {
            return;
        }
        require "custom/include/Sugarpdf/sugarpdf_default.php";
        $rewrite = false;
        foreach($this->config_keys as $key) {
            if(empty($sugarpdf_default[$key])) continue;

            if(strncmp($sugarpdf_default[$key], "include/tcpdf/", 14) === 0) {
                $sugarpdf_default[$key] = str_replace("include/tcpdf/", "vendor/tcpdf/", $sugarpdf_default[$key]);
                $rewrite = true;
            }
        }
        if($rewrite) {
            $this->log("Writing fixed custom/include/Sugarpdf/sugarpdf_default.php");
            write_array_to_file("sugarpdf_default", $sugarpdf_default, "custom/include/Sugarpdf/sugarpdf_default.php");
        }
    }
}
