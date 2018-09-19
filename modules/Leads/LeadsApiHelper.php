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


class LeadsApiHelper extends SugarBeanApiHelper
{
    public function populateFromApi(SugarBean $bean, array $submittedData, array $options = array())
    {
        foreach ($submittedData as $fieldName => $data) {
            if (isset($bean->field_defs[$fieldName])) {
                $properties = $bean->field_defs[$fieldName];
                $type = !empty($properties['custom_type']) ? $properties['custom_type'] : $properties['type'];
                /* Field with name=email is the only field of type=email supported at this time */
                if ($type === 'email') {
                    if ($fieldName !== 'email') {
                        unset($submittedData[$fieldName]);
                    }
                }
            }
        }

        parent::populateFromApi($bean, $submittedData, $options);

        return true;
    }
}

