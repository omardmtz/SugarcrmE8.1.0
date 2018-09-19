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
 * Ensure 'full_name' has a type of 'fullname' in list.php defs for all modules
 * that implement Person template.
 *
 * @see MAR-3864 for related issue.
 */
class SugarUpgradeUpdateFullNameOnListViewForPersonClasses extends UpgradeScript
{
    public $order = 7100;
    public $type = self::UPGRADE_CUSTOM;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if (version_compare($this->from_version, '7.7', '>=')) {
            // only need to run this upgrading for versions lower than 7.7
            return;
        }

        foreach ($this->getCustomViewDefs('list') as $file => $viewdefs) {
            if (!empty($viewdefs)) {
                $this->fixFullNameField($viewdefs, $file);
            }
        }
    }

    /**
     * Ensure 'full_name' has a type of 'fullname' in list.php defs
     *
     * @param array $viewdefs
     * @param string $file
     */
    public function fixFullNameField($viewdefs, $file)
    {
        $module = key($viewdefs);

        if ($this->extendsPerson($module) && isset($viewdefs[$module]['base']['view']['list']['panels'])) {
            $data = $viewdefs[$module]['base']['view']['list'];
            foreach ($data['panels'] as $panelKey => $panel) {
                if (isset($panel['fields'])) {
                    foreach ($panel['fields'] as $fieldKey => $field) {
                        if (isset($field['name']) && $field['name'] == 'full_name' && !isset($field['type'])) {
                            $data['panels'][$panelKey]['fields'][$fieldKey]['type'] = 'fullname';
                            $this->upgrader->backupFile($file);
                            $this->saveViewDefsToFile($data, $file, $module, 'list');
                            return;
                        }
                    }
                }
            }
        }
    }

    /**
     * Get array of view defs.
     * @param string $view
     * @param string $platform
     * @return array in format ($fileName => $viewdefs).
     */
    public function getCustomViewDefs($view, $platform = 'base')
    {
        $definitions = array();
        $files = glob("custom/modules/*/clients/$platform/views/$view/$view.php");
        foreach ($files as $file) {
            $viewdefs = array();
            require $file;
            $definitions[$file] = $viewdefs;
        }
        return $definitions;
    }

    /**
     * Saves viewdefs to file.
     * @param array $defs
     * @param string $module
     * @param string $file
     * @param string $view
     * @param string $platform
     */
    public function saveViewDefsToFile($defs, $file, $module, $view, $platform = 'base')
    {
        write_array_to_file("viewdefs['$module']['$platform']['view']['$view']", $defs, $file);
    }

    /**
     * Checks if a given module extends Person template.
     * @param $module
     * @return bool
     */
    public function extendsPerson($module)
    {
        $class = BeanFactory::newBean($module);
        return is_subclass_of($class, 'Person');
    }
}
