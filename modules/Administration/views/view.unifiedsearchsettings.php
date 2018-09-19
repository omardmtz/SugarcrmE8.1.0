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

use Sugarcrm\Sugarcrm\SearchEngine\SearchEngine;


/**
 *
 * Globalsearch settings page
 *
 */
class AdministrationViewUnifiedSearchSettings extends SugarView
{
    /**
     * @see SugarView::_getModuleTitleParams()
     */
    protected function _getModuleTitleParams($browserTitle = false)
    {
        global $mod_strings;

        return array(
            "<a href='index.php?module=Administration&action=index'>".translate('LBL_MODULE_NAME', 'Administration')."</a>",
            $mod_strings['LBL_GLOBAL_SEARCH_SETTINGS']
        );
    }

    /**
     * @see SugarView::_getModuleTab()
     */
    protected function _getModuleTab()
    {
        return 'Administration';
    }

    /**
     * @see SugarView::display()
     */
    public function display()
    {
        global $mod_strings, $app_strings, $app_list_strings, $current_user;
        $sugarConfig = SugarConfig::getInstance();

        // Setup smarty template
        $sugar_smarty = new Sugar_Smarty();
        $sugar_smarty->assign('APP', $app_strings);
        $sugar_smarty->assign('MOD', $mod_strings);

        // Enabled/disabled modules list
        $usa = new UnifiedSearchAdvanced();
        $modules = $usa->retrieveEnabledAndDisabledModules();
        $sugar_smarty->assign('enabled_modules', json_encode($modules['enabled']));
        $sugar_smarty->assign('disabled_modules', json_encode($modules['disabled']));

        echo $sugar_smarty->fetch(SugarAutoLoader::existingCustomOne('modules/Administration/templates/UnifiedSearchSettings.tpl'));
    }

    /**
     * Check if engine is available
     * @return boolean
     */
    protected function isAvailable()
    {
        try {
            return SearchEngine::getInstance()->isAvailable(true);
        } catch (Exception $e) {
            return false;
        }
    }
}
