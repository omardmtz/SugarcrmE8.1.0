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
 * This class implements the basic functionality that is expected from a Mailer.
 *
 * @abstract
 * @implements IMailer
 */
abstract class BaseMailer implements IMailer
{
    // constants
    const MailTransmissionProtocol = ""; // there is no protocol by default; all derived classes must set this

    // protected members
    protected $formatter;
    protected $config;
    protected $headers;
    protected $recipients;
    protected $htmlBody;
    protected $textBody;
    protected $attachments;

    /**
     * @access public
     * @param OutboundEmailConfiguration $config required
     */
    public function __construct(OutboundEmailConfiguration $config) {
        $this->reset(); // the equivalent of initializing the Mailer object's properties

        $this->config = $config;
    }

    /**
     * Sets the object properties back to their initial default values.
     *
     * @access public
     */
    public function reset() {
        $this->clearAttachments();
        $this->clearHeaders();

        $this->formatter  = new EmailFormatter();
        $this->recipients = new RecipientsCollection();
        $this->htmlBody   = null;
        $this->textBody   = null;
    }

    /**
     * Returns the value stored in the constant MailTransmissionProtocol, which represents the method by which email
     * is sent for this strategy.
     *
     * @access public
     * @return string
     */
    public function getMailTransmissionProtocol() {
        $class = get_class($this);
        return $class::MailTransmissionProtocol;
    }

    /**
     * Returns the configuration passed into the constructor
     *
     * @access public
     * @return OutboundEmailConfiguration $config
     */
    public function getConfig() {
        return $this->config;
    }


    /**
     * Replaces the existing email headers with the headers passed in as a parameter.
     *
     * @access public
     * @param EmailHeaders $headers required
     */
    public function setHeaders(EmailHeaders $headers) {
        $this->headers = $headers;
    }

    /**
     * Replaces the existing email headers with an EmailHeaders object hydrated from the array passed in as a parameter.
     *
     * @access public
     * @param array $headers required
     * @throws MailerException
     */
    public function constructHeaders($headers = array()) {
        $this->headers->buildFromArray($headers);
    }

    /**
     * Returns the value currently representing the header.
     *
     * @access public
     * @param string $key required Should look like the real header it represents.
     * @return mixed Refer to EmailHeaders::getHeader to see the possible return types.
     */
    public function getHeader($key) {
        return $this->headers->getHeader($key);
    }

    /**
     * Adds or replaces header values.
     *
     * @access public
     * @param string $key   required Should look like the real header it represents.
     * @param mixed  $value          The value of the header.
     * @throws MailerException
     */
    public function setHeader($key, $value = null) {
        $this->headers->setHeader($key, $value);
    }

    /**
     * Adds or replaces the Message-ID header.
     *
     * The unique identifier is coupled with the hostname from the configuration in order to construct a Message-ID that
     * is as unique as possible. The format of the Message-ID is <unique_id@hostname> per the recommendations from
     * {@link https://www.jwz.org/doc/mid.html}. See the section on "Message-ID generation" to learn how to construct a
     * unique identifier.
     *
     * In this particular implementation, the format of the Message-ID is <unix_timestamp.$id@hostname>. SugarCRM cleans
     * values that appear as HTML in certain cases before inserting that data into the database. Because the Message-ID
     * begins with a "<", an $id that begins with an alphabetic character will cause the Message-ID to be parsed as an
     * invalid HTML tag by HTMLPurifier. Prefixing the unix timestamp guarantees that the Message-ID will begin with
     * "<", followed by an integer, which HTMLPurifier will correctly ignore as a non-threatening tag. This allows the
     * Message-ID to be saved to the database whenever appropriate, without risk of losing the value.
     *
     * @param string $id A unique identifier.
     */
    public function setMessageId($id)
    {
        $messageId = sprintf('<%s.%s@%s>', time(), $id, $this->config->getHostname());
        $this->setHeader(EmailHeaders::MessageId, $messageId);
    }

    /**
     * Adds or replaces the Subject header.
     *
     * @access public
     * @param string $subject
     * @throws MailerException
     */
    public function setSubject($subject = null) {
        $this->setHeader(EmailHeaders::Subject, $subject);
    }

    /**
     * Restores the email headers to a fresh EmailHeaders object.
     *
     * @access public
     */
    public function clearHeaders() {
        $this->headers = new EmailHeaders();
    }

    /**
     * Clears the recipients from the selected recipient lists. By default, clear all recipients.
     *
     * @access public
     * @param bool $to  true=clear the To list; false=leave the To list alone
     * @param bool $cc  true=clear the CC list; false=leave the CC list alone
     * @param bool $bcc true=clear the BCC list; false=leave the BCC list alone
     */
    public function clearRecipients($to = true, $cc = true, $bcc = true) {
        if ($to) {
            $this->clearRecipientsTo();
        }

        if ($cc) {
            $this->clearRecipientsCc();
        }

        if ($bcc) {
            $this->clearRecipientsBcc();
        }
    }

    /**
     * Adds recipients to the To list.
     *
     * @access public
     * @param array $recipients Array of EmailIdentity objects.
     */
    public function addRecipientsTo($recipients = array()) {
        $this->recipients->addRecipients($recipients);
    }

    /**
     * Removes the recipients from the To list.
     *
     * @access public
     */
    public function clearRecipientsTo() {
        $this->recipients->clearTo();
    }

    /**
     * Adds recipients to the CC list.
     *
     * @access public
     * @param array $recipients Array of EmailIdentity objects.
     */
    public function addRecipientsCc($recipients = array()) {
        return $this->recipients->addRecipients($recipients, RecipientsCollection::FunctionAddCc);
    }

    /**
     * Removes the recipients from the CC list.
     *
     * @access public
     */
    public function clearRecipientsCc() {
        $this->recipients->clearCc();
    }

    /**
     * Adds recipients to the BCC list.
     *
     * @access public
     * @param array $recipients Array of EmailIdentity objects.
     */
    public function addRecipientsBcc($recipients = array()) {
        return $this->recipients->addRecipients($recipients, RecipientsCollection::FunctionAddBcc);
    }

    /**
     * Removes the recipients from the BCC list.
     *
     * @access public
     */
    public function clearRecipientsBcc() {
        $this->recipients->clearBcc();
    }

    /**
     * Returns the plain-text part of the email.
     *
     * @access public
     * @return string
     */
    public function getTextBody() {
        return $this->textBody;
    }

    /**
     * Sets the plain-text part of the email.
     *
     * @access public
     * @param string $body
     */
    public function setTextBody($body = null) {
        $this->textBody = $body;
    }

    /**
     * Returns the HTML part of the email.
     *
     * @access public
     * @return string
     */
    public function getHtmlBody() {
        return $this->htmlBody;
    }

    /**
     * Sets the HTML part of the email.
     *
     * @access public
     * @param string $body
     */
    public function setHtmlBody($body = null) {
        $this->htmlBody = trim($body);
    }

    /**
     * Adds an attachment from a path on the filesystem.
     *
     * @access public
     * @param Attachment $attachment
     */
    public function addAttachment(Attachment $attachment) {
        $this->attachments[] = $attachment;
    }

    /**
     * Removes any existing attachments by restoring the container to an empty array.
     *
     * @access public
     */
    public function clearAttachments() {
        $this->attachments = array();
    }

    /**
     * Returns true if the value passed in as a parameter is a valid message part. Use this method to determine if a
     * message has an HTML part or a plain-text part. If both parts exist, then the message is multi-part.
     *
     * @access public
     * @param string $part required The content of the message part to inspect.
     * @return bool
     */
    public function hasMessagePart($part) {
        // the content is only valid if it's a string and it's not empty
        if (is_string($part) && trim($part) != "") {
            return true;
        }

        return false;
    }
}
