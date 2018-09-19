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
 * Fix detail views that may be broken by MergeTemplate
 * See BR-1462
 */
class SugarUpgradeFixDetailView extends UpgradeScript
{
    public $order = 5000;
    public $type = self::UPGRADE_CUSTOM;
    // List of modules from EditViewMerge.php which may be messed up
    // These are BWC modules that DetailView merger could mess up
    protected $modules = array('Campaigns', 'Meetings', 'Contracts');
    // Broken fields. Label is produced by MergeTemplates, customCode is the original code that should be there
    protected $fields_names = array(
        'date_modified' => array(
                'label'=> 'LBL_MODIFIED_NAME',
                'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}'),
        'date_entered' => array(
                'label' => 'LBL_CREATED',
                'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}'),
    );

    public function run()
    {
        if(version_compare($this->from_version, '7.0', '>=')) {
            // right now there's no need to run this on 7
            return;
        }
        foreach($this->modules as $module) {
            $this->fixModule($module);
        }
    }

    protected function fixModule($module) {
        if(!isModuleBWC($module)) {
            $this->log("$module is not BWC, not checking");
            return;
        }
        $filename = "custom/modules/$module/metadata/detailviewdefs.php";
        if(file_exists($filename)) {
            $this->log("Checking $filename");
            $viewdefs = array();
            include $filename;
            if(empty($viewdefs[$module]) || empty($viewdefs[$module]['DetailView']['panels'])) {
                $this->log("Could not find viewdefs, skipping");
                return;
            }
            $modified = false;
            foreach($viewdefs[$module]['DetailView']['panels'] as $pname => $panel) {
                foreach($panel as $rid => $row) {
                    foreach($row as $fid => $field) {
                        // Check that the field is one of the broken fields and has broken label
                        // and no custom code
                        if(is_array($field) && !empty($field['name']) && !empty($field['label'])
                            && !isset($field['customCode'])
                            && !empty($this->fields_names[$field['name']])
                            && $this->fields_names[$field['name']]['label'] == $field['label']) {
                            // Reset field to using proper custom code
                            $newfield = array('name' => $field['name'], 'customCode' => $this->fields_names[$field['name']]['customCode']);
                            $viewdefs[$module]['DetailView']['panels'][$pname][$rid][$fid] = $newfield;
                            $modified = true;
                        }
                    }
                }
            }
            if($modified) {
                $this->log("Updating $filename");
                write_array_to_file("viewdefs['$module']['DetailView']", $viewdefs[$module]['DetailView'], $filename);
            }
        }
    }

}
