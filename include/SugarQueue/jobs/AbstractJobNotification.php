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

abstract class JobNotification
{
    /**
     * @var SchedulersJob
     */
    protected $job;

    /**
     * The Label that will be used for the subject line
     *
     * @var string
     */
    protected $subjectLabel = 'LBL_JOB_NOTIFICATION_SUBJECT_GENERIC';

    /**
     * The Label that will be used for the body of the notification and email
     *
     * @var string
     */
    protected $bodyLabel = 'LBL_JOB_NOTIFICATION_BODY_GENERIC';

    /**
     * Should we include a help link in the messages?
     *
     * @var bool
     */
    protected $includeHelpLink = false;

    /**
     * What module should the help point to?
     *
     * @var string
     */
    protected $helpModule = '';


    /**
     * A way to disable notification from inside the job
     *
     * @var bool
     */
    public $sendNotifications = true;

    /**
     * Test to see if the all the Jobs in the group are done.
     *
     * @return bool
     * @throws SugarQueryException
     */
    protected function isJobGroupDone()
    {
        // when we don't have a job_group, return true
        if (empty($this->job->job_group)) {
            return true;
        }

        $sq = new SugarQuery();
        $sq->select(array('job_group'))
            ->fieldRaw('count(0)', 'total_jobs')
            ->fieldRaw('sum(case when status = \'done\' AND resolution = \'success\' then 1 else 0 END)', 'total_done');
        $sq->from(BeanFactory::newBean('SchedulersJobs'));
        $sq->where()
            ->equals('job_group', $this->job->job_group);
        $sq->groupBy('job_group');

        $results = $sq->execute();
        $result = array_shift($results);

        return ($result['total_jobs'] === $result['total_done']);
    }

    /**
     * Notify the Assigned User that all the jobs have been completed
     *
     * @param Bool $notification Create a In-App Notification
     * @param Bool $email Send an Email
     */
    protected function notifyAssignedUser($notification = true, $email = true)
    {
        if ($this->sendNotifications && $this->isJobGroupDone()) {

            $subject = $GLOBALS['app_strings'][$this->subjectLabel];
            $body = $GLOBALS['app_strings'][$this->bodyLabel];

            if ($this->includeHelpLink && !empty($this->helpModule)) {
                $body = $this->appendHelpLink($body);
            }
            // append the site url and then nl2br the text
            $site_url = SugarConfig::getInstance()->get('site_url');
            $body .= "\n<a href=\"{$site_url}\">{$site_url}</a>";
            $body = nl2br($body);

            if ($notification) {
                $this->createNotification($subject, $body);
            }

            if ($email) {
                // send an email
                $this->sendEmail($subject, $body);
            }
        }
    }

    protected function appendHelpLink($body)
    {
        $link = "http://www.sugarcrm.com/crm/product_doc.php?edition={$GLOBALS['sugar_flavor']}&version={$GLOBALS['sugar_version']}&lang=&module={$this->helpModule}&route=list";

        $doc_url = "<a href=\"{$link}\">{$GLOBALS['app_strings']['LBL_JOB_NOTIFICATION_DOC_LINK_TEXT']}</a>";

        return str_replace('{{doc_url}}', $doc_url, $body);
    }

    /**
     * Create the in-app notifications
     *
     * @param String $subject The Subject
     * @param String $body The Description of the Notification
     */
    protected function createNotification($subject, $body)
    {
        // create the notification
        /* @var $notification Notifications */
        $notification = BeanFactory::newBean('Notifications');
        $notification->assigned_user_id = $this->job->assigned_user_id;
        $notification->name = $subject;
        $notification->description = $body;
        $notification->severity = 'success';
        $notification->save();
    }

    /**
     * Send an email to the assigned user of the job
     *
     * @param String $subject The Subject
     * @param String $body The Body of the email
     */
    protected function sendEmail($subject, $body)
    {
        $mailTransmissionProtocol = "unknown";

        /* @var $user User */
        $user = BeanFactory::getBean('Users', $this->job->assigned_user_id);

        try {
            /* @var $mailer SmtpMailer */
            $mailer = MailerFactory::getSystemDefaultMailer();
            $mailTransmissionProtocol = $mailer->getMailTransmissionProtocol();

            // add the recipient...
            $mailer->addRecipientsTo(new EmailIdentity($user->email1, $user->full_name));
            // set the subject
            $mailer->setSubject($subject);

            $mailer->setHtmlBody($body);

            $mailer->send();
        } catch (MailerException $me) {
            $message = $me->getMessage();
            $GLOBALS["log"]->warn(
                "Notifications: error sending e-mail (method: {$mailTransmissionProtocol}), (error: {$message})"
            );
        }
    }
}
