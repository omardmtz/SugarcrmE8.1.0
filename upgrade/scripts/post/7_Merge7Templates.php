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
 * Merge Sugar 7 templates with customizations
 * Uses data from pre-script Merge7
 * @see BR-1491
 */
class SugarUpgradeMerge7Templates extends UpgradeScript
{
    public $order = 7000;
    public $type = self::UPGRADE_CUSTOM;

    /**#@+
     * Property fetched from the filename to be used throughout this upgrader
     *
     * @var string
     */
    public $moduleName;
    public $clientType;
    public $viewName;
    /**#@-*/

    /**
     * Flag that tells this upgrader whether to save a new viewdef
     *
     * @var boolean
     */
    public $needSave = false;

    public function run()
    {
        if (version_compare($this->from_version, '7.0', '<')) {
            // Not needed from upgrades from Sugar 6
            return;
        }

        if (empty($this->upgrader->state['for_merge'])) {
            // no views to upgrade
            return;
        }

        foreach ($this->upgrader->state['for_merge'] as $filename => $old_viewdefs) {
            $this->mergeView($filename, $old_viewdefs);
        }
    }

    /**
     * Load view file
     *
     * @param string $filename
     * @param string $module_name
     * @param string $platform
     * @param string $viewname
     *
     * @return NULL|array
     */
    protected function loadFile($filename, $module_name, $platform, $viewname)
    {
        $viewdefs = array();
        include $filename;
        if (empty($viewdefs) || empty($viewdefs[$module_name][$platform]['view'][$viewname]['panels'])) {
            // we do not handle non-panel views for now
            return null;
        }

        return $viewdefs;
    }

    /**
     * Extract field list from panels
     *
     * @param array $panels
     *
     * @return array
     */
    protected function fieldList($panels)
    {
        $fields = array();
        $panel_labels = array();
        foreach ($panels as $pindex => $panel) {
            if (empty($panel['fields'])) {
                continue;
            }
            if (!empty($panel['name'])) {
                $pname = $panel['name'];
            } else {
                $pname = null;
            }
            if (!empty($panel['label'])) {
                $panel_labels[$pindex] = $panel['label'];
            }
            foreach ($panel['fields'] as $fieldno => $field) {
                if (is_array($field)) {
                    if (empty($field['name'])) {
                        // omit no-name fields
                        continue;
                    }
                    $fname = $field['name'];
                } else {
                    $fname = $field;
                }
                $fields[$fname] = array("pname" => $pname, "pindex" => $pindex, "findex" => $fieldno, "data" => $field);
            }
        }

        return array($fields, $panel_labels);
    }

    /**
     * Merge view data
     *
     * @param string $filename
     * @param array  $old_viewdefs
     */
    protected function mergeView($filename, $old_viewdefs)
    {
        $this->log("Merging view $filename");
        list($modules, $module_name, $clients, $platform, $views, $viewname) = explode(DIRECTORY_SEPARATOR, $filename);

        $new_viewdefs = $this->loadFile($filename, $module_name, $platform, $viewname);
        $custom_viewdefs = $this->loadFile("custom/$filename", $module_name, $platform, $viewname);

        // These checks duplicate ones in Merge7, but better safe than sorry
        if (empty($old_viewdefs) || empty($new_viewdefs) || empty($custom_viewdefs)) {
            // defs missing - can't do anything here
            $this->log("Merge7Templates At least one of three necessary defs are missing... skipping");

            return;
        }

        // Shorten our viewdef path for easier handling
        $oldDefs = $old_viewdefs[$module_name][$platform]['view'][$viewname];
        $newDefs = $new_viewdefs[$module_name][$platform]['view'][$viewname];
        $customDefs = $custom_viewdefs[$module_name][$platform]['view'][$viewname];
        if ($this->defsUnchanged($oldDefs, $newDefs, $customDefs)) {
            // no changes to handle
            $this->log("Merge7Templates No changes to merge...");

            return;
        }

        // Set our known for use by helper methods
        $this->moduleName = $module_name;
        $this->clientType = $platform;
        $this->viewName = $viewname;

        // Handle panels first
        $custom_viewdefs = $this->mergePanelDefs($old_viewdefs, $new_viewdefs, $custom_viewdefs);

        // Handle everything else
        $custom_viewdefs = $this->mergeOtherDefs($old_viewdefs, $new_viewdefs, $custom_viewdefs);

        if ($this->needSave) {
            $this->log("Saving updated custom/$filename");
            write_array_to_file("viewdefs['{$this->moduleName}']['{$this->clientType}']['view']['{$this->viewName}']", $custom_viewdefs[$this->moduleName][$this->clientType]['view'][$this->viewName], "custom/$filename");
        }
    }

    /**
     * Merges panel defs, combining custom into new defs then into the old
     * defs.
     *
     * @param array $oldDefs The previous instance view defs
     * @param array $newDefs The upgrade instance view defs
     * @param array $customDefs Customizations from the previous instance
     *
     * @return array A new custom set of viewdefs
     */
    public function mergePanelDefs($old_viewdefs, $new_viewdefs, $custom_viewdefs)
    {
        list($old_fields, $old_panel_labels) = $this->fieldList(
            $old_viewdefs[$this->moduleName][$this->clientType]['view'][$this->viewName]['panels']
        );
        list($new_fields, $new_panel_labels) = $this->fieldList(
            $new_viewdefs[$this->moduleName][$this->clientType]['view'][$this->viewName]['panels']
        );

        // Here we care only for field presence, not for changes in field metadata
        $removed_fields = array_udiff_assoc($old_fields, $new_fields, function () {
                return 0;
            }
        );
        $added_fields = array_udiff_assoc($new_fields, $old_fields, function () {
                return 0;
            }
        );
        // This may include also added & removed fields, we'll remove them later
        $changed_fields = array_udiff_assoc($new_fields, $old_fields, function ($a, $b) {
                return $a == $b ? 0 : -1;
            }
        );

        // Index custom fields too
        list($custom_fields, $custom_panel_labels) = $this->fieldList(
            $custom_viewdefs[$this->moduleName][$this->clientType]['view'][$this->viewName]['panels']
        );

        if (empty($added_fields) && empty($removed_fields) && empty($changed_fields)) {
            if ($old_panel_labels == $new_panel_labels) {
                // nothing to do
                $this->log("Merge7Templates mergePanelDefs No changes to merge... skipping");

                return $custom_viewdefs;
            } else {
                $custom_viewdefs = $this->megePanelDefLabels(
                    $old_panel_labels,
                    $new_panel_labels,
                    $custom_panel_labels,
                    $custom_viewdefs
                );
            }
        }
        $this->log("Fields added: " . var_export($added_fields, true));
        $this->log("Fields removed: " . var_export($removed_fields, true));

        foreach ($added_fields as $field => $data) {
            unset($changed_fields[$field]);
            if (!empty($custom_fields[$field])) {
                // Already in custom view - we're done
                continue;
            }
            if ($this->checkComboFields($field, $data, $custom_fields)) {
                continue;
            }
            $this->addField($custom_viewdefs[$this->moduleName][$this->clientType]['view'][$this->viewName]['panels'], $data['pindex'], $data['pname'], $data['data']);
            $this->needSave = true;
        }

        foreach ($removed_fields as $field => $data) {
            unset($changed_fields[$field]);
            if (empty($custom_fields[$field])) {
                // If this field not in custom view, we're done
                continue;
            }

            $pindex = $custom_fields[$field]['pindex'];
            $findex = $custom_fields[$field]['findex'];
            // Remove field from panel
            unset($custom_viewdefs[$this->moduleName][$this->clientType]['view'][$this->viewName]['panels'][$pindex]['fields'][$findex]);
            // Re-index the fields
            $custom_viewdefs[$this->moduleName][$this->clientType]['view'][$this->viewName]['panels'][$pindex]['fields'] = array_values($custom_viewdefs[$this->moduleName][$this->clientType]['view'][$this->viewName]['panels'][$pindex]['fields']);
            $this->needSave = true;
        }

        if (!empty($changed_fields)) {
            $this->log("Fields changed: " . var_export($added_fields, true));
            foreach ($changed_fields as $field => $data) {
                if (empty($custom_fields[$field]) || empty($old_fields[$field]) || empty($new_fields[$field])) {
                    // Custom has no such field - ignore it
                    // Other ones should not be empty since we'd catch them on added/removed but check anyway for safety
                    continue;
                }
                // Change only if custom matches old data
                if ($custom_fields[$field]['data'] == $old_fields[$field]['data']) {
                    $pindex = $custom_fields[$field]['pindex'];
                    $findex = $custom_fields[$field]['findex'];
                    $custom_viewdefs[$this->moduleName][$this->clientType]['view'][$this->viewName]['panels'][$pindex]['fields'][$findex] = $new_fields[$field]['data'];
                    $this->needSave = true;
                }
            }
        }

        return $custom_viewdefs;
    }

    /**
     * Merges panel labels, combining custom into new labels then into the old labels.
     *
     * @param array $old_panel_labels The previous instance view labels
     * @param array $new_panel_labels The upgrade instance view labels
     * @param array $custom_panel_labels Customization labels from the previous instance
     * @param array $custom_viewdefs Customization viewdefs from the previous instance
     *
     * @return array A new custom set of viewdefs
     */
    public function megePanelDefLabels($old_panel_labels, $new_panel_labels, $custom_panel_labels, $custom_viewdefs)
    {
        $removed_panel_labels = array_udiff_assoc($old_panel_labels, $new_panel_labels, function () {
            return 0;
        });
        $added_panel_labels = array_udiff_assoc($new_panel_labels, $old_panel_labels, function () {
            return 0;
        });
        $changed_panel_labels = array_udiff_assoc($new_panel_labels, $old_panel_labels, function ($a, $b) {
            return $a == $b ? 0 : -1;
        });
        $cVdefs = $custom_viewdefs[$this->moduleName][$this->clientType]['view'][$this->viewName]['panels'];
        if (!empty($added_panel_labels)) {
            $this->log("Panel labels added: " . var_export($added_panel_labels, true));
            foreach ($added_panel_labels as $pindex => $label) {
                unset($changed_panel_labels[$pindex]);
                if (!empty($custom_panel_labels[$pindex])) {
                    continue;
                }
                $cVdefs[$pindex]['label'] = $label;
                $this->needSave = true;
            }
        }
        if (!empty($removed_panel_labels)) {
            $this->log("Panel labels removed: " . var_export($removed_panel_labels, true));
            foreach ($removed_panel_labels as $pindex => $label) {
                unset($changed_panel_labels[$pindex]);
                if (empty($custom_panel_labels[$pindex])) {
                    continue;
                }
                unset($cVdefs[$pindex]['label']);
                $this->needSave = true;
            }
        }
        if (!empty($changed_panel_labels)) {
            $this->log("Panel labels changed: " . var_export($changed_panel_labels, true));
            foreach ($changed_panel_labels as $pindex => $label) {
                if (empty($custom_panel_labels[$pindex]) ||
                    empty($old_panel_labels[$pindex]) ||
                    empty($new_panel_labels[$pindex])) {
                    // Custom has no such index - ignore it
                    continue;
                }
                // Change only if custom matches old data
                if ($custom_panel_labels[$pindex] == $old_panel_labels[$pindex]) {
                    $cVdefs[$pindex]['label'] = $new_panel_labels[$pindex]['label'];
                    $this->needSave = true;
                }
            }
        }
        $custom_viewdefs[$this->moduleName][$this->clientType]['view'][$this->viewName]['panels'] = $cVdefs;
        return $custom_viewdefs;
    }

    /**
     * Add a field to panel, trying to match panel name
     *
     * @param array &$panels Panels list
     * @param array $pindex Index of the panel to update
     * @param array $pname Name of the panel to update
     * @param array $field Field data
     */
    protected function addField(&$panels, $pindex, $pname, $field)
    {
        // Try by name
        foreach ($panels as $cpindex => $panel) {
            if (!empty($panel['name']) && $panel['name'] == $pname) {
                $panels[$cpindex]['fields'][] = $field;

                return;
            }
        }
        if (empty($panels[$pindex])) {
            // if we do not have this index, use last panel
            end($panels);
            $pindex = key($panels);
        }
        // add to panel by index
        $panels[$pindex]['fields'][] = $field;
    }

    /**
     * Check to see if there are changes between the old def and the new def OR
     * the custom def and the new def
     *
     * @param array $old View defs for the previous installation
     * @param array $new View defs for the upgraded installation
     * @param array $custom Custom viewdefs from the previous installation
     *
     * @return boolean
     */
    public function defsUnchanged($old, $new, $custom)
    {
        // Grab all keys of the defs so we can diff them
        $oldKeys = array_keys($old);
        $newKeys = array_keys($new);
        $custKeys = array_keys($custom);

        // Set our default flag, which says that there were no changes
        $changed = false;

        // Check old defs for changes in the new
        foreach ($oldKeys as $key) {
            if (!isset($new[$key]) || $old[$key] != $new[$key]) {
                $changed = true;
            }
        }

        // Check custom props for changes in the new
        foreach ($custKeys as $def) {
            if (!isset($new[$def]) || $custom[$def] != $new[$def]) {
                $changed = true;
            }
        }

        return $changed === false;
    }

    /**
     * Check for dupes between field-to-merge and custom fields, while handling fieldset-type fields
     *
     * @param $field field-to-merge's name
     * @param $data field-to-merge's field data
     * @param $old_fields all old fields
     *
     * @return bool true if dupe is found
     */
    protected function checkComboFields($field, $data, $old_fields)
    {
        $needle_list = array();
        // If field to check is of type 'fieldset',
        // add each field in the fieldset to the list of fields to check
        // else, only check the field
        if (isset($data['data']['type']) && $data['data']['type'] === 'fieldset') {
            // Populate search array
            foreach ($data['data']['fields'] as $set_field) {
                if (is_array($set_field) && !empty($set_field['name'])) {
                    $needle_list[] = $set_field['name'];
                } else {
                    if (is_string($set_field)) {
                        $needle_list[] = $set_field;
                    }
                }
            }
        } else {
            $needle_list[] = $field;
        }

        // Check if field exists in our needle list.
        // If field is a fieldset, check each field within the fieldset
        foreach ($old_fields as $fname => $fdata) {
            // If this old field is a fieldset
            if (isset($fdata['data']['type']) && $fdata['data']['type'] === 'fieldset') {
                foreach ($fdata['data']['fields'] as $set_field) {
                    if (is_array($set_field) && !empty($set_field['name']) && in_array($set_field['name'], $needle_list)) {
                        return true;
                    } else {
                        if (is_string($set_field) && in_array($set_field, $needle_list)) {
                            return true;
                        }
                    }
                }
            } else {
                if (in_array($fname, $needle_list)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Merges non 'panel' defs, combining custom into new defs then into the old
     * defs.
     *
     * @param array $oldDefs The previous instance view defs
     * @param array $newDefs The upgrade instance view defs
     * @param array $customDefs Customizations from the previous instance
     *
     * @return array A new custom set of viewdefs
     */
    public function mergeOtherDefs($oldDefs, $newDefs, $customDefs)
    {
        $oDefs = $oldDefs[$this->moduleName][$this->clientType]['view'][$this->viewName];
        $nDefs = $newDefs[$this->moduleName][$this->clientType]['view'][$this->viewName];
        $cDefs = $customDefs[$this->moduleName][$this->clientType]['view'][$this->viewName];
        if (!empty($cDefs)) {
            $merged = MergeUtils::deepMergeDef($oDefs, $nDefs, $cDefs);
            if (!empty($merged) && $merged != $cDefs) {
                $this->needSave = true;
                // Sanitize the top level viewdefs elements, remove if empty
                $merged = $this->sanitizeTopLevelDefElements($merged);
                $customDefs[$this->moduleName][$this->clientType]['view'][$this->viewName] = $merged;
            }
        }

        return $customDefs;
    }

    /**
     * Sanitize the top level view defs elements, remove it if it is empty.
     *
     * @param array $viewdefs The merged view defs elements
     *
     * @return array A new sanitized viewdefs
     */
    public function sanitizeTopLevelDefElements($viewdefs)
    {
        // Sanitize our top level view def elements. If there is an empty value
        // at the highest levels then we should not keep them. e.g. buttons, etc.
        foreach (array_keys($viewdefs) as $key) {
            if (empty($viewdefs[$key])) {
                $this->log("*** Top level empty viewdefs removed: $key ***");
                unset($viewdefs[$key]);
            }
        }

        return $viewdefs;
    }
}
