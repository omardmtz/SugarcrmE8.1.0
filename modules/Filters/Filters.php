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


class Filters extends Basic
{
    public $module_dir = 'Filters';
    public $module_name = 'Filters';
    public $object_name = 'Filters';
    public $table_name = 'filters';
    public $importable = false;
    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $team_id;
    public $team_set_id;
    public $team_count;
    public $team_name;
    public $team_link;
    public $team_count_link;
    public $teams;
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $filter_definition;

    public function retrieve($id = '-1', $encode = true, $deleted = false)
    {
        // TODO: Remove after ENGRD-8/ENGRD-17.
        $encode = false;
        return parent::retrieve($id, $encode, $deleted);
    }

    /**
     * Get the filterable field types and their operators.
     *
     * @param string $client The client that you are working on.
     * @return array The list of filterable field types and their operators.
     */
    public static function getOperators($client = 'base')
    {
        $filtersMetadata = MetaDataManager::getManager($client)->getSugarFilters();
        if(empty($filtersMetadata['operators']['meta'])) {
            return array();
        }
        return $filtersMetadata['operators']['meta'];
    }
}
