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

// $Id: SugarWidgetSubPanelTopComposeEmailButton.php 56851 2010-06-07 22:17:02Z jenny $



class SugarWidgetSubPanelTopComposeEmailButton extends SugarWidgetSubPanelTopButton
{
	var $form_value = '';
    
    public function getWidgetId()
    {
    	global $app_strings;
		$this->form_value = $app_strings['LBL_COMPOSE_EMAIL_BUTTON_LABEL'];
    	return parent::getWidgetId();
    }

    public function display(array $defines, $additionalFormFields = array())
	{
		if((ACLController::moduleSupportsACL($defines['module'])  && !ACLController::checkAccess($defines['module'], 'edit', true) ||
			$defines['module'] == "Activities" & !ACLController::checkAccess("Emails", 'edit', true))){
			$temp = '';
			return $temp;
		}

        /**
         * if module is hidden or subpanel for the module is hidden - doesn't show quick create button
         */
        if ( SugarWidget::isModuleHidden( 'Emails' ) )
        {
            return '';
        }

		global $app_strings,$current_user,$sugar_config,$beanList,$beanFiles;
		$title = $app_strings['LBL_COMPOSE_EMAIL_BUTTON_TITLE'];
		//$accesskey = $app_strings['LBL_COMPOSE_EMAIL_BUTTON_KEY'];
		$value = $app_strings['LBL_COMPOSE_EMAIL_BUTTON_LABEL'];
		$parent_type = $defines['focus']->module_dir;
		$parent_id = $defines['focus']->id;

		//martin Bug 19660
		$userPref = $current_user->getPreference('email_link_type');
		$defaultPref = $sugar_config['email_default_client'];
		if($userPref != '') {
			$client = $userPref;
		} else {
			$client = $defaultPref;
		}
		if($client != 'sugar') {
			$bean = $defines['focus'];
			// awu: Not all beans have emailAddress property, we must account for this
			if (isset($bean->emailAddress)){
				$to_addrs = $bean->emailAddress->getPrimaryAddress($bean);
				$button = "<input class='button' type='button'  value='$value'  id='". $this->getWidgetId() . "'  name='".preg_replace('[ ]', '', $value)."'   title='$title' onclick=\"location.href='mailto:$to_addrs';return false;\" />";
			}
			else{
				$button = "<input class='button' type='button'  value='$value'  id='". $this->getWidgetId() ."'  name='".preg_replace('[ ]', '', $value)."'  title='$title' onclick=\"location.href='mailto:';return false;\" />";
			}
		} else {
			//Generate the compose package for the quick create options.
    		$composeData = array("parent_id" => $parent_id, "parent_type"=>$parent_type);
            $eUi = new EmailUI();
            $j_quickComposeOptions = $eUi->generateComposePackageForQuickCreate($composeData, http_build_query($composeData), false, $defines['focus']);

            $button = "<input title='$title'  id='". $this->getWidgetId()."'  onclick='SUGAR.quickCompose.init($j_quickComposeOptions);' class='button' type='submit' name='".preg_replace('[ ]', '', $value)."_button' value='$value' />";
		}
		return $button;
	}
}
