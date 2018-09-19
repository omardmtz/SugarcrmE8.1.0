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

use Sugarcrm\Sugarcrm\IdentityProvider\Authentication\Config;

class UsersApiHelper extends SugarBeanApiHelper
{
    /**
     * @var Config
     */
    protected $idpConfig = null;

    /**
     * Formats the bean so it is ready to be handed back to the API's client.
     * Checks if user has access to a given record (if record module/id is specified in the api args)
     *
     * @param $bean SugarBean The bean you want formatted
     * @param $fieldList array Which fields do you want formatted and returned (leave blank for all fields)
     * @param $options array Currently no options are supported
     * @return array The bean in array format, ready for passing out the API to clients.
     */
    public function formatForApi(SugarBean $bean, array $fieldList = array(), array $options = array())
    {
        $data = parent::formatForApi($bean, $fieldList, $options);

        $args = $options['args'];
        if (!empty($args['has_access_module']) && !empty($args['has_access_record'])) {
            $data['has_access'] = $this->checkUserAccess($bean, $args['has_access_module'], $args['has_access_record']);
        }

        return $data;
    }

    /**
     * Uses the checkUserAccess SugarBean method to see if the user being formatted has access to the given record
     *
     * @param SugarBean $user The user to check access for
     * @param $module The module of the record we're checking access to
     * @param $recordId The id of the record we're checking access to
     * @return bool True if the user has access, false otherwise
     */
    protected function checkUserAccess(SugarBean $user, $module, $recordId)
    {
        $record = BeanFactory::newBean($module);
        $record->id = $recordId;
        return $record->checkUserAccess($user);
    }


    public function populateFromApi(SugarBean $bean, array $submittedData, array $options = array())
    {
        if ($this->getIdpConfig()->isIDMModeEnabled()) {
            $submittedData = $this->filterIDMModeDisabledFields($bean, $submittedData);
        }
        parent::populateFromApi($bean, $submittedData, $options);
        if (!$bean->new_with_id && !empty($bean->id)) {
            return true;
        }

        if (empty($submittedData) || empty($submittedData['user_name'])) {
            throw new SugarApiExceptionMissingParameter("Missing username");
        }

        return true;
    }

    /**
     * deny edit non-editable fields
     * @param SugarBean $bean
     * @param array $submittedData
     * @return array
     */
    protected function filterIDMModeDisabledFields(SugarBean $bean, array $submittedData)
    {
        $submittedData = array_diff_key($submittedData, $this->getIdpConfig()->getIDMModeDisabledFields());
        if (!empty($submittedData['email'])) {
            $submittedData['email'] = $this->filterEmailField($bean, $submittedData['email']);
        }
        return $submittedData;
    }

    /**
     * filter emails
     * @param SugarBean $bean
     * @param array $emails
     * @return array
     */
    protected function filterEmailField(SugarBean $bean, array $emails)
    {
        /** @var User $bean */
        if (!is_array($emails)) {
            return [];
        }
        $primaryAddress = $bean->emailAddress->getPrimaryAddress($bean);
        $primaryAddressExists = false;
        foreach ($emails as $key => $email) {
            if (empty($email['email_address'])) {
                unset($emails[$key]);
                continue;
            }

            if (!empty($email['primary_address']) && strcasecmp($primaryAddress, $email['email_address']) != 0) {
                unset($emails[$key]);
                continue;
            }

            $emails[$key]['primary_address'] = false;
            if (strcasecmp($primaryAddress, $email['email_address']) == 0) {
                $emails[$key]['primary_address'] = true;
                $primaryAddressExists = true;
            }
        }
        if (!$primaryAddressExists) {
            $emails[] = $this->getPrimaryEmailAddressInApiFormat($bean);
        }
        return $emails;
    }

    /**
     * return user primary address in API format
     * @param SugarBean $bean
     * @return array
     */
    protected function getPrimaryEmailAddressInApiFormat(SugarBean $bean)
    {
        $bean->load_relationship('email_addresses_primary');
        $primaryEmailAddresses = $bean->email_addresses_primary->getBeans();
        $primaryEmailAddress = array_pop($primaryEmailAddresses);
        $rawData = array_pop($bean->email_addresses_primary->rows);
        return [
            'email_address_id' => $primaryEmailAddress->id,
            'email_address' => $primaryEmailAddress->email_address,
            'invalid_email' => (bool) $primaryEmailAddress->invalid_email,
            'opt_out' => (bool) $primaryEmailAddress->opt_out,
            'reply_to_address' => (bool) $rawData['reply_to_address'],
            'primary_address' => true,
        ];
    }

    /**
     * Return idp config
     * @return Config
     */
    protected function getIdpConfig()
    {
        if (is_null($this->idpConfig)) {
            $this->idpConfig = new Config(\SugarConfig::getInstance());
        }
        return $this->idpConfig;
    }
}
