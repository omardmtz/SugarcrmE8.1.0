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
 * Remove duplicate fields from record view.
 * Class SugarUpgradeFixDuplicateFields
 */
class SugarUpgradeFixDuplicateFields extends UpgradeScript
{
    /**
     * {@inheritdoc}
     */
    public $order = 7920;

    /**
     * {@inheritdoc}
     */
    public $type = self::UPGRADE_CUSTOM;

    /**
     * @var MetaDataFiles
     */
    protected $meta;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        global $beanList;
        $this->init();
        foreach ($beanList as $module => $_) {
            if (BeanFactory::newBean($module) instanceof SugarBean && !isModuleBWC($module)) {
                $this->checkRecordView($module);
            }
        }
    }

    /**
     * Initialize internal variables.
     */
    protected function init()
    {
        $this->meta = new MetaDataFiles();
    }

    /**
     * Check record view for module.
     * @param string $module Module name.
     */
    protected function checkRecordView($module)
    {
        $parser = new UpgradeSidecarGridLayoutMetaDataParser(MB_RECORDVIEW, $module, '', 'base');
        if ($parser->removeDubplicateFields()) {
            $this->log("Duplicates removed from record view for module '{$module}'");
        }
    }
}

/**
 * Class extends viewdefs manipulation during update process.
 * Class UpgradeSidecarGridLayoutMetaDataParser
 */
class UpgradeSidecarGridLayoutMetaDataParser extends SidecarGridLayoutMetaDataParser
{
    /**
     * Removes duplicates from record view.
     * @returns boolean Return true if any duplicate has been removed, false otherwise.
     */
    public function removeDubplicateFields()
    {
        $layout = $this->getLayout();
        $empties = array(MBConstants::$EMPTY['name'], MBConstants::$FILLER['name']);
        $exists = array();
        $remove = array();
        foreach ($layout as $panelID => $panel) {
            foreach ($panel as $row) {
                foreach ($row as $field) {
                    if (!in_array($field['name'], $empties)) {
                        if (!isset($exists[$field['name']])) {
                            $exists[$field['name']] = true;
                        } else {
                            $remove[$field['name']] = true;
                        }
                    }
                }
            }
        }
        if (!empty($remove)) {
            foreach ($remove as $field => $_) {
                $this->removeDuplicateField($field);
            }
            $this->handleSave(false);
            return true;
        }
        return false;
    }

    /**
     * Removes all duplicates for the field.
     * @param string $fieldName Name of a field
     * @see SidecarGridLayoutMetaDataParser::removeField
     */
    public function removeDuplicateField($fieldName)
    {
        $found = false;
        $newDefs = array();
        foreach ($this->_viewdefs['panels'] as $panelID => $panel) {
            foreach ($panel as $rowIndex => $row) {
                if (is_array($row)) {
                    foreach ($row as $fieldIndex => $field) {
                        if ($field === $fieldName) {
                            if ($found) {
                                $panel[$rowIndex][$fieldIndex] = MBConstants::$EMPTY['name'];
                            }
                            $found = true;
                        }
                    }
                }
            }

            if (!$found) {
                $newDefs[$panelID] = $panel;
                continue;
            }

            $newRows = array();
            foreach ($panel as $rowIndex => $row) {
                if (is_array($row)) {
                    $cols = count($row);
                    $empties = 0;
                    foreach ($row as $field) {
                        if ($field === MBConstants::$EMPTY['name'] || $field === MBConstants::$FILLER['name']) {
                            $empties++;
                        }
                    }
                    //empty row
                    if ($empties == $cols) {
                        continue;
                    }

                    $newRows[] = $row;
                }
            }

            if (count($newRows) > 0) {
                $newDefs[$panelID] = $newRows;
            }
        }
        $this->_viewdefs['panels']  = $newDefs;
    }
}
