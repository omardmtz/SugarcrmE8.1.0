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

 * Description: Bean class for the users_last_import table
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 ********************************************************************************/


require_once('modules/Import/Forms.php');

class UsersLastImport extends SugarBean
{
    /**
     * Fields in the table
     */
    public $id;
    public $assigned_user_id;
    public $import_module;
    public $bean_type;
    public $bean_id;
    public $deleted;

    /**
     * Set the default settings from Sugarbean
     */
    public $module_dir = 'Import';
    public $table_name = "users_last_import";
    public $object_name = "UsersLastImport";
    var $disable_custom_fields = true;
    public $column_fields = array(
        "id",
        "assigned_user_id",
        "bean_type",
        "bean_id",
        "deleted"
        );
    public $new_schema = true;
    public $additional_column_fields = Array();

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->disable_row_level_security =true;
    }

    /**
     * Extends SugarBean::listviewACLHelper
     *
     * @return array
     */
    public function listviewACLHelper()
    {
        $array_assign = parent::listviewACLHelper();
        $is_owner = false;
        if ( !ACLController::moduleSupportsACL('Accounts')
                || ACLController::checkAccess('Accounts', 'view', $is_owner) ) {
            $array_assign['ACCOUNT'] = 'a';
        }
        else {
            $array_assign['ACCOUNT'] = 'span';
        }
        return $array_assign;
    }

    /**
     * Delete all the records for a particular user
     *
     * @param string $user_id user id of the user doing the import
     */
    public function mark_deleted_by_user_id($user_id)
    {
        $query = "DELETE FROM {$this->table_name} WHERE assigned_user_id = ? ";
        $conn = $this->db->getConnection();
        $conn->executeQuery($query, array($user_id));
    }

    /**
     * Undo a single record
     *
     * @param string $id specific users_last_import id to undo
     */
    public function undoById($id)
    {
        global $current_user;

        $query1 = "SELECT bean_id, bean_type FROM users_last_import WHERE assigned_user_id = '$current_user->id'
                   AND id = '$id' AND deleted=0";

        $result1 = $this->db->query($query1);
        if ( !$result1 )
            return false;

        while ( $row1 = $this->db->fetchByAssoc($result1))
            $this->_deleteRecord($row1['bean_id'],$row1['bean_type']);

        return true;
    }

    /**
     * Undo an import
     *
     * @param string $module  module being imported into
     */
    public function undo($module)
    {
        global $current_user;

        $query1 = "SELECT bean_id, bean_type FROM users_last_import WHERE assigned_user_id = '$current_user->id'
                   AND import_module = '$module' AND deleted=0";

        $result1 = $this->db->query($query1);
        if ( !$result1 )
            return false;

        while ( $row1 = $this->db->fetchByAssoc($result1))
            $this->_deleteRecord($row1['bean_id'],$row1['bean_type']);

        return true;
    }

    /**
     * Deletes a record in a bean
     *
     * @param $bean_id
     * @param $module
     */
    protected function _deleteRecord($bean_id,$module)
    {
        static $focus;

        // load bean
        if ( !( $focus instanceof $module) ) {
            $focus = BeanFactory::newBeanByName($module);
        }

        $focus->mark_relationships_deleted($bean_id);

        $result = $this->db->query(
            "DELETE FROM {$focus->table_name}
                WHERE id = '{$bean_id}'"
            );
        if (!$result)
            return false;
        // Bug 26318: Remove all created e-mail addresses ( from jchi )
        $result2 = $this->db->query(
            "SELECT email_address_id
                FROM email_addr_bean_rel
                WHERE email_addr_bean_rel.bean_id='{$bean_id}'
                    AND email_addr_bean_rel.bean_module='{$focus->module_dir}'");
        $this->db->query(
            "DELETE FROM email_addr_bean_rel
                WHERE email_addr_bean_rel.bean_id='{$bean_id}'
                    AND email_addr_bean_rel.bean_module='{$focus->module_dir}'"
            );

        while ( $row2 = $this->db->fetchByAssoc($result2)) {
            if ( !$this->db->getOne(
                    "SELECT email_address_id
                        FROM email_addr_bean_rel
                        WHERE email_address_id = '{$row2['email_address_id']}'") )
                $this->db->query(
                    "DELETE FROM email_addresses
                        WHERE id = '{$row2['email_address_id']}'");
        }

        if ($focus->hasCustomFields())
            $this->db->query(
                "DELETE FROM {$focus->table_name}_cstm
                    WHERE id_c = '{$bean_id}'");
    }

    /**
     * Get a list of bean types created in the import
     *
     * @param string $module  module being imported into
     */
    public static function getBeansByImport($module)
    {
        global $current_user;

        $db = DBManagerFactory::getInstance();

        $query1 = sprintf(
            'SELECT DISTINCT bean_type FROM users_last_import WHERE assigned_user_id = %s'
                . ' AND import_module = %s AND deleted=0',
            $db->quoted($current_user->id),
            $db->quoted($module)
        );

        $result1 = $db->query($query1);
        if ( !$result1 )
            return array($module);

        $returnarray = array();
        while ($row1 = $db->fetchByAssoc($result1)) {
            $returnarray[] = $row1['bean_type'];
        }

        return $returnarray;
    }

}
