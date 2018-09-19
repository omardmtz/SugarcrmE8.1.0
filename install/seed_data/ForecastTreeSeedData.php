<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

class ForecastTreeSeedData {

    private $common;

    public function __construct()
    {
        $this->common = new Common();
    }

    /**
     * populateUserSeedData
     * This function populates the forecast_tree table with user data based on the entries
     * in the users table and the reports_to_id structure.
     * @return array
     */
    public function populateUserSeedData()
    {
        global $dictionary;
        require_once 'modules/TableDictionary.php';

        $results = $GLOBALS['db']->query("SELECT id, user_name, reports_to_id FROM users WHERE status = 'Active'");
        while(($row = $GLOBALS['db']->fetchByAssoc($results)))
        {
            $GLOBALS['db']->insertParams(
                $dictionary['forecast_tree']['table'],
                $dictionary['forecast_tree']['fields'],
                array(
                    'id' => $row['id'],
                    'name' => $row['user_name'],
                    'hierarchy_type' => 'users',
                    'user_id' => $row['id'],
                    'parent_id' => $row['reports_to_id'],
                )
            );
        }
    }

    public function populateProductCategorySeedData()
    {
        $results = $GLOBALS['db']->query("SELECT id, name, parent_id, assigned_user_id, 'category' type FROM product_categories WHERE deleted=0 UNION SELECT id, name, category_id, '1', 'product' type FROM product_templates WHERE deleted=0");
        while(($row = $GLOBALS['db']->fetchByAssoc($results)))
        {
            $parent_id = empty($row['parent_id']) ? '' : $row['parent_id'];
            $assigned_user_id = empty($row['assigned_user_id']) ? '1' : $row['assigned_user_id'];
            $query = "INSERT INTO forecast_tree (id, name, hierarchy_type, user_id, parent_id) VALUES ('{$row['id']}', '{$row['name']}', 'products', '{$assigned_user_id}', '{$parent_id}')";
            $GLOBALS['db']->query($query);
        }
    }

}
