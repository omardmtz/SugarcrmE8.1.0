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

class EmailAddressesApi extends ModuleApi
{
    /**
     * Adds email_address to the list of fields that cannot be updated because email address strings are immutable.
     *
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->disabledUpdateFields[] = 'email_address';
        $this->disabledUpdateFields[] = 'email_address_caps';
        parent::__construct();
    }

    /**
     * Registers the create route for EmailAddresses to guarantee that {@link EmailAddressesApi::createBean()} is used
     * in place of {@link ModuleApi::createBean()}.
     *
     * Registers the update route for EmailAddresses to guarantee that {@link EmailAddressesApi::__construct()} is used.
     *
     * {@inheritdoc}
     */
    public function registerApiRest()
    {
        return array(
            'create' => array(
                'reqType' => 'POST',
                'path' => array('EmailAddresses'),
                'pathVars' => array('module'),
                'method' => 'createRecord',
                'shortHelp' => 'This method creates a new EmailAddresses record',
                'longHelp' => 'modules/EmailAddresses/clients/base/api/help/email_addresses_record_post_help.html',
                'exceptions' => array(
                    'SugarApiExceptionInvalidParameter',
                    'SugarApiExceptionMissingParameter',
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionNotFound',
                ),
            ),
            'update' => array(
                'reqType' => 'PUT',
                'path' => array('EmailAddresses', '?'),
                'pathVars' => array('module', 'record'),
                'method' => 'updateRecord',
                'shortHelp' => 'This method updates an EmailAddresses record',
                'longHelp' => 'modules/EmailAddresses/clients/base/api/help/email_addresses_record_put_help.html',
                'exceptions' => array(
                    'SugarApiExceptionInvalidParameter',
                    'SugarApiExceptionMissingParameter',
                    'SugarApiExceptionNotAuthorized',
                    'SugarApiExceptionNotFound',
                ),
            ),
        );
    }

    /**
     * If the specified email address already exists, then that record is returned without making any changes.
     *
     * {@inheritdoc}
     */
    public function createBean(ServiceBase $api, array $args, array $additionalProperties = array())
    {
        $this->requireArgs($args, array('email_address'));

        if (!SugarEmailAddress::isValidEmail($args['email_address'])) {
            throw new SugarApiExceptionInvalidParameter("Invalid email address: {$args['email_address']}");
        }

        // Does this email address already exist?
        $address = new SugarEmailAddress();
        $guid = $address->getGuid($args['email_address']);

        if (empty($guid)) {
            // Create a new email address.
            $args['email_address_caps'] = strtoupper($args['email_address']);
            return parent::createBean($api, $args, $additionalProperties);
        } else {
            // Return the existing email address.
            $args['record'] = $guid;
            return $this->reloadBean($api, $args);
        }
    }
}
