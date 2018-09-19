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
 



class BugsQuickCreate extends QuickCreate {
    
    var $javascript;
    
    function process() {
        global $current_user, $timedate, $app_list_strings, $current_language, $mod_strings;
        $mod_strings = return_module_language($current_language, 'Bugs');
        
        parent::process();
  
        $this->ss->assign("PRIORITY_OPTIONS", get_select_options_with_id($app_list_strings['bug_priority_dom'], $app_list_strings['bug_priority_default_key']));
        $this->ss->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['bug_status_dom'], $app_list_strings['bug_status_default_key']));
        $this->ss->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['bug_type_dom'],$app_list_strings['bug_type_default_key']));

        if($this->viaAJAX) { // override for ajax call
            $this->ss->assign('saveOnclick', "onclick='if(check_form(\"bugsQuickCreate\")) return SUGAR.subpanelUtils.inlineSave(this.form.id, \"bugs\"); else return false;'");
            $this->ss->assign('cancelOnclick', "onclick='return SUGAR.subpanelUtils.cancelCreate(\"subpanel_bugs\")';");
        }
        
        $this->ss->assign('viaAJAX', $this->viaAJAX);

        $this->javascript = new javascript();
        $this->javascript->setFormName('bugsQuickCreate');
        
        $focus = BeanFactory::newBean('Bugs');
        $this->javascript->setSugarBean($focus);
        $this->javascript->addAllFields('');

        $this->ss->assign('additionalScripts', $this->javascript->getScript(false));
    }   
}
?>