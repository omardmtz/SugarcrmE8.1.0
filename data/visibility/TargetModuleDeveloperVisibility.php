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

/**
 * ACL Visiblity driven by a related module.
 * Only supports SugarQuery
 */
class TargetModuleDeveloperVisibility extends ACLVisibility
{

    protected $targetModuleField = "";

    /**
     * @param SugarBean $bean
     */
    public function __construct($bean, $options)
    {
        if (!empty($options['targetModuleField'])) {
            $this->targetModuleField = $options['targetModuleField'];
        }

        return parent::__construct($bean);
    }

    /**
     * Add visibility clauses to the WHERE part of the query for SugarQuery Object
     *
     * @param SugarQuery $query
     *
     * @return SugarQuery
     */
    public function addVisibilityWhereQuery(SugarQuery $query, $options = array())
    {
        global $current_user;

        if (!empty($this->targetModuleField) && !$current_user->isAdmin()) {
            $modules = $current_user->getDeveloperModules();
            if (empty($modules)) {
                $modules = array('');
            }
            $query->where()->in($this->targetModuleField, $modules);
        }

        return $query;
    }

    /**
    * Add visibility clauses to the WHERE part of the query
    * @param string $query
    * @return string
    */
    public function addVisibilityWhere(&$query)
    {
        global $current_user;

        if (!empty($this->targetModuleField) && !$current_user->isAdmin()) {
            $table_alias = $this->getOption('table_alias');
            $db = DBManagerFactory::getInstance();
            if (empty($table_alias)) {
                $table_alias = $this->bean->table_name;
            }
            $modules = array_map(function ($value) use ($db) {
                    return $db->quoted($value);
                },
                $current_user->getDeveloperModules()
            );

            if (empty($modules)) {
                $devWhere = "$table_alias.{$this->targetModuleField} IS NULL";
            } else {
                $devWhere = "$table_alias.{$this->targetModuleField} IN (" . implode(',', $modules) . ")";
            }
            if (!empty($query)) {
                $query .= " AND $devWhere";
            } else {
                $query = $devWhere;
            }
        }

        return $query;
    }
}
