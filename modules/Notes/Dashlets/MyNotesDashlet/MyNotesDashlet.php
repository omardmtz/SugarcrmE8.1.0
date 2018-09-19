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



class MyNotesDashlet extends DashletGeneric { 
    public function __construct($id, $def = null)
    {
        global $current_user, $app_strings, $dashletData;
		require('modules/Notes/Dashlets/MyNotesDashlet/MyNotesDashlet.data.php');
        
        parent::__construct($id, $def);

        if(empty($def['title'])) $this->title = translate('LBL_MY_NOTES_DASHLETNAME', 'Notes');
         
        $this->searchFields = $dashletData['MyNotesDashlet']['searchFields'];
        $this->columns = $dashletData['MyNotesDashlet']['columns'];
        
        $this->seedBean = BeanFactory::newBean('Notes');        
    }    
}
