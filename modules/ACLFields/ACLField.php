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

require_once('modules/ACLFields/actiondefs.php');
/**
 * Field-level ACLs
 * @api
 */
class ACLField extends SugarBean
{
    public $module_dir = 'ACLFields';
    public $object_name = 'ACLField';
    public $table_name = 'acl_fields';
    public $disable_custom_fields = true;
    public $new_schema = true;
    public $disable_row_level_security = true;

    /**
     * Cache of the ACL fields
     * @var array
     */
    public static $acl_fields = array();

    /**
    * static getAvailableFields($module, $object=false)
    * Adds available fields for module
    * @internal
    * @param STRING $module
    * @param STRING $object object name
    */
    static function getAvailableFields($module, $object=false)
    {
        static $exclude = array('deleted', 'assigned_user_id');
        static $modulesAvailableFields = array();
        if(!isset($modulesAvailableFields[$module])){
            if(empty($GLOBALS['dictionary'][$object]['fields'])){
                $mod = BeanFactory::newBean($module);
                if(empty($mod->acl_fields)) return array();
                $fieldDefs = $mod->field_defs;
            }else{
                $fieldDefs = $GLOBALS['dictionary'][$object]['fields'];
                if(isset($GLOBALS['dictionary'][$object]['acl_fields']) && $GLOBALS['dictionary'][$object]=== false){
                    return array();
                }
            }

            $availableFields = array();
            foreach($fieldDefs as $field=>$def){

                // FIXME this condition needs some refactoring to make any sense at all
                // we also need to document the rules because currently studio fields
                // should follow the same rules as our vardefs...
                if ((
                        !empty($def['source']) && $def['source'] == 'custom_fields') &&
                        $def['type'] != 'id' && (empty($def['dbType']) || ($def['dbType'] != 'id')
                    ) ||
                    (!empty($def['group']) && empty($def['hideacl'])) ||
                    (
                        empty($def['hideacl']) && !empty($def['type']) && !in_array($field, $exclude) &&
                        (
                            (
                                empty($def['source']) && $def['type'] != 'id' &&
                                (empty($def['dbType']) || ($def['dbType'] != 'id'))
                            ) || !empty($def['link']) || in_array($def['type'], SugarBean::$relateFieldTypes)
                        )
                    )
                ) {
                        if(empty($def['vname']))$def['vname'] = '';
                        $fkey = (!empty($def['group']))? $def['group']: $field;
                        $label = (!empty($fieldDefs[$fkey]['vname']))?$fieldDefs[$fkey]['vname']:$def['vname'];
                        $fkey = strtolower($fkey);
                        $field = strtolower($field);
                        $required = !empty($def['required']);
                        if($field == 'name'){
                            $required = true;
                        }
                        if(empty($availableFields[$fkey])){
                            $availableFields[$fkey] = array('id'=>$fkey, 'required'=>$required, 'key'=>$fkey, 'name'=> $field, 'label'=>$label, 'category'=>$module, 'role_id'=> '', 'aclaccess'=>ACL_ALLOW_DEFAULT, 'fields'=>array($field=>$label) );
                        }else{
                            if(!empty($required)){
                                $availableFields[$fkey]['required'] = 1;
                            }
                            $availableFields[$fkey]['fields'][strtolower($field)] = $label;
                        }
                }
            }
            $modulesAvailableFields[$module] = $availableFields;
        }
        return $modulesAvailableFields[$module];
    }

    /**
     * Get ACL fields
     * @internal
     * @param string $module
     * @param string $user_id
     * @param string $role_id
     * @return array
     */
    public static function getFields($module, $user_id = null, $role_id = null)
    {
        $fields = ACLField::getAvailableFields($module, false);

        if ($role_id) {
            $builder = DBManagerFactory::getConnection()
                ->createQueryBuilder();

            $builder->from('acl_fields', 'af')
                ->select('af.id', 'af.name', 'af.category', 'af.role_id', 'af.aclaccess')
                ->join('af', 'acl_roles', 'ar', 'ar.id = af.role_id AND ar.deleted = 0');

            $builder->where(
                'af.category = ' . $builder->createPositionalParameter($module),
                'ar.id = ' . $builder->createPositionalParameter($role_id),
                'af.deleted = 0'
            );

            if ($user_id) {
                $builder->join('af', 'acl_roles_users', 'aru', 'aru.role_id = ar.id AND aru.deleted = 0')
                    ->andWhere('aru.user_id = ' . $user_id);
            }

            $stmt = $builder->execute();

            while (($row = $stmt->fetch())) {
                if(!empty($fields[$row['name']]) && ($row['aclaccess'] < $fields[$row['name']]['aclaccess'] || $fields[$row['name']]['aclaccess'] == 0) ){
                    $row['key'] = $row['name'];
                    $row['label'] = $fields[$row['name']]['label'];
                    $row['fields'] = $fields[$row['name']]['fields'];
                    if(isset($fields[$row['name']]['required'])) {
                    $row['required'] = $fields[$row['name']]['required'];
                    }
                    $fields[$row['name']] =  $row;
                }
            }
        }

        ksort($fields);
        return $fields;
    }

    /**
     * @internal
     * @param string $role_id
     * @return array
     */
    public static function getACLFieldsByRole($role_id)
    {
        $query = 'SELECT id, name, category, role_id, aclaccess
FROM acl_fields
WHERE role_id = ?
AND deleted = 0';

        $stmt = DBManagerFactory::getConnection()
            ->executeQuery($query, array($role_id));

        $fields = array();
        while (($row = $stmt->fetch())) {
            $fields[$row['id']] = $row;
        }

        return $fields;
    }

    /**
     * Load user field ACL data
     *
     * @internal
     * @param string $module_name Module name
     * @param string $object
     * @param string $user_id
     * @param bool $refresh
     * @return array
     */
    public static function loadUserFields($module_name, $object, $user_id, $refresh = false)
    {
        global $dictionary;

        if(empty($user_id)) {
            return array();
        }
        if(!$refresh && isset(self::$acl_fields[$user_id][$module_name]))
        {
            return self::$acl_fields[$user_id][$module_name];
        }

        // We can not cache per user ID because ACLs are stored per role
        if(!$refresh) {
            $cached = self::loadFromCache($user_id, 'fields');
            if ($cached) {
                // ACL data for some modules may already have been loaded and it shouldn't be erased
                // in case it's not cached
                if (isset(self::$acl_fields[$user_id])) {
                    self::$acl_fields[$user_id] = array_merge(self::$acl_fields[$user_id], $cached);
                } else {
                    self::$acl_fields[$user_id] = $cached;
                }
            }

            if(isset(self::$acl_fields[$user_id][$module_name])) {
                return self::$acl_fields[$user_id][$module_name];
            }
        }

        // do not fetch data for field ACL if it's disabled
        // we need to use $dictionary directly instead of SugarBean as its constructor calls this method,
        // and creating a new bean instance to access the dictionary data will create endless recursion
        if (isset($dictionary[$object]['acl_fields']) && !$dictionary[$object]['acl_fields']) {
            self::$acl_fields[$user_id][$module_name] = array();
            self::storeToCache($user_id, 'fields', self::$acl_fields[$user_id]);

            return array();
        }

        $query = 'SELECT af.name, af.aclaccess FROM acl_fields af '
            . 'INNER JOIN acl_roles_users aru ON aru.user_id = ? AND aru.deleted=0 '
            . 'INNER JOIN acl_roles ar ON aru.role_id = ar.id AND ar.id = af.role_id AND ar.deleted = 0 '
            . 'WHERE af.deleted = 0 '
            . 'AND af.category = ?';

        $stmt = $GLOBALS['db']->getConnection()->executeQuery($query, [$user_id, $module_name]);

        $allFields = ACLField::getAvailableFields($module_name, $object);
        self::$acl_fields[$user_id][$module_name] = array();
        while ($row = $stmt->fetch()) {
            if($row['aclaccess'] != 0 && (empty(self::$acl_fields[$user_id][$module_name][$row['name']]) || self::$acl_fields[$user_id][$module_name][$row['name']] > $row['aclaccess']))
            {
                self::$acl_fields[$user_id][$module_name][$row['name']] = $row['aclaccess'];
                if(!empty($allFields[$row['name']])){
                    foreach($allFields[$row['name']]['fields'] as $field=>$label ){
                        self::$acl_fields[$user_id][$module_name][strtolower($field)] = $row['aclaccess'];
                    }
                }
            }
        }

        self::storeToCache($user_id, 'fields', self::$acl_fields[$user_id]);
        return self::$acl_fields[$user_id][$module_name];

    }

    public static $field_cache = array();

    /**
     * Filter fields list by ACLs
     * NOTE: works with global ACLs
	 * @internal
     * @param array $list Field list. Will be modified.
     * @param string $category Module for ACL
     * @param string $user_id
     * @param bool $is_owner Should owner-only ACLs be counted?
     * @param bool $by_key use list keys
     * @param int $min_access Minimal access level to require
     * @param bool $blank_value Put blank string in place of removed fields?
     * @param bool $addACLParam Add 'acl' key with acl access value?
     * @param string $suffix Field suffix to strip from the list.
     */
    public static function listFilter(&$list, $category, $user_id, $is_owner, $by_key = true, $min_access = 1, $blank_value = false, $addACLParam = false, $suffix = '')
    {
        foreach($list as $key=>$value){
            if($by_key){
                $field = $key;
                if(is_array($value) && !empty($value['group'])){

                    $field = $value['group'];
                }
            }else{
                if(is_array($value)){
                    if(!empty($value['group'])){
                        $value = $value['group'];
                    }else if(!empty($value['name'])){
                        $value = $value['name'];
                    }else{
                        $value = '';
                    }
                }
                $field = $value;
            }
            if(isset(self::$field_cache['lower'][$field])){
                $field = self::$field_cache['lower'][$field];
            } else {
                $oField = $field;
                $field = strtolower($field);
                if(!empty($suffix))$field = str_replace($suffix, '', $field);
                self::$field_cache['lower'][$oField] = $field;
            }
            if(!isset(self::$field_cache[$is_owner][$field])){
                $context = array("user_id" => $user_id);
                if($is_owner) {
                    $context['owner_override'] = true;
                }
                $access = SugarACL::getFieldAccess($category, $field, $context);
                self::$field_cache[$is_owner][$field] = $access;
            }else{
                $access = self::$field_cache[$is_owner][$field];
            }
            if($addACLParam){
                $list[$key]['acl'] = $access;
            }else if($access< $min_access){
                if($blank_value){
                    $list[$key] = '';
                }else{
                    unset($list[$key]);
                }
            }
        }
    }


   /**
    * hasAccess
    *
    * This function returns an integer value representing the access level for a given field of a module for
    * a user.  It also takes into account whether or not the user needs to have ownership of the record (assigned to the user)
    *
    * Returns 0 - for no access
    * Returns 1 - for read access
    * returns 2 - for write access
    * returns 4 - for read/write access
    * @internal
    * @param String $field The name of the field to retrieve ACL access for
    * @param String $module The name of the module that contains the field to lookup ACL access for
    * @param string|User|null $user_id The user id of the user instance to check ACL access for, or the User object,
    *                                  or null which means current user. Using User is recommended since it's fastest.
    * @param boolean $is_owner Boolean value indicating whether or not the field access should also take into account ownership access
    * @return Integer value indicating the ACL field level access
    */
    static function hasAccess($field = false, $module = 0, $user_id = null, $is_owner = null)
    {
        if(is_null($user_id)) {
            $user = $GLOBALS['current_user'];
            $user_id = $user->id;
        } elseif($user_id instanceof User) {
			$user = $user_id;
			$user_id = $user->id;
		} elseif($user_id == $GLOBALS['current_user']->id) {
            $user = $GLOBALS['current_user'];
        }

        if(!isset(self::$acl_fields[$user_id][$module][$field])){
            return 4;
        }

        if(empty($user)) {
            $user = BeanFactory::getBean("Users", $user_id);
        }

        if (!empty($user) && $user->isAdmin()) {
            return 4;
        }
        $tbaConfigurator = new TeamBasedACLConfigurator();

        $access = self::$acl_fields[$user_id][$module][$field];

        if($access == ACL_READ_WRITE || ($is_owner && ($access == ACL_READ_OWNER_WRITE || $access == ACL_OWNER_READ_WRITE))) {
            return 4;
        } elseif($access == ACL_READ_ONLY || $access==ACL_READ_OWNER_WRITE) {
            return 1;
        } elseif ($tbaConfigurator->isEnabledForModule($module) && $tbaConfigurator->isValidAccess($access)) {
            // Handled by SugarACLTeamBased.
            return 4;
        }
        return 0;
    }

    /**
     * Generates ACL specific condition for the given query condition, if needed
     *
     * @param SugarQuery_Builder_Condition $condition Original condition
     * @param User|null $user ACL user
     *
     * @return SugarQuery_Builder_Where|null ACL specific condition or NULL if not applicable
     */
    public static function generateAclCondition(SugarQuery_Builder_Condition $condition, User $user = null)
    {
        if (!$user || $user->isAdmin()) {
            return null;
        }

        $field = $condition->field;

        if (!isset(self::$acl_fields[$user->id][$field->moduleName][$field->field])) {
            return null;
        }

        $access = self::$acl_fields[$user->id][$field->moduleName][$field->field];

        if ($access == ACL_OWNER_READ_WRITE) {
            return self::generateIsOwnerCondition($condition->query, $field->moduleName, $user);
        }

        return null;
    }

    /**
     * Generates a condition which filters out the records not owned by the given user
     *
     * @param SugarQuery $query The query to generate the condition for
     * @param string $module The module which the records being selected belong to
     * @param User $user ACL user
     *
     * @return SugarQuery_Builder_Where|null Condition or NULL if not applicable
     */
    protected static function generateIsOwnerCondition(SugarQuery $query, $module, User $user)
    {
        $bean = BeanFactory::newBean($module);
        if (!$bean) {
            return null;
        }

        $fields = array('assigned_user_id', 'created_by');
        $fields = array_intersect($fields, array_keys($bean->field_defs));

        $previous = $result = null;
        while (count($fields) > 0) {
            $field = array_pop($fields);

            $condition = new SugarQuery_Builder_Condition($query);
            $condition->setOperator('=')->setField($field)->setValues($user->id)->ignoreAcl();

            $result = new SugarQuery_Builder_Orwhere($query);
            $result->add($condition);

            if ($previous !== null) {
                $isNull = new SugarQuery_Builder_Condition($query);
                $isNull->setField($field)->isNull()->ignoreAcl();

                $and = new SugarQuery_Builder_Andwhere($query);
                $and->add($isNull);
                $and->add($previous);

                $result->add($and);
            }

            $previous = $result;
        }

        return $result;
    }

    /**
     * @internal
     * @param string $module
     * @param string $role_id
     * @param string $field_id
     * @param string $access
     */
    public static function setAccessControl($module, $role_id, $field_id, $access)
    {
        $acl = new ACLField();
        $id = md5($module. $role_id . $field_id);
        if(!$acl->retrieve($id) ){
            //if we don't have a value and its never been saved no need to start now
            if(empty($access))return false;
            $acl->id = $id;
            $acl->new_with_id = true;
        }

        $acl->aclaccess = $access;
        $acl->category = $module;
        $acl->name = $field_id;
        $acl->role_id = $role_id;
        $acl->save();

    }

    public static function clearACLCache()
    {
        self::$acl_fields = array();
        ACLAction::clearACLCache();
    }

    /**
     * Check if there are any field ACLs defined in this module for this user
     * @param string $user_id
     * @param string $module
     * @return boolean
     */
    public static function hasACLs($user_id, $module)
    {
        if(empty(self::$acl_fields[$user_id])) {
            self::$acl_fields[$user_id] = self::loadFromCache($user_id, 'fields');
        }
        return !empty(self::$acl_fields[$user_id][$module]);
    }

    /**
     * @param string $user_id
     * @param string $type
     */
    protected static function loadFromCache($user_id, $type)
    {
        return AclCache::getInstance()->retrieve($user_id, $type);
    }

    /**
     * @param string $user_id
     * @param string $type
     * @param array $data
     */
    protected static function storeToCache($user_id, $type, $data)
    {
        return AclCache::getInstance()->store($user_id, $type, $data);
    }
}
