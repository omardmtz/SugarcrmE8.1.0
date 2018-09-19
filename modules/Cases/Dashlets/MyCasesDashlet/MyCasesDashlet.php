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

// $Id: MyCasesDashlet.php 51865 2009-10-27 00:40:13Z clee $



class MyCasesDashlet extends DashletGeneric { 
    public function __construct($id, $def = null)
    {
        global $current_user, $app_strings;
		require('modules/Cases/Dashlets/MyCasesDashlet/MyCasesDashlet.data.php');
		
        parent::__construct($id, $def);
        
        if(empty($def['title'])) $this->title = translate('LBL_LIST_MY_CASES', 'Cases');
        $this->searchFields = $dashletData['MyCasesDashlet']['searchFields'];
        $this->columns = $dashletData['MyCasesDashlet']['columns'];
        $this->seedBean = BeanFactory::newBean('Cases');        
    }
}
