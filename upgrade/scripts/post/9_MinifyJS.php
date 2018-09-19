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
 * Rebuild minified JS files
 */
class SugarUpgradeMinifyJS extends UpgradeScript
{
    public $order = 9200;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        $_REQUEST['root_directory'] = $this->context['source_dir'];
        $_REQUEST['js_rebuild_concat'] = 'rebuild';

        // As part of the upgrade process, we always have to rebuild the cache
        // see TY-826 for details
        $_REQUEST['force_rebuild'] = true;

        // Add some reasonable logging for identification later
        $this->log("MINIFY UPGRADER: About to require minify.php");
        // Changed require_once to require, to ensure this actually gets included
        require 'jssource/minify.php';
        $this->log("MINIFY UPGRADER: Minification should have taken place and new javascript minified files should be in place");
    }
}
