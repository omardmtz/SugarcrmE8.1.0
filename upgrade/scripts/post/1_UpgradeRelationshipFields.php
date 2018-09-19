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
 * Upgrade relationship fields from list to map format
 */
class SugarUpgradeUpgradeRelationshipFields extends UpgradeScript
{
    // this script should run before 1_ClearVarDefs in order to make sure all custom relationships are in the proper
    // format before the new relationship cache is built
    public $order = 1090;
    public $type = self::UPGRADE_CUSTOM;

    public function run()
    {
        $files = $this->getFiles();
        foreach ($files as $file) {
            $this->processFile($file);
        }
    }

    protected function getFiles()
    {
        return array_merge(
            // relationships created in Studio
            glob('custom/metadata/*MetaData.php'),
            // relationships defined by installed extensions in Module Builder
            glob('custom/Extension/modules/relationships/relationships/*MetaData.php')
        );
    }

    protected function loadDefinition($name, $file)
    {
        $dictionary = array();
        include $file;
        return $dictionary[$name];
    }

    protected function saveDefinition($name, $definition, $file)
    {
        write_array_to_file('dictionary[\'' . $name . '\']', $definition, $file);
    }

    protected function processFile($file)
    {
        $name = basename($file, 'MetaData.php');
        $definition = $this->loadDefinition($name, $file);
        if (empty($definition['fields'])) {
            $this->log('No field definitions for ' . $name);
            return;
        }

        $converted = array();
        foreach ($definition['fields'] as $key => $value) {
            if (!isset($value['name'])) {
                $this->log('No field name in relationship ' . $name . ' definition for key ' . $key);
                $newKey = $key;
            } else {
                $newKey = $value['name'];
            }
            $converted[$newKey] = $value;
        }

        if (array_keys($definition['fields']) === array_keys($converted)) {
            $this->log('Field definitions for relationship ' . $name . ' are in correct format');
        } else {
            $definition['fields'] = $converted;
            $this->log('Saving converted field definitions for relationship ' . $name);
            $this->saveDefinition($name, $definition, $file);
        }
    }
}
