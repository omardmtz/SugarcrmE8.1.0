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

/* Internal Module Imports */

/**
 * Needs a proxy to the PHPMailer library.
 */

/**
 * Requires BaseMailer in order to extend it.
 */

/* External Imports */

/**
 * Needs to take on an OutboundSmtpEmailConfiguration.
 */

/**
 * This class implements the basic functionality that is expected from a Mailer that uses PHPMailer to deliver its
 * messages.
 *
 * @extends BaseMailer
 */
class SmtpMailer extends BaseMailer
{
    /* Constants */

    /**
     * Only use SMTP to send email with PHPMailer.
     */
    const MailTransmissionProtocol = "smtp";

    /**
     * Internal PHPMailer instance
     */
    protected $mailer;

    /**
     * {@inheritDoc}
     */
    public function connect()
    {
        $this->mailer = $this->generateMailer(); // get a fresh PHPMailer object
        $this->transferConfigurations($this->mailer);
        // connect to the SMTP server
        $this->connectToHost($this->mailer);
    }

    /**
     * Sends email using PHPMailer.
     *
     * {@inheritDoc}
     */
    public function send()
    {
        try {
            // create internal PHPMailer instance and connect to the SMTP server
            $this->connect();
            // transfer the email headers to PHPMailer
            $this->transferHeaders($this->mailer);
            // transfer the recipients to PHPMailer
            $this->transferRecipients($this->mailer);
            // transfer the message to PHPMailer
            $this->transferBody($this->mailer);
            // transfer the attachments to PHPMailer
            $this->transferAttachments($this->mailer);
        } catch (MailerException $me) {
            $GLOBALS["log"]->fatal($me->getLogMessage());
            $GLOBALS["log"]->info($me->getTraceMessage());
            $GLOBALS["log"]->info(print_r($this->config->toArray(),true));
            throw($me);
        }

        try {
            // send the email with PHPMailer
            $this->mailer->send();
            $messageId = $this->headers->getMessageId();

            // Capture the Message-ID set by PHPMailer if the caller didn't supply one.
            // This allows callers to use the Message-ID after the email has been sent.
            if (empty($messageId)) {
                $messageId = $this->mailer->getLastMessageID();
                $this->headers->setHeader(EmailHeaders::MessageId, $messageId);
            }

            /*--- Debug Only ----------------------------------------------------*/
            $message = "MAIL SENT:\n";
            $message .= "--- Mail Config ---\n" . print_r($this->config->toArray(), true);
            $headers = array(
                EmailHeaders::Subject => $this->headers->getSubject(),
                EmailHeaders::From => $this->headers->getFrom(),
                EmailHeaders::MessageId => $messageId,
            );
            $message .= "--- Mail Headers ---\n" . print_r($headers, true);
            $GLOBALS["log"]->debug($message);
            /*--- Debug Only ----------------------------------------------------*/

            return $this->mailer->getSentMIMEMessage();
        } catch (Exception $e) {
            // eat the phpmailerException but use it's message to provide context for the failure
            $me = new MailerException($e->getMessage(), MailerException::FailedToSend);
            $GLOBALS["log"]->error($me->getLogMessage());
            $GLOBALS["log"]->info($me->getTraceMessage());
            $GLOBALS["log"]->info(print_r($this->config->toArray(),true));
            throw($me);
        }
    }

    /**
     * Performs any logic necessary to instantiate an object of the Mailer of choice and return it.
     *
     * @access protected
     * @return PHPMailer
     */
    protected function generateMailer()
    {
        return new PHPMailerProxy;
    }

    /**
     * Transfers the configurations to set up the PHPMailer object before attempting to send with it.
     *
     * @access protected
     * @param PHPMailer $mailer
     */
    protected function transferConfigurations(PHPMailer &$mailer)
    {
        // explicitly set the language even though PHPMailer will do it on its own
        // this call could fail (only if English is not used), but it should be safe to ignore it
        $mailer->setLanguage();

        // transfer the basic configurations to PHPMailer
        $mailer->Mailer   = $this->getMailTransmissionProtocol();
        $mailer->Hostname = $this->config->getHostname();
        $mailer->CharSet  = $this->config->getCharset();
        $mailer->Encoding = $this->config->getEncoding();
        $mailer->WordWrap = $this->config->getWordwrap();

        // transfer the SMTP configurations to PHPMailer
        $mailer->Host       = $this->config->getHost();
        $mailer->Port       = $this->config->getPort();
        $mailer->SMTPSecure = $this->config->getSecurityProtocol();
        $mailer->SMTPAuth   = $this->config->isAuthenticationRequired();
        $mailer->Username   = $this->config->getUsername();
        $mailer->Password   = from_html($this->config->getPassword()); // perform HTML character translations
    }

    /**
     * Connects to the SMTP server specified in the PHPMailer configurations. This allows us to establish the connection
     * to the SMTP server and catch any errors associated with the connection instead of letting PHPMailer establish
     * the connection at send time, which would result in losing the context of the failure.
     *
     * @access protected
     * @param PHPMailer $mailer
     * @throws MailerException
     */
    protected function connectToHost(PHPMailer &$mailer)
    {
        try {
            // have PHPMailer attempt to connect to the SMTP server
            $result = $mailer->smtpConnect();
            // returns true if connection is successful
            if (!$result) {
                throw new Exception('Connection Failed');
            }
        } catch (Exception $e) {
            //TODO: it would be better if the caller added the details to the message so that the mailer has no
            // knowledge of what it means to be a system or personal configuration
            $message = ($this->config->getConfigType() === 'system')
                ? $GLOBALS['app_strings']['LBL_EMAIL_INVALID_SYSTEM_OUTBOUND']
                : $GLOBALS['app_strings']['LBL_EMAIL_INVALID_PERSONAL_OUTBOUND'];
            throw new MailerException(
                "Failed to connect to outbound SMTP Mail Server: {$message}",
                MailerException::FailedToConnectToRemoteServer
            );
        }
    }

    /**
     * Transfers the email headers to PHPMailer.
     *
     * @access protected
     * @param PHPMailer $mailer
     * @throws MailerException
     * @throws phpmailerException
     */
    protected function transferHeaders(PHPMailer &$mailer)
    {
        // will throw an exception if an error occurs; will let it bubble up
        $headers = $this->headers->packageHeaders();

        foreach ($headers as $key => $value) {
            switch ($key) {
                case EmailHeaders::From:
                    if (!empty($value[1])) {
                        // perform character set and HTML character translations on the From name
                        $value[1] = $this->formatter->translateCharacters(
                            $value[1],
                            $this->config->getLocale(),
                            $this->config->getCharset()
                        );
                    }

                    // set PHPMailer's From so that PHPMailer can correctly construct the From header at send time
                    try {
                        $mailer->setFrom($value[0], $value[1]);
                    } catch (Exception $e) {
                        throw new MailerException(
                            "Failed to add the " . EmailHeaders::From . " header: " . $e->getMessage(),
                            MailerException::FailedToTransferHeaders
                        );
                    }

                    break;
                case EmailHeaders::ReplyTo:
                    // only allow PHPMailer to automatically set the Reply-To if this header isn't provided
                    // so clear PHPMailer's Reply-To array if this header is provided
                    $mailer->clearReplyTos();

                    if (!empty($value[1])) {
                        // perform character set and HTML character translations on the Reply-To name
                        $value[1] = $this->formatter->translateCharacters(
                            $value[1],
                            $this->config->getLocale(),
                            $this->config->getCharset()
                        );
                    }

                    // set PHPMailer's ReplyTo so that PHPMailer can correctly construct the Reply-To header at send
                    // time
                    try {
                        // PHPMailer's AddReplyTo could return true or false or allow an exception to bubble up. We
                        // want the same behavior to be applied for both false and on error, so throw a
                        // phpMailerException on failure.
                        if (!$mailer->addReplyTo($value[0], $value[1])) {
                            // doesn't matter what the message is since we're going to eat phpmailerExceptions
                            throw new phpmailerException();
                        }
                    } catch (Exception $e) {
                        throw new MailerException(
                            "Failed to add the " . EmailHeaders::ReplyTo . " header: " . $e->getMessage(),
                            MailerException::FailedToTransferHeaders
                        );
                    }

                    break;
                case EmailHeaders::Sender:
                    // set PHPMailer's Sender so that PHPMailer can correctly construct the Sender header at send time
                    $mailer->Sender = $value;
                    break;
                case EmailHeaders::MessageId:
                    // set PHPMailer's MessageId so that PHPMailer can correctly construct the Message-ID header at
                    // send time
                    $mailer->MessageID = $value;
                    break;
                case EmailHeaders::Priority:
                    // set PHPMailer's Priority so that PHPMailer can correctly construct the Priority header at send
                    // time
                    $mailer->Priority = $value;
                    break;
                case EmailHeaders::DispositionNotificationTo:
                    // set PHPMailer's ConfirmReadingTo so that PHPMailer can correctly construct the
                    // Disposition-Notification-To header at send time
                    $mailer->ConfirmReadingTo = $value;
                    break;
                case EmailHeaders::Subject:
                    // perform character set and HTML character translations on the subject
                    $value = $this->formatter->translateCharacters(
                        $value,
                        $this->config->getLocale(),
                        $this->config->getCharset()
                    );

                    // set PHPMailer's Subject so that PHPMailer can correctly construct the Subject header at send time
                    $mailer->Subject = $value;
                    break;
                default:
                    // it's not known, so it must be a custom header; add it to PHPMailer's custom headers array
                    //TODO: any need for charset translations for from_html on the value?
                    $mailer->addCustomHeader($key, $value);
                    break;
            }
        }
    }

    /**
     * Transfers the recipients to PHPMailer.
     *
     * @access protected
     * @param PHPMailer $mailer
     */
    protected function transferRecipients(PHPMailer &$mailer)
    {
        // clear the recipients from PHPMailer; only necessary if the PHPMailer object can be re-used, but there is
        // no harm in doing it anyway
        $mailer->clearAllRecipients();

        // get the recipients
        $to  = $this->recipients->getTo();
        $cc  = $this->recipients->getCc();
        $bcc = $this->recipients->getBcc();

        //TODO: should you be able to initiate a send without any To recipients?
        foreach ($to as $recipient) {
            $recipient->decode();

            // perform MIME character set translations on the recipient's name
            //TODO: why translateCharsetMIME here but translateCharset on other headers?
            $name = $this->config->getLocale()->translateCharsetMIME(
                $recipient->getName(),
                "UTF-8",
                $this->config->getCharset()
            );

            try {
                // attempt to add the recipient to PHPMailer in the To list
                $mailer->addAddress($recipient->getEmail(), $name);
            } catch (Exception $e) {
                // eat the exception for now as we'll send to as many valid recipients as possible
            }
        }

        foreach ($cc as $recipient) {
            $recipient->decode();

            // perform MIME character set translations on the recipient's name
            //TODO: why translateCharsetMIME here but translateCharset on other headers?
            $name = $this->config->getLocale()->translateCharsetMIME(
                $recipient->getName(),
                "UTF-8",
                $this->config->getCharset()
            );

            try {
                // attempt to add the recipient to PHPMailer in the CC list
                $mailer->addCC($recipient->getEmail(), $name);
            } catch (Exception $e) {
                // eat the exception for now as we'll send to as many valid recipients as possible
            }
        }

        foreach ($bcc as $recipient) {
            $recipient->decode();

            // perform MIME character set translations on the recipient's name
            //TODO: why translateCharsetMIME here but translateCharset on other headers?
            $name = $this->config->getLocale()->translateCharsetMIME(
                $recipient->getName(),
                "UTF-8",
                $this->config->getCharset()
            );

            try {
                // attempt to add the recipient to PHPMailer in the BCC list
                $mailer->addBCC($recipient->getEmail(), $name);
            } catch (Exception $e) {
                // eat the exception for now as we'll send to as many valid recipients as possible
            }
        }
    }

    /**
     * Transfers the message to PHPMailer.
     *
     * @access protected
     * @param PHPMailer $mailer
     */
    protected function transferBody(PHPMailer &$mailer)
    {
        // just to be safe, let's clear the bodies from PHPMailer
        $mailer->Body    = "";
        $mailer->AltBody = "";

        // this is a hack to make sure that from_html is called on each message part prior to any future code that
        // needs HTML entities converted to real HTML characters
        $textBody = from_html($this->textBody);
        $htmlBody = from_html($this->htmlBody);

        $hasText = $this->hasMessagePart($textBody); // is there a plain-text part?
        $hasHtml = $this->hasMessagePart($htmlBody); // is there an HTML part?

        // perform some preparations on the plain-text part, if one exists
        if ($hasText) {
            // perform character set translations on the plain-text body
            $textBody = $this->prepareTextBody($this->textBody);

            // wp: if plain text version has lines greater than 998, use base64 encoding
            // use the config's wordwrap limit instead of 998 to increase flexibility
            // all newlines must be LF in order for this work; CRLF works too
            $useBase64Encoding = false;
            $wordWrap          = $this->config->getWordwrap();
            $textBodyLines     = explode("\n", $textBody);
            $numberOfLines     = count($textBodyLines);

            for ($i = 0; !$useBase64Encoding && $i < $numberOfLines; $i++) {
                if (strlen($textBodyLines[$i]) > $wordWrap) {
                    $useBase64Encoding = true;
                }
            }

            if ($useBase64Encoding) {
                $mailer->Encoding = Encoding::Base64;
            }
        }

        // add the HTML part to PHPMailer, if one exists
        if ($hasHtml) {
            // perform character set translations HTML body
            $htmlBody = $this->prepareHtmlBody($htmlBody);

            // there is an HTML part so set up PHPMailer appropriately for sending a multi-part email
            $mailer->isHTML(true);
            $mailer->Encoding = Encoding::Base64; // so that embedded images are encoded properly
            $mailer->Body     = $htmlBody;        // the HTML part is the primary message body
        }

        // add the plain-text part to the appropriate PHPMailer body
        if ($hasText && $hasHtml) {
            // it's a multi-part email with both the plain-text and HTML parts
            $mailer->AltBody = $textBody; // the plain-text part is the alternate message body
        } elseif ($hasText) {
            // there is only a plain-text part so set up PHPMailer appropriately for sending a text-only email
            $mailer->Body = $textBody; // the plain-text part is the primary message body
        } else {
            // you should never actually send an HTML email without a plain-text part, but we'll allow it (for now)
        }
    }

    /**
     * Transfers both file attachments and embedded images to PHPMailer.
     *
     * @access protected
     * @param PHPMailer $mailer
     * @throws MailerException
     */
    protected function transferAttachments(PHPMailer &$mailer)
    {
        // clear the attachments from PHPMailer; only necessary if the PHPMailer object can be re-used, but there is
        // no harm in doing it anyway
        $mailer->clearAttachments();

        foreach ($this->attachments as $attachment) {
            if ($attachment instanceof EmbeddedImage) {
                // it's an embedded image

                // perform character set and HTML character translations on the file name
                $name = $this->formatter->translateCharacters(
                    $attachment->getName(),
                    $this->config->getLocale(),
                    $this->config->getCharset()
                );

                // transfer the image to PHPMailer so it can be embedded correctly at send time
                if (!$mailer->addEmbeddedImage(
                    $attachment->getPath(),
                    $attachment->getCid(),
                    $name,
                    $attachment->getEncoding(),
                    $attachment->getMimeType())
                ) {
                    throw new MailerException(
                        "Failed to embed the image at " . $attachment->getPath(),
                        MailerException::InvalidAttachment
                    );
                }
            } elseif ($attachment instanceof Attachment) {
                // it's a normal file attachment
                try {
                    // perform character set and HTML character translations on the file name
                    $name = $this->formatter->translateCharacters(
                        $attachment->getName(),
                        $this->config->getLocale(),
                        $this->config->getCharset()
                    );

                    // transfer the attachment to PHPMailer so it can be attached correctly at send time
                    $mailer->addAttachment(
                        $attachment->getPath(),
                        $name,
                        $attachment->getEncoding(),
                        $attachment->getMimeType());
                } catch (Exception $e) {
                    throw new MailerException(
                        "Failed to add the attachment at " . $attachment->getPath() . ": " . $e->getMessage(),
                        MailerException::InvalidAttachment
                    );
                }
            } else {
                // oops!
                // there really shouldn't be a way to get an attachment into the Mailer that isn't an Attachment
                // or an EmbeddedImage, but it's probably best to verify it anyway
                throw new MailerException("Invalid attachment type", MailerException::InvalidAttachment);
            }
        }
    }

    /**
     * Performs character set translations on the plain-text body based on the charset defined in the configuration.
     *
     * @access protected
     * @param string $body required The plain-text body that is to be translated.
     * @return string The translated body.
     */
    protected function prepareTextBody($body)
    {
        $body = $this->formatter->formatTextBody($body);

        // perform character set and HTML character translations on the plain-text body
        $body = $this->formatter->translateCharacters($body, $this->config->getLocale(), $this->config->getCharset());

        return $body;
    }

    /**
     * Enforces RFC compliance to the structure of the HTML body and performs character set translations on the body
     * based on the charset defined in the configuration.
     *
     * @access protected
     * @param string $body required The HTML body that is to be translated.
     * @return string The compliant and translated body.
     */
    protected function prepareHtmlBody($body)
    {
        $formatted = $this->formatter->formatHtmlBody($body);
        $body      = $formatted["body"];
        $images    = $formatted["images"];

        foreach ($images as $embeddedImage) {
            $this->addAttachment($embeddedImage);
        }

        $body = $this->forceRfcComplianceOnHtmlBody($body);

        // perform character set and HTML character translations on the HTML body
        $body = $this->formatter->translateCharacters($body, $this->config->getLocale(), $this->config->getCharset());

        return $body;
    }

    /**
     * HTML message bodies must include a doctype and appropriate html, head, and body elements to be RFC compliant.
     * Enforces this compliance by wrapping the body with the compliant document structure if the body is not found
     * to be compliant.
     *
     * @note This is flawed because the absence of "<html" doesn't also indicate the absence of "</body>" or "</html>".
     *       Yet, the assumption is that it is an indication of said absence. Thus, there is a potential for the
     *       document body to become invalid HTML.
     *
     * @param string $body required The HTML body to test for compliance and modify, if necessary.
     * @return string The compliant HTML body.
     */
    protected function forceRfcComplianceOnHtmlBody($body)
    {
        if (strpos($body, "<html") === false) {
            $subject   = $this->headers->getSubject(); // used for the document title
            $charset   = $this->config->getCharset();  // used for the document charset
            $language  = get_language_header();

            // prepend the document head and append the footer elements to the body
            $body      = <<<eoq
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" {$language}>
<head>
<meta http-equiv="Content-Type" content="text/html; charset={$charset}" />
<title>{$subject}</title>
</head>
<body>
{$body}
</body>
</html>
eoq;
        }

        return $body;
    }
}
