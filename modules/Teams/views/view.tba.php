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

class TeamsViewTBA extends SugarView
{
    /**
     * @see SugarView::_getModuleTitleParams()
     */
    protected function _getModuleTitleParams($browserTitle = false)
    {
        global $mod_strings;

        return array(
            "<a href='index.php?module=Administration&action=index'>".$mod_strings['LBL_MODULE_NAME']."</a>",
            $mod_strings['LBL_TBA_CONFIGURATION']
        );
    }

    /**
     * @see SugarView::preDisplay()
     */
    public function preDisplay()
    {
        if (!$GLOBALS['current_user']->isAdminForModule('Users')) {
            ACLController::displayNoAccess(true);
            sugar_cleanup(true);
        }

        parent::preDisplay();
    }

    /**
     * @see SugarView::display()
     */
    public function display()
    {
        $sugar_smarty = new Sugar_Smarty();
        $sugar_smarty->assign('APP', $GLOBALS['app_strings']);
        $sugar_smarty->assign('MOD', $GLOBALS['mod_strings']);
        $sugar_smarty->assign('APP_LIST', $GLOBALS['app_list_strings']);
        $sugar_smarty->assign('actionsList', $this->_getUserActionsList());
        $sugar_smarty->assign('moduleTitle', $this->getModuleTitle(false));
        $sugar_smarty->assign('isUserAdmin', $GLOBALS['current_user']->isAdmin());

        $tbaConfigurator = new TeamBasedACLConfigurator();
        $sugar_smarty->assign('config', $tbaConfigurator->getConfig());

        echo $sugar_smarty->fetch(SugarAutoLoader::existingCustomOne('modules/Teams/tpls/TBAConfiguration.tpl'));
    }

    /**
     * Get sorted modules list which are implement TBA and which are not hidden.
     */
    private function _getUserActionsList()
    {
        $tbaConfigurator = new TeamBasedACLConfigurator();
        $modules = $tbaConfigurator->getListOfPublicTBAModules();
        // sort modules by module label
        $modulesTitles = array();
        foreach ($modules as $name) {
            $beanList = array_keys($GLOBALS['beanList']);

            // Prevent empty tabs if module is disabled
            if (in_array($name, $beanList)) {
                $modulesTitles[$name] = $GLOBALS['app_list_strings']['moduleList'][$name];
            }
        }
        asort($modulesTitles);

        return array_keys($modulesTitles);
    }
}
