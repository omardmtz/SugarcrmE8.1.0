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

class SugarWidgetSubPanelTopScheduleCallButton extends SugarWidgetSubPanelTopButtonQuickCreate
{
    function &_get_form($defines, $additionalFormFields = null)
    {
        global $app_strings;
        global $currentModule;

        $this->module = "Calls";
        $this->subpanelDiv = "activities";

        if (!empty($this->module)) {
            $defines['child_module_name'] = $this->module;
        } else {
            $defines['child_module_name'] = $defines['module'];
        }

        if (!empty($this->subpanelDiv)) {
            $defines['subpanelDiv'] = $this->subpanelDiv;
        }

        $defines['parent_bean_name'] = get_class($defines['focus']);

        // Clicking Create from BWC subpanels for sidecar should open sidecar create view
        // Doing this early so as not to spend cycles on code that will go unused
        $sidecarButton = $this->_get_form_sidecar($defines);
        if ($sidecarButton) {
            return $sidecarButton;
        }

        // Create the additional form fields with real values if they were not passed in
        if (empty($additionalFormFields) && $this->additional_form_fields) {
            foreach ($this->additional_form_fields as $key => $value) {
                if (!empty($defines['focus']->$value)) {
                    $additionalFormFields[$key] = $defines['focus']->$value;
                } else {
                    $additionalFormFields[$key] = '';
                }
            }
        }

        $form = 'form' . $defines['child_module_name'];
        $button = '<form onsubmit="return SUGAR.subpanelUtils.sendAndRetrieve(this.id, \'subpanel_' . strtolower($defines['subpanelDiv']) . '\', \'' . addslashes($app_strings['LBL_LOADING']) . '\');" action="index.php" method="post" name="form" id="form' . $form . "\">\n";

        // module_button is used to override the value of module name
        $button .= "<input type='hidden' name='target_module' value='" . $defines['child_module_name'] . "'>\n";
        $button .= "<input type='hidden' name='" . strtolower($defines['parent_bean_name']) . "_id' value='" . $defines['focus']->id . "'>\n";

        if (isset($defines['focus']->name)) {
            $button .= "<input type='hidden' name='" . strtolower($defines['parent_bean_name']) . "_name' value='" . $defines['focus']->name . "'>";
        }

        $button .= '<input type="hidden" name="to_pdf" value="true" />';
        $button .= '<input type="hidden" name="tpl" value="QuickCreate.tpl" />';
        $button .= '<input type="hidden" name="return_module" value="' . $currentModule . "\" />\n";
        $button .= '<input type="hidden" name="return_action" value="' . $defines['action'] . "\" />\n";
        $button .= '<input type="hidden" name="return_id" value="' . $defines['focus']->id . "\" />\n";
        $button .= '<input type="hidden" name="record" value="" />';

        // TODO: move this out and get $additionalFormFields working properly
        if (empty($additionalFormFields['parent_type'])) {
            if ($defines['focus']->object_name == 'Contact') {
                $additionalFormFields['parent_type'] = 'Accounts';
            } else {
                $additionalFormFields['parent_type'] = $defines['focus']->module_dir;
            }
        }
        if (empty($additionalFormFields['parent_name'])) {
            if ($defines['focus']->object_name == 'Contact') {
                $additionalFormFields['parent_name'] = $defines['focus']->account_name;
                $additionalFormFields['account_name'] = $defines['focus']->account_name;
            } else {
                $additionalFormFields['parent_name'] = $defines['focus']->name;
            }
        }
        if (empty($additionalFormFields['parent_id'])) {
            if ($defines['focus']->object_name == 'Contact') {
                $additionalFormFields['parent_id'] = $defines['focus']->account_id;
                $additionalFormFields['account_id'] = $defines['focus']->account_id;
            } else {
                $additionalFormFields['parent_id'] = $defines['focus']->id;
            }
        }

        $button .= '<input type="hidden" name="action" value="SubpanelCreates" />' . "\n";
        $button .= '<input type="hidden" name="module" value="Home" />' . "\n";
        $button .= '<input type="hidden" name="target_action" value="QuickCreate" />' . "\n";

        // fill in additional form fields for all but action
        foreach ($additionalFormFields as $key => $value) {
            if ($key != 'action') {
                $button .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />' . "\n";
            }
        }
        $button .= getVersionedScript('include/SugarFields/Fields/Datetimecombo/Datetimecombo.js') . "\n";

        return $button;
    }

    public function display(array $defines, $additionalFormFields = array())
    {
        $focus = BeanFactory::newBean('Calls');
        if (!$focus->ACLAccess('EditView')) {
            return '';
        }

        return parent::display($defines, $additionalFormFields);
    }
}
