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


class ConfiguratorViewHistoryContactsEmails extends SugarView
{
    public function preDisplay()
    {
        if (!is_admin($GLOBALS['current_user'])) {
            sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']);
        }
    }

    public function display()
    {
        $modules = array();
        foreach ($GLOBALS['beanList'] as $moduleName => $objectName) {
            $bean = BeanFactory::newBean($moduleName);

            if (!($bean instanceof SugarBean)) {
                continue;
            }
            if (empty($bean->field_defs)) {
                continue;
            }

            // these are the specific modules we care about
            if (!in_array($moduleName, array('Opportunities','Accounts','Cases'))) {
                continue;
            }

            $modules[$moduleName] = array(
                'module' => $moduleName,
                'label' => translate($moduleName),
                'enabled' => true,
                );
        }

        if (!empty($GLOBALS['sugar_config']['hide_history_contacts_emails'])) {
            foreach ($GLOBALS['sugar_config']['hide_history_contacts_emails'] as $moduleName => $flag) {
                $modules[$moduleName]['enabled'] = !$flag;
            }
        }

        $this->ss->assign('modules', $modules);
        $this->ss->display('modules/Configurator/tpls/historyContactsEmails.tpl');
    }
}
