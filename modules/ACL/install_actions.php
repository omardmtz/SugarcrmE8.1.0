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

require_once 'modules/ACLRoles/SeedRoles.php';

global $current_user,$beanList, $beanFiles, $mod_strings;

$installed_classes = array();
$ACLbeanList=$beanList;


// In the event that previous Tracker entries were installed from 510RC, we need to fix the category value
$GLOBALS['db']->query("UPDATE acl_actions set acltype = 'TrackerPerf' where category = 'TrackerPerfs'");
$GLOBALS['db']->query("UPDATE acl_actions set acltype = 'TrackerSession' where category = 'TrackerSessions'");
$GLOBALS['db']->query("UPDATE acl_actions set acltype = 'TrackerQuery' where category = 'TrackerQueries'");

if(is_admin($current_user)){
    foreach($ACLbeanList as $module=>$class){

        if(empty($installed_classes[$class]) && isset($beanFiles[$class])){
            if($class == 'Tracker'){
                ACLAction::addActions('Trackers', 'Tracker');
            } else {
                $mod = BeanFactory::newBeanByName($class);
                $GLOBALS['log']->debug("DOING: $class");
                if($mod instanceof SugarBean && $mod->bean_implements('ACL') && $mod->isACLRoleEditable()){
                    // BUG 10339: do not display messages for upgrade wizard
                    if(!isset($_REQUEST['upgradeWizard'])){
                        echo translate('LBL_ADDING','ACL','') . $mod->module_dir . '<br>';
                    }
                    $createACL = false;
                    $aclList = SugarACL::loadACLs($mod->getACLCategory());
                    foreach ($aclList as $acl) {
                        if ($acl instanceof SugarACLStatic) {
                            $createACL = true;
                        }
                    }

                    if (!empty($createACL)) {
                        if (!empty($mod->acltype)) {
                            ACLAction::addActions($mod->getACLCategory(), $mod->acltype);
                        } else {
                            ACLAction::addActions($mod->getACLCategory());
                        }
                    }

                    $installed_classes[$class] = true;
                }
            }
        }
    }



$installActions = false;
$missingAclRolesActions = false;

$role1 = BeanFactory::newBean('ACLRoles');

$role_id = $GLOBALS['db']->fetchOne("SELECT id FROM acl_roles where name = 'Tracker'");

if(!empty($role_id['id'])) {
   $role_id = $role_id['id'];
   $role1->retrieve($role_id);
   $count = $GLOBALS['db']->fetchOne("SELECT count(role_id) as count FROM acl_roles_actions where role_id = '{$role_id}'");
   // If there are no corresponding entries in acl_roles_actions, then we need to add it
   if(empty($count['count'])) {
        $missingAclRolesActions = true;
   }
} else {
   $role1->name = "Tracker";
   $role1->description = "Tracker Role";
   $role1_id = $role1->save();
   $role1->set_relationship('acl_roles_users', array('role_id'=>$role1->id ,'user_id'=>1), false);
   $installActions = true;
}

if($installActions || $missingAclRolesActions) {
    $defaultTrackerRoles = array(
        'Tracker'=>array(
            'Trackers'=>array('admin'=>1, 'access'=>89, 'view'=>90, 'list'=>90, 'edit'=>90, 'delete'=>90, 'import'=>90, 'export'=>90),
            'TrackerQueries'=>array('admin'=>1, 'access'=>89, 'view'=>90, 'list'=>90, 'edit'=>90, 'delete'=>90, 'import'=>90, 'export'=>90),
            'TrackerPerfs'=>array('admin'=>1, 'access'=>89, 'view'=>90, 'list'=>90, 'edit'=>90, 'delete'=>90, 'import'=>90, 'export'=>90),
            'TrackerSessions'=>array('admin'=>1, 'access'=>89, 'view'=>90, 'list'=>90, 'edit'=>90, 'delete'=>90, 'import'=>90, 'export'=>90),
        )
    );


    foreach($defaultTrackerRoles as $roleName=>$role){
        foreach($role as $category=>$actions){
            foreach($actions as $name=>$access_override){
                $queryACL="SELECT id FROM acl_actions where category='$category' and name='$name'";
                $actionId = $GLOBALS['db']->fetchOne($queryACL);
                if (isset($actionId['id']) && !empty($actionId['id'])){
                    $role1->setAction($role1->id, $actionId['id'], $access_override);
                }
            }
        }
    } //foreach
}
//Check for the existence of MLA roles

$role1 = BeanFactory::newBean('ACLRoles');

    $mlaRoles = getMLARoles();
    $rolesToAdd = array_keys($mlaRoles);
    $sql = "SELECT id, name FROM acl_roles WHERE name IN ('";
    $sql .= implode("', '", $rolesToAdd);
    $sql .= "') AND deleted = 0";
    $db = DBManagerFactory::getInstance();
    $result = $db->query($sql);
    $roles = array();
    while ($row = $db->fetchByAssoc($result)) {
        $roles[$row['id']] = $row['name'];
    }
    foreach ($roles as $id => $name) {
        $count = $db->fetchOne("SELECT count(role_id) as count FROM acl_roles_actions where role_id = " . $db->quoted($id));
        // If there are no corresponding entries in acl_roles_actions, then we need to add it
        if (!empty($count['count'])) {
            $key = array_search($name, $rolesToAdd);
            if ($key !== false) {
                unset($rolesToAdd[$key]); // this role ok, no need to add
            }
        }
    }

    foreach($mlaRoles as $roleName=>$role){
        if (!in_array($roleName, $rolesToAdd)) {
            continue;
        }
        $role1 = BeanFactory::newBean('ACLRoles');
        $role1->name = $roleName;
        $role1->description = $roleName." Role";
        $role1_id=$role1->save();
        foreach($role as $category=>$actions){
            foreach($actions as $name=>$access_override){
                if($name=='fields'){
                    foreach($access_override as $field_id=>$access){
                        ACLField::setAccessControl($category, $role1_id, $field_id, $access);
                    }
                }else{
                    $queryACL="SELECT id FROM acl_actions where category='$category' and name='$name'";
                    $actionId = $GLOBALS['db']->fetchOne($queryACL);
                    if (isset($actionId['id']) && !empty($actionId['id'])){
                        $role1->setAction($role1_id, $actionId['id'], $access_override);
                    }
                }
            }
        }
    }
}
?>
