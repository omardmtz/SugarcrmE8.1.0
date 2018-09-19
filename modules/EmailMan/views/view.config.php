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
/*********************************************************************************

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/EmailMan/Forms.php');

class ViewConfig extends SugarView
{
    /**
	 * @see SugarView::_getModuleTitleParams()
	 */
	protected function _getModuleTitleParams($browserTitle = false)
	{
	    global $mod_strings;

    	return array(
    	   "<a href='index.php?module=Administration&action=index'>".translate('LBL_MODULE_NAME','Administration')."</a>",
    	   translate('LBL_MASS_EMAIL_CONFIG_TITLE','Administration'),
    	   );
    }

    /**
	 * @see SugarView::preDisplay()
	 */
	public function preDisplay()
 	{
 	    global $current_user;

 	    if ( !is_admin($current_user)
 	            && !is_admin_for_module($GLOBALS['current_user'],'Emails')
 	            && !is_admin_for_module($GLOBALS['current_user'],'Campaigns') )
 	        sugar_die("Unauthorized access to administration.");
    }

    /**
	 * @see SugarView::display()
	 */
	public function display()
	{
        global $mod_strings;
        global $app_list_strings;
        global $app_strings;
        global $current_user;
        global $sugar_config;

        echo $this->getModuleTitle(false);
        global $currentModule;

        $focus = Administration::getSettings(); //retrieve all admin settings.
        $GLOBALS['log']->info("Mass Emailer(EmailMan) ConfigureSettings view");

        $this->ss->assign("MOD", $mod_strings);
        $this->ss->assign("APP", $app_strings);

        $this->ss->assign("RETURN_MODULE", "Administration");
        $this->ss->assign("RETURN_ACTION", "index");

        $this->ss->assign("MODULE", $currentModule);
        $this->ss->assign("HEADER", get_module_title("EmailMan", "{MOD.LBL_CONFIGURE_SETTINGS}", true));
        $this->ss->assign("notify_fromaddress", $focus->settings['notify_fromaddress']);
        $this->ss->assign("notify_send_from_assigning_user", (isset($focus->settings['notify_send_from_assigning_user']) && !empty($focus->settings['notify_send_from_assigning_user'])) ? "checked='checked'" : "");
        $this->ss->assign("notify_on", ($focus->settings['notify_on']) ? "checked='checked'" : "");
        $this->ss->assign(
            "allow_user_email_accounts",
            (empty($sugar_config['disable_user_email_config']) ? "checked" : "")
        );
        $this->ss->assign(
            'new_email_addresses_opted_out',
            empty($sugar_config['new_email_addresses_opted_out']) ? '' : 'checked'
        );
        $this->ss->assign("notify_fromname", $focus->settings['notify_fromname']);
        $this->ss->assign("notify_allow_default_outbound_on", (!empty($focus->settings['notify_allow_default_outbound']) && $focus->settings['notify_allow_default_outbound']) ? "checked='checked'" : "");

        $this->ss->assign("mail_smtptype", $focus->settings['mail_smtptype']);
        $this->ss->assign("mail_smtpserver", $focus->settings['mail_smtpserver']);
        $this->ss->assign("mail_smtpport", $focus->settings['mail_smtpport']);
        $this->ss->assign("mail_smtpuser", $focus->settings['mail_smtpuser']);
        $this->ss->assign("mail_smtpauth_req", ($focus->settings['mail_smtpauth_req']) ? "checked='checked'" : "");
        $this->ss->assign("mail_haspass", empty($focus->settings['mail_smtppass'])?0:1);
        $this->ss->assign("MAIL_SSL_OPTIONS", get_select_options_with_id($app_list_strings['email_settings_for_ssl'], $focus->settings['mail_smtpssl']));

        //Assign the current users email for the test send dialogue.
        $this->ss->assign("CURRENT_USER_EMAIL", $current_user->email1);

        $showSendMail = FALSE;
        $outboundSendTypeCSSClass = "yui-hidden";
        if(isset($sugar_config['allow_sendmail_outbound']) && $sugar_config['allow_sendmail_outbound'])
        {
            $showSendMail = TRUE;
            $app_list_strings['notifymail_sendtype']['sendmail'] = 'sendmail';
            $outboundSendTypeCSSClass = "";
        }

        $this->ss->assign("OUTBOUND_TYPE_CLASS", $outboundSendTypeCSSClass);
        $this->ss->assign("mail_sendtype_options", get_select_options_with_id($app_list_strings['notifymail_sendtype'], $focus->settings['mail_sendtype']));

        ///////////////////////////////////////////////////////////////////////////////
        ////	USER EMAIL DEFAULTS
        // editors
        $editors = $app_list_strings['dom_email_editor_option'];
        $newEditors = array();
        foreach($editors as $k => $v) {
            if($k != "") { $newEditors[$k] = $v; }
        }
        ////	END USER EMAIL DEFAULTS
        ///////////////////////////////////////////////////////////////////////////////


        //setting to manage.
        //emails_per_run
        //tracking_entities_location_type default or custom
        //tracking_entities_location http://www.sugarcrm.com/track/

        //////////////////////////////////////////////////////////////////////////////
        ////	EMAIL SECURITY
        if(!isset($sugar_config['email_xss']) || empty($sugar_config['email_xss'])) {
            $sugar_config['email_xss'] = getDefaultXssTags();
        }

        foreach(unserialize(base64_decode($sugar_config['email_xss'])) as $k => $v) {
            $this->ss->assign($k."Checked", 'CHECKED');
        }

        ////	END EMAIL SECURITY
        ///////////////////////////////////////////////////////////////////////////////

        $email = BeanFactory::newBean('Emails');
        $this->ss->assign('ROLLOVER', $email->rolloverStyle);
        $this->ss->assign('THEME', $GLOBALS['theme']);

        $this->ss->assign("JAVASCRIPT",get_validate_record_js());
        $this->ss->display('modules/EmailMan/tpls/config.tpl');
    }
}
