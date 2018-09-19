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


class SugarFieldEmail extends SugarFieldBase
{
    public $needsSecondaryQuery = true;
    protected $formTemplateMap = array(
        'popup_query_form' => 'Base',
    );

    /**
     * {@inheritDoc}
     *
     * Unsets the email record from the data array if the user does not have access
     */
    public function apiFormatField(
        array &$data,
        SugarBean $bean,
        array $args,
        $fieldName,
        $properties,
        array $fieldList = null,
        ServiceBase $service = null
    ) {
        $this->ensureApiFormatFieldArguments($fieldList, $service);

        if (empty($bean->emailAddress->hasFetched)) {
            $emailsRaw = $bean->emailAddress->getAddressesByGUID($bean->id, $bean->module_name);
        } else {
            $emailsRaw = $bean->emailAddress->addresses;
        }

        if (!empty($emailsRaw)) {
            array_walk($emailsRaw, array($this, "formatEmails"));
            $data[$fieldName] = $emailsRaw;
        } else {
            $data[$fieldName] = array();
        }
    }
    /**
     * This should be called when the bean is saved from the API. 
     * Most fields can just use default, which calls the field's 
     * individual ->save() function instead.
     * 
     * @param SugarBean $bean the bean performing the save
     * @param array $params an array of parameters relevant to the save, which will be an array passed up to the API
     * @param string $field The name of the field to save (the vardef name, not the form element name)
     * @param array $properties Any properties for this field
     */
    public function apiSave(SugarBean $bean, array $params, $field, $properties)
    {
        if (!is_array($params[$field])) {
            // Not an array, don't do anything.
            return;
        }

        if (!isset($bean->emailAddress)) {
            $bean->emailAddress = BeanFactory::newBean('EmailAddresses');
        }
        if (empty($bean->emailAddress->addresses) 
            && !isset($bean->emailAddress->hasFetched)) {
            $oldAddresses = $bean->emailAddress->getAddressesByGUID($bean->id, $bean->module_name);
        } else {
            $oldAddresses = $bean->emailAddress->addresses;
        }
        
        array_walk($params[$field], array($this, 'formatEmails'));
        $emailOptoutDefault = !empty($GLOBALS['sugar_config']['new_email_addresses_opted_out']);

        $bean->emailAddress->addresses = array();
        foreach ($params[$field] as $email ) {
            if (empty($email['email_address'])) {
                // Can't save an empty email address
                continue;
            }
            // Search each one for a matching set, otherwise use the defaults
            $mergeAddr = array(
                'primary_address' => false,
                'invalid_email' => false,
                'opt_out' => $emailOptoutDefault,
            );
            foreach ($oldAddresses as $address) {
                if (strtolower($address['email_address']) == strtolower($email['email_address'])) {
                    $mergeAddr = $address;
                    break;
                }
            }

            $email = array_merge($mergeAddr, $email);
            if (!SugarEmailAddress::isValidEmail($email['email_address'])) {
                throw new SugarApiExceptionInvalidParameter("{$email['email_address']} is an invalid email address");
            }
            $bean->emailAddress->addAddress($email['email_address'],
                                            $email['primary_address'],
                                            false,
                                            $email['invalid_email'],
                                            $email['opt_out']);
        }

        $bean->emailAddress->save($bean->id, $bean->module_dir, $params[$field]);

        // Here is a hack for SugarEmailAddress.php so it doesn't attempt a legacy save
        $bean->emailAddress->dontLegacySave = true;
        $bean->emailAddress->populateLegacyFields($bean);
    }

    /**
     * Format a Raw email array record from the email_address relationship
     * 
     * @param array $rawEmail 
     * @return array
     */
    public function formatEmails(array &$rawEmail, $key) 
    {
        static $emailProperties = array(
            'email_address_id' => true,
            'email_address' => true,
            'opt_out' => true,
            'invalid_email' => true,
            'primary_address' => true,
            'reply_to_address' => true,
        );

        static $boolProperties = array(
            'opt_out',
            'invalid_email',
            'primary_address',
            'reply_to_address',
        );            

        $rawEmail = array_intersect_key($rawEmail, $emailProperties);
        
        foreach ($boolProperties as $prop) {
            if (isset($rawEmail[$prop])) {
                $rawEmail[$prop] = (bool)$rawEmail[$prop];
            }
        }
        
        if (isset($rawEmail['email_address'])) {
            $rawEmail['email_address'] = trim($rawEmail['email_address']);
        }
    }

    /**
     * Run a secondary query and populate the results into the array of beans
     *
     * @overrides SugarFieldBase::runSecondaryQuery
     */
    public function runSecondaryQuery($fieldName, SugarBean $seed, array $beans)
    {
        if (empty($beans)) {
            return;
        }

        if (!isset($seed->emailAddress)) {
            $seed->emailAddress = BeanFactory::newBean('EmailAddresses');
        }
        
        $query = $seed->emailAddress->getEmailsQuery($seed->module_name);
        
        $query->where()->in('ear.bean_id', array_keys($beans));
        // Directly fetch rows because the emailAddress bean expects addresses as arrays, not beans
        $query->select('ear.bean_id');
        $rows = $query->execute();

        foreach ($beans as $bean) {
            if (!isset($bean->emailAddress)) {
                $bean->emailAddress = BeanFactory::newBean('EmailAddresses');
            }
            // This way if there are no email addresses attached to a bean we don't double-fetch
            $bean->emailAddress->hasFetched = true;
        }

        // Primary address works in weird ways.
        // The getEmailsQuery() code orders by primary_address
        // So the first email address returned by bean should be primary
        // Even if it isn't flagged because the old primary was deleted or whatever
        $has_primary = array();

        foreach ($rows as $row) {
            $row['bean_id'] = $GLOBALS['db']->fromConvert($row['bean_id'], 'id');
            $bean = $beans[$row['bean_id']];
            if (empty($has_primary[$row['bean_id']])) {
                $row['primary_address'] = true;
                $has_primary[$row['bean_id']] = true;
            } else {
                $row['primary_address'] = false;
            }
            $bean->emailAddress->addAddress(
                $row['email_address'],
                $row['primary_address'],
                false,
                $row['invalid_email'],
                $row['opt_out'],
                $row['id'],
                false
            );
        }
    }
}
