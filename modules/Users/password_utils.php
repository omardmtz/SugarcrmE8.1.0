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

 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/


function canSendPassword() {
    global $mod_strings,
           $current_user,
           $app_strings;


    if ($current_user->is_admin) {
        $emailTemplate                             = new EmailTemplate();
        $emailTemplate->disable_row_level_security = true;

        if ($emailTemplate->retrieve($GLOBALS['sugar_config']['passwordsetting']['generatepasswordtmpl']) == '') {
            return $mod_strings['LBL_EMAIL_TEMPLATE_MISSING'];
        }

        if (empty($emailTemplate->body) && empty($emailTemplate->body_html)) {
            return $app_strings['LBL_EMAIL_TEMPLATE_EDIT_PLAIN_TEXT'];
        }

        if (!OutboundEmailConfigurationPeer::validSystemMailConfigurationExists($current_user)) {
            return $mod_strings['ERR_SERVER_SMTP_EMPTY'];
        }

        $emailErrors = $mod_strings['ERR_EMAIL_NOT_SENT_ADMIN'];

        try {
            $config = OutboundEmailConfigurationPeer::getSystemDefaultMailConfiguration();

            if ($config instanceof OutboundSmtpEmailConfiguration) {
                $emailErrors .= "<br>-{$mod_strings['ERR_SMTP_URL_SMTP_PORT']}";

                if ($config->isAuthenticationRequired()) {
                    $emailErrors .= "<br>-{$mod_strings['ERR_SMTP_USERNAME_SMTP_PASSWORD']}";
                }
            }
        } catch (MailerException $me) {
            // might want to report the error
        }

        $emailErrors .= "<br>-{$mod_strings['ERR_RECIPIENT_EMAIL']}";
        $emailErrors .= "<br>-{$mod_strings['ERR_SERVER_STATUS']}";

        return $emailErrors;
    }

    return $mod_strings['LBL_EMAIL_NOT_SENT'];
}

/**
 * Check if password has expired.
 * @param {User|string} $user
 * @return Boolean indicating if password is expired or not
 */
function hasPasswordExpired($user, $updateNumberLogins = false)
{
    if (!$user instanceof User) {
        $usr_id = BeanFactory::newBean('Users')->retrieve_user_id($user);
        $user = BeanFactory::getBean('Users', $usr_id);
    }
    $type = '';
	if ($user->system_generated_password == '1'){
        $type='syst';
    }
    else{
        $type='user';
    }

    if ($user->portal_only=='0'){
	    $res=$GLOBALS['sugar_config']['passwordsetting'];

	  	if ($type != '') {
		    switch($res[$type.'expiration']) {
	        case '1':
		    	global $timedate;
                    if ($user->pwd_last_changed == '') {
                        $user->pwd_last_changed= $timedate->nowDb();
                        //Suppress date_modified so a new _hash isn't generated
                        $user->update_date_modified = false;
                        $user->save();
                    }
                    // Date-time field may be in DB format if save occurred on previous calls.
                    $dbFormat = $timedate->get_db_date_time_format();
                    if ($timedate->check_matching_format($user->pwd_last_changed, $dbFormat)) {
                        $pass_changed_timestamp = $timedate->fromDb($user->pwd_last_changed);
                    } else {
                        $pass_changed_timestamp = $timedate->fromUser($user->pwd_last_changed, $user);
                    }
                // SP-1790: Creating user with default password expiration settings results in password expired page on first login
                // Below, we calc $expireday essentially doing type*time; that requires that expirationtype factor is 1 or
                // greater, however, expirationtype defaults to following values: 0/day, 7/week, 30/month
                // (See and {debug} PasswordManager.tpl for more info)
                $expiretype = $res[$type.'expirationtype'];
                $expiretype = (!isset($expiretype) || $expiretype == '0') ? '1' : $expiretype;
                $expireday = $expiretype * $res[$type.'expirationtime'];
                    $expiretime = $pass_changed_timestamp->get("+{$expireday} days")->ts;

                if ($timedate->getNow()->ts < $expiretime) {
                    return false;
                } else {
                    $_SESSION['expiration_label']= 'LBL_PASSWORD_EXPIRATION_TIME';
                    return true;
                }
                break;

		    case '2':
		    	$login = $user->getPreference('loginexpiration');
                //Only increment number of logins if we're actually doing an update
                if ($updateNumberLogins) {
                    $login = $login + 1;
                    $user->setPreference('loginexpiration',$login);
                    //Suppress date_modified so a new _hash isn't generated
                    $user->update_date_modified = false;
                    $user->save();
                }
		        if ($login >= $res[$type.'expirationlogin']){
		        	$_SESSION['expiration_label']= 'LBL_PASSWORD_EXPIRATION_LOGIN';
		        	return true;
                }
                else {
                    return false;
                }
                break;

		    case '0':
		        return false;
		   	 	break;
		    }
		}
    }
}
