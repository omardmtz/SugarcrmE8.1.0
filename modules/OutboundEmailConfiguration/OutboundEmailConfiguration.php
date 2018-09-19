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

// external imports
                                                      // type

/**
 * Represents the base configurations and contains the logic for setting the configurations for a Mailer.
 */
class OutboundEmailConfiguration
{
    // protected members
    protected $userId;      // the ID of the user who "owns" this configuration
    protected $configId;    // the ID of the OutboundEmail record
    protected $configName;  // the name of the configuration
    protected $configType;  // the type of the configuration ("user" or "system")
    protected $inboxId;     // the ID of the InboundEmail record associated with the configuration (null for "system")
    protected $mode;        // the sending strategy
    protected $personal;    // true=a user's personal configuration; false=shared configuration
    protected $displayName; // the name that user's use to identify the configuration
    protected $from;        // the EmailIdentity representing the sender of an email (used for the From email header)
    protected $replyTo;     // the EmailIdentity representing where replies should be sent (used for the Reply-To email
                            // header)
    protected $hostname;    // the hostname to use in Message-ID and Received headers and as default HELO string
                            // not the server hostname
    protected $locale;      // the Localization object necessary for performing character set translations
    protected $charset;     // the character set of the message
    protected $encoding;    // the encoding of the message, which must be one of the valid encodings from Encoding
    protected $wordwrap;    // number of characters per line before the message body wrap
    protected $inboundIds;  // the inbound IDs associated with this outbound reply email if any

    /**
     * @access public
     */
    public function __construct(User $user) {
        $this->setUserId($user->id);
        $this->loadDefaultConfigs();
    }

    /**
     * Sets the mailer configuration to its default settings for this sending strategy.
     *
     * @access public
     */
    public function loadDefaultConfigs() {
        $this->setMode();
        $this->setHostname();
        $this->setLocale();
        $this->setCharset();
        $this->setEncoding();
        $this->setWordwrap();
    }

    /**
     * Sets or overwrites the hostname configuration.
     *
     * @access public
     * @param string $hostname required
     * @throws MailerException
     */
    public function setHostname($hostname = "") {
        if (!is_string($hostname)) {
            throw new MailerException(
                "Invalid Configuration: hostname must be a string",
                MailerException::InvalidConfiguration
            );
        }

        $this->hostname = $hostname;
    }

    /**
     * Returns the hostname configuration.
     *
     * @access public
     * @return string
     */
    public function getHostname() {
        return $this->hostname;
    }

    /**
     * Sets or overwrites the locale configuration.
     *
     * @access public
     * @param Localization|null $locale Null is an acceptable value for the purposes of defaulting $this->locale to
     *                                  null, but the setter should only be used publicly with a valid Localization
     *                                  object.
     */
    public function setLocale(Localization $locale = null) {
        $this->locale = $locale;
    }

    /**
     * @access public
     * @return Localization|null Null if initialized but never set.
     */
    public function getLocale() {
        return $this->locale;
    }

    /**
     * Sets or overwrites the charset configuration.
     *
     * @access public
     * @param string $charset required
     * @throws MailerException
     */
    public function setCharset($charset = "utf-8") {
        if (!is_string($charset)) {
            throw new MailerException(
                "Invalid Configuration: charset must be a string",
                MailerException::InvalidConfiguration
            );
        }

        $this->charset = $charset;
    }

    /**
     * Returns the charset configuration.
     *
     * @access public
     * @return string
     */
    public function getCharset() {
        return $this->charset;
    }

    /**
     * Sets or overwrites the encoding configuration. Default to quoted-printable for plain/text.
     *
     * @access public
     * @param string $encoding required
     * @throws MailerException
     */
    public function setEncoding($encoding = Encoding::QuotedPrintable) {
        if (!Encoding::isValid($encoding)) {
            throw new MailerException(
                "Invalid Configuration: encoding is invalid",
                MailerException::InvalidConfiguration
            );
        }

        $this->encoding = $encoding;
    }

    /**
     * Returns the encoding configuration.
     *
     * @access public
     * @return string
     */
    public function getEncoding() {
        return $this->encoding;
    }

    /**
     * Sets or overwrites the wordwrap configuration, which is the number of characters before a line will be wrapped.
     *
     * @access public
     * @param int $chars required
     * @throws MailerException
     */
    public function setWordwrap($chars = 996) {
        if (!is_int($chars)) {
            throw new MailerException(
                "Invalid Configuration: wordwrap must be an integer",
                MailerException::InvalidConfiguration
            );
        }

        $this->wordwrap = $chars;
    }

    /**
     * Returns the wordwrap configuration.
     *
     * @access public
     * @return string
     */
    public function getWordwrap() {
        return $this->wordwrap;
    }

    /**
     * @access public
     * @param string $id required
     */
    public function setUserId($id) {
        $this->userId = $id;
    }

    /**
     * @access public
     * @return string
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * @access public
     * @param string $id required
     */
    public function setConfigId($id) {
        $this->configId = $id;
    }

    /**
     * @access public
     * @return string
     */
    public function getConfigId() {
        return $this->configId;
    }

    /**
     * @access public
     * @param string $name required
     */
    public function setConfigName($name) {
        $this->configName = $name;
    }

    /**
     * @access public
     * @return string
     */
    public function getConfigName() {
        return $this->configName;
    }

    /**
     * @access public
     * @param string $type required
     */
    public function setConfigType($type) {
        $this->configType = $type;
    }

    /**
     * @access public
     * @return string
     */
    public function getConfigType() {
        return $this->configType;
    }

    /**
     * @access public
     * @param string $id required
     */
    public function setInboxId($id) {
        $this->inboxId = $id;
    }

    /**
     * @access public
     * @return string
     */
    public function getInboxId() {
        return $this->inboxId;
    }

    /**
     * @access public
     * @param array $ids required
     */
    public function setInboundIds($ids) {
        $this->inboundIds = $ids;
    }

    /**
     * @access public
     * @return array
     */
    public function getInboundIds() {
        return $this->inboundIds;
    }

    /**
     * @param null|string $mode
     * @throws MailerException
     */
    public function setMode($mode = null) {
        if (empty($mode)) {
            $mode = OutboundEmailConfigurationPeer::MODE_DEFAULT;
        }

        $mode = strtolower($mode); // make sure it's lower case

        if (!OutboundEmailConfigurationPeer::isValidMode($mode)) {
            throw new MailerException("Invalid Mailer: '{$mode}' is an invalid mode", MailerException::InvalidMailer);
        }

        $this->mode = $mode;
    }

    /**
     * @access public
     * @return string
     */
    public function getMode() {
        return $this->mode;
    }

    /**
     * @access public
     * @param bool $personal
     */
    public function setPersonal($personal = false) {
        $this->personal = $personal;
    }

    /**
     * @access public
     * @return bool
     */
    public function getPersonal() {
        return $this->personal;
    }

    /**
     * @access public
     * @param string $name required
     */
    public function setDisplayName($name) {
        $this->displayName = $name;
    }

    /**
     * @access public
     * @return string
     */
    public function getDisplayName() {
        return $this->displayName;
    }

    /**
     * @access public
     * @param string      $email required
     * @param null|string $name
     * @throws MailerException Allows MailerExceptions to bubble up.
     */
    public function setFrom($email, $name = null) {
        $this->from = new EmailIdentity($email, $name);
    }

    /**
     * @access public
     * @return EmailIdentity
     */
    public function getFrom() {
        return $this->from;
    }

    /**
     * @access public
     * @param string      $email required
     * @param null|string $name
     * @throws MailerException Allows MailerExceptions to bubble up.
     */
    public function setReplyTo($email, $name = null) {
        $this->replyTo = new EmailIdentity($email, $name);
    }

    /**
     * @access public
     * @return EmailIdentity
     */
    public function getReplyTo() {
        return $this->replyTo;
    }

    /**
     * @access public
     * @return array
     */
    public function toArray() {
        return array(
            "userId"      => $this->getUserId(),
            "configId"    => $this->getConfigId(),
            "configName"  => $this->getConfigName(),
            "configType"  => $this->getConfigType(),
            "inboxId"     => $this->getInboxId(),
            "mode"        => $this->getMode(),
            "personal"    => $this->getPersonal(),
            "displayName" => $this->getDisplayName(),
            "from"        => $this->getFrom(),
            "replyTo"     => $this->getReplyTo(),
            "hostname"    => $this->getHostname()
        );
    }
}
