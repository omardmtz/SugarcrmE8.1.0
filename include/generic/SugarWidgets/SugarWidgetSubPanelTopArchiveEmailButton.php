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

class SugarWidgetSubPanelTopArchiveEmailButton extends SugarWidgetSubPanelTopButton
{
    public function display(array $defines, $additionalFormFields = array())
    {
        global $app_strings;

        if((ACLController::moduleSupportsACL($defines['module']) && !ACLController::checkAccess($defines['module'], 'edit', true) ||
            $defines['module'] == "History" & !ACLController::checkAccess("Emails", 'edit', true))){
            $temp = '';
            return $temp;
        }

        // if module is hidden or subpanel for the module is hidden - doesn't show quick create button
        if (SugarWidget::isModuleHidden('Emails')) {
            return '';
        }

        $title = $app_strings['LBL_TRACK_EMAIL_BUTTON_TITLE'];
        $value = $app_strings['LBL_TRACK_EMAIL_BUTTON_LABEL'];
        $this->module = 'Emails';

        if (ACLController::moduleSupportsACL($defines['module'])  && !ACLController::checkAccess($defines['module'], 'edit', true)){
            $button = "<input id='".preg_replace('[ ]', '', $value)."_button'  title='$title' class='button' type='button' name='".preg_replace('[ ]', '', strtolower($value))."_button' value='$value' disabled/>\n";
        } else {
            $button = "<input id='".preg_replace('[ ]', '', $value)."_button' title='$title' class='button' type='button' onClick=\"javascript:subp_archive_email();\" name='".preg_replace('[ ]', '', strtolower($value))."_button' value='$value'/>\n";
        }
        return $button;
    }
}
