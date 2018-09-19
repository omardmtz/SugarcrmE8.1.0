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

class UserSignaturesApiHelper extends SugarBeanApiHelper
{
    /**
     * {@inheritdoc}
     *
     * Adds the is_default flag based on user preference
     *
     * @param SugarBean $bean
     * @param array $fieldList
     * @param array $options
     * @return array
     */
    public function formatForApi(SugarBean $bean, array $fieldList = array(), array $options = array())
    {
        global $current_user;

        $data = parent::formatForApi($bean, $fieldList, $options);
        $defaultSignature = $current_user->getPreference('signature_default');
        $data['is_default'] = ($bean->id === $defaultSignature);
        return $data;
    }
}
