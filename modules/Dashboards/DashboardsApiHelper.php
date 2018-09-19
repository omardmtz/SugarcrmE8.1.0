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


class DashboardsApiHelper extends SugarBeanApiHelper
{
    /**
     * 'view' is deprecated because it's reserved db word.
     * Some old API (before 7.2.0) can use 'view'.
     * Because of that API will use 'view' as 'view_name' if 'view_name' isn't present.
     *
     * @param SugarBean $bean
     * @param array     $submittedData
     * @param array     $options
     *
     * @return array
     */
    public function populateFromApi(SugarBean $bean, array $submittedData, array $options = array())
    {
        if (isset($submittedData['view']) && !isset($submittedData['view_name'])) {
            $submittedData['view_name'] = $submittedData['view'];
        }
        return parent::populateFromApi($bean, $submittedData, $options);
    }

    /**
     * 'view' is deprecated because it's reserved db word.
     * Some old API (before 7.2.0) can use 'view'.
     * Because of that API will return 'view' with the same value as 'view_name'.
     *
     * @param SugarBean $bean
     * @param array     $fieldList
     * @param array     $options
     *
     * @return array
     */
    public function formatForApi(SugarBean $bean, array $fieldList = array(), array $options = array())
    {
        $data = parent::formatForApi($bean, $fieldList, $options);
        if (isset($data['view_name'])) {
            $data['view'] = $data['view_name'];
        }
        return $data;
    }
}
