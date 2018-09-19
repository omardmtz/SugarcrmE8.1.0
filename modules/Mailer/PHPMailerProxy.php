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

class PHPMailerProxy extends PHPMailer
{
    /**
     * {@inheritDoc}
     */
    public $AllowEmpty = true;

    /**
     * {@inheritDoc}
     *
     * Uses PHPMailer with exceptions.
     */
    public function __construct($exceptions = false)
    {
        parent::__construct(true);
        $this->Timeout = SugarConfig::getInstance()->get('email_mailer_timeout', 10);
        $this->SMTPAutoTLS = false;
        $this->SMTPDebug = SugarConfig::getInstance()->get('smtp_mailer_debug', 0);
        $this->Debugoutput = function ($message, $level) {
            if ($GLOBALS['log']) {
                $GLOBALS['log']->fatal("PHPMailer: debug level {$level}; message: {$message}");
            }
        };
    }

    /**
     * {@inheritDoc}
     *
     * @return SMTPProxy
     */
    public function getSMTPInstance()
    {
        if (!($this->smtp instanceof SMTPProxy)) {
            $this->smtp = new SMTPProxy();
        }

        return $this->smtp;
    }

    /**
     * Does not attempt an SMTP Connection when DISABLE_EMAIL_SEND is true. Immediately returns that the connection was
     * successful.
     *
     * {@inheritdoc}
     */
    public function smtpConnect($options = null)
    {
        if (defined('DISABLE_EMAIL_SEND') && DISABLE_EMAIL_SEND === true) {
            return true;
        }

        return parent::smtpConnect($options);
    }

    /**
     * Only performs the pre-send steps when DISABLE_EMAIL_SEND is true.
     *
     * {@inheritdoc}
     */
    public function send()
    {
        if (defined('DISABLE_EMAIL_SEND') && DISABLE_EMAIL_SEND === true) {
            try {
                return $this->preSend();
            } catch (phpmailerException $e) {
                $this->mailHeader = '';
                $this->setError($e->getMessage());
                throw $e;
            }
        }

        return parent::send();
    }

    /**
     * {@inheritdoc}
     *
     * SugarCRM cleans values that appear as HTML in certain cases before inserting that data into the database. When
     * PHPMailer generates a Message-ID header, the string may begin with a "<", followed by an alphabetic
     * character, which will cause the Message-ID to be parsed as an invalid HTML tag by HTMLPurifier. To combat this,
     * the unix timestamp is prefixed to the Message-ID to guarantee that the Message-ID will begin with "<", followed
     * by an integer, which HTMLPurifier will correctly ignore as a non-threatening tag. This allows the Message-ID to
     * be saved to the database whenever appropriate, without risk of losing the value.
     *
     * When the Message-ID is not provided or does not conform to the spec, PHPMailer generates the Message-ID using the
     * value from this method as the local part of a string that satisfies RFC 5322 section 3.6.4. Overriding this
     * method to prepend the unix timestamp to the ID allows us to generate the Message-ID as needed, without requiring
     * that we override a method that executes a lot of business logic. Additionally, all consumers of the ID are
     * guaranteed to have the same value. The ultimate format of the Message-ID, after overriding this method, is
     * <unix_timestamp.unique_id@server_hostname>.
     */
    protected function generateId()
    {
        return time() . '.' . parent::generateId();
    }

    /**
     * {@inheritDoc}
     */
    protected function setError($msg)
    {
        parent::setError($msg);

        $class = get_class($this);
        $GLOBALS['log']->fatal("{$class} encountered an error: {$this->ErrorInfo}");
    }
}
