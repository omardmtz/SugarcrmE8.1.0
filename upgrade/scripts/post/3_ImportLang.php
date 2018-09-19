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
 * add language pack config information to config.php
 */
class SugarUpgradeImportLang extends UpgradeScript
{
    public $order = 3000;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        if(!$this->toFlavor('pro')) return;
        if(!is_file('install/lang.config.php')){
       	    return;
       	}
		$this->log('install/lang.config.php exists, let\'s import the file/array into sugar_config/config.php');
		include('install/lang.config.php');

		foreach($config['languages'] as $k=>$v){
			$this->upgrader->config['languages'][$k] = $v;
		}
    }
}
