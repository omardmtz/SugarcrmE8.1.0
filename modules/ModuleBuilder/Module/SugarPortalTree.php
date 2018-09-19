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



class SugarPortalTree extends MBPackageTree{
    public function __construct()
    {
		$this->tree = new Tree('package_tree');
		$this->tree->id = 'package_tree';
		$this->mb = new SugarPortalBrowser();
		$this->populateTree($this->mb->getNodes(), $this->tree);
	}
	
	function getName(){
		return translate('LBL_SECTION_PORTAL');
	}
}
