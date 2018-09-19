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

class SugarUpgradeFixRelateFieldsFilterMetadata extends UpgradeScript
{
    public $order = 7100;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        //run only when upgrading from 7.x to 7.2.1
        if (version_compare($this->from_version, '7.0', '<') || version_compare($this->from_version, '7.2.1', '>=')) {
            return;
        }

        $this->cleanUpField('Cases', array('account_name'));
        $this->cleanUpField('Contacts', array('account_name'));
        $this->cleanUpField('Notes', array('contact_name'));
        $this->cleanUpField('Opportunities', array('account_name'));
        $this->cleanUpField('Quotes', array('account_name'));
        $this->cleanUpField(
            'RevenueLineItems',
            array(
                'account_name',
                'opportunity_name',
                'product_template_name',
                'category_name'
            )
        );
        $this->cleanUpField('Tasks', array('contact_name'));
    }

    /**
     * Removes fields' filter definition.
     *
     * More precisely we need to remove the `dbFields`, `type` and `vname`
     * properties from the filter definition of `relate` type fields.
     *
     * @param string $module The module name.
     * @param array $fields The list of fields to fix.
     */
    private function cleanUpField($module, $fields)
    {
        $file = 'custom/modules/' . $module . '/clients/base/filters/default/default.php';
        if (!file_exists($file)) {
            return;
        }

        $viewdefs = null;
        require $file;

        foreach ($fields as $fieldName) {
            if (isset($viewdefs[$module]['base']['filter']['default']['fields'][$fieldName])) {
                $viewdefs[$module]['base']['filter']['default']['fields'][$fieldName] = array();
            }
        }

        sugar_file_put_contents_atomic(
            $file,
            "<?php\n\n"
            . "/* This file was updated by 7_FixRelateFieldsFilterMetadata */\n"
            . "\$viewdefs['{$module}']['base']['filter']['default'] = "
            . var_export($viewdefs[$module]['base']['filter']['default'], true)
            . ";\n"
        );
    }
}
