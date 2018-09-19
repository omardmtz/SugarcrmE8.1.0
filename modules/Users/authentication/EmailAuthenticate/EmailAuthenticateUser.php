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

// $Id: EmailAuthenticateUser.php 51443 2009-10-12 20:34:36Z jmertic $

/**
 * This file is where the user authentication occurs. No redirection should happen in this file.
 *
 */


class EmailAuthenticateUser extends SugarAuthenticateUser
{
    private $passwordLength = 4;

    /**
     * @inheritDoc
     */
    public function loadUserOnLogin($name, $password, $fallback = false, array $params = array())
    {
        global $login_error;

        $GLOBALS['log']->debug("Starting user load for {$name}");

        if (empty($name) || empty($password)) {
            return false;
        }

        if (empty($_SESSION['lastUserId'])) {
            $input_hash = SugarAuthenticate::encodePassword($password);
            $user_id    = $this->authenticateUser($name, $input_hash);

            if (empty($user_id)) {
                $GLOBALS['log']->fatal("SECURITY: User authentication for {$name} failed");
                return false;
            }
        }

        if (empty($_SESSION['emailAuthToken'])) {
            $_SESSION['lastUserId']     = $user_id;
            $_SESSION['lastUserName']   = $name;
            $_SESSION['emailAuthToken'] = '';

            for ($i = 0; $i < $this->passwordLength; $i++) {
                $_SESSION['emailAuthToken'] .= chr(mt_rand(48, 90));
            }

            $_SESSION['emailAuthToken'] = str_replace(array('<', '>'), array('#', '@'), $_SESSION['emailAuthToken']);
            $_SESSION['login_error']    = 'Please Enter Your User Name and Emailed Session Token';
            $this->sendEmailPassword($user_id, $_SESSION['emailAuthToken']);
            return false;
        } else {
            if (strcmp($name, $_SESSION['lastUserName']) == 0 && strcmp($password, $_SESSION['emailAuthToken']) == 0) {
                $this->loadUserOnSession($_SESSION['lastUserId']);
                unset($_SESSION['lastUserId']);
                unset($_SESSION['lastUserName']);
                unset($_SESSION['emailAuthToken']);
                return true;
            }

        }

        $_SESSION['login_error'] = 'Please Enter Your User Name and Emailed Session Token';
        return false;
    }


    /**
     * Sends the users password to the email address.
     *
     * @param string $user_id
     * @param string $password
     */
    public function sendEmailPassword($user_id, $password) {
        $result = $GLOBALS['db']->query("SELECT email1, email2, first_name, last_name FROM users WHERE id='{$user_id}'");
        $row    = $GLOBALS['db']->fetchByAssoc($result);

        if (empty($row['email1']) && empty($row['email2'])) {
            $_SESSION['login_error'] = 'Please contact an administrator to setup up your email address associated to this account';
        } else {
            $mailTransmissionProtocol = "unknown";

            try {
                $mailer                   = MailerFactory::getSystemDefaultMailer();
                $mailTransmissionProtocol = $mailer->getMailTransmissionProtocol();

                // add the recipient...

                // first get all email addresses known for this recipient
                $recipientEmailAddresses = array($row["email1"], $row["email2"]);
                $recipientEmailAddresses = array_filter($recipientEmailAddresses);

                // then retrieve first non-empty email address
                $recipientEmailAddress = array_shift($recipientEmailAddresses);

                // get the recipient name that accompanies the email address
                $recipientName = "{$row["first_name"]} {$row["last_name"]}";

                $mailer->addRecipientsTo(new EmailIdentity($recipientEmailAddress, $recipientName));

                // override the From header
                $from = new EmailIdentity("no-reply@sugarcrm.com", "Sugar Authentication");
                $mailer->setHeader(EmailHeaders::From, $from);

                // set the subject
                $mailer->setSubject("Sugar Token");

                // set the body of the email... looks to be plain-text only
                $mailer->setTextBody("Your sugar session authentication token  is: {$password}");

                $mailer->send();
                $GLOBALS["log"]->info("Notifications: e-mail successfully sent");
            } catch (MailerException $me) {
                $message = $me->getMessage();
                $GLOBALS["log"]->warn("Notifications: error sending e-mail (method: {$mailTransmissionProtocol}), (error: {$message})");
            }
        }
    }
}
