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



class PasswordApi extends SugarApi
{
    public function registerApiRest()
    {
        return array(
            'create' => array(
                'reqType' => 'GET',
                'path' => array('password', 'request'),
                'pathVars' => array('module'),
                'method' => 'requestPassword',
                'shortHelp' => 'This method sends email requests to reset passwords',
                'longHelp' => 'include/api/help/password_request_get_help.html',
                'noLoginRequired' => true,
                'ignoreSystemStatusError' => true,
            ),
        );
    }

    /**
     * Resets password and sends email to user
     * @param ServiceBase $api
     * @param array $args
     * @return bool
     * @throws SugarApiExceptionRequestMethodFailure
     * @throws SugarApiExceptionMissingParameter
     */
    public function requestPassword(ServiceBase $api, array $args)
    {
        require_once('modules/Users/language/en_us.lang.php');
        $res = $GLOBALS['sugar_config']['passwordsetting'];

        $requiredParams = array(
            'email',
            'username',
        );
        if (!$GLOBALS['sugar_config']['passwordsetting']['forgotpasswordON']) {
            throw new SugarApiExceptionRequestMethodFailure(translate(
                'LBL_FORGOTPASSORD_NOT_ENABLED',
                'Users'
            ), $args);
        }

        foreach ($requiredParams as $key => $param) {
            if (!isset($args[$param])) {
                throw new SugarApiExceptionMissingParameter('Error: Missing argument.', $args);
            }
        }

        $usr = empty($this->usr) ? new User() : $this->usr;
        $useremail = $args['email'];
        $username = $args['username'];

        if (!empty($username) && !empty($useremail)) {
            $usr_id = $usr->retrieve_user_id($username);
            $usr->retrieve($usr_id);

            if (!$usr->isPrimaryEmail($useremail))
            {
                throw new SugarApiExceptionRequestMethodFailure(translate(
                    'LBL_PROVIDE_USERNAME_AND_EMAIL',
                    'Users'
                ), $args);
            }

            if ($usr->portal_only || $usr->is_group) {
                throw new SugarApiExceptionRequestMethodFailure(translate(
                    'LBL_PROVIDE_USERNAME_AND_EMAIL',
                    'Users'
                ), $args);
            }
            // email invalid can not reset password
            if (!SugarEmailAddress::isValidEmail($usr->emailAddress->getPrimaryAddress($usr))) {
                throw new SugarApiExceptionRequestMethodFailure(translate('ERR_EMAIL_INCORRECT', 'Users'), $args);
            }

            $isLink = !$GLOBALS['sugar_config']['passwordsetting']['SystemGeneratedPasswordON'];
            // if i need to generate a password (not a link)
            $password = $isLink ? '' : User::generatePassword();

            // Create URL
            // if i need to generate a link
            if ($isLink) {
                $guid = create_guid();
                $url = $GLOBALS['sugar_config']['site_url'] . "/index.php?entryPoint=Changenewpassword&guid=$guid";
                $time_now = TimeDate::getInstance()->nowDb();
                $q = "INSERT INTO users_password_link (id, username, date_generated) VALUES('" . $guid . "','" . $username . "','" . $time_now . "') ";
                $usr->db->query($q);
            }

            if ($isLink && isset($res['lostpasswordtmpl'])) {
                $emailTemp_id = $res['lostpasswordtmpl'];
            } else {
                $emailTemp_id = $res['generatepasswordtmpl'];
            }

            $additionalData = array(
                'link' => $isLink,
                'password' => $password
            );

            if (isset($url)) {
                $additionalData['url'] = $url;
            }

            $result = $usr->sendEmailForPassword($emailTemp_id, $additionalData);

            if ($result['status']) {
                return true;
            } elseif ($result['message'] != '') {
                throw new SugarApiExceptionRequestMethodFailure($result['message'], $args);
            } else {
                throw new SugarApiExceptionRequestMethodFailure('LBL_EMAIL_NOT_SENT', $args);
            }

        } else {
            throw new SugarApiExceptionMissingParameter('Error: Empty argument', $args);
        }
    }
}
