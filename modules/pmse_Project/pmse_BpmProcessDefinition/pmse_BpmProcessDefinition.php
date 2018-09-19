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
 * THIS CLASS IS FOR DEVELOPERS TO MAKE CUSTOMIZATIONS IN
 */


class pmse_BpmProcessDefinition extends pmse_BpmProcessDefinition_sugar
{
    /**
    * @inheritDoc
    */
    public function ACLAccess($view, $context = null)
    {
        switch ($view) {
            case 'list':
                if (is_array($context) && isset($context['source']) && $context['source'] === 'filter_api') {
                    return false;
                }
                break;

            case 'edit':
            case 'view':
                if (is_array($context) && isset($context['source']) && $context['source'] === 'module_api') {
                    return false;
                }
                break;
        }

        return parent::ACLAccess($view, $context);
    }

    /**
     * Gets all the locked fields for every record id given
     *
     * @param SugarBean $focus
     * @param array|string $ids array of record ids
     * @return array
     */
    public function getRelatedModuleRecords(SugarBean $focus, $ids)
    {
        // No ids means nothing to do
        if (empty($ids) || empty($focus)) {
            return array();
        }

        if (!PMSEEngineUtils::doesModuleHaveLockedFields($focus->getModuleName())) {
            return array();
        }

        // We need to make a reasonable assumption here that ids will either be
        // an imploded string of ids or an array of ids. If an array, make it a
        // string of DB quoted ids.
        if (is_array($ids)) {
            array_walk($ids, function (&$val, $key, $db) {
                $val = $db->quoted($val);
            }, $this->db);

            $ids = implode(",", $ids);
        }

        $alias = $this->db->getValidDBName($focus->table_name . '_id', false, 'alias');

        $sql = "SELECT
                    pd.pro_locked_variables def,
                    lfbr.bean_id as {$alias}
                FROM
                    $this->table_name pd
                    INNER JOIN locked_field_bean_rel lfbr
                    ON pd.id=lfbr.pd_id
                WHERE
                    lfbr.bean_module = '{$focus->module_name}' AND
                    lfbr.bean_id IN ($ids) AND
                    lfbr.deleted = 0";

        $result = $this->db->query($sql);
        $rows = array();
        while ($row = $this->db->fetchByAssoc($result, false)) {
            // In the case of empty locked field defs we need to make an array
            // since the json_decode will result in null
            $def = json_decode($row['def']);
            if ($def === null) {
                $def = [];
            }

            // If there is an existing ID to merge, merge that
            $merge = isset($rows[$row[$alias]]) ? $rows[$row[$alias]] : [];

            // Merge the mergeable and current row defs, and unique them
            $rows[$row[$alias]] = array_unique(array_merge($merge, $def));
        }

        return $rows;
    }
}
