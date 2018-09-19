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

class MailerException extends Exception
{
    const ResourceNotFound              = 1;
    const InvalidConfiguration          = 2;
    const InvalidHeader                 = 3;
    const InvalidEmailAddress           = 4;
    const FailedToSend                  = 5;
    const FailedToConnectToRemoteServer = 6;
    const FailedToTransferHeaders       = 7;
    const InvalidMessageBody            = 8;
    const InvalidAttachment             = 9;
    const InvalidMailer                 = 10;
    const ExecutableAttachment          = 11;

    static protected $errorMessageMappings = array(
        self::ResourceNotFound              => 'LBL_INTERNAL_ERROR',
        self::InvalidConfiguration          => 'LBL_INVALID_CONFIGURATION',
        self::InvalidHeader                 => 'LBL_INVALID_HEADER',
        self::InvalidEmailAddress           => 'LBL_INVALID_EMAIL',
        self::FailedToSend                  => 'LBL_INTERNAL_ERROR',
        self::FailedToConnectToRemoteServer => 'LBL_FAILED_TO_CONNECT',
        self::FailedToTransferHeaders       => 'LBL_INTERNAL_ERROR',
        self::InvalidAttachment             => 'LBL_INVALID_ATTACHMENT',
        self::InvalidMailer                 => 'LBL_INTERNAL_ERROR',
        self::ExecutableAttachment          => 'LBL_EXECUTABLE_ATTACHMENT',
    );

    public function getLogMessage() {
        return "MailerException - @(" . basename($this->getFile()) . ":" .  $this->getLine() . " [" . $this->getCode() . "]" . ") - " . $this->getMessage();
    }
    public function getTraceMessage() {
        return "MailerException: (Trace)\n" . $this->getTraceAsString();
    }
    public function getUserFriendlyMessage() {
        $moduleName = 'Emails';
        if (isset(self::$errorMessageMappings[$this->getCode()])) {
            $exception_code = self::$errorMessageMappings[$this->getCode()];
        }
        if (empty($exception_code)) {
            $exception_code = 'LBL_INTERNAL_ERROR'; //use generic message if a user-friendly version is not available
        }
        return translate($exception_code, $moduleName);
    }
}
