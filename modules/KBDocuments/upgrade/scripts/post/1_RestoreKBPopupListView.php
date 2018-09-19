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
 * Restore correct listviewdefs in popupdefs.php.
 * In some reason in old Sugar version listviewdefs can be overwritten by searchdefs.
 */
class SugarUpgradeRestoreKBPopupListView extends UpgradeScript
{
    /**
     * @inheritdoc
     */
    public $order = 1001;

    /**
     * @inheritdoc
     */
    public $type = self::UPGRADE_CUSTOM;

    /**
     * @inheritdoc
     */
    public $version = '7.7';

    /**
     * @var string
     */
    public $module = 'KBDocuments';

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!version_compare($this->from_version, '7.7', '<')) {
            return;
        }

        // Nothing to do if no customization for KB Popup listview.
        if (!file_exists('custom/modules/'.$this->module.'/clients/base/views/selection-list/selection-list.php') ||
            !file_exists('custom/modules/'.$this->module .'/metadata/popupdefs.php')
        ) {
            return;
        }

        // Get default (fields on layout) fields.
        $sidecarParser = new SidecarListLayoutMetaDataParser(MB_SIDECARPOPUPVIEW, $this->module, null, 'base');
        $sidecarDefaultFields = $sidecarParser->getDefaultFields();

        $popupParser = new PopupMetaDataParser(MB_POPUPLIST, $this->module);

        // Nothing to do if the same fields.
        if (!count(array_diff_key($sidecarDefaultFields, $popupParser->_viewdefs))) {
            return;
        }

        // Clear listviewdefs and add all fields from sidecar selection-list.
        $popupParser->_viewdefs = $sidecarDefaultFields;
        $popupParser->handleSave(false);
    }
}
