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


class CampaignLogApiHelper extends SugarBeanApiHelper
{
    /**
     * This function adds support for the related_name field (of type 'function' which is no longer supported)
     * @param $bean SugarBean The bean you want formatted
     * @param $fieldList array Which fields do you want formatted and returned (leave blank for all fields)
     * @param $options array Currently no options are supported
     * @return array The bean in array format, ready for passing out the API to clients.
     */
    public function formatForApi(SugarBean $bean, array $fieldList = array(), array $options = array())
    {
        $data = parent::formatForApi($bean, $fieldList, $options);

        if(in_array('related_name', $fieldList) && !empty($bean->related_id) && !empty($bean->related_type)) {
            $relatedBean = BeanFactory::getBean($bean->related_type, $bean->related_id);
            if(!empty($relatedBean)) {
                if ($bean->related_type == 'CampaignTrackers') {
                    $relatedNameField = 'tracker_url';
                } elseif ($bean->related_type == 'Contacts' || $bean->related_type == 'Leads' || $bean->related_type == 'Prospects') {
                    $relatedNameField = 'full_name';
                } else {
                    $relatedNameField = 'name';
                }

                $data['parent_id'] = $bean->related_id;
                $data['parent_type'] = $bean->related_type;
                $data['related_name'] = $relatedBean->$relatedNameField;
            }
        }
        return $data;
    }
}
