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

// This will need to be pathed properly when packaged
require_once 'modules/UpgradeWizard/SidecarUpdate/SidecarAbstractMetaDataUpgrader.php';
require_once 'modules/ModuleBuilder/parsers/views/AbstractMetaDataParser.php';

class SidecarListMetaDataUpgrader extends SidecarAbstractMetaDataUpgrader
{
    /**
     * Should we delete pre-upgrade files?
     * Not deleting listviews since we may need them for popups in subpanels driven by BWC module.
     * See BR-1044
     * @var bool
     */
    public $deleteOld = false;

    /**
     * The actual legacy defs converter. For list it is simply taking the old
     * def array, looping over it, lowercasing the field names, adding that to
     * each iteration and saving that into a 'fields' array inside of the panels
     * array.
     */
    public function convertLegacyViewDefsToSidecar() {
        $this->logUpgradeStatus('Converting ' . $this->client . ' list view defs for ' . $this->module);
        if(empty($this->legacyViewdefs)) {
            $this->logUpgradeStatus('Empty metadata, doing nothing');
            return false;
        }
        // Get the default defs so that data can be merged as needed
        $defaults = $this->loadDefaultMetadata();
        $defaults = $this->setDefaultsByKey($defaults);

        // Now get to the converting
        $newdefs = array();
        foreach ($this->legacyViewdefs as $field => $def) {
            if (!$this->isValidField($field)) {
                continue;
            }
            $defs = array();
            $defs['name'] = strtolower($field);
            unset($def['name']); // Prevents old defs from overriding the new

            // Bug 57414 - Available fields of mobile listview shown under
            //             default fields list after upgrade
            // For portal upgrades:
            //  - Default should be true by virtue of the field being in the viewdefs
            // For mobile upgrades:
            //  - Default is true if default was set truthy before
            // For both platforms:
            //  - enabled is true if it was not set before, or if it was set to true
            if ($this->client == 'portal') {
                $defs['default'] = true;
            } else {
                if (isset($def['default'])) {
                    // If it was set in the mobile metadata, use that (bool) value
                    $defs['default'] = AbstractMetaDataParser::isTruthy($def['default']);
                    unset($def['default']); // remove to prevent overriding in merge
                } else {
                    // Was not set, so it is not default. This allows a field to
                    // be available without being default
                    $defs['default'] = false;
                }
            }

            // Enabled is almost always true by virtue of this field being in the defs
            if (!isset($def['enabled'])) {
                $defs['enabled'] = true;
            } else {
                // This will more than likely never run, but you never know
                // If somehow the field was marked enabled in a non truthy way...
                $defs['enabled'] = AbstractMetaDataParser::isTruthy($def['enabled']);
                unset($def['enabled']); // unsetting to prevent clash in merge
            }

            // Merge the rest of the defs
            $defs = array_merge($defs, $def);

            // Merge the new defs into the defaults
            if (isset($defaults[$defs['name']])) {
                $defs = array_merge($defaults[$defs['name']], $defs);
            }

            // Remove module from the defs since the app doesn't like that
            unset($defs['module']);

            // Some MergeTemplate merges leave type for fields like team_name as relate
            // ignoring custom_type. We can't fix it there so we fix it here.
            // Since list takes def from vardef anyway, we can delete type if it matches vardef
            // See BR-1402
            if(!empty($defs['type']) && !empty($this->field_defs)
               && !empty($this->field_defs[$field]['type']) && $defs['type'] == $this->field_defs[$field]['type']
            ) {
                unset($defs['type']);
            }

            // Fix email1 to email field change
            // Also fix the label for this field
            if ($defs['name'] === 'email1') {
                $defs['name'] = 'email';
                $defs['label'] = 'LBL_ANY_EMAIL';
            }

            $newdefs[] = $defs;
        }
        $this->logUpgradeStatus("view defs converted, getting normalized module name");

        // This is the structure of the sidecar list meta
        $module = $this->getNormalizedModuleName();
        $this->logUpgradeStatus("module name normalized to: $module");

        // Clean up client to mobile for wireless clients
        $client = $this->client == 'wireless' ? 'mobile' : $this->client;
        $this->logUpgradeStatus("Setting new $client {$this->type} view defs internally for $module");
        $this->sidecarViewdefs[$module][$client]['view']['list'] = array(
            'panels' => array(
                array(
                    'label' => 'LBL_PANEL_DEFAULT',
                    'fields' => $newdefs,
                ),
            ),
        );
    }

    /**
     * Scans the Sugar7 list view style metadata and keys the list defs on field
     * name for use later
     * 
     * @param array $defaults Array of current defs
     * @return array
     */
    public function setDefaultsByKey(Array $defaults)
    {
        $return = array();
        if (isset($defaults['panels'])) {
            foreach ($defaults['panels'] as $panel) {
                if (isset($panel['fields']) && is_array($panel['fields'])) {
                    foreach ($panel['fields'] as $fielddef) {
                        if (isset($fielddef['name'])) {
                            $return[$fielddef['name']] = $fielddef;
                        }
                    }
                }
            }
        }
        
        return $return;
    }
}
