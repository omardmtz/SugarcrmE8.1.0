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

class EmailManController extends SugarController
{
    function action_Save()
    {

        $configurator = new Configurator();
        global $sugar_config;
        global $current_user, $mod_strings;
        if (!is_admin($current_user)
                && !is_admin_for_module($GLOBALS['current_user'],'Emails')
                && !is_admin_for_module($GLOBALS['current_user'],'Campaigns')
        ){
            sugar_die($mod_strings['LBL_UNAUTH_ACCESS']);
        }

        //Do not allow users to spoof for sendmail if the config flag is not set.
        if( !isset($sugar_config['allow_sendmail_outbound']) || !$sugar_config['allow_sendmail_outbound'])
            $_REQUEST['mail_sendtype'] = "SMTP";

        // save Outbound settings  #Bug 20033 Ensure data for Outbound email exists before trying to update the system mailer.
        if(isset($_REQUEST['mail_sendtype']) && empty($_REQUEST['campaignConfig'])) {
            $oe = new OutboundEmail();
            $oe = $oe->getSystemMailerSettings();
            $oe->populateFromPost();
            $oe->saveSystem();
        }

        $focus = BeanFactory::newBean('Administration');

        if(isset($_POST['tracking_entities_location_type'])) {
            if ($_POST['tracking_entities_location_type'] != '2') {
                unset($_POST['tracking_entities_location']);
                unset($_POST['tracking_entities_location_type']);
            }
        }
        // cn: handle mail_smtpauth_req checkbox on/off (removing double reference in the form itself
        if( !isset($_POST['mail_smtpauth_req']) )
        {
            $_POST['mail_smtpauth_req'] = 0;
            if (empty($_POST['campaignConfig'])) {
                $_POST['notify_allow_default_outbound'] = 0; // If smtp auth is disabled ensure outbound is disabled.
            }
        }

        $optOutConfigName = 'new_email_addresses_opted_out';
        $configurator->config[$optOutConfigName] = isset($_REQUEST[$optOutConfigName]) && $_REQUEST[$optOutConfigName];

        $focus->saveConfig();

        // mark user metadata changed so the user preferences get refreshed
        // (user preferences contain email client preference)
        $mm = MetaDataManager::getManager();
        $mm->setUserMetadataHasChanged($current_user);

        ///////////////////////////////////////////////////////////////////////////////
        ////	SECURITY
        $security = array();
        if(isset($_REQUEST['applet'])) $security['applet'] = 'applet';
        if(isset($_REQUEST['base'])) $security['base'] = 'base';
        if(isset($_REQUEST['embed'])) $security['embed'] = 'embed';
        if(isset($_REQUEST['form'])) $security['form'] = 'form';
        if(isset($_REQUEST['frame'])) $security['frame'] = 'frame';
        if(isset($_REQUEST['frameset'])) $security['frameset'] = 'frameset';
        if(isset($_REQUEST['iframe'])) $security['iframe'] = 'iframe';
        if(isset($_REQUEST['import'])) $security['import'] = '\?import';
        if(isset($_REQUEST['layer'])) $security['layer'] = 'layer';
        if(isset($_REQUEST['link'])) $security['link'] = 'link';
        if(isset($_REQUEST['object'])) $security['object'] = 'object';
        if(isset($_REQUEST['style'])) $security['style'] = 'style';
        if(isset($_REQUEST['xmp'])) $security['xmp'] = 'xmp';
        $security['script'] = 'script';

        $configurator->config['email_xss'] = base64_encode(serialize($security));

        $configurator->config['disable_user_email_config'] = isset($_REQUEST['allow_user_email_accounts'])
            && !$_REQUEST['allow_user_email_accounts'];

        ////	SECURITY
        ///////////////////////////////////////////////////////////////////////////////

        ksort($sugar_config);

        $configurator->handleOverride();

        // Refresh the cache after we've had a chance to write to config_override.php.
        MetaDataManager::refreshSectionCache([MetaDataManager::MM_CONFIG]);
    }
}
