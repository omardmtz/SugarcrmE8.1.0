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

use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Config as IdmConfig;

class UsersViewEdit extends ViewEdit {
var $useForSubpanel = true;

    function preDisplay() {
        $this->fieldHelper = UserViewHelper::create($this->ss, $this->bean, 'EditView');
        $this->fieldHelper->setupAdditionalFields();

        parent::preDisplay();
    }

    /**
     * {@inheritDoc}
     *
     * @param string $type Ignored
     */
    public function getMetaDataFile($type = null)
    {
        $userType = 'Regular';
        if($this->fieldHelper->usertype == 'PORTAL_ONLY'){
            $userType = 'Portal';
        }
        if($this->fieldHelper->usertype == 'GROUP'){
            $userType = 'Group';
        }

        return parent::getMetaDataFile($userType != 'Regular' ? $this->type . 'group' : null);
    }

    function display() {
        global $current_user, $app_list_strings;

        $idpConfig = new IdmConfig(\SugarConfig::getInstance());
        if ($idpConfig->isIDMModeEnabled() && !$this->bean->isUpdate()) {
            $this->showRedirectToCloudConsole($idpConfig->buildCloudConsoleUrl('userCreate'));
        }

        //lets set the return values
        $return_module = $this->request->getValidInputRequest('return_module', 'Assert\Bean\ModuleName');
        $this->ss->assign('RETURN_MODULE', $return_module);

        $this->ss->assign('IS_ADMIN', $current_user->is_admin ? true : false);

        //make sure we can populate user type dropdown.  This usually gets populated in predisplay unless this is a quickeditform
        if(!isset($this->fieldHelper)){
            $this->fieldHelper = UserViewHelper::create($this->ss, $this->bean, 'EditView');
            $this->fieldHelper->setupAdditionalFields();
        }

        if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
            $return_action = $this->request->getValidInputRequest('return_action');
            $this->ss->assign('RETURN_ACTION', $return_action);
            $record = $this->request->getValidInputRequest('record', 'Assert\Guid');
            $this->ss->assign('RETURN_ID', $record);
            $this->bean->id = "";
            $this->bean->user_name = "";
            $this->ss->assign('ID','');
        } else {
            if (!$return_module) {
                $this->ss->assign('RETURN_MODULE', $this->bean->module_dir);
            }

            $return_id = $this->request->getValidInputRequest('return_id', 'Assert\Guid', $this->bean->id);
            if (isset($return_id)) {
                $return_action = $this->request->getValidInputRequest('return_action', null, 'DetailView');
                $this->ss->assign('RETURN_ID', $return_id);
                $this->ss->assign('RETURN_ACTION', $return_action);
            }
        }


        ///////////////////////////////////////////////////////////////////////////////
        ////	REDIRECTS FROM COMPOSE EMAIL SCREEN
        $type = $this->request->getValidInputRequest('type');
        if ($type && $return_module == 'Emails') {
            $this->ss->assign('REDIRECT_EMAILS_TYPE', $type);
        }
        ////	END REDIRECTS FROM COMPOSE EMAIL SCREEN
        ///////////////////////////////////////////////////////////////////////////////

        ///////////////////////////////////////////////////////////////////////////////
        ////	NEW USER CREATION ONLY
        if(empty($this->bean->id)) {
            $this->ss->assign('SHOW_ADMIN_CHECKBOX','height="30"');
            $this->ss->assign('NEW_USER','1');
        }else{
            $this->ss->assign('NEW_USER','0');
            $this->ss->assign('NEW_USER_TYPE','DISABLED');
            $this->ss->assign('REASSIGN_JS', "return confirmReassignRecords();");
        }

        ////	END NEW USER CREATION ONLY
        ///////////////////////////////////////////////////////////////////////////////

        global $sugar_flavor;
        $admin = Administration::getSettings();

        if((isset($sugar_flavor) && $sugar_flavor != null) &&
           (isset($admin->settings['license_enforce_user_limit']) && $admin->settings['license_enforce_user_limit'] == 1)) {
            if (empty($this->bean->id)) {
                $license_users = $admin->settings['license_users'];
                if ($license_users != '') {
                    $license_seats_needed = count( get_user_array(false, "", "", false, null, " AND ".User::getLicensedUsersWhere(), false) ) - $license_users;
                } else {
                    $license_seats_needed = -1;
                }
                if( $license_seats_needed >= 0 ){
                    displayAdminError( translate('WARN_LICENSE_SEATS_USER_CREATE', 'Administration') . translate('WARN_LICENSE_SEATS2', 'Administration')  );
                    if( isset($_SESSION['license_seats_needed'])) {
                        unset($_SESSION['license_seats_needed']);
                    }
                    //die();
                }
            }
        }

        // FIXME: Translate error prefix
        $error_string = $this->request->getValidInputRequest('error_string');
        if ($error_string) {
            $this->ss->assign('ERROR_STRING', '<span class="error">Error: ' . htmlspecialchars($error_string, ENT_QUOTES, 'UTF-8') . '</span>');
        }

        $error_password = $this->request->getValidInputRequest('error_password');
        if ($error_password) {
            $this->ss->assign('ERROR_PASSWORD', '<span id="error_pwd" class="error">Error: ' . htmlspecialchars($error_password, ENT_QUOTES, 'UTF-8') . '</span>');
        }

        // Build viewable versions of a few fields for non-admins
        if(!empty($this->bean->id)) {
            if( !empty($this->bean->status) ) {
                $this->ss->assign('STATUS_READONLY',$app_list_strings['user_status_dom'][$this->bean->status]);
            }
            if( !empty($this->bean->employee_status) ) {
                $this->ss->assign('EMPLOYEE_STATUS_READONLY', $app_list_strings['employee_status_dom'][$this->bean->employee_status]);
            }
            if( !empty($this->bean->reports_to_id) ) {
                $reportsToUserField = "<input type='text' name='reports_to_name' id='reports_to_name' value='{$this->bean->reports_to_name}' disabled>\n";
                $reportsToUserField .= "<input type='hidden' name='reports_to_id' id='reports_to_id' value='{$this->bean->reports_to_id}'>";
                $this->ss->assign('REPORTS_TO_READONLY', $reportsToUserField);
            }
            if( !empty($this->bean->title) ) {
                $this->ss->assign('TITLE_READONLY', $this->bean->title);
            }
            if( !empty($this->bean->department) ) {
                $this->ss->assign('DEPT_READONLY', $this->bean->department);
            }
        }

        $processSpecial = false;
        $processFormName = '';
        if ( isset($this->fieldHelper->usertype) && ($this->fieldHelper->usertype == 'GROUP'
             || $this->fieldHelper->usertype == 'PORTAL_ONLY'
            )) {
            $this->ev->formName = 'EditViewGroup';

            $processSpecial = true;
            $processFormName = 'EditViewGroup';
        }

        //Bug#51609 Replace {php} code block in EditViewHeader.tpl
        $action_button = array();
        $APP = $this->ss->get_template_vars('APP');
        $PWDSETTINGS = $this->ss->get_template_vars('PWDSETTINGS');
        $REGEX = $this->ss->get_template_vars('REGEX');
        $CHOOSER_SCRIPT = $this->ss->get_template_vars('CHOOSER_SCRIPT');
        $REASSIGN_JS = $this->ss->get_template_vars('REASSIGN_JS');
        $RETURN_ACTION = $this->ss->get_template_vars('RETURN_ACTION');
        $RETURN_MODULE = $this->ss->get_template_vars('RETURN_MODULE');
        $RETURN_ID = $this->ss->get_template_vars('RETURN_ID');

        $minpwdlength = !empty($PWDSETTINGS['minpwdlength']) ? $PWDSETTINGS['minpwdlength'] : '';
        $maxpwdlength =  !empty($PWDSETTINGS['maxpwdlength']) ? $PWDSETTINGS['maxpwdlength'] : '';
        $action_button_header[] = <<<EOD
                    <input type="button" id="SAVE_HEADER" title="{$APP['LBL_SAVE_BUTTON_TITLE']}" accessKey="{$APP['LBL_SAVE_BUTTON_KEY']}"
                          class="button primary" onclick="var _form = $('#EditView')[0]; if (!set_password(_form,newrules('{$minpwdlength}','{$maxpwdlength}','{$REGEX}'))) return false; if (!Admin_check()) return false; _form.action.value='Save'; {$CHOOSER_SCRIPT} {$REASSIGN_JS} if(verify_data(EditView)) { submit_form(_form); }"
                          name="button" value="{$APP['LBL_SAVE_BUTTON_LABEL']}">
EOD
        ;
        $action_button_header[] = <<<EOD
                    <input	title="{$APP['LBL_CANCEL_BUTTON_TITLE']}" id="CANCEL_HEADER" accessKey="{$APP['LBL_CANCEL_BUTTON_KEY']}"
                              class="button" onclick="var _form = $('#EditView')[0]; _form.action.value='{$RETURN_ACTION}'; _form.module.value='{$RETURN_MODULE}'; _form.record.value='{$RETURN_ID}'; _form.submit()"
                              type="button" name="button" value="{$APP['LBL_CANCEL_BUTTON_LABEL']}">
EOD
        ;
        $action_button_header = array_merge($action_button_header, $this->ss->get_template_vars('BUTTONS_HEADER'));
        $this->ss->assign('ACTION_BUTTON_HEADER', $action_button_header);

        $action_button_footer[] = <<<EOD
                    <input type="button" id="SAVE_FOOTER" title="{$APP['LBL_SAVE_BUTTON_TITLE']}" accessKey="{$APP['LBL_SAVE_BUTTON_KEY']}"
                          class="button primary" onclick="var _form = $('#EditView')[0]; if (!set_password(_form,newrules('{$minpwdlength}','{$maxpwdlength}','{$REGEX}'))) return false; if (!Admin_check()) return false; _form.action.value='Save'; {$CHOOSER_SCRIPT} {$REASSIGN_JS} if(verify_data(EditView)) { submit_form(_form); }"
                          name="button" value="{$APP['LBL_SAVE_BUTTON_LABEL']}">
EOD
        ;
        $action_button_footer[] = <<<EOD
                    <input	title="{$APP['LBL_CANCEL_BUTTON_TITLE']}" id="CANCEL_FOOTER" accessKey="{$APP['LBL_CANCEL_BUTTON_KEY']}"
                              class="button" onclick="var _form = $('#EditView')[0]; _form.action.value='{$RETURN_ACTION}'; _form.module.value='{$RETURN_MODULE}'; _form.record.value='{$RETURN_ID}'; _form.submit()"
                              type="button" name="button" value="{$APP['LBL_CANCEL_BUTTON_LABEL']}">
EOD
        ;
        $action_button_footer = array_merge($action_button_footer, $this->ss->get_template_vars('BUTTONS_FOOTER'));
        $this->ss->assign('ACTION_BUTTON_FOOTER', $action_button_footer);

        //if the request object has 'scrolltocal' set, then we are coming here from the tour window box and need to set flag to true
        // so that footer.tpl fires off script to scroll to calendar section
        if(!empty($_REQUEST['scrollToCal'])){
            $this->ss->assign('scroll_to_cal', true);
        }

        // Check for IDM mode.
        $this->ss->assign('SHOW_NON_EDITABLE_FIELDS_ALERT', $idpConfig->isIDMModeEnabled());
        if ($GLOBALS['current_user']->isAdminForModule('Users') && $this->bean->id !== $GLOBALS['current_user']->id) {
            $label = 'LBL_IDM_MODE_NON_EDITABLE_FIELDS_FOR_ADMIN_USER';
        } else {
            $label = 'LBL_IDM_MODE_NON_EDITABLE_FIELDS_FOR_REGULAR_USER';
        }
        $this->ss->assign('NON_EDITABLE_FIELDS_MSG', translate($label, 'Users'));

        $this->ev->process($processSpecial,$processFormName);

		echo $this->ev->display($this->showTitle);

    }


    /**
     * getHelpText
     *
     * This is a protected function that returns the help text portion.  It is called from getModuleTitle.
     * We override the function from SugarView.php to make sure the create link only appears if the current user
     * meets the valid criteria.
     *
     * @param $module String the formatted module name
     * @return $theTitle String the HTML for the help text
     */
    protected function getHelpText($module)
    {
        $theTitle = '';

        if($GLOBALS['current_user']->isAdminForModule('Users')
        ) {
        $createImageURL = SugarThemeRegistry::current()->getImageURL('create-record.gif');
            $url = 'index.php?' . http_build_query(
                array(
                    'module' => $module,
                    'action' => 'EditView',
                    'return_module' => $module,
                    'return_action' => 'DetailView',
                )
            );
        $theTitle = <<<EOHTML
&nbsp;
<img src='{$createImageURL}' alt='{$GLOBALS['app_strings']['LNK_CREATE']}'>
<a href="{$url}" class="utilsLink">
{$GLOBALS['app_strings']['LNK_CREATE']}
</a>
EOHTML;
        }
        return $theTitle;
    }

    /**
     * Show redirect to cloud console
     * @param string $url cloud console url
     */
    protected function showRedirectToCloudConsole($url)
    {
        $ss = new Sugar_Smarty();
        $error = string_format($GLOBALS['mod_strings']['ERR_CREATE_USER_FOR_IDM_MODE'], [$url]);
        $ss->assign("error", $error);
        $ss->display('modules/Users/tpls/errorMessage.tpl');
        sugar_cleanup(true);
    }
}
