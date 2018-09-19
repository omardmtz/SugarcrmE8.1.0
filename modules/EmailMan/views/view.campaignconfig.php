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

class ViewCampaignconfig extends SugarView
{
    /**
	 * @see SugarView::_getModuleTitleParams()
	 */
	protected function _getModuleTitleParams($browserTitle = false)
	{
	    global $mod_strings;
	    
    	return array(
    	   "<a href='index.php?module=Administration&action=index'>".translate('LBL_MODULE_NAME','Administration')."</a>",
    	   translate('LBL_CAMPAIGN_CONFIG_TITLE','Administration'),
    	   );
    }
    
    /**
	 * @see SugarView::preDisplay()
	 */
	public function preDisplay()
 	{
 	    global $current_user, $mod_strings;
 	    
 	    if ( !is_admin($current_user)
 	            && !is_admin_for_module($GLOBALS['current_user'],'Campaigns') ) 
 	        sugar_die($mod_strings['LBL_UNAUTH_ACCESS']);
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
        
        echo $this->getModuleTitle(false);
        global $currentModule, $sugar_config;
        
        $focus = Administration::getSettings(); //retrieve all admin settings.
        $GLOBALS['log']->info("Mass Emailer(EmailMan) ConfigureSettings view");
        
        $this->ss->assign("MOD", $mod_strings);
        $this->ss->assign("APP", $app_strings);
        $this->ss->assign("THEME", SugarThemeRegistry::current()->__toString());
        $this->ss->assign("RETURN_MODULE", "Administration");
        $this->ss->assign("RETURN_ACTION", "index");
        
        $this->ss->assign("MODULE", $currentModule);

        if (isset($focus->settings['massemailer_campaign_emails_per_run']) && !empty($focus->settings['massemailer_campaign_emails_per_run'])) {
            $this->ss->assign("EMAILS_PER_RUN", $focus->settings['massemailer_campaign_emails_per_run']);
        } else  {
            $this->ss->assign("EMAILS_PER_RUN", 500);
        }
        
        if (!isset($focus->settings['massemailer_tracking_entities_location_type']) or empty($focus->settings['massemailer_tracking_entities_location_type']) or $focus->settings['massemailer_tracking_entities_location_type']=='1') {
            $this->ss->assign("default_checked", "checked");
            $this->ss->assign("TRACKING_ENTRIES_LOCATION_STATE", "disabled");
            $this->ss->assign("TRACKING_ENTRIES_LOCATION",$mod_strings['TRACKING_ENTRIES_LOCATION_DEFAULT_VALUE']);
        } else  {
            $this->ss->assign("userdefined_checked", "checked");
            $this->ss->assign("TRACKING_ENTRIES_LOCATION",$focus->settings["massemailer_tracking_entities_location"]);
        }
        $this->ss->assign("SITEURL",$sugar_config['site_url']);
        
        
        // Change the default campaign to not store a copy of each message.
        if (!empty($focus->settings['massemailer_email_copy']) and $focus->settings['massemailer_email_copy']=='1') {
            $this->ss->assign("yes_checked", "checked='checked'");
        } else  {
            $this->ss->assign("no_checked", "checked='checked'");
        }
        
        $email = BeanFactory::newBean('Emails');
        $this->ss->assign('ROLLOVER', $email->rolloverStyle);
        
        $this->ss->assign("JAVASCRIPT",get_validate_record_js());
        $this->ss->display("modules/EmailMan/tpls/campaignconfig.tpl");
    }
}
