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
 * Class MailRecord
 * @deprecated This was a bridge for {@link MailApi} to work with {@link Email}. It is no longer used. Use
 * {@link EmailsApi} instead.
 */
class MailRecord
{
    static private $statuses = array(
        // Initial Status -  "Create" or "Update"
        "draft", // draft
        "scheduled", // scheduled for future date time
        "ready", // ready to be sent

        // Intermediate 'In-Progess' Status
        "sending", // transient status

        // Terminal Status
        "sent",
    );

    const ATTACHMENT_TYPE_UPLOAD = 'upload';
    const ATTACHMENT_TYPE_DOCUMENT = 'document';
    const ATTACHMENT_TYPE_TEMPLATE = 'template';

    public $mockEmailBean = null; // For Testing Purposes Only

    public $mailConfig;
    public $fromAddress;
    public $toAddresses;
    public $ccAddresses;
    public $bccAddresses;
    public $attachments;
    public $teams;
    public $related;
    public $subject;
    public $html_body;
    public $text_body;
    public $date_sent;
    public $assigned_user_id;

    /**
     * Logs a deprecation warning.
     *
     * @deprecated This class is no longer used and is not recommended.
     */
    public function __construct()
    {
        LoggerManager::getLogger()->deprecated(
            'MailRecord was a bridge for MailApi to work with Email. It is no longer used and has been deprecated. ' .
            'Use EmailsApi instead.'
        );
    }

    /**
     * Saves the email as a draft.
     *
     * @deprecated This method is no longer used and is not recommended.
     * @see MailApi::handleMail()
     * @return array
     */
    public function saveAsDraft()
    {
        LoggerManager::getLogger()->deprecated('MailRecord::saveAsDraft() has been deprecated.');

        return $this->toEmailBean("draft");
    }

    /**
     * Saves and sends the email.
     *
     * @deprecated This method is no longer used and is not recommended.
     * @see MailApi::handleMail()
     * @return array
     */
    public function send()
    {
        LoggerManager::getLogger()->deprecated('MailRecord::send() has been deprecated.');

        return $this->toEmailBean("ready");
    }

    /**
     * Saves the email as a draft.
     *
     * @deprecated This method is no longer used and is not recommended.
     * @see MailApi::archiveMail()
     * @return array
     */
    public function archive()
    {
        LoggerManager::getLogger()->deprecated('MailRecord::archive() has been deprecated.');

        return $this->toEmailBean("archived");
    }

    /**
     * Prepares and executes the email request according to the expectations of the status.
     *
     * @deprecated This method is no longer used and is not recommended.
     * @see MailRecord::saveAsDraft()
     * @see MailRecord::send()
     * @see MailRecord::archive()
     * @param $status
     * @return array - Mail API Response Record
     * @throws MailerException
     */
    protected function toEmailBean($status)
    {
        LoggerManager::getLogger()->deprecated('MailRecord::toEmailBean() has been deprecated.');

        if (!empty($this->mockEmailBean)) {
            $email = $this->mockEmailBean; // Testing purposes only
        } else {
            $email = new Email();
        }
        $email->email2init();

        $fromAccount = null;

        if (!empty($this->mailConfig)) {
            $fromAccount = $this->mailConfig;
        }

        $to = $this->addRecipients($this->toAddresses);
        $cc = $this->addRecipients($this->ccAddresses);
        $bcc = $this->addRecipients($this->bccAddresses);

        $attachments = $this->splitAttachments($this->attachments);

        $request = $this->setupSendRequest($status, $fromAccount, $to, $cc, $bcc, $attachments);
        $_REQUEST = array_merge($_REQUEST, $request);

        $errorData = null;

        try {
            $this->startCapturingOutput();
            $email->email2Send($request);
            $errorData = $this->endCapturingOutput();

            if (strlen($errorData) > 0) {
                throw new MailerException('Email2Send returning unexpected output: ' . $errorData);
            }

            $response = $this->toApiResponse($status, $email);
            return $response;

        } catch (Exception $e) {
            if (is_null($errorData)) {
                $errorData = $this->endCapturingOutput();
            }
            if (!($e instanceof MailerException)) {
                $e = new MailerException($e->getMessage());
            }
            if (empty($errorData)) {
                $GLOBALS["log"]->error("Message: " . $e->getLogMessage());
            } else {
                $GLOBALS["log"]->error("Message: " . $e->getLogMessage() . "  Data: " . $errorData);
            }

            throw $e;
        }
    }

    /**
     * Constructs the email request that will passed on.
     *
     * @deprecated This method is no longer used and is not recommended.
     * @see MailRecord::toEmailBean()
     * @param string $status
     * @param null   $from
     * @param string $to
     * @param string $cc
     * @param string $bcc
     * @param array $attachments
     * @return array
     */
    protected function setupSendRequest(
        $status = "ready",
        $from = null,
        $to = "",
        $cc = "",
        $bcc = "",
        $attachments = array()
    ) {
        LoggerManager::getLogger()->deprecated('MailRecord::setupSendRequest() has been deprecated.');

        $request = array(
            "fromAccount" => $from,
            "archive_from_address" => $this->fromAddress, // "archived" status only
            "sendSubject" => $this->subject,
            "sendTo" => $to,
            "sendCc" => $cc,
            "sendBcc" => $bcc,
            "saveToSugar" => "1",
            "sendDescription" => "", // defaulted to an empty string
        );

        if (!empty($this->html_body)) {
            $request["sendDescription"] = from_html($this->html_body);
            $request["setEditor"] = "1";
        } elseif (!empty($this->text_body)) {
            $request["sendDescription"] = from_html($this->text_body);
        }

        $requestKeys = array(
            self::ATTACHMENT_TYPE_UPLOAD => 'attachments',
            self::ATTACHMENT_TYPE_DOCUMENT => 'documents',
            self::ATTACHMENT_TYPE_TEMPLATE => 'templateAttachments',
        );
        foreach ($attachments as $key => $value) {
            $requestKey = isset($requestKeys[$key]) ? $requestKeys[$key] : $key;
            $request[$requestKey] = implode('::', $attachments[$key]);
        }

        if (is_array($this->related) && !empty($this->related["type"]) && !empty($this->related["id"])) {
            $request["parent_type"] = $this->related["type"];
            $request["parent_id"] = $this->related["id"];
        }

        if (is_array($this->teams) && !empty($this->teams["primary"])) {
            $request["primaryteam"] = $this->teams["primary"];
            $teamIds = array($this->teams["primary"]);

            if (isset($this->teams["others"]) && is_array(($this->teams["others"]))) {
                foreach ($this->teams["others"] as $teamId) {
                    $teamIds[] = $teamId;
                }
            }

            $request["teamIds"] = implode(",", $teamIds);
        }

        if ($status === 'draft') {
            $request["saveDraft"] = "true"; // send ("ready") is the default behavior
        } elseif ($status === 'archived') {
            if (!empty($this->date_sent)) {
                $request['dateSent'] = $this->date_sent;
            }
            if (!empty($this->assigned_user_id)) {
                $request['assignedUser'] = $this->assigned_user_id;
            }
        }

        $request["MAIL_RECORD_STATUS"] = $status;

        return $request;
    }

    /**
     * Starts the output buffer. Wraps the function call so that it is possible to mock/stub this behavior.
     *
     * @deprecated This method is no longer used and is not recommended.
     * @see MailRecord::toEmailBean()
     */
    protected function startCapturingOutput()
    {
        LoggerManager::getLogger()->deprecated('MailRecord::startCapturingOutput() has been deprecated.');

        ob_start();
    }

    /**
     * Collects the contents from the output buffer and cleans the buffer. Wraps the function calls so that it is
     * possible to mock/stub this behavior.
     *
     * @deprecated This method is no longer used and is not recommended.
     * @see MailRecord::toEmailBean()
     * @return string
     */
    protected function endCapturingOutput()
    {
        $contents = ob_get_contents();
        ob_end_clean();

        LoggerManager::getLogger()->deprecated('MailRecord::endCapturingOutput() has been deprecated.');

        return $contents;
    }

    /**
     * Format recipient addresses as comma-separated strings.
     *
     * @deprecated This method is no longer used and is not recommended.
     * @see MailRecord::toEmailBean()
     * @param array $recipients
     * @return string
     */
    protected function addRecipients($recipients = array())
    {
        LoggerManager::getLogger()->deprecated('MailRecord::addRecipients() has been deprecated.');

        $addedRecipients = array();

        if (is_array($recipients)) {
            foreach ($recipients as $recipient) {
                $identity = $this->generateEmailIdentity($recipient);

                if ($identity) {
                    $formattedRecipient = array();
                    $name = $identity->getName();

                    if (!empty($name)) {
                        $formattedRecipient[] = $name;
                    }

                    $formattedRecipient[] = "<" . $identity->getEmail() . ">";

                    // add the formatted recipient to the array of all recipients to be imploded
                    // separate the name and email address by a single space
                    $addedRecipients[] = implode(" ", $formattedRecipient);
                }
            }
        }

        return implode(", ", $addedRecipients);
    }

    /**
     * Split attachment list into separate lists by type
     *
     * @deprecated This method is no longer used and is not recommended.
     * @see MailRecord::toEmailBean()
     * @param array $attachments
     * @return array
     */
    protected function splitAttachments($attachments = array())
    {
        LoggerManager::getLogger()->deprecated('MailRecord::splitAttachments() has been deprecated.');

        $addedAttachments = array();

        if (is_array($attachments)) {
            foreach ($attachments as $attachment) {
                $type = $attachment['type'];
                if (!array_key_exists($type, $addedAttachments)) {
                    $addedAttachments[$type] = array();
                }
                if ($type === self::ATTACHMENT_TYPE_UPLOAD) {
                    $addedAttachments[$type][] = $attachment['id'] . $attachment["name"];
                } else {
                    $addedAttachments[$type][] = $attachment['id'];
                }
            }
        }

        return $addedAttachments;
    }

    /**
     * Returns an EmailIdentity object from the set of recipients data that is passed in.
     *
     * @deprecated This method is no longer used and is not recommended.
     * @see MailRecord::addRecipients()
     * @param $data
     * @return EmailIdentity
     */
    protected function generateEmailIdentity($data)
    {
        LoggerManager::getLogger()->deprecated('MailRecord::generateEmailIdentity() has been deprecated.');

        $recipient = null;

        if (is_array($data) && !empty($data['email'])) {
            $email = $data['email'];
            $name = null;

            if (isset($data['name'])) {
                $name = $data['name'];
            }

            $recipient = new EmailIdentity($email, $name);
        }

        return $recipient;
    }

    /**
     * Returns the Api Response Record
     *
     * @deprecated This method is no longer used and is not recommended.
     * @see MailRecord::toEmailBean()
     * @param string $status Status that came in on the request
     * @param Email $email
     * @return array
     */
    protected function toApiResponse($status, $email)
    {
        LoggerManager::getLogger()->deprecated('MailRecord::toApiResponse() has been deprecated.');

        $response = array(
            "id" => $email->id,
            "date_entered" => $email->date_entered,
            "date_modified" => $email->date_modified,
            "assigned_user_id" => $email->assigned_user_id,
            "modified_user_id" => $email->modified_user_id,
            "created_by" => $email->created_by,
            "deleted" => $email->deleted,
            "to_addresses" => $this->toAddresses,
            "cc_addresses" => $this->ccAddresses,
            "bcc_addresses" => $this->bccAddresses,
            "attachments" => $this->attachments,
            "teams" => $this->teams,
            "related" => $this->related,
            "subject" => $this->subject,
            "html_body" => $this->html_body,
            "text_body" => $this->text_body,
            "status" => ($status == 'ready') ? 'sent' : $status,
            'state' => $email->state,
        );

        if (!empty($this->date_sent)) {
            $timedate = TimeDate::getInstance();
            $date = $timedate->fromDb($this->date_sent);
            $response['date_sent'] = $timedate->asIso($date);
        }

        if (!empty($this->fromAddress)) {
            $response['from_address'] = $this->fromAddress;
        }

        return $response;
    }
}
