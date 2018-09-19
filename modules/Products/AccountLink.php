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


class AccountLink extends Link2
{
    public function getSubpanelQuery($params = array(), $return_array = false)
    {
        $db = DBManagerFactory::getInstance();
        $result = parent::getSubpanelQuery($params, $return_array);
        if($return_array)
        {
            $result ['join'] .= ' LEFT JOIN quotes ON products.quote_id = quotes.id ';
            $result['where'] .= ' AND (quotes.quote_stage IS NULL OR quotes.quote_stage NOT IN (' . $db->quoted('Closed Lost') . ',' . $db->quoted('Closed Dead') . ')) AND ( quotes.deleted = 0 OR quotes.deleted IS NULL )';
            array_push($result['join_tables'], 'quotes');
        }
        return $result;
    }
}