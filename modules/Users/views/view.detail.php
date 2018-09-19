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

class UsersViewDetail extends ViewDetail {
    function preDisplay() {
        global $current_user, $app_strings, $sugar_config;

        if(!isset($this->bean->id) ) {
            // No reason to set everything up just to have it fail in the display() call
            return;
        }

        $idpConfig = new IdmConfig(\SugarConfig::getInstance());

        if (!$current_user->isAdminForModule('Users') && !$current_user->isDeveloperForModule('Users') &&
        $this->bean->id != $current_user->id) {
            ACLController::displayNoAccess(true);
            sugar_cleanup(true);
        }

        parent::preDisplay();

        $viewHelper = UserViewHelper::create($this->ss, $this->bean, 'DetailView');
        $viewHelper->setupAdditionalFields();

        $errors = "";
        $msgGood = false;
        if (isset($_SESSION['new_pwd']) && $_SESSION['new_pwd']!= 0){
            if ($_SESSION['new_pwd']=='4'){
                require_once('modules/Users/password_utils.php');
                $errors.=canSendPassword();
            }
            else {
                $errors .= translate('LBL_NEW_USER_PASSWORD_' . $_SESSION['new_pwd'], 'Users');
                $msgGood = true;
            }
            unset($_SESSION['new_pwd']);
        }else{
            //IF BEAN USER IS LOCKOUT
            if($this->bean->getPreference('lockout')=='1') {
                $errors.=translate('ERR_USER_IS_LOCKED_OUT','Users');
            }
        }
        $this->ss->assign("ERRORS", $errors);
        $this->ss->assign("ERROR_MESSAGE", $msgGood ? translate('LBL_PASSWORD_SENT','Users') : translate('LBL_CANNOT_SEND_PASSWORD','Users'));
        $buttons = array();
        if ((is_admin($current_user) || $_REQUEST['record'] == $current_user->id
                )
            && !empty($sugar_config['default_user_name'])
            && $sugar_config['default_user_name'] == $this->bean->user_name
            && isset($sugar_config['lock_default_user_name'])
            && $sugar_config['lock_default_user_name']) {
            $buttons[] = "<input id='edit_button' accessKey='".$app_strings['LBL_EDIT_BUTTON_KEY']."' name='Edit' title='".$app_strings['LBL_EDIT_BUTTON_TITLE']."' value='".$app_strings['LBL_EDIT_BUTTON_LABEL']."' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='DetailView'; this.form.return_id.value='".$this->bean->id."'; this.form.action.value='EditView'\" type='submit' value='" . $app_strings['LBL_EDIT_BUTTON_LABEL'] .  "'>";

        }
        elseif (is_admin($current_user)|| ($GLOBALS['current_user']->isAdminForModule('Users')&& !$this->bean->is_admin)
                || $_REQUEST['record'] == $current_user->id) {
            $buttons[] = "<input title='".$app_strings['LBL_EDIT_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_EDIT_BUTTON_KEY']."' name='Edit' id='edit_button' value='".$app_strings['LBL_EDIT_BUTTON_LABEL']."' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='DetailView'; this.form.return_id.value='".$this->bean->id."'; this.form.action.value='EditView'\" type='submit' value='" . $app_strings['LBL_EDIT_BUTTON_LABEL'] .  "'>";
            if ((is_admin($current_user)|| $GLOBALS['current_user']->isAdminForModule('Users')
                    )) {
                if (!$current_user->is_group && !$idpConfig->isIDMModeEnabled()) {
                    $buttons[] = "<input id='duplicate_button' title='".$app_strings['LBL_DUPLICATE_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_DUPLICATE_BUTTON_KEY']."' class='button' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='DetailView'; this.form.isDuplicate.value=true; this.form.action.value='EditView'\" type='submit' name='Duplicate' value='".$app_strings['LBL_DUPLICATE_BUTTON_LABEL']."'>";

                    if($this->bean->id != $current_user->id) {
                        $buttons[] ="<input id='delete_button' type='button' class='button' onclick='confirmDelete();' value='".$app_strings['LBL_DELETE_BUTTON_LABEL']."' />";
                    }

                    if (!$this->bean->portal_only && !$this->bean->is_group && !$this->bean->external_auth_only
                        && isset($sugar_config['passwordsetting']['SystemGeneratedPasswordON']) && $sugar_config['passwordsetting']['SystemGeneratedPasswordON']){
                        $buttons[] = "<input title='".translate('LBL_GENERATE_PASSWORD_BUTTON_TITLE','Users')."' class='button' LANGUAGE=javascript onclick='generatepwd(\"".$this->bean->id."\");' type='button' name='password' value='".translate('LBL_GENERATE_PASSWORD_BUTTON_LABEL','Users')."'>";
                    }
                }
            }
        }

        $buttons = array_merge($buttons, $this->ss->get_template_vars('BUTTONS_HEADER'));

        $this->ss->assign('EDITBUTTONS',$buttons);

        $show_roles = (!($this->bean->is_group=='1' || $this->bean->portal_only=='1'));
        $this->ss->assign('SHOW_ROLES', $show_roles);
        //Mark whether or not the user is a group or portal user
        $this->ss->assign('IS_GROUP_OR_PORTAL', ($this->bean->is_group=='1' || $this->bean->portal_only=='1') ? true : false);
        if ( $show_roles ) {
            ob_start();
            echo "<div>";
            require_once('modules/ACLRoles/DetailUserRole.php');
            echo "</div></div>";

            $file = SugarAutoLoader::loadExtension("userpage");
            if($file) {
                include $file;
            }

            $role_html = ob_get_contents();
            ob_end_clean();
            $this->ss->assign('ROLE_HTML',$role_html);
        }
        
        // Tell the template to render the javascript that requests new metadata
        // after a user preference change
        $this->ss->assign('refreshMetadata', !empty($_REQUEST['refreshMetadata']));

    }

    /**
     * {@inheritDoc}
     *
     * @param string $type Ignored
     */
    public function getMetaDataFile($type = null)
    {
        $userType = 'Regular';
        if($this->bean->portal_only == 1){
            $userType = 'Portal';
        }
        if($this->bean->is_group == 1){
            $userType = 'Group';
        }

        return parent::getMetaDataFile(($userType != 'Regular') ? $this->type . 'group' : null);
    }

    function display() {
        if ($this->bean->portal_only == 1 || $this->bean->is_group == 1 ) {
            $this->options['show_subpanels'] = false;
            $this->dv->formName = 'DetailViewGroup';
            $this->dv->view = 'DetailViewGroup';
        }

        return parent::display();
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

}
