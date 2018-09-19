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


class ReportsUtilities
{
    private $user;
    private $language;

    public function __construct() {
        global $current_user,
               $current_language;

        $this->user     = $current_user;
        $this->language = $current_language;
    }

    /**
     * Notify the report owner of an invalid report definition.
     *
     * @param User   $recipient required
     * @param string $message   required
     * @throws MailerException Allows exceptions to bubble up for the caller to report if desired.
     */
    public function sendNotificationOfInvalidReport($recipient, $message) {
        $mod_strings = return_module_language($this->language, "Reports");
        $subject = $mod_strings["ERR_REPORT_INVALID_SUBJECT"];
        $this->sendNotificationOfReport($recipient, $subject, $message);
    }

    /**
     * Notify the report owner of deactivated report schedule.
     *
     * @param int  $report_id
     * @param User $owner
     * @param User $subscriber
     *
     * @throws MailerException Allows exceptions to bubble up for the caller to report if desired.
     */
    public function sendNotificationOfDisabledReport($report_id, User $owner = null, User $subscriber = null)
    {
        $recipients = array($owner, $subscriber);
        $recipients = array_filter($recipients);

        // return early in case there are no recipients specified
        if (!$recipients) {
            return;
        }

        $mod_strings = return_module_language($this->language, 'Reports');
        $subject = $mod_strings['ERR_REPORT_DEACTIVATED_SUBJECT'];

        $body = string_format($mod_strings['ERR_REPORT_DEACTIVATED'], array($report_id));

        // make sure that the same user doesn't receive the notification twice
        $unique = array();
        foreach ($recipients as $recipient) {
            $unique[$recipient->id] = $recipient;
        }

        foreach ($unique as $recipient) {
            $this->sendNotificationOfReport($recipient, $subject, $body);
        }
    }

    /**
     * Notifies the given user of a report problem
     *
     * @param User   $recipient Message recipient
     * @param string $subject   Message subject
     * @param string $body      Message body
     */
    protected function sendNotificationOfReport(User $recipient, $subject, $body)
    {
        $mailer = MailerFactory::getSystemDefaultMailer();

        // set the subject of the email
        $mailer->setSubject($subject);

        // set the body of the email...
        $textOnly = EmailFormatter::isTextOnly($body);
        if ($textOnly) {
            $mailer->setTextBody($body);
        } else {
            $textBody = strip_tags(br2nl($body)); // need to create the plain-text part
            $mailer->setTextBody($textBody);
            $mailer->setHtmlBody($body);
        }

        // add the recipient...

        // first get all email addresses known for this recipient
        $recipientEmailAddresses = array($recipient->email1, $recipient->email2);
        $recipientEmailAddresses = array_filter($recipientEmailAddresses);

        // then retrieve first non-empty email address
        $recipientEmailAddress = array_shift($recipientEmailAddresses);

        // a MailerException is raised if $email is invalid, which prevents the call to send below
        $mailer->addRecipientsTo(new EmailIdentity($recipientEmailAddress));

        // send the email
        $mailer->send();
    }
}
