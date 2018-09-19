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
 * Add modules to tabs for CE->PRO
 * TODO: irrelevant for 7?
 */
class SugarUpgradeAddModulesToCE extends UpgradeScript
{
    public $order = 4000;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if(!($this->from_flavor == 'ce' && $this->toFlavor('pro'))) return;

        //check to see if there are any new files that need to be added to systems tab
        //retrieve old modules list
        $this->log('check to see if new modules exist');
        if(empty($this->state['old_modules'])) {
            $this->log('No old modules info, skipping it');
            return;
        } else {
            $oldModuleList = $this->state['old_modules'];
        }

        $newModuleList = array();
        include('include/modules.php');
        $newModuleList = $moduleList;

        //include tab controller
        $newTB = new TabController();

        //make sure new modules list has a key we can reference directly
        $newModuleList = $newTB->get_key_array($newModuleList);
        $oldModuleList = $newTB->get_key_array($oldModuleList);

        //iterate through list and remove commonalities to get new modules
        foreach ($newModuleList as $remove_mod){
            if(in_array($remove_mod, $oldModuleList)){
                unset($newModuleList[$remove_mod]);
            }
        }

        $must_have_modules= array(
                'Activities'=>'Activities',
                'Calendar'=>'Calendar',
                'Reports' => 'Reports',
                'Quotes' => 'Quotes',
                'Products' => 'Products',
                'Forecasts' => 'Forecasts',
                'Contracts' => 'Contracts',
        );
        $newModuleList = array_merge($newModuleList,$must_have_modules);

        //new modules list now has left over modules which are new to this install, so lets add them to the system tabs
        $this->log('new modules to add are '.var_export($newModuleList,true));

        //grab the existing system tabs
        $tabs = $newTB->get_system_tabs();

        //add the new tabs to the array
        foreach($newModuleList as $nm ){
            $tabs[$nm] = $nm;
        }

        //now assign the modules to system tabs
        $newTB->set_system_tabs($tabs);
        $this->log('module tabs updated');

    }
}
