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

class OutboundEmailApi extends ModuleApi
{
    /**
     * {@inheritdoc}
     */
    public function registerApiRest()
    {
        return [
            'create' => [
                'reqType' => 'POST',
                'path' => ['OutboundEmail'],
                'pathVars' => ['module'],
                'method' => 'createRecord',
                'shortHelp' => 'This method creates a new OutboundEmail record',
                'longHelp' => 'modules/OutboundEmail/clients/base/api/help/outbound_email_post_help.html',
                'exceptions' => [
                    'SugarApiExceptionInvalidParameter',
                    'SugarApiExceptionMissingParameter',
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionNotFound',
                    'SugarApiException',
                ],
            ],
            'update' => [
                'reqType' => 'PUT',
                'path' => ['OutboundEmail', '?'],
                'pathVars' => ['module', 'record'],
                'method' => 'updateRecord',
                'shortHelp' => 'This method updates an OutboundEmail record',
                'longHelp' => 'modules/OutboundEmail/clients/base/api/help/outbound_email_record_put_help.html',
                'exceptions' => [
                    'SugarApiExceptionInvalidParameter',
                    'SugarApiExceptionMissingParameter',
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionNotFound',
                    'SugarApiException',
                ],
            ],
        ];
    }

    /**
     * Only "user" accounts can be created. The "system" and "system-override" accounts are always created by the
     * application.
     *
     * {@inheritdoc}
     */
    public function createRecord(ServiceBase $api, array $args)
    {
        $this->requireArgs($args, ['module']);
        $systemTypes = [
            OutboundEmail::TYPE_SYSTEM,
            OutboundEmail::TYPE_SYSTEM_OVERRIDE,
        ];

        if (isset($args['type']) && in_array($args['type'], $systemTypes)) {
            throw new SugarApiExceptionNotAuthorized(
                'EXCEPTION_CREATE_SYSTEM_ACCOUNT_NOT_AUTHORIZED',
                [
                    'type' => $args['type'],
                    'module' => translate('LBL_MODULE_NAME', $args['module']),
                ],
                $args['module']
            );
        }

        return parent::createRecord($api, $args);
    }

    /**
     * {@inheritdoc}
     * @uses OutboundEmail::saveSystem() to save the "system" account.
     */
    protected function saveBean(SugarBean $bean, ServiceBase $api, array $args)
    {
        $this->validateSmtpConfiguration($bean);

        if ($bean->type === OutboundEmail::TYPE_SYSTEM) {
            $bean->saveSystem(true);
            BeanFactory::unregisterBean($bean->module_name, $bean->id);
        } else {
            parent::saveBean($bean, $api, $args);
        }
    }

    /**
     * Get Mailer instance from MailerFactory
     *
     * @param OutboundEmailConfiguration $outboundEmailConfiguration
     * @return mixed Mailer
     */
    protected function getMailer(OutboundEmailConfiguration $outboundEmailConfiguration)
    {
        return MailerFactory::getMailer($outboundEmailConfiguration);
    }

    /**
     * Validate the SMTP account settings and verify that the SMTP server can be successfully connected to.
     *
     * @param SugarBean $oe
     * @throws SugarApiException
     */
    private function validateSmtpConfiguration(SugarBean $oe)
    {
        try {
            $configurations = array('from_email' => 'a@a');
            $outboundEmailConfiguration = OutboundEmailConfigurationPeer::buildOutboundEmailConfiguration(
                $GLOBALS['current_user'],
                $configurations,
                $oe
            );

            $mailer = $this->getMailer($outboundEmailConfiguration);
            if (empty($mailer)) {
                throw new MailerException('Invalid Mailer', MailerException::InvalidMailer);
            }
            $mailer->connect();
        } catch (MailerException $e) {
            throw new SugarApiException(
                $e->getUserFriendlyMessage(),
                null,
                'Emails',
                422,
                'smtp_server_error'
            );
        }
    }
}
