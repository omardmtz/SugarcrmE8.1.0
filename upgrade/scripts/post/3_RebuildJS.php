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
 * Rebuild JS language caches
 */
class SugarUpgradeRebuildJS extends UpgradeScript
{
    public $order = 3000;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        if(empty($this->upgrader->config['js_lang_version']))
        	$this->upgrader->config['js_lang_version'] = 1;
        else
        	$this->upgrader->config['js_lang_version'] += 1;

        //remove lanugage cache files
        require_once('include/SugarObjects/LanguageManager.php');
        LanguageManager::clearLanguageCache();
    }
}
