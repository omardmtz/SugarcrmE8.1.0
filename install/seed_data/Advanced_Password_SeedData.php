<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

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
require('config.php');
global $sugar_config;
global $timedate;
global $mod_strings;

$Team = new Team();
$Team_id = $Team->retrieve_team_id('Administrator');

//Sent when the admin generate a new password
$EmailTemp = new EmailTemplate();
$EmailTemp->name = $mod_strings['advanced_password_new_account_email']['name'];
$EmailTemp->description = $mod_strings['advanced_password_new_account_email']['description'];
$EmailTemp->subject = $mod_strings['advanced_password_new_account_email']['subject'];
$EmailTemp->body = $mod_strings['advanced_password_new_account_email']['txt_body'];
$EmailTemp->body_html = $mod_strings['advanced_password_new_account_email']['body'];
$EmailTemp->deleted = 0;

$EmailTemp->team_id = $Team_id;
$EmailTemp->published = 'off';
$EmailTemp->type = 'system';
$EmailTemp->text_only = 0;
$id =$EmailTemp->save();
$sugar_config['passwordsetting']['generatepasswordtmpl'] = $id;

//User generate a link to set a new password
$EmailTemp = new EmailTemplate();
$EmailTemp->name = $mod_strings['advanced_password_forgot_password_email']['name'];
$EmailTemp->description = $mod_strings['advanced_password_forgot_password_email']['description'];
$EmailTemp->subject = $mod_strings['advanced_password_forgot_password_email']['subject'];
$EmailTemp->body = $mod_strings['advanced_password_forgot_password_email']['txt_body'];
$EmailTemp->body_html = $mod_strings['advanced_password_forgot_password_email']['body'];
$EmailTemp->deleted = 0;

$EmailTemp->team_id = $Team_id;
$EmailTemp->published = 'off';
$EmailTemp->type = 'system';
$EmailTemp->text_only = 0;
$id =$EmailTemp->save();
$sugar_config['passwordsetting']['lostpasswordtmpl'] = $id;

// set all other default settings
$sugar_config['passwordsetting']['forgotpasswordON'] = true;
$sugar_config['passwordsetting']['SystemGeneratedPasswordON'] = true;
$sugar_config['passwordsetting']['systexpirationtime'] = 7;
$sugar_config['passwordsetting']['systexpiration'] = 1;
$sugar_config['passwordsetting']['linkexpiration'] = true;
$sugar_config['passwordsetting']['linkexpirationtime'] = 24;
$sugar_config['passwordsetting']['linkexpirationtype'] = 60;
$sugar_config['passwordsetting']['minpwdlength'] = 6;
$sugar_config['passwordsetting']['oneupper'] = true;
$sugar_config['passwordsetting']['onelower'] = true;
$sugar_config['passwordsetting']['onenumber'] = true;

write_array_to_file("sugar_config", $sugar_config, "config.php");
