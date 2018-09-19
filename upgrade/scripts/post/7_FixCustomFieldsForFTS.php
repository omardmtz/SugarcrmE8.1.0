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

class SugarUpgradeFixCustomFieldsForFTS extends UpgradeScript
{
    public $order = 7100;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        if (!version_compare($this->from_version, '7.2.2', '<')) {
            // only needed for upgrades from pre-7.2.2
            return;
        }

        foreach (glob('custom/Extension/modules/*/Ext/Vardefs/*', GLOB_BRACE) as $customFieldFile) {
            if (is_dir($customFieldFile)) {
                continue;
            }
            $dictionary = array();
            require $customFieldFile;

            if (!empty($dictionary)) {
                $module = key($dictionary);

                if (!empty($dictionary[$module]['fields'])) {
                    $field = key($dictionary[$module]['fields']);

                    if (!empty($dictionary[$module]['fields'][$field]['full_text_search'])
                        && !empty($dictionary[$module]['fields'][$field]['full_text_search']['boost'])
                        && !isset($dictionary[$module]['fields'][$field]['full_text_search']['enabled'])) {

                        $dictionary[$module]['fields'][$field]['full_text_search']['enabled'] = true;
                        $strToFile = "<?php\n\n"
                                   . "/* This file was updated by 7_FixCustomFieldsForFTS */\n";

                        foreach ($dictionary[$module]['fields'][$field] as $key=>$value) {
                            $strToFile .= "\$dictionary['{$module}']['fields']['{$field}']['{$key}'] = ". var_export($value, true) . ";\n";
                        }

                        sugar_file_put_contents_atomic($customFieldFile, $strToFile);
                    }
                }
            }
        }
    }
}
