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

if (!function_exists('verifyAndCleanup')) {
    /**
     * Verifies the given user's data, sends the result as JSON, and then exits
     * @param User $user The user whose data you want to verify
     */
    function verifyAndCleanup($user)
    {
        $status = $user->verify_data();
        $data = array(
            'status' => $status,
            'error_string' => $user->error_string,
        );
        header('Content-Type: application/json');
        echo json_encode($data);
        sugar_cleanup(true);
    }
}

$display_tabs_def = isset($_REQUEST['display_tabs_def']) ? html_entity_decode($_REQUEST['display_tabs_def']) : '';
$hide_tabs_def = isset($_REQUEST['hide_tabs_def']) ? html_entity_decode($_REQUEST['hide_tabs_def']): '';
$remove_tabs_def = isset($_REQUEST['remove_tabs_def']) ? html_entity_decode($_REQUEST['remove_tabs_def']): '';

$DISPLAY_ARR = array();
$HIDE_ARR = array();
$REMOVE_ARR = array();

parse_str($display_tabs_def, $DISPLAY_ARR);
parse_str($hide_tabs_def, $HIDE_ARR);
parse_str($remove_tabs_def, $REMOVE_ARR);

if (isset($_POST['id'])) {
    sugar_die('Unauthorized access to administration.');
}
if (isset($_POST['record']) && !is_admin($current_user) &&
     !$GLOBALS['current_user']->isAdminForModule('Users') &&
     $_POST['record'] != $current_user->id
) {
    sugar_die('Unauthorized access to administration.');
} elseif (!isset($_POST['record']) && !is_admin($current_user) &&
     !$GLOBALS['current_user']->isAdminForModule('Users')) {
    sugar_die('Unauthorized access to user administration.');
}

$focus = BeanFactory::getBean('Users', $_POST['record']);
if (!$focus->id) {
    // generate ID for the newly created user in order to be able to save preferences
    // before the bean is saved
    $focus->id = create_guid();
    $focus->new_with_id = true;
}

//update any ETag seeds that are tied to the user object changing
$focus->incrementETag('mainMenuETag');

// [BR-200] Set the reauth forcing array of fields now for comparison later
$userApi = new CurrentUserApi;
$reauthFields = array_keys($userApi->getUserPrefsToCache());
$currentReauthPrefs = array();
foreach ($reauthFields as $field) {
    $currentReauthPrefs[$field] = $focus->getPreference($field);
}

// Flag to determine whether to save a new password or not.
// Bug 43241 - Changed $focus->id to $focus->user_name to make sure that a
// system generated password is made when converting employee to user
if (empty($focus->user_name)) {
    $newUser = true;
    // C.L. Bug 48898 - Clear the user_array register value that may be cached to
    // resolve the get_assigned_user_name function calls when create new user
    clear_register_value('user_array', $focus->object_name);
} else {
    $newUser = false;
}

if (!$current_user->is_admin && !$GLOBALS['current_user']->isAdminForModule('Users')) {
    if ($current_user->id != $focus->id || !empty($_POST['is_admin']) ||
        (!empty($_POST['UserType']) && $_POST['UserType'] == 'Administrator') ||
        (!$newUser && !empty($_POST['user_name']) && $_POST['user_name'] != $focus->user_name)
    ) {
        $GLOBALS['log']->fatal("SECURITY:Non-Admin " . $current_user->id .
            " attempted to change settings for user:". $focus->id);
        header("Location: index.php?module=Users&action=Logout");
        exit;
    }
}

// Populate the custom fields
$sfh = new SugarFieldHandler();
foreach ($focus->field_defs as $fieldName => $field) {
    if (isset($field['source']) && $field['source'] == 'custom_fields') {
        $type = !empty($field['custom_type']) ? $field['custom_type'] : $field['type'];
        $sf = $sfh->getSugarField($type);
        if ($sf != null) {
            $sf->save($focus, $_POST, $fieldName, $field, '');
        } else {
            $GLOBALS['log']->fatal("Field '$fieldName' does not have a SugarField handler");
        }
    }
}

$teamSetField = new SugarFieldTeamset('Teamset');
if (!$newUser && $teamSetField != null) {
    $teamSetField->save($focus, $_POST, 'team_name', '');
}

$portal = array('user_name', 'last_name', 'status', 'portal_only');
$group = array('user_name', 'last_name', 'status', 'is_group');
if (isset($_POST['portal_only']) && ($_POST['portal_only'] == '1' || $focus->portal_only)) {
    foreach ($portal as $field) {
        if (isset($_POST[$field])) {
            $value = $_POST[$field];
            $focus->$field = $value;
        }
    }
}

if (isset($_POST['is_group']) && ($_POST['is_group'] == '1' || $focus->is_group)) {
    foreach ($group as $field) {
        if (isset($_POST[$field])) {
            $value = $_POST[$field];
            $focus->$field = $value;
        }
    }
}

// copy the group or portal user name over.  We renamed the field in order to
// ensure auto-complete would not change the value
if (isset($_POST['user_name'])) {
    $focus->user_name = $_POST['user_name'];
}

// if the user saved is a Regular User
if (!$focus->is_group && !$focus->portal_only) {
    foreach ($focus->column_fields as $fieldName) {
        $field = $focus->field_defs[$fieldName];
        $type = !empty($field['custom_type']) ? $field['custom_type'] : $field['type'];
        $sf = $sfh->getSugarField($type);
        if ($sf != null) {
            $sf->save($focus, $_POST, $fieldName, $field, '');
        } else {
            $GLOBALS['log']->fatal("Field '$fieldName' does not have a SugarField handler");
        }
    }
    foreach ($focus->additional_column_fields as $fieldName) {
        $field = $focus->field_defs[$fieldName];
        $type = !empty($field['custom_type']) ? $field['custom_type'] : $field['type'];
        $sf = $sfh->getSugarField($type);
        if ($sf != null) {
            $sf->save($focus, $_POST, $fieldName, $field, '');
        } else {
            $GLOBALS['log']->fatal("Field '$fieldName' does not have a SugarField handler");
        }
    }

    $focus->is_group = 0;
    $focus->portal_only = 0;

    if (isset($_POST['user_name'])) {
        $focus->user_name = $_POST['user_name'];
    }

    // change user type to/from admin if desired
    if ((isset($_POST['is_admin']) && ($_POST['is_admin'] == 'on' || $_POST['is_admin'] == '1')) ||
       (isset($_POST['UserType']) && $_POST['UserType'] == "Administrator")
    ) {
        $focus->setAdmin(true);
    } elseif (isset($_POST['is_admin']) && empty($_POST['is_admin'])) {
        $focus->setAdmin(false);
    }

    if (empty($_POST['receive_notifications'])) {
        $focus->receive_notifications = 0;
    }

    if (isset($_POST['mailmerge_on']) && !empty($_POST['mailmerge_on'])) {
        $focus->setPreference('mailmerge_on', 'on', 0, 'global');
    } else {
        $focus->setPreference('mailmerge_on', 'off', 0, 'global');
    }

    if (!empty($only_verify_data)) {
        verifyAndCleanup($focus);
    }

    if (isset($_POST['user_swap_last_viewed'])) {
        $focus->setPreference('swap_last_viewed', $_POST['user_swap_last_viewed'], 0, 'global');
    } else {
        $focus->setPreference('swap_last_viewed', '', 0, 'global');
    }

    if (isset($_POST['user_swap_shortcuts'])) {
        $focus->setPreference('swap_shortcuts', $_POST['user_swap_shortcuts'], 0, 'global');
    } else {
        $focus->setPreference('swap_shortcuts', '', 0, 'global');
    }

    if (isset($_POST['user_theme'])) {
        $focus->setPreference('user_theme', $_POST['user_theme'], 0, 'global');
        $_SESSION['authenticated_user_theme'] = $_POST['user_theme'];
    }

    if (isset($_POST['user_module_favicon'])) {
        $focus->setPreference('module_favicon', $_POST['user_module_favicon'], 0, 'global');
    } else {
        $focus->setPreference('module_favicon', '', 0, 'global');
    }

    // BR-237 Force a reauth for user metadata changes so that these changes
    // are picked up by clients immediately
    $refreshMetadata = false;
    $tabs = new TabController();

    // Get the current display tabs to see if any of them are different
    $curTabs = $tabs->get_tabs($current_user);
    $curDisplay = array_keys($curTabs[0]);

    if (isset($DISPLAY_ARR['display_tabs'])) {
        //Put home back in.  It needs to be first display module in Sugar 7
        array_unshift($DISPLAY_ARR['display_tabs'], 'Home');

        // Order is relevant on display modules, use identical (===) comparison
        // If DISPLAY_ARR changed, so did HIDE_ARR
        // Save tabs only if there are changes
        if (array_values($DISPLAY_ARR['display_tabs']) !== array_values($curDisplay)) {
            $refreshMetadata = true;
            $tabs->set_user_tabs($DISPLAY_ARR['display_tabs'], $focus, 'display');
        }
    }

    if ($current_user->isAdminForModule('Users') || $tabs->get_users_can_edit()) {
        if (isset($HIDE_ARR['hide_tabs'])) {
            $tabs->set_user_tabs($HIDE_ARR['hide_tabs'], $focus, 'hide');
        } else {
            $tabs->set_user_tabs(array(), $focus, 'hide');
        }
    }

    if (is_admin($current_user)) {
        if (isset($REMOVE_ARR['remove_tabs'])) {
            $tabs->set_user_tabs($REMOVE_ARR['remove_tabs'], $focus, 'remove');
        } else {
            $tabs->set_user_tabs(array(), $focus, 'remove');
        }
    }

    // Always set this preference to 'off' as we are not using it any more
    $focus->setPreference('no_opps', 'off', 0, 'global');

    if (isset($_POST['reminder_checked']) && $_POST['reminder_checked'] == '1' &&
        isset($_POST['reminder_checked'])) {
        $focus->setPreference('reminder_time', $_POST['reminder_time'], 0, 'global');
    } else {
        // cn: bug 5522, need to unset reminder time if unchecked.
        $focus->setPreference('reminder_time', -1, 0, 'global');
    }

    if (isset($_POST['email_reminder_checked']) && $_POST['email_reminder_checked'] == '1' &&
        isset($_POST['email_reminder_checked'])) {
        $focus->setPreference('email_reminder_time', $_POST['email_reminder_time'], 0, 'global');
    } else {
        $focus->setPreference('email_reminder_time', -1, 0, 'global');
    }
    if (isset($_POST['timezone'])) {
        $focus->setPreference('timezone', $_POST['timezone'], 0, 'global');
    }
    if (isset($_POST['ut'])) {
        $focus->setPreference('ut', '0', 0, 'global');
    } else {
        $focus->setPreference('ut', '1', 0, 'global');
    }
    if (isset($_POST['currency'])) {
        $focus->setPreference('currency', $_POST['currency'], 0, 'global');
    }
    if (isset($_POST['default_currency_significant_digits'])) {
        $focus->setPreference(
            'default_currency_significant_digits',
            $_POST['default_currency_significant_digits'],
            0,
            'global'
        );
    }
    $focus->setPreference('currency_show_preferred', isset($_POST['currency_show_preferred']), false, 'global');
    if (isset($_POST['num_grp_sep'])) {
        $focus->setPreference('num_grp_sep', $_POST['num_grp_sep'], 0, 'global');
    }
    if (isset($_POST['dec_sep'])) {
        $focus->setPreference('dec_sep', $_POST['dec_sep'], 0, 'global');
    }
    if (isset($_POST['fdow'])) {
        $focus->setPreference('fdow', $_POST['fdow'], 0, 'global');
    }
    if (isset($_POST['dateformat'])) {
        $focus->setPreference('datef', $_POST['dateformat'], 0, 'global');
    }
    if (isset($_POST['timeformat'])) {
        $focus->setPreference('timef', $_POST['timeformat'], 0, 'global');
    }
    if (isset($_POST['timezone'])) {
        $focus->setPreference('timezone', $_POST['timezone'], 0, 'global');
    }
    if (isset($_POST['mail_fromname'])) {
        $focus->setPreference('mail_fromname', $_POST['mail_fromname'], 0, 'global');
    }
    if (isset($_POST['mail_fromaddress'])) {
        $focus->setPreference('mail_fromaddress', $_POST['mail_fromaddress'], 0, 'global');
    }
    if (isset($_POST['mail_sendtype'])) {
        $focus->setPreference('mail_sendtype', $_POST['mail_sendtype'], 0, 'global');
    }
    if (isset($_POST['mail_smtpserver'])) {
        $focus->setPreference('mail_smtpserver', $_POST['mail_smtpserver'], 0, 'global');
    }
    if (isset($_POST['mail_smtpport'])) {
        $focus->setPreference('mail_smtpport', $_POST['mail_smtpport'], 0, 'global');
    }
    if (isset($_POST['mail_smtpuser'])) {
        $focus->setPreference('mail_smtpuser', $_POST['mail_smtpuser'], 0, 'global');
    }
    if (isset($_POST['mail_smtppass'])) {
        $focus->setPreference('mail_smtppass', $_POST['mail_smtppass'], 0, 'global');
    }
    if (isset($_POST['default_locale_name_format'])) {
        $focus->setPreference('default_locale_name_format', $_POST['default_locale_name_format'], 0, 'global');
    }
    if (isset($_POST['export_delimiter'])) {
        $focus->setPreference('export_delimiter', $_POST['export_delimiter'], 0, 'global');
    }
    if (isset($_POST['default_export_charset'])) {
        $focus->setPreference('default_export_charset', $_POST['default_export_charset'], 0, 'global');
    }
    if (isset($_POST['use_real_names'])) {
        $focus->setPreference('use_real_names', 'on', 0, 'global');
    } elseif (!isset($_POST['use_real_names']) && !isset($_POST['from_dcmenu'])) {
        // Make sure we're on the full form and not the QuickCreate.
        $focus->setPreference('use_real_names', 'off', 0, 'global');
    }

    if (isset($_POST['mail_smtpauth_req'])) {
        $focus->setPreference('mail_smtpauth_req', $_POST['mail_smtpauth_req'], 0, 'global');
    } else {
        $focus->setPreference('mail_smtpauth_req', '', 0, 'global');
    }

    // SSL-enabled SMTP connection
    if (isset($_POST['mail_smtpssl'])) {
        $focus->setPreference('mail_smtpssl', 1, 0, 'global');
    } else {
        $focus->setPreference('mail_smtpssl', 0, 0, 'global');
    }
    ///////////////////////////////////////////////////////////////////////////
    ////    PDF SETTINGS
    foreach ($_POST as $k => $v) {
        if (strpos($k, "sugarpdf_pdf") !== false) {
            $focus->setPreference($k, $v, 0, 'global');
        }
    }
    ////    END PDF SETTINGS
    ///////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////////////////////////////////////
    ////	SIGNATURES
    if (isset($_POST['signature_id'])) {
        $focus->setPreference('signature_default', $_POST['signature_id'], 0, 'global');
    }

    if (isset($_POST['signature_prepend'])) {
        $focus->setPreference('signature_prepend', $_POST['signature_prepend'], 0, 'global');
    }
    ////	END SIGNATURES
    ///////////////////////////////////////////////////////////////////////////


    if (isset($_POST['email_link_type'])) {
        $focus->setPreference('email_link_type', $_REQUEST['email_link_type']);
    }
    if (isset($_REQUEST['email_show_counts'])) {
        $focus->setPreference('email_show_counts', $_REQUEST['email_show_counts'], 0, 'global');
    } else {
        $focus->setPreference('email_show_counts', 0, 0, 'global');
    }
    if (isset($_REQUEST['email_editor_option'])) {
        $focus->setPreference('email_editor_option', $_REQUEST['email_editor_option'], 0, 'global');
    }
    if (isset($_REQUEST['default_email_charset'])) {
        $focus->setPreference('default_email_charset', $_REQUEST['default_email_charset'], 0, 'global');
    }

    if (isset($_POST['calendar_publish_key'])) {
        $focus->setPreference(
            'calendar_publish_key',
            SugarCleaner::stripTags($_POST['calendar_publish_key'], false),
            0,
            'global'
        );
    }
} elseif (!empty($only_verify_data) && ($focus->is_group || $focus->portal_only)) {
    // provide the only-verify option for groups and portal users too
    verifyAndCleanup($focus);
}

if (!$focus->verify_data()) {
    header(
        "Location: index.php?action=EditView&module=Users&isDuplicate=true&record=".$_REQUEST['return_id'].
        "&error_string=".urlencode($focus->error_string)
    );
    exit;
} else {
    // Handle setting of the metadata change for this user
    if (!$refreshMetadata) {
        foreach ($currentReauthPrefs as $key => $val) {
            if ($focus->getPreference($key) != $val) {
                $refreshMetadata = true;
                break;
            }
        }
    }

    // [BR-200] Force reauth so user pref metadata is refreshed
    if ($refreshMetadata) {
        // This will more than likely already be true, but force it to be sure
        $focus->update_date_modified = true;
    }

    $GLOBALS['sugar_config']['disable_team_access_check'] = true;
    $focus->save();
    $GLOBALS['sugar_config']['disable_team_access_check'] = false;

    $return_id = $focus->id;
    $ieVerified = true;

    global $new_pwd;
    $new_pwd = '';
    if ((isset($_POST['old_password']) || $focus->portal_only) &&
        (isset($_POST['new_password']) && !empty($_POST['new_password'])) &&
        (isset($_POST['password_change']) && $_POST['password_change'] == 'true')) {
        if (!$focus->change_password(
            html_entity_decode($_POST['old_password']),
            html_entity_decode($_POST['new_password'])
        )) {
            if ((isset($_POST['page']) && $_POST['page'] == 'EditView')) {
                header("Location: index.php?action=EditView&module=Users&record=" . $_POST['record'] .
                    "&error_password=" . urlencode($focus->error_string));
                exit;
            }
            if ((isset($_POST['page']) && $_POST['page'] == 'Change')) {
                header("Location: index.php?action=ChangePassword&module=Users&record=" . $_POST['record'] .
                    "&error_password=" . urlencode($focus->error_string));
                exit;
            }
        } else {
            if ($newUser) {
                $new_pwd = '3';
            } else {
                $new_pwd = '1';
            }
        }
    }

    ///////////////////////////////////////////////////////////////////////////
    ////	OUTBOUND EMAIL SAVES
    ///////////////////////////////////////////////////////////////////////////

    // FIXME: this variable name is NSFW. See BR-3358
    $sysOutboundAccunt = new OutboundEmail();

    // If a user is not allowed to use the default system outbound account then they will be
    // saving their own username/password for the system account
    if (! $sysOutboundAccunt->isAllowUserAccessToSystemDefaultOutbound()) {
        $userOverrideOE = $sysOutboundAccunt->getUsersMailerForSystemOverride($focus->id);
        if ($userOverrideOE != null) {
            // User is allowed to clear username and pass so no need to check for blanks.
            if (isset($_REQUEST['mail_smtpuser'])) {
                $userOverrideOE->mail_smtpuser = $_REQUEST['mail_smtpuser'];
            }

            if (isset($_REQUEST['mail_smtppass'])) {
                $userOverrideOE->mail_smtppass = $_REQUEST['mail_smtppass'];
            }

            $userOverrideOE->populateFromUser($focus);
            $userOverrideOE->save();
        } else {
            // If a user name and password for the mail account is set, create the users override account.
            if (!(empty($_REQUEST['mail_smtpuser']) || empty($_REQUEST['mail_smtppass']))) {
                $sysOutboundAccunt->createUserSystemOverrideAccount(
                    $focus->id,
                    $_REQUEST['mail_smtpuser'],
                    $_REQUEST['mail_smtppass']
                );
            }
        }
    }


    ///////////////////////////////////////////////////////////////////////////
    ////	INBOUND EMAIL SAVES
    if (isset($_REQUEST['server_url']) && !empty($_REQUEST['server_url'])) {
        $ie = BeanFactory::newBean('InboundEmail');
        $ie->disable_row_level_security = true;
        if (false === $ie->savePersonalEmailAccount($return_id, $focus->user_name)) {
            header("Location: index.php?action=Error&module=Users&error_string=&ie_error=true&id=" . $return_id);
            die(); // die here, else the header redirect below takes over.
        }
    } elseif (isset($_REQUEST['ie_id']) && !empty($_REQUEST['ie_id']) && empty($_REQUEST['server_url'])) {
        // user is deleting their I-E

        $ie = BeanFactory::newBean('InboundEmail');
        $ie->disable_row_level_security = true;
        $ie->deletePersonalEmailAccount($_REQUEST['ie_id'], $focus->user_name);
    }
    ////	END INBOUND EMAIL SAVES
    ///////////////////////////////////////////////////////////////////////////
    if (($newUser) && !($focus->is_group) && !($focus->portal_only) &&
        isset($sugar_config['passwordsetting']['SystemGeneratedPasswordON']) &&
        $sugar_config['passwordsetting']['SystemGeneratedPasswordON']
    ) {
        $new_pwd = '2';
        require_once 'modules/Users/GeneratePassword.php';
    }
}

//handle navigation from user wizard
if (isset($_REQUEST['whatnext'])) {
    if ($_REQUEST['whatnext']== 'import') {
        header("Location:index.php?module=Import&action=step1&import_module=Administration");
        return;
    } elseif ($_REQUEST['whatnext']== 'users') {
        header("Location:index.php?module=Users&action=index");
        return;
    } elseif ($_REQUEST['whatnext']== 'settings') {
        header("Location:index.php?module=Configurator&action=EditView");
        return;
    } elseif ($_REQUEST['whatnext']== 'studio') {
        header("Location:index.php?module=ModuleBuilder&action=index&type=studio");
        return;
    } else {
        //do nothing, let the navigation continue as normal using code below
    }
}

if (isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") {
    $return_module = $_REQUEST['return_module'];
} else {
    $return_module = "Users";
}
if (isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") {
    $return_action = $_REQUEST['return_action'];
} else {
    $return_action = "DetailView";
}
if (isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "" &&
    (!isset($_REQUEST['isDuplicate']) || $_REQUEST['isDuplicate'] == "0")
) {
    $return_id = $_REQUEST['return_id'];
}

$GLOBALS['log']->debug("Saved record with id of ".$return_id);

$redirect = "index.php?action={$return_action}&module={$return_module}&record={$return_id}";

// cn: bug 6897 - detect redirect to Email compose
$redirect .= isset($_REQUEST['type']) ? "&type={$_REQUEST['type']}" : '';

$redirect .= isset($_REQUEST['return_id']) ? "&return_id={$_REQUEST['return_id']}" : '';

// Set the refresh metadata flag for changes that require it. This includes when
// metadata needs to be refreshed because of a user pref change, but only if its
// for the current user changing their own profile
$sameUser = !empty($focus->id) && $focus->id == $GLOBALS['current_user']->id;
$redirect .= $sameUser && $refreshMetadata ? '&refreshMetadata=1' : '';
$_SESSION['new_pwd'] = $new_pwd;
if (!headers_sent()) {
    header("Location: {$redirect}");
}
