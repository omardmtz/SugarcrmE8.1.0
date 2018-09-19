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
/*********************************************************************************

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

use Doctrine\DBAL\Connection;
use Sugarcrm\Sugarcrm\Audit\Formatter as AuditFormatter;
use Sugarcrm\Sugarcrm\DependencyInjection\Container;

require_once 'modules/Audit/field_assoc.php';

class Audit extends SugarBean
{
    public $module_dir = "Audit";
    public $object_name = "Audit";

    public $disable_vardefs = true;
    public $disable_custom_fields = true;

    public $genericAssocFieldsArray = array();
    public $moduleAssocFieldsArray = array();

    private $fieldDefs;

    // This is used to retrieve related fields from form posts.
    public $additional_column_fields = Array();

    public function __construct()
    {
        parent::__construct();
        $this->team_id = 1; // make the item globally accessible

        // load up the assoc fields array from globals
        $this->getAssocFieldsArray();
        $this->getFieldDefs();
    }

    public $new_schema = true;

    public function get_summary_text()
    {
        return $this->name;
    }

    public function fill_in_additional_list_fields()
    {
    }

    public function fill_in_additional_detail_fields()
    {
    }

    public function fill_in_additional_parent_fields()
    {
    }

    public function get_list_view_data()
    {
    }

    public function get_audit_link()
    {

    }

    /**
     * wrapper for evil global var
     * @protected
     */
    protected function getAssocFieldsArray()
    {
        global $genericAssocFieldsArray, $moduleAssocFieldsArray;
        $this->genericAssocFieldsArray = (!empty($genericAssocFieldsArray) &&
            is_array($genericAssocFieldsArray)) ? $genericAssocFieldsArray : array();

        $this->moduleAssocFieldsArray = (!empty($moduleAssocFieldsArray) &&
            is_array($moduleAssocFieldsArray)) ? $moduleAssocFieldsArray : array();
    }

    /**
     * wrapper to get fielddefs for class - punch out for unit testing
     * @protected
     */
    protected function getFieldDefs()
    {
        if (empty($this->fieldDefs)) {
            require 'metadata/audit_templateMetaData.php';
            $this->fieldDefs = $dictionary['audit']['fields'];
        }
        return $this->fieldDefs;
    }
    /**
     * This method gets the Audit log and formats it specifically for the API.
     * @param  type SugarBean $bean
     * @return array
     */
    public function getAuditLog(SugarBean $bean)
    {
        global $timedate;

        if (!$bean->is_AuditEnabled()) {
            return array();
        }

        $auditTable = $bean->get_audit_table_name();

        $query = "SELECT atab.*, ae.source, ae.type AS event_type, usr.user_name AS created_by_username
                  FROM {$auditTable} atab
                  LEFT JOIN audit_events ae ON (ae.id = atab.event_id)
                  LEFT JOIN users usr ON (usr.id = atab.created_by) 
                  WHERE  atab.parent_id = ?
                  ORDER BY atab.date_created DESC, atab.id DESC";

        $db = DBManagerFactory::getInstance();

        $stmt = $db->getConnection()->executeQuery($query, [$bean->id]);
        if (empty($stmt)) {
            return array();
        }

        $fieldDefs = $this->fieldDefs;
        $return = array();

        $aclCheckContext = array('bean' => $bean);
        $rows = [];
        while ($row = $stmt->fetch()) {
            if (!SugarACL::checkField($bean->module_dir, $row['field_name'], 'access', $aclCheckContext)) {
                continue;
            }

            //convert date
            $dateCreated = $timedate->fromDbType($db->fromConvert($row['date_created'], 'datetime'), "datetime");
            $row['date_created'] = $timedate->asIso($dateCreated);

            $row['source'] = json_decode($row['source'], true);

            $viewName = array_search($row['field_name'], Team::$nameTeamsetMapping);
            if ($viewName) {
                $row['field_name'] = $viewName;
                $return[] = $this->handleTeamSetField($row);
                continue;
            }

            if ($this->handleRelateField($bean, $row)) {
                $return[] = $row;
                continue;
            }

            // look for opportunities to relate ids to name values.
            if (!empty($this->genericAssocFieldsArray[$row['field_name']]) ||
                !empty($this->moduleAssocFieldsArray[$bean->object_name][$row['field_name']])
            ) {
                foreach ($fieldDefs as $field) {
                    if (in_array($field['name'], array('before_value_string', 'after_value_string'))) {
                        $row[$field['name']] =
                            $this->getNameForId($row['field_name'], $row[$field['name']]);
                    }
                }
            }

            $rows[] = $this->formatRowForApi($row);
        }

        Container::getInstance()->get(AuditFormatter::class)->formatRows($rows);

        return $rows;
    }

    /**
     * Wrapper around static method self::getAssociatedFieldName($fieldName, $fieldValue)
     * @param string $fieldName
     * @param string $fieldValue
     * @return string
     */
    protected function getNameForId($fieldName, $fieldValue)
    {
        return self::getAssociatedFieldName($fieldName, $fieldValue);
    }

    /**
     * Handles relate field.
     *
     * @param SugarBean $bean
     * @param array $row A row of database-queried audit table results.
     * @return boolean
     */
    protected function handleRelateField($bean, &$row)
    {
        $fields = $bean->getAuditEnabledFieldDefinitions(true);

        if (isset($fields[$row['field_name']]) && $fields[$row['field_name']]['type'] === 'relate') {
            $field = $fields[$row['field_name']];
            $row['field_name'] = $field['name'];

            if (!empty($row['before_value_string'])) {
                $beforeBean = BeanFactory::getBean($field['module'], $row['before_value_string']);
                if (!empty($beforeBean)) {
                    $row['before_value_string'] = $beforeBean->get_summary_text();
                }
            }

            if (!empty($row['after_value_string'])) {
                $afterBean = BeanFactory::getBean($field['module'], $row['after_value_string']);
                if (!empty($afterBean)) {
                    $row['after_value_string'] = $afterBean->get_summary_text();
                }
            }

            $row = $this->formatRowForApi($row);
            return true;
        }
        return false;
    }

    /**
     * Handles the special-cased `team_set_id` field when fetching rows for the
     * Audit Log API. It is needed in order to prevent processing this field as
     * type `relate`.
     *
     * @param array $row A row of database-queried audit table results.
     * @return array The API-formatted $row.
     */
    protected function handleTeamSetField($row = array())
    {
        if (empty($row)) {
            return $row;
        }

        require_once 'modules/Teams/TeamSetManager.php';
        $row['before_value_string'] = TeamSetManager::getTeamsFromSet($row['before_value_string']);
        $row['after_value_string'] = TeamSetManager::getTeamsFromSet($row['after_value_string']);

        $row = $this->formatRowForApi($row);
        return $row;
    }

    /**
     * Formats a db-fetched row for the Audit Log API with `before` and `after`
     * values.
     *
     * @param array $row A row of database-queried audit table results.
     * @return array The API-formatted $row.
     */
    protected function formatRowForApi($row = array())
    {
        if (empty($row)) {
            return $row;
        }

        if (empty($row['before_value_string']) && empty($row['after_value_string'])) {
            $row['before'] = $row['before_value_text'];
            $row['after'] = $row['after_value_text'];
        } else {
            $row['before'] = $row['before_value_string'];
            $row['after'] = $row['after_value_string'];
        }

        unset($row['before_value_string']);
        unset($row['before_value_text']);
        unset($row['after_value_string']);
        unset($row['after_value_text']);

        return $row;
    }

    /**
     * Formats datetime value according to type, or returns it as is in case it's empty
     *
     * @param mixed $value
     * @param string $type
     *
     * @return mixed
     */
    protected function formatDateTime($value, $type)
    {
        global $timedate;

        if ($value) {
            $obj = $timedate->fromDbType($value, $type);
            $value = $timedate->asIso($obj);
        }

        return $value;
    }

   public static function get_audit_list()
    {
        global $focus, $genericAssocFieldsArray, $moduleAssocFieldsArray, $current_user, $timedate, $app_strings;
        $audit_list = array();
        if (!empty($_REQUEST['record'])) {
               $result = $focus->retrieve($_REQUEST['record']);

        if ($result == null || !$focus->ACLAccess('', $focus->isOwner($current_user->id))) {
                sugar_die($app_strings['ERROR_NO_RECORD']);
            }
        }

        if ($focus->is_AuditEnabled()) {
            $order= ' order by '.$focus->get_audit_table_name().'.date_created desc' ;//order by contacts_audit.date_created desc
            $query = "SELECT ".$focus->get_audit_table_name().".*, users.user_name FROM ".$focus->get_audit_table_name().", users WHERE ".$focus->get_audit_table_name().".created_by = users.id AND ".$focus->get_audit_table_name().".parent_id = '$focus->id'".$order;

            $result = $focus->db->query($query);
                // We have some data.
                require 'metadata/audit_templateMetaData.php';
                $fieldDefs = $dictionary['audit']['fields'];
                while (($row = $focus->db->fetchByAssoc($result))!= null) {
                    if(!ACLField::hasAccess($row['field_name'], $focus->module_dir, $GLOBALS['current_user']->id, $focus->isOwner($GLOBALS['current_user']->id))) continue;

                    //If the team_set_id field has a log entry, we retrieve the list of teams to display
                    $viewName = array_search($row['field_name'], Team::$nameTeamsetMapping);
                    if ($viewName) {
                        $row['field_name'] = $viewName;
                        require_once 'modules/Teams/TeamSetManager.php';
                        $row['before_value_string'] = TeamSetManager::getCommaDelimitedTeams($row['before_value_string']);
                        $row['after_value_string'] = TeamSetManager::getCommaDelimitedTeams($row['after_value_string']);
                    }
                    $temp_list = array();

                    foreach ($fieldDefs as $field) {
                            if (array_key_exists($field['name'], $row)) {
                                if(($field['name'] == 'before_value_string' || $field['name'] == 'after_value_string') &&
                                    (array_key_exists($row['field_name'], $genericAssocFieldsArray) || (!empty($moduleAssocFieldsArray[$focus->object_name]) && array_key_exists($row['field_name'], $moduleAssocFieldsArray[$focus->object_name])) )
                                   ) {

                                   $temp_list[$field['name']] = self::getAssociatedFieldName($row['field_name'], $row[$field['name']]);
                                } else {
                                   $temp_list[$field['name']] = $row[$field['name']];
                                }

                                if ($field['name'] == 'date_created') {
                                   $date_created = '';
                                   if (!empty($temp_list[$field['name']])) {
                                        $date_created = $timedate->to_display_date_time($temp_list[$field['name']]);
                                        $date_created = !empty($date_created)?$date_created:$temp_list[$field['name']];
                                   }
                                   $temp_list[$field['name']]=$date_created;
                                }
                                 if (($field['name'] == 'before_value_string' || $field['name'] == 'after_value_string') && ($row['data_type'] == "enum" || $row['data_type'] == "multienum")) {
                                     global $app_list_strings;
                                    $enum_keys = unencodeMultienum($temp_list[$field['name']]);
                                    $enum_values = array();
                                    foreach ($enum_keys as $enum_key) {
                                    if (isset($focus->field_defs[$row['field_name']]['options'])) {
                                        $domain = $focus->field_defs[$row['field_name']]['options'];
                                            if(isset($app_list_strings[$domain][$enum_key]))
                                                $enum_values[] = $app_list_strings[$domain][$enum_key];
                                    }
                                    }
                                    if (!empty($enum_values)) {
                                        $temp_list[$field['name']] = implode(', ', $enum_values);
                                    }
                                    if ($temp_list['data_type']==='date') {
                                        $temp_list[$field['name']]=$timedate->to_display_date($temp_list[$field['name']], false);
                                    }
                                 } elseif (($field['name'] == 'before_value_string' || $field['name'] == 'after_value_string') && ($row['data_type'] == "datetimecombo")) {
                                     if (!empty($temp_list[$field['name']]) && $temp_list[$field['name']] != 'NULL') {
                                         $temp_list[$field['name']]=$timedate->to_display_date_time($temp_list[$field['name']]);
                                     } else {
                                         $temp_list[$field['name']] = '';
                                     }
                                 } elseif ($field['name'] == 'field_name') {
                                    global $mod_strings;
                                    if (isset($focus->field_defs[$row['field_name']]['vname'])) {
                                        $label = $focus->field_defs[$row['field_name']]['vname'];
                                        $temp_list[$field['name']] = translate($label, $focus->module_dir);
                                    }
                                }
                        }
                    }

                    $temp_list['created_by'] = $row['user_name'];
                    $audit_list[] = $temp_list;
                }
        }

        return $audit_list;
    }

    /**
     * Return a more readable name for an id
     * @param {String} $fieldName
     * @param {String} $fieldValue
     * @return string
     */
    public static function getAssociatedFieldName($fieldName, $fieldValue)
    {
    global $focus,  $genericAssocFieldsArray, $moduleAssocFieldsArray;

        if (!empty($moduleAssocFieldsArray[$focus->object_name]) && array_key_exists($fieldName, $moduleAssocFieldsArray[$focus->object_name])) {
        $assocFieldsArray =  $moduleAssocFieldsArray[$focus->object_name];

        } elseif (array_key_exists($fieldName, $genericAssocFieldsArray)) {
            $assocFieldsArray =  $genericAssocFieldsArray;
        } else {
            return $fieldValue;
        }
        $query = "";
        $field_arr = $assocFieldsArray[$fieldName];
        $query = "SELECT ";
        if (is_array($field_arr['select_field_name'])) {
            $count = count($field_arr['select_field_name']);
            $index = 1;
            foreach ($field_arr['select_field_name'] as $col) {
                $query .= $col;
                if ($index < $count) {
                    $query .= ", ";
                }
                $index++;
            }
         } else {
               $query .= $field_arr['select_field_name'];
         }

         $query .= " FROM ".$field_arr['table_name']." WHERE ".$field_arr['select_field_join']." = '".$fieldValue."'";

         $db = DBManagerFactory::getInstance();
         $result = $db->query($query);
         if (!empty($result)) {
             if ($row = $db->fetchByAssoc($result)) {
                if (is_array($field_arr['select_field_name'])) {
                    $returnVal = "";
                    foreach ($field_arr['select_field_name'] as $col) {
                        $returnVal .= $row[$col]." ";
                    }

                    return $returnVal;
                } else {
                       return $row[$field_arr['select_field_name']];
                }
            }
        }
    }
}
