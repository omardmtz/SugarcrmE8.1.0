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
 * This upgrader will check whether the converted custom record view is a tab layout and duration field missing
 * when converting from bwc to sidecar.  It will add the duration field to the bottom of the business card
 *
 * @see MAR-3872
 */
class SugarUpgradeAddDurationMeetingsCustomRecordView extends UpgradeScript
{
    public $order = 7450;
    public $type = self::UPGRADE_CUSTOM;

    /**
     * The current viewdefs for a given file
     *
     * @var array
     */
    public $viewdefs = array();

    /**
     * The path for the custom record
     *
     * @var array
     */
    protected $filePath = 'custom/modules/Meetings/clients/base/views/record/record.php';

    /**
     * The state of the save, true when a save needs to be run
     *
     * @var boolean
     */
    protected $save = false;

    public function run()
    {
        if ($this->shouldRun()) {
            $this->updateCustomRecordViews();
        }
    }

    /**
     * Handles checking whether this upgrader needs to run or not
     *
     * @return boolean
     */
    protected function shouldRun()
    {
        return version_compare($this->from_version, '7.6', '<')
               && version_compare($this->to_version, '7.6', '>=');
    }

    /**
     * Handles triggering of viewdefs updates, if there are any to be made
     */
    protected function updateCustomRecordViews()
    {
        $this->handleRecordUpdate();
        if ($this->shouldSave()) {
            $this->saveFile();
        }
    }

    /**
     * Checks whether this customer has a custom record view
     */
    protected function hasCustomRecordView()
    {
        return file_exists($this->filePath);
    }

    /**
     * Handles the triggering of a metadata update
     */
    protected function handleRecordUpdate()
    {
        if ($this->hasCustomRecordView()) {
            $viewdefs = $this->getCustomRecordViewDef();
            $this->replaceFieldsForRecordView($viewdefs);
        }
    }

    /**
     * Handles the loading of the custom record view def
     *
     * @return array
     */
    protected function getCustomRecordViewDef()
    {
        $viewdefs = array();
        require $this->filePath;

        return $viewdefs;
    }

    /**
     * Handles actual replacement of viewdefs when needed. This check if the duration
     * fieldset exists and added to the business card if not on the panel
     *
     * @param array $defs The complete viewdef
     */
    protected function replaceFieldsForRecordView($defs)
    {
        $module = key($defs);
        $rows = $defs[$module]['base']['view']['record'];

        if (isset($rows['templateMeta']['useTabs'])
            && $rows['templateMeta']['useTabs'] === true
        ) {
            $hasDurationField = false;
            foreach ($rows['panels'] as $rowIndex => $panel) {
                if (isset($panel['fields']) && is_array($panel['fields'])) {
                    foreach ($panel['fields'] as $fieldIndex => $field) {
                        if (is_array($field)) {
                            if ($field['name'] === 'duration') {
                                if (isset($field['type']) && $field['type'] === 'duration') {
                                    $hasDurationField = true;
                                } else {
                                    //not the right duration field. Remove it
                                    unset($rows['panels'][$rowIndex]['fields'][$fieldIndex]);
                                }

                            }
                        }
                    }
                }
                // Reorganize the keys so they make sense
                $rows['panels'][$rowIndex]['fields'] = array_values($rows['panels'][$rowIndex]['fields']);
            }

            if (!$hasDurationField) {
                //Stuff at the bottom of the business card
                $durationDef = $this->getNewDurationFieldDef();
                if (!empty($durationDef)) {
                    $rows['panels'][1]['fields'][] = $durationDef;

                    $defs[$module]['base']['view']['record'] = $rows;
                    $this->viewdefs = $defs;
                    $this->save = true;
                } else {
                    $this->log('Meetings Record View Upgrade for Tab Layout: Could not retrieve OOTB Record View.');
                }
            }
        }
    }

    /**
     * Returns the duration fieldset def that was added to the record views for Calls and Meeting
     *
     * @return array|null
     */
    protected function getNewDurationFieldDef()
    {
        $file = 'modules/Meetings/clients/base/views/record/record.php';

        if (file_exists($file)) {
            require $file;
            if (isset($viewdefs['Meetings']['base']['view']['record']['panels'])) {
                foreach ($viewdefs['Meetings']['base']['view']['record']['panels'] as $panel) {
                    if (isset($panel['fields'])) {
                        foreach ($panel['fields'] as $field) {
                            if (isset($field['name'])
                                && $field['name'] === 'duration'
                                && $field['type'] === 'duration'
                            ) {
                                return $field;
                            }
                        }
                    }
                }
            }
        }
        return null;
    }

    /**
     * Returns whether a save should happen depending whether changes were
     *
     * @return boolean
     */
    protected function shouldSave()
    {
        return $this->save;
    }

    /**
     * Performs the saving of the custom record view
     */
    protected function saveFile()
    {
        $module = key($this->viewdefs);
        $data = $this->viewdefs[$module]['base']['view']['record'];
        $this->log('Meetings Record View Upgrade for Tab Layout: Adding Duration Field.');
        $this->log('Meetings Record View Upgrade for Tab Layout: Saving custom record view def.');
        write_array_to_file("viewdefs['$module']['base']['view']['record']", $data, $this->filePath);

    }
}
