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
 * Fix rname in relationships, change 'name' to 'full_name'
 *
 * @see PAT-1173 for related issue
 */
class SugarUpgradeUpdateNameForPersonClasses extends UpgradeScript
{
    public $order = 7100;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        if (version_compare($this->from_version, '7.6', '>=')) {
            // only need to run this upgrading for versions lower than 7.6
            return;
        }

        foreach (glob('custom/Extension/modules/*/Ext/Vardefs/*.php') as $file) {
            if (is_dir($file)) {
                continue;
            }

            $dictionary = array();
            require $file;

            if (!empty($dictionary)) {
                $this->fixRelationship($dictionary, $file);
            }
        }
    }

    /**
     * Fix dict defs and attempt to overwrite record file
     * @param array $dictionary
     * @param string $file
     */
    public function fixRelationship($dictionary, $file)
    {
        $module = key($dictionary);

        if (empty($dictionary[$module]['fields'])) {
            return;
        }

        $field = key($dictionary[$module]['fields']) . '_name';

        if (isset($dictionary[$module]['fields'][$field])) { // if the _name field of this relationship is set
            $fieldDef = $dictionary[$module]['fields'][$field];
            if (isset($fieldDef['module']) && // and there is a module set
                is_subclass_of(BeanFactory::newBean($fieldDef['module']), 'Person') && // and it is a subclass of Person
                isset($fieldDef['db_concat_fields']) && // and db_concat_fields are set
                $fieldDef['rname'] == 'name' && // and rname is set to name
                $fieldDef['type'] == 'relate') { // and its type is relate

                $dictionary[$module]['fields'][$field]['rname'] = 'full_name'; // change 'name' to 'full_name'
                $strToFile = "<?php\n\n";

                foreach ($dictionary[$module]['fields'] as $key => $value) {
                    $strToFile .= "\$dictionary[\"{$module}\"][\"fields\"][\"{$key}\"] = " . var_export(
                        $value,
                        true
                    ) . ";\n";
                }

                $this->upgrader->backupFile($file);
                sugar_file_put_contents_atomic($file, $strToFile);
            }
        }
    }
}
