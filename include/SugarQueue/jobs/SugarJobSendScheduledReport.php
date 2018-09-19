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


/**
 * Class to run a job which should submit report to a single user and schedule next run time
 */
class SugarJobSendScheduledReport implements RunnableSchedulerJob
{
    /**
     * @var SchedulersJob
     */
    protected $job;

    /**
     * @param SchedulersJob $job
     */
    public function setJob(SchedulersJob $job)
    {
        $this->job = $job;
    }

    /**
     * @param $data
     * @return bool
     */
    public function run($data)
    {
        global $current_user;
        global $current_language;
        global $locale;

        $this->job->runnable_ran = true;
        $this->job->runnable_data = $data;

        $report_schedule_id = $data;

        $reportSchedule = new ReportSchedule();
        $scheduleInfo = $reportSchedule->getInfo($report_schedule_id);

        $GLOBALS["log"]->debug("-----> in Reports foreach() loop");

        $savedReport = BeanFactory::getBean('Reports', $scheduleInfo['report_id']);

        $GLOBALS["log"]->debug("-----> Generating Reporter");
        $reporter = new Report(from_html($savedReport->content));

        $reporter->is_saved_report = true;
        $reporter->saved_report = $savedReport;
        $reporter->saved_report_id = $savedReport->id;

        $mod_strings = return_module_language($current_language, 'Reports');

        // prevent invalid report from being processed
        if (!$reporter->is_definition_valid()) {
            $invalidFields = $reporter->get_invalid_fields();
            $args          = array($scheduleInfo['report_id'], implode(', ', $invalidFields));
            $message       = string_format($mod_strings['ERR_REPORT_INVALID'], $args);

            $GLOBALS["log"]->fatal("-----> {$message}");

            $reportOwner = BeanFactory::retrieveBean('Users', $savedReport->assigned_user_id);
            if ($reportOwner) {
                $reportsUtils = new ReportsUtilities();
                try {
                    $reportsUtils->sendNotificationOfInvalidReport($reportOwner, $message);
                } catch (MailerException $me) {
                    //@todo consider logging the error at the very least
                }
            }

            $this->job->failJob('Report field definition is invalid');
            return false;
        } else {
            $GLOBALS["log"]->debug("-----> Reporter settings attributes");
            $reporter->layout_manager->setAttribute("no_sort", 1);

            $GLOBALS["log"]->debug("-----> Reporter Handling PDF output");
            require_once 'modules/Reports/templates/templates_tcpdf.php';
            $reportFilename = template_handle_pdf($reporter, false);

            // get the recipient's data...

            // first get all email addresses known for this recipient
            $recipientEmailAddresses = array($current_user->email1, $current_user->email2);
            $recipientEmailAddresses = array_filter($recipientEmailAddresses);

            // then retrieve first non-empty email address
            $recipientEmailAddress = array_shift($recipientEmailAddresses);

            // get the recipient name that accompanies the email address
            $recipientName = $locale->formatName($current_user);

            try {
                $GLOBALS["log"]->debug("-----> Generating Mailer");
                $mailer = MailerFactory::getSystemDefaultMailer();

                // set the subject of the email
                $subject = empty($savedReport->name) ? "Report" : $savedReport->name;
                $mailer->setSubject($subject);

                // add the recipient
                $mailer->addRecipientsTo(new EmailIdentity($recipientEmailAddress, $recipientName));

                // attach the report, using the subject as the name of the attachment
                $charsToRemove  = array("\r", "\n");
                // remove these characters from the attachment name
                $attachmentName = str_replace($charsToRemove, "", $subject);
                // replace spaces with the underscores
                $attachmentName = str_replace(" ", "_", "{$attachmentName}.pdf");
                $attachment     = new Attachment($reportFilename, $attachmentName, Encoding::Base64, "application/pdf");
                $mailer->addAttachment($attachment);

                // set the body of the email
                $body = $mod_strings["LBL_HELLO"];

                if ($recipientName != "") {
                    $body .= " {$recipientName}";
                }

                $body .= ",\n\n" .
                    $mod_strings["LBL_SCHEDULED_REPORT_MSG_INTRO"] .
                    $savedReport->date_entered .
                    $mod_strings["LBL_SCHEDULED_REPORT_MSG_BODY1"] .
                    $savedReport->name .
                    $mod_strings["LBL_SCHEDULED_REPORT_MSG_BODY2"];

                $textOnly = EmailFormatter::isTextOnly($body);
                if ($textOnly) {
                    $mailer->setTextBody($body);
                } else {
                    $textBody = strip_tags(br2nl($body)); // need to create the plain-text part
                    $mailer->setTextBody($textBody);
                    $mailer->setHtmlBody($body);
                }

                $GLOBALS["log"]->debug("-----> Sending PDF via Email to [ {$recipientEmailAddress} ]");
                $mailer->send();

                $GLOBALS["log"]->debug("-----> Send successful");
                $reportSchedule->update_next_run_time(
                    $report_schedule_id,
                    $scheduleInfo["next_run"],
                    $scheduleInfo["time_interval"]
                );
            } catch (MailerException $me) {
                switch ($me->getCode()) {
                    case MailerException::InvalidEmailAddress:
                        $GLOBALS["log"]->info("No email address for {$recipientName}");
                        break;
                    default:
                        $GLOBALS["log"]->fatal("Mail error: " . $me->getMessage());
                        break;
                }
            }

            $GLOBALS["log"]->debug("-----> Removing temporary PDF file");
            unlink($reportFilename);

            $this->job->succeedJob();

            return true;
        }
    }
}
