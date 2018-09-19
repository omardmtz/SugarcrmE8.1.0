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
 * Moves files containing custom labels for relationships to new location
 *
 * During upgrade from 7.1.x to 7.2.0, some custom labels are missing
 * because the location of relationship labels was changed. We move the
 * files to new the location, so that they are now visible when languages
 * are rebuilt
 *
 * @ticket BR-1645
 * @link https://github.com/sugarcrm/Mango/commit/914db19
 */
class SugarUpgradeFixCustomRelationshipLabels extends UpgradeScript
{
    public $order = 2040;
    public $type = self::UPGRADE_CUSTOM;
    public $silent = false;

    public function run()
    {
        global $moduleList;

        if (version_compare($this->from_version, '7.5.0', '>=')) {
            return;
        }

        $config = SugarConfig::getInstance();
        $languages = array_keys($config->get('languages'));
        $mi = new ModuleInstaller();
        $mi->silent = $this->silent;

        foreach ($languages as $language) {
            $files = $labels = array();

            // read all labels of the given language before rebuilding language labels,
            // otherwise rebuild of the 1st module's labels will nuke the labels of the subsequent modules
            foreach ($moduleList as $module) {
                $file = 'custom/modules/' . $module . '/Ext/Language/' . $language . '.lang.ext.php';
                if (file_exists($file)) {
                    $this->upgrader->log(
                        sprintf('FixCustomLabels: found "%s" labels for "%s" module', $language, $module)
                    );
                    $files[] = $file;
                    $labels[$module] = $this->getLabels($file);
                }
            }

            foreach ($labels as $module => $moduleLabels) {
                $this->upgrader->log(
                    sprintf('FixCustomLabels: merging "%s" labels for "%s" module', $language, $module)
                );
                ParserLabel::addLabels($language, $moduleLabels, $module);
            }

            foreach ($files as $file) {
                $this->upgrader->log(
                    sprintf('FixCustomLabels: removing "%s"', $file)
                );
                unlink($file);
            }
        }
    }

    /**
     * Returns variables defined in file
     *
     * @param string $file
     * @return array
     */
    protected function getLabels($file)
    {
        $mod_strings = array();
        require $file;

        return $mod_strings;
    }
}
