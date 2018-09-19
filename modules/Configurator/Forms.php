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

 * Description:  Contains a variety of utility functions specific to this module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

function get_configsettings_js() {
	global $mod_strings;
	global $app_strings;

	$lbl_last_name = $mod_strings['LBL_NOTIFY_FROMADDRESS'];
	$err_missing_required_fields = $app_strings['ERR_MISSING_REQUIRED_FIELDS'];

	return <<<EOQ

<script type="text/javascript" language="Javascript">
<!--  to hide script contents from old browsers

function notify_setrequired(f) {

	return true;
}

function add_checks(f) {
	removeFromValidate('ConfigureSettings', 'mail_smtpserver');
	removeFromValidate('ConfigureSettings', 'mail_smtpport');
	removeFromValidate('ConfigureSettings', 'mail_smtpuser');
	removeFromValidate('ConfigureSettings', 'mail_smtppass');
	
	removeFromValidate('ConfigureSettings', 'proxy_port');
	removeFromValidate('ConfigureSettings', 'proxy_host');
	removeFromValidate('ConfigureSettings', 'proxy_username');
	removeFromValidate('ConfigureSettings', 'proxy_password');
	
	removeFromValidate('ConfigureSettings', 'list_max_entries_per_page');
	removeFromValidate('ConfigureSettings', 'list_max_entries_per_subpanel');	

	removeFromValidate('ConfigureSettings', 'logger_file_name');
	removeFromValidate('ConfigureSettings', 'logger_file_ext');
	removeFromValidate('ConfigureSettings', 'logger_file_maxSize');
	removeFromValidate('ConfigureSettings', 'logger_file_maxLogs');


	if (typeof f.mail_sendtype != "undefined" && f.mail_sendtype.value == "SMTP") {
		addToValidate('ConfigureSettings', 'mail_smtpserver', 'varchar', 'true', '{$mod_strings['LBL_MAIL_SMTPSERVER']}');
		addToValidate('ConfigureSettings', 'mail_smtpport', 'int', 'true', '{$mod_strings['LBL_MAIL_SMTPPORT']}');
		if (f.mail_smtpauth_req.checked) {
			addToValidate('ConfigureSettings', 'mail_smtpuser', 'varchar', 'true', '{$mod_strings['LBL_MAIL_SMTPUSER']}');
			addToValidate('ConfigureSettings', 'mail_smtppass', 'varchar', 'true', '{$mod_strings['LBL_MAIL_SMTPPASS']}');
		}
	}
	
	if (typeof f.proxy_on != "undefined" && f.proxy_on.checked ) {
		addToValidate('ConfigureSettings', 'proxy_port', 'int', 'true', '{$mod_strings['LBL_PROXY_PORT']}');
		addToValidate('ConfigureSettings', 'proxy_host', 'varchar', 'true', '{$mod_strings['LBL_PROXY_HOST']}');
		if (f.proxy_auth.checked ) {
			addToValidate('ConfigureSettings', 'proxy_username', 'varchar', 'true', '{$mod_strings['LBL_PROXY_USERNAME']}');
			addToValidate('ConfigureSettings', 'proxy_password', 'varchar', 'true', '{$mod_strings['LBL_PROXY_PASSWORD']}');
		}
	}
	
	addToValidateMoreThan('ConfigureSettings', 'list_max_entries_per_page', 'int', true, '', 1);
	addToValidateMoreThan('ConfigureSettings', 'list_max_entries_per_subpanel', 'int', true, '', 1);

	addToValidate('ConfigureSettings', 'logger_file_name', 'varchar', 'true', '{$mod_strings['LBL_LOGGER_FILENAME']}');
	addToValidate('ConfigureSettings', 'logger_file_ext', 'varchar', 'true', '{$mod_strings['LBL_LOGGER_FILE_EXTENSION']}');
	addToValidate('ConfigureSettings', 'logger_file_maxSize', 'varchar', 'true', '{$mod_strings['LBL_LOGGER_MAX_LOG_SIZE']}');
	addToValidateMoreThan('ConfigureSettings', 'logger_file_maxLogs', 'int', true, '', 1);
	
	return true;
}

notify_setrequired(document.ConfigureSettings);

// end hiding contents from old browsers  -->
</script>

EOQ;
}


?>
