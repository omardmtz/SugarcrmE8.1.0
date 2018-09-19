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

class MailApi extends ModuleApi
{
    /*-- API Argument Constants --*/
    const EMAIL_CONFIG = "email_config";
    const FROM_ADDRESS = "from_address";
    const TO_ADDRESSES = "to_addresses";
    const CC_ADDRESSES = "cc_addresses";
    const BCC_ADDRESSES = "bcc_addresses";
    const ATTACHMENTS = "attachments";
    const TEAMS = "teams";
    const RELATED = "related";
    const SUBJECT = "subject";
    const HTML_BODY = "html_body";
    const TEXT_BODY = "text_body";
    const STATUS = "status";
    const DATE_SENT = "date_sent";
    const ASSIGNED_USER_ID = "assigned_user_id";

    /*-- API Fields with default values --*/
    public static $fields = array(
        self::EMAIL_CONFIG => '',
        self::FROM_ADDRESS => '',
        self::TO_ADDRESSES => array(),
        self::CC_ADDRESSES => array(),
        self::BCC_ADDRESSES => array(),
        self::ATTACHMENTS => array(),
        self::TEAMS => array(),
        self::RELATED => array(),
        self::SUBJECT => '',
        self::HTML_BODY => '',
        self::TEXT_BODY => '',
        self::STATUS => '',
        self::DATE_SENT => '',
        self::ASSIGNED_USER_ID => '',
    );

    /*-- Supported API Status values --*/
    static private $apiStatusValues = array(
        "draft", // draft
        "ready", // ready to be sent
        "archive" // archived
    );

    /*-- Supported API Attachment Type values --*/
    static private $apiAttachmentTypes = array(
        "document",
        "template",
        "upload",
    );

    private $emailRecipientsService;

    /**
     * {@inheritdoc}
     */
    public function registerApiRest()
    {
        $api = array(
            'createMail' => array(
                'reqType' => 'POST',
                'path' => array('Mail'),
                'pathVars' => array(''),
                'method' => 'createMail',
                'shortHelp' => 'Create Mail Item',
                'longHelp' => 'modules/Emails/clients/base/api/help/mail_post_help.html',
            ),
            'archiveMail' => array(
                'reqType' => 'POST',
                'path' => array('Mail', 'archive'),
                'pathVars' => array(''),
                'method' => 'archiveMail',
                'shortHelp' => 'Archive Mail Item',
                'longHelp'  => 'modules/Emails/clients/base/api/help/mail_archive_help.html',
            ),
            'recipientLookup' => array(
                'reqType' => 'POST',
                'path' => array('Mail', 'recipients', 'lookup'),
                'pathVars' => array(''),
                'method' => 'recipientLookup',
                'shortHelp' => 'Lookup Email Recipient Info',
                'longHelp' => 'modules/Emails/clients/base/api/help/mail_recipients_lookup_post_help.html',
            ),
            'listRecipients' => array(
                'reqType' => 'GET',
                'path' => array('Mail', 'recipients', 'find'),
                'pathVars' => array(''),
                'method' => 'findRecipients',
                'shortHelp' => 'Search For Email Recipients',
                'longHelp' => 'modules/Emails/clients/base/api/help/mail_recipients_find_get_help.html',
            ),
            'validateEmailAddresses' => array(
                'reqType' => 'POST',
                'path' => array('Mail', 'address', 'validate'),
                'pathVars' => array(''),
                'method' => 'validateEmailAddresses',
                'shortHelp' => 'Validate One Or More Email Address',
                'longHelp' => 'modules/Emails/clients/base/api/help/mail_address_validate_post_help.html',
            ),
            'saveAttachment' => array(
                'reqType' => 'POST',
                'path' => array('Mail', 'attachment'),
                'pathVars' => array('', ''),
                'method' => 'saveAttachment',
                'rawPostContents' => true,
                'shortHelp' => 'Saves a mail attachment.',
                'longHelp' => 'modules/Emails/clients/base/api/help/mail_attachment_post_help.html',
            ),
            'removeAttachment' => array(
                'reqType' => 'DELETE',
                'path' => array('Mail', 'attachment', '?'),
                'pathVars' => array('', '', 'file_guid'),
                'method' => 'removeAttachment',
                'rawPostContents' => true,
                'shortHelp' => 'Removes a mail attachment',
                'longHelp' => 'modules/Emails/clients/base/api/help/mail_attachment_record_delete_help.html',
            ),
            'clearUserCache' => array(
                'reqType' => 'DELETE',
                'path' => array('Mail', 'attachment', 'cache'),
                'pathVars' => array('', '', ''),
                'method' => 'clearUserCache',
                'rawPostContents' => true,
                'shortHelp' => 'Clears the user\'s attachment cache directory',
                'longHelp' => 'modules/Emails/clients/base/api/help/mail_attachment_cache_delete_help.html',
            ),
        );

        return $api;
    }

    /**
     * @deprecated POST /Mail has been deprecated and will not be available after v11. Use POST /Emails instead.
     * @see EmailsApi::createRecord()
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function createMail(ServiceBase $api, array $args)
    {
        LoggerManager::getLogger()->deprecated(
            'POST /Mail has been deprecated and will not be available after v11. Use POST /Emails instead.'
        );

        return $this->handleMail($api, $args);
    }

    /**
     * @deprecated PUT /Mail/:record has been deprecated and will not be available after v11. Use PUT /Emails/:record
     * instead.
     * @see EmailsApi::updateRecord()
     * @param ServiceBase $api
     * @param array $args
     * @return array
     * @throws SugarApiExceptionMissingParameter
     * @throws SugarApiExceptionRequestMethodFailure
     * @throws SugarApiExceptionInvalidParameter
     */
    public function updateMail(ServiceBase $api, array $args)
    {
        LoggerManager::getLogger()->deprecated(
            'PUT /Mail/:record has been deprecated and will not be available after v11. Use PUT /Emails/:record ' .
            'instead.'
        );

        $email = new Email();

        if (isset($args['email_id']) && !empty($args['email_id'])) {
            if ((!$email->retrieve($args['email_id'])) || ($email->id != $args['email_id'])) {
                throw new SugarApiExceptionMissingParameter();
            }

            if ($email->state !== Email::STATE_DRAFT) {
                throw new SugarApiExceptionRequestMethodFailure();
            }
        } else {
            throw new SugarApiExceptionInvalidParameter();
        }

        return $this->handleMail($api, $args);
    }

    /**
     * Archive email.
     *
     * @deprecated POST /Mail/archive has been deprecated and will not be available after v11. Use POST /Emails with
     * `{"state": "Archived"}` instead.
     * @see EmailsApi::createRecord()
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function archiveMail(ServiceBase $api, array $args)
    {
        LoggerManager::getLogger()->deprecated(
            'POST /Mail/archive has been deprecated and will not be available after v11. Use POST /Emails with ' .
            '{"state": "Archived"} instead.'
        );

        // Perform Front End argument validation per the Mail API architecture
        // Non-compliant arguments will result in an Invalid Parameter Exception Thrown
        $this->validateArguments($args);
        $mailRecord = $this->initMailRecord($args);
        return $mailRecord->archive();
    }

    /**
     * @deprecated This method is no longer used and is not recommended.
     * @see MailApi::createMail()
     * @see MailApi::updateMail()
     * @param ServiceBase $api
     * @param array $args
     * @return array
     * @throws SugarApiExceptionRequestMethodFailure
     */
    protected function handleMail(ServiceBase $api, array $args)
    {
        LoggerManager::getLogger()->deprecated('MailApi::handleMail() has been deprecated.');

        // Perform Front End argument validation per the Mail API architecture
        // Non-compliant arguments will result in an Invalid Parameter Exception Thrown
        $this->validateArguments($args);

        $mailRecord = $this->initMailRecord($args);

        try {
            if ($args[self::STATUS] == "ready") {
                $response = $mailRecord->send(); // send immediately
            } else {
                $response = $mailRecord->saveAsDraft(); // save as draft
            }
        } catch (MailerException $e) {
            $eMessage = $e->getUserFriendlyMessage();
            if (isset($GLOBALS["log"])) {
                $GLOBALS["log"]->error("MailApi: Request Failed - Message: {$eMessage}");
            }
            throw new SugarApiExceptionRequestMethodFailure($eMessage, null, 'Emails');
        }

        return $response;
    }

    /**
     * This endpoint accepts an array of one or more recipients and tries to resolve unsupplied arguments.
     * EmailRecipientsService::lookup contains the lookup and resolution rules.
     *
     * @deprecated POST /Mail/recipients/lookup has been deprecated and will not be available after v11.
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function recipientLookup(ServiceBase $api, array $args)
    {
        LoggerManager::getLogger()->deprecated(
            'POST /Mail/recipients/lookup has been deprecated and will not be available after v11.'
        );

        $recipients = $args;
        unset($recipients['__sugar_url']);

        $emailRecipientsService = $this->getEmailRecipientsService();

        $result = array();
        foreach ($recipients as $recipient) {
            $result[] = $emailRecipientsService->lookup($recipient);
        }

        return $result;
    }

    /**
     * Finds recipients that match the search term.
     *
     * Arguments:
     *    q           - search string
     *    module_list -  one of the keys from $modules
     *    order_by    -  columns to sort by (one or more of $sortableColumns) with direction
     *                   ex.: name:asc,id:desc (will sort by last_name ASC and then id DESC)
     *    offset      -  offset of first record to return
     *    max_num     -  maximum records to return
     *
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function findRecipients(ServiceBase $api, array $args)
    {
        if (ini_get('max_execution_time') > 0 && ini_get('max_execution_time') < 300) {
            ini_set('max_execution_time', 300);
        }
        $term = (isset($args["q"])) ? trim($args["q"]) : "";
        $offset = 0;
        $limit = (!empty($args["max_num"])) ? (int)$args["max_num"] : 20;
        $orderBy = array();

        if (!empty($args["offset"])) {
            if ($args["offset"] === "end") {
                $offset = "end";
            } else {
                $offset = (int)$args["offset"];
            }
        }

        $modules = array(
            "users" => "users",
            "accounts" => "accounts",
            "contacts" => "contacts",
            "leads" => "leads",
            "prospects" => "prospects",
            "all" => "LBL_DROPDOWN_LIST_ALL",
        );
        $module = $modules["all"];

        if (!empty($args["module_list"])) {
            $moduleList = strtolower($args["module_list"]);

            if (array_key_exists($moduleList, $modules)) {
                $module = $modules[$moduleList];
            }
        }

        if (!empty($args["order_by"])) {
            $orderBys = explode(",", $args["order_by"]);

            foreach ($orderBys as $sortBy) {
                $column = $sortBy;
                $direction = "ASC";

                if (strpos($sortBy, ":")) {
                    // it has a :, it's specifying ASC / DESC
                    list($column, $direction) = explode(":", $sortBy);

                    if (strtolower($direction) == "desc") {
                        $direction = "DESC";
                    } else {
                        $direction = "ASC";
                    }
                }

                // only add column once to the order-by clause
                if (empty($orderBy[$column])) {
                    $orderBy[$column] = $direction;
                }
            }
        }

        $records = array();
        $nextOffset = -1;

        if ($offset !== "end") {
            $emailRecipientsService = $this->getEmailRecipientsService();
            $records = $emailRecipientsService->find($term, $module, $orderBy, $limit+1, $offset);
            $totalRecords = count($records);
            if ($totalRecords > $limit) {
                // means there are more records in DB than limit specified
                $nextOffset = $offset + $limit;
                array_pop($records);
            }

            $apiHelpers = array();
            $retrieveOptions = array();
            if (!empty($args['erased_fields'])) {
                $retrieveOptions = ['erased_fields' => true, 'encode' => false, 'use_cache' => false];
            }
            foreach ($records as $idx => $record) {
                $bean = BeanFactory::retrieveBean($record['_module'], $record['id'], $retrieveOptions);
                if (!isset($apiHelpers[$record['_module']])) {
                    $apiHelpers[$record['_module']] = ApiHelper::getHelper($api, $bean);
                }
                if (isset($bean->erased_fields)) {
                    $records[$idx]['_erased_fields'] = $bean->erased_fields;
                }
                $records[$idx]['_acl'] = $apiHelpers[$record['_module']]->getBeanAcl($bean, array_keys($record));
            }
        }

        return array(
            "next_offset" => $nextOffset,
            "records" => $records,
        );
    }

    /**
     * Perform Audit Validation on Input Arguments and normalize
     *
     * @deprecated This method is no longer used and is not recommended.
     * @see MailApi::archiveMail()
     * @see MailApi::handleMail()
     * @param array $args
     */
    public function validateArguments(array &$args)
    {
        LoggerManager::getLogger()->deprecated('MailApi::validateArguments() has been deprecated.');

        $bean = BeanFactory::getBean('Emails');
        $relatedToOptions = $bean->field_defs['parent_name']['options'];
        $relatedToModules = array_keys($GLOBALS['app_list_strings'][$relatedToOptions]);

        /*--- Validate status value ---*/
        if (empty($args[self::STATUS]) || !in_array($args[self::STATUS], self::$apiStatusValues)) {
            $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_VALUE', array(self::STATUS));
        }

        /*--- Validate Mail Configuration ---*/
        if ($args[self::STATUS] === "ready" && empty($args[self::EMAIL_CONFIG])) {
            $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_VALUE', array(self::EMAIL_CONFIG));
        }

        /*--- Validate FROM_ADDRESS if 'archive' ---*/
        if ($args[self::STATUS] === "archive") {
            if (empty($args[self::FROM_ADDRESS]) || !is_string($args[self::FROM_ADDRESS])) {
                $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_VALUE', array(self::FROM_ADDRESS));
            }
            $fromAddress = empty($args[self::FROM_ADDRESS]) ? '' : trim($args[self::FROM_ADDRESS]);
            if (empty($fromAddress)) {
                $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_VALUE', array(self::FROM_ADDRESS));
            }
        }

        /*--- Validate DATE_SENT if 'archive' ---*/
        if ($args[self::STATUS] === "archive") {
            if (empty($args[self::DATE_SENT]) || !is_string($args[self::DATE_SENT])) {
                $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_VALUE', array(self::DATE_SENT));
            }
            $dateSent = empty($args[self::DATE_SENT]) ? '' : trim($args[self::DATE_SENT]);
            if (empty($dateSent)) {
                $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_VALUE', array(self::DATE_SENT));
            }
        }

        /*--- Validate ASSIGNED_USER_ID if 'archive' - Argument is Optional - so can be empty string ---*/
        if ($args[self::STATUS] === "archive") {
            if (isset($args[self::ASSIGNED_USER_ID]) && !is_string($args[self::ASSIGNED_USER_ID])) {
                $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_FORMAT', array(self::ASSIGNED_USER_ID));
            }
        }

        /*--- Validate TO Recipients ---*/
        $isRequired = $args[self::STATUS] === "archive" ? true : false;
        $this->validateRecipients($args, self::TO_ADDRESSES, $isRequired);

        /*--- Validate CC Recipients ---*/
        $this->validateRecipients($args, self::CC_ADDRESSES);

        /*--- Validate BCC Recipients ---*/
        $this->validateRecipients($args, self::BCC_ADDRESSES);

        /*--- Validate Attachments ---*/
        if (isset($args[self::ATTACHMENTS])) {
            if (!is_array($args[self::ATTACHMENTS])) {
                $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_FORMAT', array(self::ATTACHMENTS));
            }
            foreach ($args[self::ATTACHMENTS] as $attachment) {
                if (!is_array($attachment)) {
                    $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_FORMAT', array(self::ATTACHMENTS));
                }
                if (empty($attachment['type']) || !in_array($attachment['type'], self::$apiAttachmentTypes)) {
                    $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_FIELD', array(self::ATTACHMENTS, 'type'));
                }
                if (empty($attachment['id']) || !is_string($attachment['id'])) {
                    $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_FIELD', array(self::ATTACHMENTS, 'id'));
                }
                if ($attachment['type'] == 'upload' && empty($attachment['name'])) {
                    $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_FIELD', array(self::ATTACHMENTS, 'name'));
                }
            }
        }

        /*--- Validate Teams ---*/
        if (isset($args[self::TEAMS])) {
            if (!is_array($args[self::TEAMS])) {
                $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_FORMAT', array(self::TEAMS));
            }
            /* Primary is REQUIRED if Teams supplied */
            if (!isset($args[self::TEAMS]["primary"]) || !is_string(
                $args[self::TEAMS]["primary"]
            ) || empty($args[self::TEAMS]["primary"])
            ) {
                $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_FIELD', array(self::TEAMS, 'primary'));
            }
            if (isset($args[self::TEAMS]["others"])) {
                if (!is_array($args[self::TEAMS]["others"])) {
                    $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_FIELD', array(self::TEAMS, 'others'));
                }
                foreach ($args[self::TEAMS]["others"] as $otherTeam) {
                    if (!is_string($otherTeam) || empty($otherTeam)) {
                        $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_FIELD', array(self::TEAMS, 'others'));
                    }
                }
            }
        }

        /*--- Validate Related ---*/
        if (isset($args[self::RELATED])) {
            if (!is_array($args[self::RELATED])) {
                $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_FORMAT', array(self::RELATED));
            }
            if (!empty($args[self::RELATED])) {
                if (empty($args[self::RELATED]["id"]) || !is_string($args[self::RELATED]["id"])) {
                    $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_FIELD', array(self::RELATED, 'id'));
                }
                if (empty($args[self::RELATED]["type"]) || !is_string($args[self::RELATED]["type"])) {
                    $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_FIELD', array(self::RELATED, 'type'));
                }
                if (!in_array($args[self::RELATED]["type"], $relatedToModules)) {
                    $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_FIELD', array(self::RELATED, 'type'));
                }
            }
        }

        /*--- Validate Subject ---*/
        if (isset($args[self::SUBJECT]) && !is_string($args[self::SUBJECT])) {
            $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_FORMAT', array(self::SUBJECT));
        }

        if ($args[self::STATUS] === "archive") {
            $subject = empty($args[self::SUBJECT]) ? '' : trim($args[self::SUBJECT]);
            if (empty($subject)) {
                $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_VALUE', array(self::SUBJECT));
            }
        }

        /*--- Validate html_body ---*/
        if (isset($args[self::HTML_BODY]) && !is_string($args[self::HTML_BODY])) {
            $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_FORMAT', array(self::HTML_BODY));
        }

        /*--- Validate text_body ---*/
        if (isset($args[self::TEXT_BODY]) && !is_string($args[self::TEXT_BODY])) {
            $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_FORMAT', array(self::TEXT_BODY));
        }

        /*--- Initialize any Unprovided Arguments to their Defaults ---*/
        foreach (self::$fields AS $k => $v) {
            if (!isset($args[$k])) {
                $args[$k] = $v;
            }
        }

        /*--- If Sending Mail, make sure there is at least One Recipient specified --*/
        if (($args[self::STATUS] !== "draft") &&
            empty($args[self::TO_ADDRESSES]) &&
            empty($args[self::CC_ADDRESSES]) &&
            empty($args[self::BCC_ADDRESSES])
        ) {
            $this->invalidParameter('LBL_MAILAPI_NO_RECIPIENTS');
        }

    }

    /**
     * Validate Recipient List
     *
     * @deprecated This method is no longer used and is not recommended.
     * @see MailApi::validateArguments()
     * @param array $args
     * @param string $argName
     * @param bool $isRequired
     */
    protected function validateRecipients(array $args, $argName, $isRequired = false)
    {
        LoggerManager::getLogger()->deprecated('MailApi::validateRecipients() has been deprecated.');

        $recipientCount = 0;
        if (isset($args[$argName])) {
            if (!is_array($args[$argName])) {
                $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_FORMAT', array($argName));
            }
            foreach ($args[$argName] as $recipient) {
                if (!is_array($recipient)) {
                    $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_FORMAT', array($argName));
                }
                if (empty($recipient['email'])) {
                    $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_FIELD', array($argName, "email"));
                }
                if (!is_string($recipient['email'])) {
                    $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_FIELD', array($argName, "email"));
                }
                $recipientCount++;
            }
        }
        if ($isRequired && $recipientCount == 0) {
            $this->invalidParameter('LBL_MAILAPI_INVALID_ARGUMENT_VALUE', array($argName));
        }
    }

    /**
     * Log Audit Errors and Throw Appropriate Exception
     *
     * @deprecated This method is no longer used and is not recommended.
     * @see MailApi::validateArguments()
     * @see MailApi::validateRecipients()
     * @param string $message
     * @param null|array $msgArgs
     * @throws SugarApiExceptionInvalidParameter
     */
    protected function invalidParameter($message, $msgArgs = null)
    {
        LoggerManager::getLogger()->deprecated('MailApi::invalidParameter() has been deprecated.');

        throw new SugarApiExceptionInvalidParameter($message, $msgArgs, 'Emails');
    }

    /**
     * Instantiate and initialize the MaiRecord from the incoming api arguments
     *
     * @deprecated This method is no longer used and is not recommended.
     * @see MailApi::archiveMail()
     * @see MailApi::handleMail()
     * @param array $args
     * @return MailRecord
     */
    protected function initMailRecord(array $args)
    {
        LoggerManager::getLogger()->deprecated('MailApi::initMailRecord() has been deprecated.');

        $mailRecord = new MailRecord();
        $mailRecord->mailConfig = $args[self::EMAIL_CONFIG];
        $mailRecord->toAddresses = $args[self::TO_ADDRESSES];
        $mailRecord->ccAddresses = $args[self::CC_ADDRESSES];
        $mailRecord->bccAddresses = $args[self::BCC_ADDRESSES];
        $mailRecord->attachments = $args[self::ATTACHMENTS];
        $mailRecord->teams = $args[self::TEAMS];
        $mailRecord->related = $args[self::RELATED];
        $mailRecord->subject = $args[self::SUBJECT];
        $mailRecord->html_body = $args[self::HTML_BODY];
        $mailRecord->text_body = $args[self::TEXT_BODY];
        $mailRecord->fromAddress = $args[self::FROM_ADDRESS];
        $mailRecord->assigned_user_id = $args[self::ASSIGNED_USER_ID];

        if (!empty($args[self::DATE_SENT])) {
            $date = TimeDate::getInstance()->fromIso($args[self::DATE_SENT]);
            $mailRecord->date_sent = $date->asDb();
        }

        return $mailRecord;
    }

    /**
     * Validates email addresses. The return value is an array of key-value pairs where the keys are the email
     * addresses and the values are booleans indicating whether or not the email address is valid.
     *
     * @deprecated POST /Mail/address/validate has been deprecated and will not be available after v11.
     * @param ServiceBase $api
     * @param array $args
     * @return array
     * @throws SugarApiException
     */
    public function validateEmailAddresses(ServiceBase $api, array $args)
    {
        LoggerManager::getLogger()->deprecated(
            'POST /Mail/address/validate has been deprecated and will not be available after v11.'
        );

        $validatedEmailAddresses = array();
        unset($args["__sugar_url"]);
        if (!is_array($args)) {
            throw new SugarApiExceptionInvalidParameter('Invalid argument: cannot validate');
        }
        if (empty($args)) {
            throw new SugarApiExceptionMissingParameter('Missing email address(es) to validate');
        }
        $emailAddresses = $args;
        foreach ($emailAddresses as $emailAddress) {
            $validatedEmailAddresses[$emailAddress] = SugarEmailAddress::isValidEmail($emailAddress);
        }
        return $validatedEmailAddresses;
    }

    /**
     * @see MailApi::recipientLookup()
     * @see MailApi::findRecipients()
     * @return EmailRecipientsService
     */
    protected function getEmailRecipientsService()
    {
        if (!($this->emailRecipientsService instanceof EmailRecipientsService)) {
            $this->emailRecipientsService = new EmailRecipientsService;
        }

        return $this->emailRecipientsService;
    }

    /**
     * Saves an email attachment using the POST method
     *
     * @deprecated POST /Mail/attachment has been deprecated and will not be available after v11. Use POST
     * /Notes/temp/file/filename to upload an attachment and POST /Emails/:record/link/attachments to link it to an
     * email.
     * @param ServiceBase $api The service base
     * @param array $args Arguments array built by the service base
     * @return array metadata about the attachment including name, guid, and nameForDisplay
     */
    public function saveAttachment(ServiceBase $api, array $args)
    {
        LoggerManager::getLogger()->deprecated(
            'POST /Mail/attachment has been deprecated and will not be available after v11. Use POST ' .
            '/Notes/temp/file/filename to upload an attachment and POST /Emails/:record/link/attachments to link it ' .
            'to an email.'
        );

        $this->checkPostRequestBody();
        $email = $this->getEmailBean();
        $email->email2init();
        $metadata = $email->email2saveAttachment();
        return $metadata;
    }

    /**
     * Removes an email attachment
     *
     * @deprecated DELETE /Mail/attachment/:id has been deprecated and will not be available after v11. Use DELETE
     * /Notes/:record/file/filename to delete an uploaded file from the filesystem. Use DELETE
     * /Emails/:record/link/attachments/:remote_id to remove an attachment from an email. Note that removing an
     * attachment from an email will also delete it from the filesystem.
     * @param ServiceBase $api The service base
     * @param array $args The request args
     * @return bool
     * @throws SugarApiExceptionRequestMethodFailure
     */
    public function removeAttachment(ServiceBase $api, array $args)
    {
        LoggerManager::getLogger()->deprecated(
            'DELETE /Mail/attachment/:id has been deprecated and will not be available after v11. Use DELETE ' .
            '/Notes/:id/file/filename to delete a file from the filesystem. Use DELETE ' .
            '/Emails/:id/link/attachments/:remote_id to remove an attachment from an email. Note that removing an ' .
            'attachment from an email will also delete it from the filesystem.'
        );

        $email = $this->getEmailBean();
        $email->email2init();
        $fileGUID = $args['file_guid'];
        $fileName = $email->et->userCacheDir . "/" . $fileGUID;
        $filePath = clean_path($fileName);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        return true;
    }

    /**
     * Clears the user's attachment cache directory
     *
     * @deprecated DELETE /Mail/attachment/cache has been deprecated and will not be available after v11. Attachments
     * are no longer written as temporary files to the current user's cache directory.
     * @param ServiceBase $api The service base
     * @param array $args The request args
     * @return bool
     * @throws SugarApiExceptionRequestMethodFailure
     */
    public function clearUserCache(ServiceBase $api, array $args)
    {
        LoggerManager::getLogger()->deprecated(
            'DELETE /Mail/attachment/cache has been deprecated and will not be available after v11.'
        );

        $em = new EmailUI();
        $em->preflightUserCache();
        return true;
    }

    /**
     * Returns a new Email bean, used for testing purposes
     *
     * @deprecated This method is no longer used and is not recommended.
     * @see MailApi::saveAttachment()
     * @see MailApi::removeAttachment()
     * @return Email
     */
    protected function getEmailBean()
    {
        return new Email();
    }
}
