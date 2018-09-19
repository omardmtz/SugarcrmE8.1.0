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


class ForecastWorksheetsApiHelper extends SugarBeanApiHelper
{
    /**
     * Formats the bean so it is ready to be handed back to the API's client. Certian fields will get extra processing
     * to make them easier to work with from the client end.
     *
     * @param $bean SugarBean|ForecastWorksheet The bean you want formatted
     * @param $fieldList array Which fields do you want formatted and returned (leave blank for all fields)
     * @param $options array Currently no options are supported
     * @return array The bean in array format, ready for passing out the API to clients.
     */
    public function formatForApi(SugarBean $bean, array $fieldList = array(), array $options = array())
    {
        $data = parent::formatForApi($bean, $fieldList, $options);
        $data['parent_deleted'] = 0;
        if ($bean->draft == 0) {
            $sq = new SugarQuery();
            $sq->select('id');
            $sq->from(BeanFactory::newBean($bean->parent_type))->where()
                ->equals('id', $bean->parent_id);
            $beans = $sq->execute();
            if (empty($beans)) {
                $data['parent_name'] = $data['name'];
                $data['parent_deleted'] = 1;
            }
        }
        return $data;

    }
}
