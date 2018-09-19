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


class ContactsApiHelper extends SugarBeanApiHelper
{
    /**
     * This function checks the sync_contact var and does the appropriate actions
     * @param SugarBean $bean
     * @param array $submittedData
     * @param array $options
     * @return array
     */
    public function populateFromApi(SugarBean $bean, array $submittedData, array $options = array())
    {
        global $current_user;
        $data = parent::populateFromApi($bean, $submittedData, $options);

        if ($data) {
            if (!empty($bean->emailAddress) && $bean->emailAddress->addresses != $bean->emailAddress->fetchedAddresses
            ) {
                $bean->emailAddress->populateLegacyFields($bean);
            }

            if (isset($submittedData['sync_contact'])) {
                $bean->sync_contact = $submittedData['sync_contact'];
            }
        }

        return $data;
    }


}
