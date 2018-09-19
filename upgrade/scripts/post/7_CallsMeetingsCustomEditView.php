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
 * Upgrader that removes date_start, date_end and duration fields from mobile
 * edit views and replaces them with updated defs.
 */
class SugarUpgradeCallsMeetingsCustomEditView extends UpgradeScript
{
    public $order = 7450;
    public $type = self::UPGRADE_CUSTOM;

    /**
     * The current viewdefs for a given file
     * @var array
     */
    protected $viewdefs = array();

    /**
     * The state of the save, true when a save needs to be run
     * @var boolean
     */
    protected $save = false;

    public function run()
    {
        if ($this->shouldRun()) {
            $this->updateCustomEditViews();
        }
    }

    /**
     * Handles checking whether this upgrader needs to run or not
     * @return boolean
     */
    protected function shouldRun()
    {
        return version_compare($this->from_version, '7.7', '<')
               && version_compare($this->to_version, '7.7', '>=');
    }

    /**
     * Handles triggering of viewdefs updates, if there are any to be made
     */
    protected function updateCustomEditViews()
    {
        foreach (array('Calls', 'Meetings') as $module) {
            $file = "custom/modules/$module/clients/mobile/views/edit/edit.php";
            $this->handleUpdate($file);
            if ($this->shouldSave()) {
                $this->saveFile($file);
            }
        }
    }

    /**
     * Resets the state of this module's check
     */
    protected function resetState()
    {
        $this->viewdefs = array();
        $this->save = false;
    }

    /**
     * Handles the triggering of a metadata update
     * @param string $file The name of the file to work on
     */
    protected function handleUpdate($file)
    {
        $this->resetState();
        if (file_exists($file)) {
            $viewdefs = array();
            require $file;
            $this->replaceFields($viewdefs);
        }
    }

    /**
     * Handles actual replacement of viewdefs when needed. This will remove 
     * date_start and date_end fields, since those will be added as part of the
     * change that requies this upgrade.
     * @param array $defs The complete viewdef
     */
    protected function replaceFields($defs)
    {
        $module = key($defs);
        $rows = $defs[$module]['mobile']['view']['edit'];
        foreach ($rows['panels'] as $rowIndex => $panel) {
            if (isset($panel['fields']) && is_array($panel['fields'])) {
                foreach ($panel['fields'] as $fieldIndex => $field) {
                    // Handle removing of the date_start and date_end fields first
                    if ($field === 'date_start' || $field === 'date_end') {
                        $this->log("$module Mobile Edit View Upgrade: Found the $field field... removing from the viewdef");
                        unset($rows['panels'][$rowIndex]['fields'][$fieldIndex]);
                    }

                    // This will first look for fieldsets holding date_start and 
                    // date_end and remove those, then add the new def
                    if (is_array($field)) {
                        if (isset($field['fields'])) {
                            foreach ($field['fields'] as $fIndex => $f) {
                                // String fields in a field set
                                if ($f === 'date_start' || $f === 'date_end') {
                                    $this->log("$module Mobile Edit View Upgrade: Found the $field field in a fieldset [0]... removing from the viewdef");
                                    unset($rows['panels'][$rowIndex]['fields'][$fieldIndex]['fields'][$fIndex]);
                                }

                                // Array fields in a fieldset
                                if (is_array($f) && isset($f['name']) && ($f['name'] === 'date_start' || $f['name'] === 'date_end')) {
                                    $this->log("$module Mobile Edit View Upgrade: Found the $field field in a fieldset [1]... removing from the viewdef");
                                    unset($rows['panels'][$rowIndex]['fields'][$fieldIndex]['fields'][$fIndex]);
                                }
                            }
                        }

                        // Handle what we came here for. This case will be the
                        // only case in which a save occurs so none of the above
                        // changes will take affect unless the follow change also
                        // takes affect.
                        if (!empty($field['name']) && $field['name'] === 'duration') {
                            $this->log("$module Mobile Edit View Upgrade: Found the duration field... replacing it with the updated fieldset def");
                            $rows['panels'][$rowIndex]['fields'][$fieldIndex] = $this->getNewDurationDef();
                            $this->save = true;
                        }
                    }
                }

                // Reorganize the keys so they make sense
                $rows['panels'][$rowIndex]['fields'] = array_values($rows['panels'][$rowIndex]['fields']);
            }
        }

        $defs[$module]['mobile']['view']['edit'] = $rows;
        $this->viewdefs = $defs;
    }

    /**
     * This is the def that was added to the edit views for Calls and Meeting.
     * This should replace the current 'duration' view field.
     * @return array
     */
    protected function getNewDurationDef()
    {
        return array(
            'name' => 'date',
            'type' => 'fieldset',
            'related_fields' => array('date_start', 'date_end'),
            'label' => 'LBL_START_AND_END_DATE_DETAIL_VIEW',
            'fields' => array(
                array(
                    'name' => 'date_start',
                ),
                array(
                    'name' => 'date_end',
                    'required' => true,
                    'readonly' => false,  
                ),
            ),
        );
    }

    /**
     * Returns our save state flag
     * @return boolean
     */
    protected function shouldSave()
    {
        return $this->save;
    }

    /**
     * Actually saves the file if needed
     * @param string $file The file to save
     */
    protected function saveFile($file)
    {
        $module = key($this->viewdefs);
        $data = $this->viewdefs[$module]['mobile']['view']['edit'];
        $this->log("Calls/Meetings Mobile Edit View Upgrade: About to save changes to the custom $module mobile edit view defs");
        write_array_to_file("viewdefs['$module']['mobile']['view']['edit']", $data, $file);
    }
}
