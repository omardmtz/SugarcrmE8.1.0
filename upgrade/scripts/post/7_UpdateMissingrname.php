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
 * Custom relationship field in listview is blank after upgrade
 *
 * @see PAT-2071 for related issue
 */
class SugarUpgradeUpdateMissingrname extends UpgradeScript
{
    public $order = 7099;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        if (version_compare($this->from_version, '7.6.1.0', '>=')) {
            // only need to run this upgrading for versions lower than 7.6.1.0
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
            if (!isset($fieldDef['rname']) && // and rname is not set
                $fieldDef['type'] == 'relate') { // and its type is relate

                $dictionary[$module]['fields'][$field]['rname'] = 'name'; // set 'rname' to default 'name'
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
