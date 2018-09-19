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

class SugarUpgradeFixCustomMultienumFields extends UpgradeScript
{
    public $order = 7100;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        $customFieldFiles = $this->getCustomFieldFiles();
        foreach ($customFieldFiles as $file) {
            if (is_dir($file)) {
                continue;
            }
            $dictionary = array();
            require $file;

            if (empty($dictionary)) {
                continue;
            }
            $module = key($dictionary);

            if (empty($dictionary[$module]['fields'])) {
                continue;
            }
            $field = key($dictionary[$module]['fields']);

            if (empty($dictionary[$module]['fields'][$field]['type']) ||
                $dictionary[$module]['fields'][$field]['type'] != 'multienum' ||
                isset($dictionary[$module]['fields'][$field]['isMultiSelect'])
            ) {
                continue;
            }

            $this->log("Added isMultiSelect for the file: {$file}");

            $dictionary[$module]['fields'][$field]['isMultiSelect'] = true;
            $strToFile = "<?php\n\n";
            foreach ($dictionary[$module]['fields'][$field] as $key => $value) {
                $strToFile .= "\$dictionary['{$module}']['fields']['{$field}']['{$key}'] = " . var_export(
                        $value,
                        true
                    ) . ";\n";
            }
            $this->upgrader->backupFile($file);
            sugar_file_put_contents_atomic($file, $strToFile);
        }
    }

    /**
     * Return custom field paths.
     *
     * @return array
     */
    protected function getCustomFieldFiles()
    {
        return glob('custom/Extension/modules/*/Ext/Vardefs/*');
    }
}
