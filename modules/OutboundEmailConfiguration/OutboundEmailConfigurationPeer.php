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

// also imports OutboundEmailConfiguration.php

// external imports

use Sugarcrm\Sugarcrm\Util\Serialized;

class OutboundEmailConfigurationPeer
{
    const MODE_DEFAULT = "default";
    const MODE_SMTP    = "smtp";

    const STATUS_VALID_CONFIG = 0;
    const STATUS_INVALID_SYSTEM_CONFIG = 101;
    const STATUS_INVALID_USER_CONFIG = 102;

    static public $configurationStatusMessageMappings = array(
        self::STATUS_INVALID_SYSTEM_CONFIG        => 'LBL_EMAIL_INVALID_SYSTEM_CONFIGURATION',
        self::STATUS_INVALID_USER_CONFIG          => 'LBL_EMAIL_INVALID_USER_CONFIGURATION',
    );

    /**
     * Returns true/false indicating whether or not $mode is a valid sending strategy.
     *
     * @static
     * @access public
     * @param string $mode required
     * @return bool
     */
    public static function isValidMode($mode)
    {
        switch ($mode) {
            case self::MODE_DEFAULT:
            case self::MODE_SMTP:
                return true;
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * @access public
     * @param Localization $locale
     * @param string       $charset
     * @return OutboundEmailConfiguration $outboundEmailConfiguration
     */
    public static function getSystemDefaultMailConfiguration(Localization $locale = null, $charset = null)
    {
        global $app_strings;
        $systemUser = BeanFactory::newBean("Users");
        $systemUser->getSystemUser();

        $systemMailerConfiguration = static::loadOutboundEmail();
        $systemMailerConfiguration->getSystemMailerSettings();
        $systemDefaultInfo = $systemUser->getSystemDefaultNameAndEmail();

        $configurations                = array();
        $configurations["config_id"]   = $systemUser->id;
        $configurations["config_type"] = $systemUser->type;
        $configurations["inbox_id"]    = null;

        $configurations["from_email"]    = $systemDefaultInfo["email"];
        $configurations["from_name"]     = $systemDefaultInfo["name"];
        $configurations["personal"]      = false;

        $displayName = translate('LBL_OUTBOUND_EMAIL_CONFIGURATION_DISPLAY_NAME', 'OutboundEmailConfiguration');
        $displayName = string_format(
            $displayName,
            array(
                $systemDefaultInfo['name'],
                $systemDefaultInfo['email'],
                translate('LBL_SYSTEM_DEFAULT_OUTBOUND_EMAIL_CONFIGURATION', 'OutboundEmailConfiguration'),
            )
        );
        $configurations['display_name'] = $displayName;

        $outboundEmailConfiguration      = self::buildOutboundEmailConfiguration(
            $systemUser,
            $configurations,
            $systemMailerConfiguration,
            $locale,
            $charset
        );

        return $outboundEmailConfiguration;
    }

    /**
     * @access public
     * @param User $user required
     * @return bool
     */
    public static function validSystemMailConfigurationExists(User $user)
    {
        $configExists = false;

        try {
            $config = OutboundEmailConfigurationPeer::getSystemMailConfiguration($user);

            $configExists = self::isMailConfigurationValid($config);
        } catch (MailerException $me) {
            $GLOBALS["log"]->warn(
                "An error occurred while searching for a valid system mail configuration: " . $me->getMessage()
            );
        }

        return $configExists;
    }

    /**
     * @access public
     * @param OutboundEmailConfiguration $configuration required
     * @return bool
     */
    public static function isMailConfigurationValid(OutboundEmailConfiguration $configuration)
    {
        $configExists = false;

        if ($configuration instanceof OutboundSmtpEmailConfiguration) {
            $host = $configuration->getHost();
            if (!empty($host)) {
                if ($configuration->isAuthenticationRequired()) {
                    $userName = $configuration->getUsername();
                    $userPass = $configuration->getPassword();
                    if (!empty($userName) && !empty($userPass)) {
                        $configExists = true;
                    }
                } else {
                    $configExists = true;
                }
            }
        }

        return $configExists;
    }

    /**
     * @access public
     * @param User         $user    required
     * @param Localization $locale
     * @param string       $charset
     * @return OutboundEmailConfiguration System- or User-defined System-Override Mail Configuration
     * @throws MailerException
     */
    public static function getSystemMailConfiguration(User $user, Localization $locale = null, $charset = null)
    {
        $mailConfigurations = self::listOutboundMailConfigurations($user, $locale, $charset);

        foreach ($mailConfigurations AS $mailConfiguration) {
            $systemTypes = [
                OutboundEmail::TYPE_SYSTEM,
                OutboundEmail::TYPE_SYSTEM_OVERRIDE,
            ];

            if (in_array($mailConfiguration->getConfigType(), $systemTypes)) {
                return $mailConfiguration;
            }
        }

        throw new MailerException("No Valid Mail Configurations Found", MailerException::InvalidConfiguration);
    }

    /**
     * @access public
     * @param User         $user              required
     * @param string       $configuration_id  Outbound_Email Id  or  Inbound_Email Id
     * @param Localization $locale
     * @param string       $charset
     * @return OutboundEmailConfiguration or null if not found
     */
    public static function getMailConfigurationFromId(
        User $user,
        $configuration_id,
        Localization $locale = null,
        $charset = null
    ) {
        // Check for Inbound Email Address Match First -  Outbound Config Id may not be unique
        $mailConfigurations = self::listMailConfigurations($user, $locale, $charset);
        foreach ($mailConfigurations AS $mailConfiguration) {
            $inbox_id = $mailConfiguration->getInboxId();
            if (!empty($inbox_id) && $inbox_id == $configuration_id) {
                return $mailConfiguration;
            }
        }
        foreach ($mailConfigurations AS $mailConfiguration) {
            $inbox_id = $mailConfiguration->getInboxId();
            if (empty($inbox_id) && ($mailConfiguration->getConfigId() == $configuration_id)) {
                return $mailConfiguration;
            }
        }
        return null;
    }

    /**
     * Retrieves the status of the users mail configuration
     *
     * @param User $user
     *
     * @return int status of the users mail configuration
     */
    public static function getMailConfigurationStatusForUser($user)
    {
        try {
            $configuration = static::getSystemDefaultMailConfiguration();
            if ($configuration instanceof OutboundSmtpEmailConfiguration) {
                $host = $configuration->getHost();
                if (empty($host)) {
                    return static::STATUS_INVALID_SYSTEM_CONFIG;
                }
                $oe = static::loadOutboundEmail();
                if (!$oe->isAllowUserAccessToSystemDefaultOutbound() && $configuration->isAuthenticationRequired()) {
                    try {
                        $userConfiguration = static::getSystemMailConfiguration($user);
                        if ($userConfiguration instanceof OutboundSmtpEmailConfiguration) {
                            $userName = $userConfiguration->getUsername();
                            $userPass = $userConfiguration->getPassword();
                            if (empty($userName) || empty($userPass)) {
                                return static::STATUS_INVALID_USER_CONFIG;
                            }
                        }
                    } catch (MailerException $me) {
                        return static::STATUS_INVALID_USER_CONFIG;
                    }
                }
            } else {
                return static::STATUS_INVALID_SYSTEM_CONFIG;
            }
        } catch (MailerException $me) {
            return static::STATUS_INVALID_SYSTEM_CONFIG;
        }

        return static::STATUS_VALID_CONFIG;
    }

    /**
     * @access public
     * @param User         $user    required
     * @param Localization $locale
     * @param string       $charset
     * @return array MailConfigurations
     */
    public static function listValidMailConfigurations(User $user, Localization $locale = null, $charset = null)
    {
        $configs = array();
        try {
            $mailConfigurations = self::listMailConfigurations($user, $locale, $charset);
            foreach ($mailConfigurations AS $mailConfiguration) {
                if (self::isMailConfigurationValid($mailConfiguration)) {
                    $configs[] = $mailConfiguration;
                }
            }
        } catch (MailerException $me) {
            $GLOBALS["log"]->warn(
                "An error occurred while retrieving valid system mail configurations " . $me->getMessage()
            );
        }
        return $configs;
    }

    /**
     * @access public
     * @param User         $user    required
     * @param Localization $locale
     * @param string       $charset
     * @return array MailConfigurations
     * @throws MailerException
     */
    public static function listMailConfigurations(User $user, Localization $locale = null, $charset = null)
    {
        global $app_strings;
        $outboundEmailConfigurations = array();
        $ret                         = $user->getUsersNameAndEmail();
        $systemDefaultOutbound = null;

        if (empty($ret['email'])) {
            $systemReturn          = $user->getSystemDefaultNameAndEmail();
            $ret['email']          = $systemReturn['email'];
            $ret['name']           = $systemReturn['name'];
            $system_replyToAddress = $ret['email'];
        } else {
            $system_replyToAddress = '';
        }

        $system_replyToName = $ret['name'];
        $replyTo            = $user->emailAddress->getReplyToAddress($user, true);

        if (!empty($replyTo)) {
            $system_replyToAddress = $replyTo;
        }

        /* Retrieve any Inbound User Mail Accounts and the Outbound Mail Accounts Associated with them */
        $inboundEmail = new InboundEmail();
        $ieAccounts = $inboundEmail->retrieveAllByGroupIdWithGroupAccounts($user->id);
        $ie_ids = array_keys($ieAccounts);
        foreach ($ieAccounts as $inbox_id => $ie) {
            $name = $ie->get_stored_options('from_name');
            $addr = $ie->get_stored_options('from_addr');
            $storedOptions = Serialized::unserialize($ie->stored_options, array(), true);
            $isAllowedGroup = $ie->get_stored_options('allow_outbound_group_usage',false);
            if (!$ie->is_personal && !$isAllowedGroup) {
                continue;
            }

            $outbound_config_id = empty($storedOptions["outbound_email"]) ? null : $storedOptions["outbound_email"];
            $oe                 = null;

            if (!empty($outbound_config_id)) {
                $oe = static::loadOutboundEmail();
                $oe->retrieve($outbound_config_id);
            } else {
                if(empty($systemDefaultOutbound)) {
                    $systemDefaultOutbound = self::getSystemMailConfiguration($user, $locale, $charset);
                }
                $outbound_config_id = $systemDefaultOutbound->getConfigId();
                $oe = static::loadOutboundEmail();
                $oe->retrieve($outbound_config_id);
            }

            if ($name != null && $addr != null && !empty($outbound_config_id) && !empty($oe) && ($outbound_config_id == $oe->id)) {
                // turn the OutboundEmail object into a useable set of mail configurations
                $configurations                  = array();
                $configurations["config_id"]     = $outbound_config_id;
                $configurations["config_type"]   = "user";
                $configurations["inbox_id"]      = $inbox_id;
                $configurations["from_email"]    = $addr;
                $configurations["from_name"]     = $name;
                $label = $isAllowedGroup ?
                    'LBL_GROUP_EMAIL_ACCOUNT_CONFIGURATION' :
                    'LBL_USER_OUTBOUND_EMAIL_ACCOUNT_CONFIGURATION';
                $displayName = translate(
                    'LBL_OUTBOUND_EMAIL_CONFIGURATION_DISPLAY_NAME',
                    'OutboundEmailConfiguration'
                );
                $displayName = string_format(
                    $displayName,
                    array(
                        $name,
                        $addr,
                        translate($label, 'OutboundEmailConfiguration'),
                    )
                );
                $configurations['display_name'] = $displayName;
                $configurations["personal"]      = (bool)($ie->is_personal);
                $configurations["replyto_email"] = (!empty($storedOptions["reply_to_addr"])) ?
                    $storedOptions["reply_to_addr"] :
                    $addr;
                $configurations["replyto_name"]  = (!empty($storedOptions["reply_to_name"])) ?
                    $storedOptions["reply_to_name"] :
                    $name;
                $outboundEmailConfiguration      = self::buildOutboundEmailConfiguration(
                    $user,
                    $configurations,
                    $oe,
                    $locale,
                    $charset
                );
                $outboundEmailConfigurations[]   = $outboundEmailConfiguration;
            }
        }

        $systemUser = BeanFactory::newBean("Users");
        $systemUser->getSystemUser();

        $oe                        = static::loadOutboundEmail();
        $systemMailerConfiguration = $oe->getSystemMailerSettings();

        if ($oe->isAllowUserAccessToSystemDefaultOutbound()) {
            $system   = $systemMailerConfiguration;
            $personal = false;
        } else {
            $system   = $oe->getUsersMailerForSystemOverride($user->id);
            $personal = true;

            if (empty($system)) {
                // Create a User System-Override Configuration
                if ($user->id == $systemUser->id) {
                    $oe = $oe->createUserSystemOverrideAccount(
                        $user->id,
                        $systemMailerConfiguration->mail_smtpuser,
                        $systemMailerConfiguration->mail_smtppass
                    );
                } else {
                    $oe = $oe->createUserSystemOverrideAccount($user->id);
                }

                $system = $oe->getUsersMailerForSystemOverride($user->id);
            }
        }

        if (empty($system->id)) {
            throw new MailerException("No Valid Mail Configurations Found", MailerException::InvalidConfiguration);
        }

        // turn the OutboundEmail object into a useable set of mail configurations
        $oe = static::loadOutboundEmail();
        $oe->retrieve($system->id);

        $configurations                  = array();
        $configurations["config_id"]     = $system->id;
        $configurations["config_type"]   = $system->type;
        $configurations["inbox_id"]      = null;
        $configurations["from_email"]    = $ret["email"];
        $configurations["from_name"]     = $ret["name"];
        $label = $personal ?
            'LBL_USER_DEFAULT_OUTBOUND_EMAIL_CONFIGURATION' :
            'LBL_SYSTEM_DEFAULT_OUTBOUND_EMAIL_CONFIGURATION';
        $displayName = translate(
            'LBL_OUTBOUND_EMAIL_CONFIGURATION_DISPLAY_NAME',
            'OutboundEmailConfiguration'
        );
        $displayName = string_format(
            $displayName,
            array(
                $ret['name'],
                $ret['email'],
                translate($label, 'OutboundEmailConfiguration'),
            )
        );
        $configurations['display_name'] = $displayName;
        $configurations["personal"]      = $personal;
        $configurations["replyto_email"] = $system_replyToAddress;
        $configurations["replyto_name"]  = $system_replyToName;
        $configurations["inbound_ids"]   = $ie_ids;
        $outboundEmailConfiguration      = self::buildOutboundEmailConfiguration(
            $user,
            $configurations,
            $oe,
            $locale,
            $charset
        );
        $outboundEmailConfigurations[]   = $outboundEmailConfiguration;

        return $outboundEmailConfigurations;
    }
    /**
     * @access public
     * @param User         $user    required
     * @param Localization $locale
     * @param string       $charset
     * @return array MailConfigurations
     * @throws MailerException
     */
    public static function listOutboundMailConfigurations(User $user, Localization $locale = null, $charset = null)
    {
        global $app_strings;
        $outboundEmailConfigurations = array();
        $ret                         = $user->getUsersNameAndEmail();
        $systemDefaultOutbound = null;

        if (empty($ret['email'])) {
            $systemReturn          = $user->getSystemDefaultNameAndEmail();
            $ret['email']          = $systemReturn['email'];
            $ret['name']           = $systemReturn['name'];
            $system_replyToAddress = $ret['email'];
        } else {
            $system_replyToAddress = '';
        }

        $system_replyToName = $ret['name'];
        $replyTo            = $user->emailAddress->getReplyToAddress($user, true);

        if (!empty($replyTo)) {
            $system_replyToAddress = $replyTo;
        }


        $systemUser = BeanFactory::newBean("Users");
        $systemUser->getSystemUser();

        $oe                        = static::loadOutboundEmail();
        $systemMailerConfiguration = $oe->getSystemMailerSettings();

        if ($oe->isAllowUserAccessToSystemDefaultOutbound()) {
            $system   = $systemMailerConfiguration;
            $personal = false;
        } else {
            $system   = $oe->getUsersMailerForSystemOverride($user->id);
            $personal = true;

            if (empty($system)) {
                // Create a User System-Override Configuration
                if ($user->id == $systemUser->id) {
                    $oe = $oe->createUserSystemOverrideAccount(
                        $user->id,
                        $systemMailerConfiguration->mail_smtpuser,
                        $systemMailerConfiguration->mail_smtppass
                    );
                } else {
                    $oe = $oe->createUserSystemOverrideAccount($user->id);
                }

                $system = $oe->getUsersMailerForSystemOverride($user->id);
            }
        }

        if (empty($system->id)) {
            throw new MailerException("No Valid Mail Configurations Found", MailerException::InvalidConfiguration);
        }

        // turn the OutboundEmail object into a useable set of mail configurations
        $oe = static::loadOutboundEmail();
        $oe->retrieve($system->id);

        $configurations                  = array();
        $configurations["config_id"]     = $system->id;
        $configurations["config_type"]   = $system->type;
        $configurations["inbox_id"]      = null;
        $configurations["from_email"]    = $ret["email"];
        $configurations["from_name"]     = $ret["name"];
        $label = $personal ?
            'LBL_USER_DEFAULT_OUTBOUND_EMAIL_CONFIGURATION' :
            'LBL_SYSTEM_DEFAULT_OUTBOUND_EMAIL_CONFIGURATION';
        $displayName = translate(
            'LBL_OUTBOUND_EMAIL_CONFIGURATION_DISPLAY_NAME',
            'OutboundEmailConfiguration'
        );
        $displayName = string_format(
            $displayName,
            array(
                $ret['name'],
                $ret['email'],
                translate($label, 'OutboundEmailConfiguration'),
            )
        );
        $configurations['display_name'] = $displayName;
        $configurations["personal"]      = $personal;
        $configurations["replyto_email"] = $system_replyToAddress;
        $configurations["replyto_name"]  = $system_replyToName;
        $configurations["inbound_ids"]   = array();
        $outboundEmailConfiguration      = self::buildOutboundEmailConfiguration(
            $user,
            $configurations,
            $oe,
            $locale,
            $charset
        );
        $outboundEmailConfigurations[]   = $outboundEmailConfiguration;

        return $outboundEmailConfigurations;
    }

    /**
     * @access private
     * @param User          $user           required
     * @param array         $configurations required
     * @param OutboundEmail $outboundEmail  required
     * @param Localization  $locale
     * @param string        $charset
     * @return OutboundEmailConfiguration
     */
    public static function buildOutboundEmailConfiguration(
        User $user,
        $configurations,
        $outboundEmail,
        Localization $locale = null,
        $charset = null
    ) {
        $outboundEmailConfiguration = null;
        $mode                       = strtolower($outboundEmail->mail_sendtype);

        // setup the mailer's known configurations based on the type of mailer
        switch ($mode) {
            case self::MODE_SMTP:
                $outboundEmailConfiguration = new OutboundSmtpEmailConfiguration($user);
                $outboundEmailConfiguration->setHost($outboundEmail->mail_smtpserver);
                $outboundEmailConfiguration->setPort($outboundEmail->mail_smtpport);

                if ($outboundEmail->mail_smtpauth_req) {
                    // require authentication with the SMTP server
                    $outboundEmailConfiguration->setAuthenticationRequirement(true);
                    $outboundEmailConfiguration->setUsername($outboundEmail->mail_smtpuser);
                    $outboundEmailConfiguration->setPassword($outboundEmail->mail_smtppass);
                }

                // determine the appropriate encryption layer for the sending strategy
                if ($outboundEmail->mail_smtpssl == 1) {
                    $outboundEmailConfiguration->setSecurityProtocol(
                        OutboundSmtpEmailConfiguration::SecurityProtocolSsl
                    );
                } elseif ($outboundEmail->mail_smtpssl == 2) {
                    $outboundEmailConfiguration->setSecurityProtocol(
                        OutboundSmtpEmailConfiguration::SecurityProtocolTls
                    );
                }

                break;
            default:
                $outboundEmailConfiguration = new OutboundEmailConfiguration($user);
                break;
        }

        $outboundEmailConfiguration->setMode($mode);

        // hostname for SMTP HELO
        $hostname = !empty($GLOBALS['sugar_config']['helo_hostname'])
            ? $GLOBALS['sugar_config']['helo_hostname']
            : $GLOBALS['sugar_config']['host_name'];
        $outboundEmailConfiguration->setHostname($hostname);

        if (!empty($configurations["config_id"])) {
            $outboundEmailConfiguration->setConfigId($configurations["config_id"]);
        }

        if (!empty($configurations["config_type"])) {
            $outboundEmailConfiguration->setConfigType($configurations["config_type"]);
        }

        if (!empty($outboundEmail->name)) {
            $outboundEmailConfiguration->setConfigName($outboundEmail->name);
        }

        if (!empty($configurations["inbox_id"])) {
            $outboundEmailConfiguration->setInboxId($configurations["inbox_id"]);
        }

        if (!empty($configurations["inbound_ids"])) {
            $outboundEmailConfiguration->setInboundIds($configurations["inbound_ids"]);
        }

        if (!empty($configurations['from_email'])) {
            $fromName = empty($configurations['from_name']) ? '' : $configurations['from_name'];
            $outboundEmailConfiguration->setFrom($configurations['from_email'], $fromName);
        }

        if (!empty($configurations["display_name"])) {
            $outboundEmailConfiguration->setDisplayName($configurations["display_name"]);
        }

        if (!empty($configurations['replyto_email'])) {
            $replyToName = empty($configurations['replyto_name']) ? '' : $configurations['replyto_name'];
            $outboundEmailConfiguration->setReplyTo($configurations['replyto_email'], $replyToName);
        }

        if (!array_key_exists('personal', $configurations) || !is_bool($configurations['personal'])) {
            $configurations['personal'] = false;
        }
        $outboundEmailConfiguration->setPersonal($configurations['personal']);

        if (is_null($locale)) {
            $locale = $GLOBALS["locale"];
        }

        $outboundEmailConfiguration->setLocale($locale);

        if (is_null($charset)) {
            $charset = $locale->getPrecedentPreference("default_email_charset");
        }

        $outboundEmailConfiguration->setCharset($charset);

        return $outboundEmailConfiguration;
    }

    /**
     * @return OutboundEmail
     */
    public static function loadOutboundEmail()
    {
        return new OutboundEmail();
    }
}
