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



$modListHeader = array();

require_once('modules/Reports/templates/templates_pdf.php');
require_once 'modules/Reports/config.php';

global $sugar_config;

$language         = $sugar_config['default_language'];
$app_list_strings = return_app_list_strings_language($language);
$app_strings      = return_application_language($language);

$reportSchedule = new ReportSchedule();

// Process Enterprise Schedule reports via CSV
$reportsToEmailEnt = $reportSchedule->get_ent_reports_to_email("", "ent");

global $report_modules,
       $modListHeader,
       $locale;

foreach ($reportsToEmailEnt as $scheduleId => $scheduleInfo) {
    $user = BeanFactory::getBean('Users', $scheduleInfo['user_id']);

    $current_user   = $user; // should this be the global $current_user? global $current_user isn't referenced
    $modListHeader  = query_module_access_list($current_user);
    $report_modules = getAllowedReportModules($modListHeader);

    // Acquire the enterprise report to be sent
    $reportMaker = new ReportMaker();
    $reportMaker->retrieve($scheduleInfo['report_id']);
    $mod_strings = return_module_language($language, 'Reports');

    // Process data sets into CSV files

    // loop through data sets;
    $dataSets  = $reportMaker->get_data_sets();
    $tempFiles = array();

    foreach ($dataSets as $key => $dataSet) {
        $csv           = $dataSet->export_csv();
        $filenamestamp = "{$dataSet->name}_{$user->user_name}_" . date(translate("LBL_CSV_TIMESTAMP", "Reports"), time());
        $filename      = str_replace(" ", "_", "{$reportMaker->name}{$filenamestamp}.csv");
        $fp            = sugar_fopen(sugar_cached("csv/") . $filename, "w");
        fwrite($fp, $csv);
        fclose($fp);

        $tempFiles[$filename] = $filename;
    }

    // get the recipient data...

    // first get all email addresses known for this recipient
    $recipientEmailAddresses = array($user->email1, $user->email2);
    $recipientEmailAddresses = array_filter($recipientEmailAddresses);

    // then retrieve first non-empty email address
    $recipientEmailAddress = array_shift($recipientEmailAddresses);

    // get the recipient name that accompanies the email address
    $recipientName = $locale->formatName($user);

    try {
        $mailer = MailerFactory::getMailerForUser($current_user);

        // set the subject of the email
        $subject = empty($reportMaker->name) ? "Report" : $reportMaker->name;
        $mailer->setSubject($subject);

        // add the recipient
        $mailer->addRecipientsTo(new EmailIdentity($recipientEmailAddress, $recipientName));

        // add the attachments
        $tempCount = 0;

        foreach ($tempFiles as $filename) {
            $filePath       = sugar_cached("csv/") . $filename;
            $attachmentName = "{$subject}_{$tempCount}.csv";
            $attachment     = new Attachment($filePath, $attachmentName, Encoding::Base64, "application/csv");
            $mailer->addAttachment($attachment);
            $tempCount++;
        }

        // set the body of the email
        $body = $mod_strings["LBL_HELLO"];

        if ($recipientName != "") {
            $body .= " {$recipientName}";
        }

        $body .= ",\n\n" .
                 $mod_strings["LBL_SCHEDULED_REPORT_MSG_INTRO"] .
                 $reportMaker->date_entered .
                 $mod_strings["LBL_SCHEDULED_REPORT_MSG_BODY1"] .
                 $reportMaker->name .
                 $mod_strings["LBL_SCHEDULED_REPORT_MSG_BODY2"];

        // the compared strings will be the same if strip_tags had no affect
        // if the compared strings are equal, then it's a text-only message
        $textOnly = (strcmp($body, strip_tags($body)) == 0);

        if ($textOnly) {
            $mailer->setTextBody($body);
        } else {
            $textBody = strip_tags(br2nl($body)); // need to create the plain-text part
            $mailer->setTextBody($textBody);
            $mailer->setHtmlBody($body);
        }

        $mailer->send();

        $reportSchedule->update_next_run_time($scheduleInfo["id"],
                                              $scheduleInfo["next_run"],
                                              $scheduleInfo["time_interval"]);
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

    // need unlink for loop
    foreach ($tempFiles as $filename) {
        //only un rem if we need to remove cvs and we can't just stream it
        $filePath = sugar_cached('csv/') . $filename;
        unlink($filePath);
    }
}
