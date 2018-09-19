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
/*********************************************************************************
 * $Id$
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

class <module_name>ViewEdit extends ViewEdit{
 	function display(){
		if (isset($this->bean->id)) {
			$this->ss->assign("FILE_OR_HIDDEN", "hidden");
			if (empty($_REQUEST['isDuplicate']) || $_REQUEST['isDuplicate'] == 'false') {
				$this->ss->assign("DISABLED", "disabled");
			}
		} else {
			$this->ss->assign("FILE_OR_HIDDEN", "file");
		}
		parent::display();
 	}
}
