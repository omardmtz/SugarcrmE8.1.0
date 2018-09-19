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
require_once('modules/ACLActions/actiondefs.php');

/**
 * ACL actions
 * @api
 */
class ACLAction  extends SugarBean
{
    var $module_dir = 'ACLActions';
    var $object_name = 'ACLAction';
    var $table_name = 'acl_actions';
    var $new_schema = true;
    var $disable_custom_fields = true;
    public $disable_row_level_security = true;

    /**
     * Cache of the ACL values
     * @var array
     */
    protected static $acls;

    /**
    * static addActions($category, $type='module')
    * Adds all default actions for a category/type
    *
    * @param STRING $category - the category (e.g module name - Accounts, Contacts)
    * @param STRING $type - the type (e.g. 'module', 'field')
    */
    static function addActions($category, $type='module'){
        global $ACLActions;
        $db = DBManagerFactory::getInstance();
        if(isset($ACLActions[$type])){
            foreach($ACLActions[$type]['actions'] as $action_name =>$action_def){

                $action = BeanFactory::newBean('ACLActions');
                $query = "SELECT * FROM " . $action->table_name . " WHERE name='$action_name' AND category = '$category' AND acltype='$type' AND deleted=0 ";
                $row = $db->fetchOne($query);
                //only add if an action with that name and category don't exist
                if (empty($row)) {
                    $action->name = $action_name;
                    $action->category = $category;
                    $action->aclaccess = $action_def['default'];
                    $action->acltype = $type;
                    $action->modified_user_id = 1;
                    $action->created_by = 1;
                    $action->save();

                }
            }

        }else{
            sugar_die("FAILED TO ADD: $category - TYPE $type NOT DEFINED IN modules/ACLActions/actiondefs.php");
        }
    }

    /**
    * static removeActions($category, $type='module')
    * Removes all default actions for a category/type
    *
    * @param STRING $category - the category (e.g module name - Accounts, Contacts)
    * @param STRING $type - the type (e.g. 'module', 'field')
    */
    public static function removeActions($category, $type='module'){
        global $ACLActions;
        $db = DBManagerFactory::getInstance();
        if(isset($ACLActions[$type])){
            foreach($ACLActions[$type]['actions'] as $action_name =>$action_def){

                $action = BeanFactory::newBean('ACLActions');
                $query = "SELECT * FROM " . $action->table_name . " WHERE name='$action_name' AND category = '$category' AND acltype='$type' and deleted=0";
                $result = $db->query($query);
                //only add if an action with that name and category don't exist
                $row=$db->fetchByAssoc($result);
                if ($row != null) {
                    $action->mark_deleted($row['id']);
                }
            }
        }else{
            sugar_die("FAILED TO REMOVE: - CATEGORY : $category - TYPE $type NOT DEFINED IN modules/ACLActions/actiondefs.php");
        }
    }

    /**
    * static AccessColor($access)
    *
    * returns the color associated with an access level
    * these colors exist in the definitions in modules/ACLActions/actiondefs.php
    * @param INT $access - the access level you want the color for
    * @return the color either name or hex representation or false if the level does not exist
    */
    protected static function AccessColor($access){
        global $ACLActionAccessLevels;
        if(isset($ACLActionAccessLevels[$access])){

            return $ACLActionAccessLevels[$access]['color'];
        }
        return false;

    }

    /**
    * static AccessName($access)
    *
    * returns the translated name  associated with an access level
    * these label definitions  exist in the definitions in modules/ACLActions/actiondefs.php
    * @param INT $access - the access level you want the color for
    * @return the translated access level name or false if the level does not exist
    */
    static function AccessName($access){
        global $ACLActionAccessLevels;
        if(isset($ACLActionAccessLevels[$access])){
            return translate($ACLActionAccessLevels[$access]['label'], 'ACLActions');
        }
        return false;

    }

    /**
     * static AccessLabel($access)
     *
     * returns the label  associated with an access level
     * these label definitions  exist in the definitions in modules/ACLActions/actiondefs.php
     * @param INT $access - the access level you want the color for
     * @return the access level label or false if the level does not exist
     */
    protected static function AccessLabel($access){
        global $ACLActionAccessLevels;
        if(isset($ACLActionAccessLevels[$access])){
            $label=preg_replace('/(LBL_ACCESS_)(.*)/', '$2', $ACLActionAccessLevels[$access]['label']);
            return strtolower($label);

        }
        return false;

    }

    /**
    * static getAccessOptions()
    * this is used for building select boxes
    * @return array containg access levels (ints) as keys and access names as values
    */
    protected static function getAccessOptions( $action, $type='module'){
        global $ACLActions;
        $options = array();

        if(empty($ACLActions[$type]['actions'][$action]['aclaccess']))return $options;
        foreach($ACLActions[$type]['actions'][$action]['aclaccess'] as $action){
            $options[$action] = ACLAction::AccessName($action);
        }
        return $options;

    }

    /**
    * function static getDefaultActions()
    * This function will return a list of acl actions with their default access levels
    *
    *
    */
    public static function getDefaultActions($type='module', $action=''){
        $query = "SELECT * FROM acl_actions WHERE deleted=0 ";
        if(!empty($type)){
            $query .= " AND acltype='$type'";
        }
        if(!empty($action)){
            $query .= "AND name='$action'";
        }
        $query .= " ORDER BY category";

        $db = DBManagerFactory::getInstance();
        $result = $db->query($query);
        $default_actions = array();
        while($row = $db->fetchByAssoc($result) ){
            $acl = BeanFactory::newBean('ACLActions');
            $acl->populateFromRow($row);
            $default_actions[] = $acl;
        }
        return $default_actions;
    }

    protected static function loadFromCache($user_id, $type)
    {
        return AclCache::getInstance()->retrieve($user_id, $type);
    }

    protected static function storeToCache($user_id, $type, $data)
    {
        return AclCache::getInstance()->store($user_id, $type, $data);
    }

    /**
    * static getUserActions($user_id,$refresh=false, $category='', $action='')
    * returns a list of user actions
    * @param string $user_id
    * @param BOOLEAN $refresh
    * @param STRING $category
    * @param STRING $action
    * @return ARRAY of ACLActionsArray
    */
    static function getUserActions($user_id,$refresh=false, $category='',$type='', $action='')
    {
        if(empty($user_id)) {
            return array();
        }
        //check in the cache if we already have it loaded
        if(!$refresh && empty(self::$acls[$user_id])) {
            self::$acls[$user_id] = self::loadFromCache($user_id, 'acls');
        }
        if(!$refresh && !empty(self::$acls[$user_id])){
            if(empty($category) && empty($action)){
                return self::$acls[$user_id];
            }else{
                if(!empty($category) && isset(self::$acls[$user_id][$category])){
                    if(empty($action)){
                        if(empty($type)){
                            return self::$acls[$user_id][$category];
                        }
                        if(isset(self::$acls[$user_id][$category][$type])) {
                            return self::$acls[$user_id][$category][$type];
                        }
                    }else if(!empty($type) && isset(self::$acls[$user_id][$category][$type][$action])){
                        return self::$acls[$user_id][$category][$type][$action];
                    }
                }
            }
        }
        //if we don't have it loaded then lets check against the db
        $db = DBManagerFactory::getInstance();
        $conn = $db->getConnection();
        $qbSubQuery = $conn->createQueryBuilder();
        $qbSubQuery
            ->select('acl_roles_users.user_id', 'acl_roles_actions.action_id', 'acl_roles_actions.access_override')
            ->from('acl_roles_users')
            ->leftJoin(
                'acl_roles_users',
                'acl_roles_actions',
                'acl_roles_actions',
                'acl_roles_actions.role_id = acl_roles_users.role_id AND acl_roles_actions.deleted=0'
            )
            ->where($qbSubQuery->expr()->eq(
                'acl_roles_users.user_id',
                $qbSubQuery->createPositionalParameter($user_id)
            ))
            ->andWhere($qbSubQuery->expr()->eq('acl_roles_users.deleted', '0'));

        $qb = $conn->createQueryBuilder();
        $qb->select(
            'acl_actions.id',
            'acl_actions.name',
            'acl_actions.category',
            'acl_actions.acltype',
            'acl_actions.aclaccess',
            'tt.access_override'
        )
            ->from('acl_actions')
            ->leftJoin(
                'acl_actions',
                '(' . $qb->importSubQuery($qbSubQuery) . ')',
                'tt',
                'tt.action_id = acl_actions.id'
            )
            ->andWhere($qb->expr()->eq('acl_actions.deleted', 0))
            ->addOrderBy('acl_actions.category')
            ->addOrderBy('acl_actions.name');

        if(!empty($category)){
            $qb->andWhere($qb->expr()->eq('acl_actions.category', $qb->createPositionalParameter($category)));
        }
        if(!empty($action)){
            $qb->andWhere($qb->expr()->eq('acl_actions.name', $qb->createPositionalParameter($action)));
        }
        if(!empty($type)){
            $qb->andWhere($qb->expr()->eq('acl_actions.acltype', $qb->createPositionalParameter($type)));
        }
        $stmt = $qb->execute();

        $selected_actions = array();
        while ($row = $stmt->fetch()) {
            $isOverride = !empty($row['access_override']);
            if ($isOverride) {
                $row['aclaccess'] = $row['access_override'];
            }

            if (!isset($selected_actions[$row['category']][$row['acltype']][$row['name']])
                || ($isOverride
                    && ($selected_actions[$row['category']][$row['acltype']][$row['name']]['aclaccess'] > $row['aclaccess']
                        || $selected_actions[$row['category']][$row['acltype']][$row['name']]['isDefault']
                    )
                )
            ) {
                $selected_actions[$row['category']][$row['acltype']][$row['name']] = array(
                    'id' => $row['id'],
                    'aclaccess' => $row['aclaccess'],
                    'isDefault' => !$isOverride,
                );
            }
        }

        //only set the session variable if it was a full list;
        if(empty($category) && empty($action)){
            if(!isset(self::$acls)){
                self::$acls = array();
            }
            self::$acls[$user_id] = $selected_actions;
        }else{
            if(empty($action) && !empty($category)){
                if(!empty($type)){
                    if(isset($selected_actions[$category][$type])) {
                        self::$acls[$user_id][$category][$type] = $selected_actions[$category][$type];
                    } else {
                        self::$acls[$user_id][$category][$type] = array();
                    }
                }
                if(isset($selected_actions[$category])) {
                    self::$acls[$user_id][$category] = $selected_actions[$category];
                } else {
                    self::$acls[$user_id][$category] = array();
                }
            }else{
                if(!empty($action) && !empty($category) && !empty($type)){
                    if(isset($selected_actions[$category][$action])) {
                        self::$acls[$user_id][$category][$type][$action] = $selected_actions[$category][$action];
                    } else {
                        self::$acls[$user_id][$category][$type][$action] = array();
                    }

                }
            }
        }

        // Sort by translated categories
        uksort($selected_actions, "ACLAction::langCompare");
        self::storeToCache($user_id, 'acls', self::$acls[$user_id]);
        return $selected_actions;
    }

    private static function langCompare($a, $b)
    {
        global $app_list_strings;
        // Fallback to array key if translation is empty
        $a = empty($app_list_strings['moduleList'][$a]) ? $a : $app_list_strings['moduleList'][$a];
        $b = empty($app_list_strings['moduleList'][$b]) ? $b : $app_list_strings['moduleList'][$b];
        if ($a == $b)
            return 0;
        return ($a < $b) ? -1 : 1;
    }

    /**
    * (static/ non-static)function hasAccess($is_owner= false , $access = 0)
    * checks if a user has access to this acl if the user is an owner it will check if owners have access
    *
    * This function may either be used statically or not. If used staticlly a user must pass in an access level not equal to zero
    * @param boolean $is_owner
    * @param int $access
    * @return true or false
    */
    static function hasAccess($is_owner=false, $access = 0){
        $tbaConfigurator = new TeamBasedACLConfigurator();
        if ($tbaConfigurator->isEnabledGlobally() && $tbaConfigurator->isValidAccess($access)) {
            // Handled by SugarACLTeamBased.
            return true;
        }

        if($access != 0 && $access == ACL_ALLOW_ALL || ($is_owner && $access == ACL_ALLOW_OWNER))return true;
       //if this exists, then this function is not static, so check the aclaccess parameter
        if(isset($this) && isset($this->aclaccess)){
            if($this->aclaccess == ACL_ALLOW_ALL || ($is_owner && $this->aclaccess == ACL_ALLOW_OWNER))
            return true;
        }
        return false;
    }

    /**
    * static function userHasAccess($user_id, $category, $action, $is_owner = false)
    *
    * @param GUID $user_id the user id who you want to check access for
    * @param STRING $category the category you would like to check access for
    * @param STRING $action the action of that category you would like to check access for
    * @param BOOLEAN OPTIONAL $is_owner if the object is owned by the user you are checking access for
    */
    public static function userHasAccess($user_id, $category, $action,$type='module', $is_owner = false){
       global $current_user;
        //check if we don't have it set in the cache if not lets reload the cache
        if(ACLAction::getUserAccessLevel($user_id, $category, 'access', $type) < ACL_ALLOW_ENABLED) return false;
        if(empty(self::$acls[$user_id][$category][$type][$action])){
            ACLAction::getUserActions($user_id, false);

        }
        if(!empty(self::$acls[$user_id][$category][$type][$action])){
            if($action == 'access' && self::$acls[$user_id][$category][$type][$action]['aclaccess'] == ACL_ALLOW_ENABLED) return true;
            return ACLAction::hasAccess($is_owner, self::$acls[$user_id][$category][$type][$action]['aclaccess']);
        }
        return false;

    }
    /**
    * function getUserAccessLevel($user_id, $category, $action,$type='module')
    * returns the access level for a given category and action
    *
    * @param GUID  $user_id
    * @param STRING $category
    * @param STRING $action
    * @param STRING $type
    * @return INT (ACCESS LEVEL)
    */
    public static function getUserAccessLevel($user_id, $category, $action,$type='module'){
        if(empty(self::$acls[$user_id][$category][$type][$action])){
            ACLAction::getUserActions($user_id, false);

        }
        if(!empty(self::$acls[$user_id][$category][$type][$action])){
            $actionAccess = self::$acls[$user_id][$category][$type][$action]['aclaccess'];

            if (!empty(self::$acls[$user_id][$category][$type]['admin']) && self::$acls[$user_id][$category][$type]['admin']['aclaccess'] >= ACL_ALLOW_ADMIN)
            {
                $tbaConfigurator = new TeamBasedACLConfigurator();
                if ($tbaConfigurator->isValidAccess($actionAccess)) {
                    // The TBA is not suppressed by admin access.
                    return $actionAccess;
                }
                // If you have admin access for a module, all ACL's are allowed
                return self::$acls[$user_id][$category][$type]['admin']['aclaccess'];
            }
            return $actionAccess;
        }
    }

    /**
    * STATIC function userNeedsOwnership($user_id, $category, $action,$type='module')
    * checks if a user should have ownership to do an action
    *
    * @param GUID $user_id
    * @param STRING $category
    * @param STRING $action
    * @param STRING $type
    * @return boolean
    */
    public static function userNeedsOwnership($user_id, $category, $action,$type='module'){
        //check if we don't have it set in the cache if not lets reload the cache

        if(empty(self::$acls[$user_id][$category][$type][$action])){
            ACLAction::getUserActions($user_id, false);

        }


        if(!empty(self::$acls[$user_id][$category][$type][$action])){
            return self::$acls[$user_id][$category][$type][$action]['aclaccess'] == ACL_ALLOW_OWNER;
        }
        return false;

    }
    /**
    *
    * static pass by ref setupCategoriesMatrix(&$categories)
    * takes in an array of categories and modifes them adding display information
    *
    * @param unknown_type $categories
    */
    public static function setupCategoriesMatrix(&$categories){
        global $ACLActions, $current_user;
        $names = array();
        $disabled = array();
        $tbaConfigurator = new TeamBasedACLConfigurator();
        foreach($categories as $cat_name=>$category){
            foreach($category as $type_name=>$type){
                foreach($type as $act_name=>$action){
                    $names[$act_name] = isset($ACLActions[$type_name]['actions'][$act_name])
                        ? translate($ACLActions[$type_name]['actions'][$act_name]['label'], 'ACLActions')
                        : $act_name;
                    $categories[$cat_name][$type_name][$act_name]['accessColor'] = ACLAction::AccessColor($action['aclaccess']);
                    if($type_name== 'module'){

                        if($act_name != 'aclaccess' && $categories[$cat_name]['module']['access']['aclaccess'] == ACL_ALLOW_DISABLED){
                            $categories[$cat_name][$type_name][$act_name]['accessColor'] = 'darkgray';
                            $disabled[] = $cat_name;
                        }

                    }
                    $categories[$cat_name][$type_name][$act_name]['accessName'] = ACLAction::AccessName($action['aclaccess']);
                    $categories[$cat_name][$type_name][$act_name]['accessLabel'] = ACLAction::AccessLabel($action['aclaccess']);

                    if($cat_name=='Users'&& $act_name=='admin'){
                        $categories[$cat_name][$type_name][$act_name]['accessOptions'][ACL_ALLOW_DEFAULT]=ACLAction::AccessName(ACL_ALLOW_DEFAULT);;
                        $categories[$cat_name][$type_name][$act_name]['accessOptions'][ACL_ALLOW_DEV]=ACLAction::AccessName(ACL_ALLOW_DEV);;
                        $categories[$cat_name][$type_name][$act_name]['accessOptions'][ACL_ALLOW_ADMIN_DEV]=ACLAction::AccessName(ACL_ALLOW_ADMIN_DEV);
                    }
                    else{
                    $categories[$cat_name][$type_name][$act_name]['accessOptions'] =  ACLAction::getAccessOptions($act_name, $type_name);
                        if (!$tbaConfigurator->isAccessibleForModule($cat_name)) {
                            $tbaModuleKeys = array_values($tbaConfigurator->getModuleOptions());
                            foreach ($categories[$cat_name][$type_name][$act_name]['accessOptions'] as $key => $label) {
                                if (in_array($key, $tbaModuleKeys)) {
                                    unset($categories[$cat_name][$type_name][$act_name]['accessOptions'][$key]);
                                }
                            }
                        }
                    }
                }
            }
        }

        if(!is_admin($current_user)){
            foreach($disabled as $cat_name){
                unset($categories[$cat_name]);
            }
        }
        return $names;
    }



    /**
    * function toArray()
    * returns this acl as an array
    *
    * @return array of fields with id, name, access and category
    */
    public function toArray($dbOnly = false, $stringOnly = false, $upperKeys=false)
    {
        $array_fields = array('id', 'aclaccess');
        $arr = array();
        foreach($array_fields as $field){
            $arr[$field] = $this->$field;
        }
        return $arr;
    }

    /**
    * function fromArray($arr)
    * converts an array into an acl mapping name value pairs into files
    *
    * @param Array $arr
    */
    function fromArray($arr){
        foreach($arr as $name=>$value){
            $this->$name = $value;
        }
    }

    /**
     * @deprecated
     */
    public function clearSessionCache()
    {
        self::clearACLCache();
    }

    /**
    * function clearSessionCache()
    * clears the session variable storing the cache information for acls
    *
    */
    public static function clearACLCache()
    {
        self::$acls = array();
        AclCache::getInstance()->clear();
    }

    public function save($check_notify = false)
    {
        self::clearACLCache();
        parent::save($check_notify);
    }

    public function mark_deleted($id)
    {
        self::clearACLCache();
        parent::mark_deleted($id);
    }

    /**
     * Check if there are any ACLs defined in this module for this user
     * @param string $user_id
     * @param string $module
     * @return boolean
     */
    public static function hasACLs($user_id, $module)
    {
        if(empty(self::$acls[$user_id])) {
            self::$acls[$user_id] = self::loadFromCache($user_id, 'acls');
        }
        return !empty(self::$acls[$user_id][$module]);
    }

    /**
     * Directly set ACL data. Useful mostly for unit tests.
     * @param string $user_id
     * @param string $module
     * @param array $data
     */
    public static function setACLData($user_id, $module, $data)
    {
        self::$acls[$user_id][$module] = $data;
    }
}
