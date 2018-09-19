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
 * Fix missing link for name fields in extension and custom modules in lists and subpanels
 *
 * Affected files: (custom)?/modules/<module name>/clients/base/views/(subpanel-list/subpanel-list.php|subpanel-for-.* /subpanel-for-.*|list/list.php)
 */
class SugarUpgradeFixNameLink extends UpgradeScript
{
    public $order = 7910;
    public $type = self::UPGRADE_CUSTOM;

    protected $affectedViewTypes = array(
        'list',
        'subpanel-list',
        'subpanel-for-*'
    );

    public function run()
    {
        // Only run this when coming from a version lower than 7.7.0
        if (version_compare($this->from_version, '7.7.0', '>=')) {
            return;
        }

        $files = array();
        $customModules = $this->getCustomModules();
        foreach ($this->affectedViewTypes as $viewType) {
            $files = array_merge(
                $files,
                glob("custom/modules/*/clients/base/views/$viewType/$viewType.php")
            );
            foreach ($customModules as $module) {
                $files = array_merge(
                    $files,
                    glob("modules/$module/clients/base/views/$viewType/$viewType.php")
                );
            }
        }
        foreach ($files as $file) {
            $this->process($file);
        }
    }

    /**
     * Fix layout defs
     *
     * @param string $file Viewdef file to change
     *
     * @return void
     */
    protected function process($file)
    {
        $viewdefs = array();
        include $file;

        $toCopyFile = false;
        if (!empty($viewdefs)) {
            $module = key($viewdefs);
            $viewType = key($viewdefs[$module]['base']['view']);
            $defs = $viewdefs[$module]['base']['view'][$viewType];

            foreach ($defs['panels'][0]['fields'] as $fieldName => $details) {
                if (isset($details['name'])
                    && !isset($details['link'])
                    && (($details['name'] === 'name')
                        || ($details['name'] === 'document_name'))
                ) {
                    $details['link'] = true;
                    $defs['panels'][0]['fields'][$fieldName] = $details;
                    $toCopyFile = true;
                }
            }
        }

        if ($toCopyFile) {
            $strToFile = "<?php\n\n";
            $strToFile .= "/* This file was updated by 7_FixNameLink.php */\n";
            $strToFile .= "\$viewdefs['$module']['base']['view']['$viewType'] = " . var_export($defs, true) . ";\n";

            $this->upgrader->backupFile($file);
            sugar_file_put_contents_atomic($file, $strToFile);
        }
    }

    /**
     * Get SugarCRM instance custom modules
     *
     * @return array
     */
    protected function getCustomModules()
    {
        // Find all the custom classes we want to convert.
        // don't make $beanList as global
        $beanList = array();
        foreach (SugarAutoLoader::existing('include/modules_override.php', SugarAutoLoader::loadExtension("modules")) as $modExtFile) {
            include $modExtFile;
        }

        return array_keys($beanList);
    }
}
