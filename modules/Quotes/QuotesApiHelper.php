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



class QuotesApiHelper extends SugarBeanApiHelper
{
    /**
     * This function sets up shipping and billing address for new Quote.
     *
     * @param SugarBean|Quote $quote The current SugarBean that is being worked with
     * @param array $submittedData The data from the request
     * @param array $options Any Options that may have been passed in.
     * @return array|boolean An array of validation errors if any occurred, otherwise `true`.
     */
    public function populateFromApi(SugarBean $quote, array $submittedData, array $options = array())
    {
        parent::populateFromApi($quote, $submittedData, $options);

        // valid relate modules
        $valid_relate_modules = array('Contacts', 'Accounts');
        // Bug #57888 : REST API: Create related quote must populate billing/shipping contact and account
        if (isset($submittedData['relate_module'], $submittedData['relate_record']) &&
            in_array($submittedData['relate_module'], $valid_relate_modules)
        ) {
            $this->setAddressFromBean($submittedData['relate_module'], $submittedData['relate_record'], $quote);
        } else {
            // we are not on a related record, so lets check the field and fill in the data correctly
            $hasBillingAccountId = (isset($quote->billing_account_id) && !empty($quote->billing_account_id));
            $hasShippingAccountId = (isset($quote->shipping_account_id) && !empty($quote->shipping_account_id));
            $hasBillingContactId = (isset($quote->billing_contact_id) && !empty($quote->billing_contact_id));
            $hasShippingContactId = (isset($quote->shipping_contact_id) && !empty($quote->shipping_contact_id));

            if ($hasBillingAccountId) {
                $account = BeanFactory::getBean('Accounts', $quote->billing_account_id);
                $this->processBeanAddressFields($account, $quote, 'billing', 'billing', 'shipping');
            } elseif (!$hasBillingAccountId && $hasBillingContactId) {
                $contact = BeanFactory::getBean('Contacts', $quote->billing_contact_id);
                $this->processBeanAddressFields($contact, $quote, 'shipping', 'primary', 'alt');
            }

            if (!$hasShippingAccountId && !$hasShippingContactId && $hasBillingAccountId) {
                // we don't have a id set for the shipping account or contact, pull the account from the billing
                $quote->shipping_account_id = $quote->billing_account_id;
                $hasShippingAccountId = true;
            }

            if ($hasShippingAccountId && !$hasShippingContactId) {
                $account = BeanFactory::getBean('Accounts', $quote->shipping_account_id);
                $this->processBeanAddressFields($account, $quote, 'shipping', 'shipping', 'billing');
            } elseif ($hasShippingContactId) {
                $contact = BeanFactory::getBean('Contacts', $quote->shipping_contact_id);
                $this->processBeanAddressFields($contact, $quote, 'shipping', 'primary', 'alt');
            }
        }

        return true;
    }

    /**
     * Handle Setting the Addresses
     *
     * @param String $fromModule
     * @param String $fromId
     * @param SugarBean|Quote $quote
     */
    protected function setAddressFromBean($fromModule, $fromId, SugarBean $quote)
    {
        $fromBean = BeanFactory::getBean($fromModule, $fromId);
        if ($fromModule == 'Contacts') {
            $quote->shipping_contact_id = $fromId;
            $quote->billing_contact_id = $fromId;
            $typeKey = 'primary';
            $altTypeKey = 'alt';
        } elseif ($fromModule == 'Accounts') {
            $quote->billing_account_id = $fromId;
            $quote->shipping_account_id = $fromId;
            $typeKey = 'shipping';
            $altTypeKey = 'billing';
        }

        // set the shipping address first
        $this->processBeanAddressFields($fromBean, $quote, 'shipping', $typeKey, $altTypeKey);

        // change the type key for the billing address, when we are pulling from Accounts
        if ($fromModule == 'Accounts') {
            $typeKey = 'billing';
            $altTypeKey = 'shipping';
        }

        // if the initial bean has an account set on it, we need to to set the billing address
        // to the account address fields vs the contact address fields.
        // if there is no account_id then it will just set the billing address fields from the contact
        if (!empty($fromBean->account_id)) {
            $quote->billing_account_id = $fromBean->account_id;
            $quote->shipping_account_id = $fromBean->account_id;

            unset($fromBean);

            $fromBean = BeanFactory::getBean('Accounts', $quote->shipping_account_id);
            $typeKey = 'billing';
            $altTypeKey = 'shipping';
        }

        // set the billing address
        $this->processBeanAddressFields($fromBean, $quote, 'billing', $typeKey, $altTypeKey);

    }

    /**
     * Utility Method to set the fields on a given $quote from another bean.
     *
     * @param SugarBean $fromBean
     * @param SugarBean|Quote $quote
     * @param string $type What field type are we setting on the $quote
     * @param string $primaryField The primary field on the $fromBean
     * @param string $altField The secondary field on the $fromBean
     */
    protected function processBeanAddressFields($fromBean, $quote, $type, $primaryField, $altField)
    {
        $fields = array('street', 'city', 'state', 'postalcode', 'country');
        foreach ($fields as $field) {
            $quoteField = $type . "_address_" . $field;
            $quote->$quoteField = $this->getAddressFormContact(
                $quote->$quoteField,
                $fromBean,
                $primaryField . "_address_" . $field,
                $altField . "_address_" . $field
            );
        }
    }

    /**
     * Utility method to pick which string to return, if $quote_value is not empty, just return it,
     * otherwise check $property and then $alt_property for a value, if they are both empty, this will
     * just return an empty string
     *
     * @param string $quote_value The current value on the quote
     * @param SugarBean $fromBean The SugarBean we are looking at for a value
     * @param string $primaryField The first field to check
     * @param string $altField The second field to check
     * @return string
     */
    protected function getAddressFormContact($quote_value, $fromBean, $primaryField, $altField)
    {
        return !empty($quote_value) ? $quote_value
            : (isset($fromBean->$primaryField) ? $fromBean->$primaryField
                : (isset($fromBean->$altField) ? $fromBean->$altField
                    : ''));
    }
}
