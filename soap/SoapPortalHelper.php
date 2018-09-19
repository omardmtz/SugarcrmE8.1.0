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

use Sugarcrm\Sugarcrm\Util\Arrays\ArrayFunctions\ArrayFunctions;
use Sugarcrm\Sugarcrm\Security\Validator\Constraints\Sql\OrderBy;
use Sugarcrm\Sugarcrm\Security\Validator\Constraints\Sql\OrderDirection;
use Sugarcrm\Sugarcrm\Security\Validator\Validator;

$portal_modules = array('Contacts', 'Accounts', 'Notes');
$portal_modules[] = 'Cases';
$portal_modules[] = 'Bugs';

/**
 * Validates ORDER BY statement
 *
 * @param $orderBy string
 * @throws InvalidArgumentException
 */
function validateOrderBy($orderBy)
{
    $constraintOrderBy = new OrderBy();
    $constraintOrderDirection = new OrderDirection();

    $parts = preg_split('/\s*,\s*/', trim($orderBy));
    foreach ($parts as $part) {
        $order = preg_split('/\s+/', $part, 2);

        $violations = Validator::getService()->validate($order[0], $constraintOrderBy);
        if (count($violations) > 0) {
            $msg = array_reduce(iterator_to_array($violations), function ($msg, $violation) {
                return empty($msg) ? $violation->getMessage() : $msg . ' - ' . $violation->getMessage();
            });
            throw new \InvalidArgumentException($msg);
        }

        if (count($order) > 1) {
            $violations = Validator::getService()->validate($order[1], $constraintOrderDirection);
            if (count($violations) > 0) {
                $msg = array_reduce(iterator_to_array($violations), function ($msg, $violation) {
                    return empty($msg) ? $violation->getMessage() : $msg . ' - ' . $violation->getMessage();
                });
                throw new \InvalidArgumentException($msg);
            }
        }
    }
}

/*
BUGS
*/

function get_bugs_in_contacts($in, $orderBy = '')
    {
    validateOrderBy($orderBy);

        //bail if the in is empty
        if(empty($in)  || $in =='()' || $in =="('')")return;
        // First, get the list of IDs.

        $query = "SELECT cb.bug_id as id from contacts_bugs cb, bugs b where cb.bug_id = b.id and b.deleted = 0 and b.portal_viewable = 1 and cb.contact_id IN $in AND cb.deleted=0";
        if(!empty($orderBy)){
            $query .= ' ORDER BY ' . $orderBy;
        }

        $sugar = BeanFactory::newBean('Contacts');
        $sugar->disable_row_level_security = true;
        set_module_in($sugar->build_related_in($query), 'Bugs');
    }

function get_bugs_in_accounts($in, $orderBy = '')
    {
    validateOrderBy($orderBy);
        //bail if the in is empty
        if(empty($in)  || $in =='()' || $in =="('')")return;
        // First, get the list of IDs.

        $query = "SELECT ab.bug_id as id from accounts_bugs ab, bugs b where ab.bug_id = b.id and b.deleted = 0 and b.portal_viewable = 1 and ab.account_id IN $in AND ab.deleted=0";
        if(!empty($orderBy)){
            $query .= ' ORDER BY ' . $orderBy;
        }

        $sugar = BeanFactory::newBean('Accounts');
        $sugar->disable_row_level_security = true;

        set_module_in($sugar->build_related_in($query), 'Bugs');
    }

/*
Cases
*/

function get_cases_in_contacts($in, $orderBy = '')
    {
    validateOrderBy($orderBy);
        //bail if the in is empty
        if(empty($in)  || $in =='()' || $in =="('')")return;
        // First, get the list of IDs.

        $query = "SELECT case_id as id from contacts_cases cc, cases c where cc.case_id = c.id AND c.deleted = 0 AND c.portal_viewable = 1 AND cc.contact_id IN $in AND cc.deleted=0";
        if(!empty($orderBy)){
            $query .= ' ORDER BY ' . $orderBy;
        }

        $sugar = BeanFactory::newBean('Contacts');
        $sugar->disable_row_level_security = true;
        set_module_in($sugar->build_related_in($query), 'Cases');
    }

function get_cases_in_accounts($in, $orderBy = '')
    {
    validateOrderBy($orderBy);
        if(empty($_SESSION['viewable']['Accounts'])){
            return;
        }
        //bail if the in is empty
        if(empty($in)  || $in =='()' || $in =="('')")return;
        // First, get the list of IDs.
        $query = "SELECT id from cases where deleted = 0 AND portal_viewable = 1 AND account_id IN $in";
        if(!empty($orderBy)){
            $query .= ' ORDER BY ' . $orderBy;
        }

        $sugar = BeanFactory::newBean('Accounts');
        $sugar->disable_row_level_security = true;
        set_module_in($sugar->build_related_in($query), 'Cases');
    }



/*
NOTES
*/


function get_notes_in_contacts($in, $orderBy = '')
    {
    validateOrderBy($orderBy);
        //bail if the in is empty
        if(empty($in)  || $in =='()' || $in =="('')")return;
        // First, get the list of IDs.
        $query = "SELECT id from notes where contact_id IN $in AND deleted=0 AND portal_flag=1";
        if(!empty($orderBy)){
            $query .= ' ORDER BY ' . $orderBy;
        }

        $contact = BeanFactory::newBean('Contacts');
        $contact->disable_row_level_security = true;
        $note = BeanFactory::newBean('Notes');
        $note->disable_row_level_security = true;
        return $contact->build_related_list($query, $note);
    }

function get_notes_in_module($in, $module, $orderBy = '')
    {
    validateOrderBy($orderBy);
        //bail if the in is empty
        if(empty($in)  || $in =='()' || $in =="('')")return;
        // First, get the list of IDs.
        $query = "SELECT id from notes where parent_id IN $in AND parent_type='$module' AND deleted=0 AND portal_flag = 1";
        if(!empty($orderBy)){
            $query .= ' ORDER BY ' . $orderBy;
        }

        $sugar = BeanFactory::newBean($module);
        if(empty($sugar)) {
            return array();
        }

        $sugar->disable_row_level_security = true;
        $note = BeanFactory::newBean('Notes');
        $note->disable_row_level_security = true;
        return $sugar->build_related_list($query, $note);
    }

    function get_related_in_module($in, $module, $rel_module, $orderBy = '', $row_offset = 0, $limit= -1)
    {
    validateOrderBy($orderBy);

        $rel = BeanFactory::newBean($rel_module);
        if(empty($rel)) {
        	return array();
        }

        $sugar = BeanFactory::newBean($module);
        if(empty($sugar)) {
        	return array();
        }

        //bail if the in is empty
        if(empty($in)  || $in =='()' || $in =="('')")return;

        // First, get the list of IDs.
        $query = "SELECT id from {$rel->table_name}
            where parent_id IN {$in} AND parent_type = {$GLOBALS['db']->quoted($module)}
             AND deleted = 0 AND portal_flag = 1";

        if(!empty($orderBy)){
            $valid = new SugarSQLValidate();
            $fakeWhere = " 1=1 ";
            if($valid->validateQueryClauses($fakeWhere,$orderBy)) {
                $query .= ' ORDER BY '. $orderBy;
            } else {
                $GLOBALS['log']->error("Bad order by: $orderBy");
            }

        }

        $sugar->disable_row_level_security = true;
        $rel->disable_row_level_security = true;

        $count_query = $sugar->create_list_count_query($query);
        if(!empty($count_query))
        {
            // We have a count query.  Run it and get the results.
            $result = $sugar->db->query($count_query, true, "Error running count query for $sugar->object_name List: ");
            $assoc = $sugar->db->fetchByAssoc($result);
            if(!empty($assoc['c']))
            {
                $rows_found = $assoc['c'];
            }
        }
        $list = $sugar->build_related_list($query, $rel, $row_offset, $limit);
        $list['result_count'] = $rows_found;
        return $list;
    }

function get_accounts_from_contact($contact_id, $orderBy = '')
    {
    validateOrderBy($orderBy);
                // First, get the list of IDs.
        $query = "SELECT account_id as id from accounts_contacts where contact_id='".$GLOBALS['db']->quote($contact_id)."' AND deleted=0";
        if(!empty($orderBy)){
            $query .= ' ORDER BY ' . $orderBy;
        }
        $sugar = BeanFactory::newBean('Contacts');
        $sugar->disable_row_level_security = true;
        set_module_in($sugar->build_related_in($query), 'Accounts');
    }

function get_contacts_from_account($account_id, $orderBy = '')
    {
    validateOrderBy($orderBy);
        // First, get the list of IDs.
        $query = "SELECT contact_id as id from accounts_contacts where account_id='".$GLOBALS['db']->quote($account_id)."' AND deleted=0";
        if(!empty($orderBy)){
            $query .= ' ORDER BY ' . $orderBy;
        }
        $sugar = BeanFactory::newBean('Accounts');
        $sugar->disable_row_level_security = true;
        set_module_in($sugar->build_related_in($query), 'Contacts');
    }

function get_related_list($in, $template, $where, $order_by, $row_offset = 0, $limit = ""){

    validateOrderBy($order_by);
        $template->disable_row_level_security = true;

        $q = '';
        //if $in is empty then pass in a query to get the list of related list
        if(empty($in)  || $in =='()' || $in =="('')"){
            $in = '';
            //build the query to pass into the template list function
             $q = 'select id from '.$template->table_name.' where deleted = 0 ';
        	//add where statement if it is not empty
			if(!empty($where)){
                $valid = new SugarSQLValidate();
                if(!$valid->validateQueryClauses($where)) {
                    $GLOBALS['log']->error("Bad query: $where");
                    // No way to directly pass back an error.
                    return array();
                }

				$q .= ' and ( '.$where.' ) ';
			}
        }

        return $template->build_related_list_where($q, $template, $where, $in, $order_by, $limit, $row_offset);

    }

function build_relationship_tree($contact){
    global $sugar_config;
    $contact->retrieve($contact->id);

    $contact->disable_row_level_security = true;
    get_accounts_from_contact($contact->id);

    set_module_in(array('list'=>array($contact->id), 'in'=> "('".$GLOBALS['db']->quote($contact->id)."')"), 'Contacts');

    $accounts = $_SESSION['viewable']['Accounts'];
    foreach($accounts as $id){
        if(!isset($sugar_config['portal_view']) || $sugar_config['portal_view'] != 'single_user'){
            get_contacts_from_account($id);
        }
    }
}

function get_contacts_in(){
    return $_SESSION['viewable']['contacts_in'];
}

function get_accounts_in(){
    return $_SESSION['viewable']['accounts_in'];
}

function get_module_in($module_name){
    if(!isset($_SESSION['viewable'][$module_name])){
        return '()';
    }

    $module_name_in = ArrayFunctions::array_access_keys($_SESSION['viewable'][$module_name]);
    $module_name_list = array();
    foreach ( $module_name_in as $name ) {
        $module_name_list[] = $GLOBALS['db']->quote($name);
    }

    $mod_in = "('" . join("','", $module_name_list) . "')";
    $_SESSION['viewable'][strtolower($module_name).'_in'] = $mod_in;

    return $mod_in;
}

function set_module_in($arrayList, $module_name){

        if(!isset($_SESSION['viewable'][$module_name])){
            $_SESSION['viewable'][$module_name] = array();
        }
        foreach($arrayList['list'] as $id){
            $_SESSION['viewable'][$module_name][$id] = $id;
        }
        if($module_name == 'Accounts' && isset($id)){
            $_SESSION['account_id'] = $id;
        }

        if(!empty($_SESSION['viewable'][strtolower($module_name).'_in'])){
            if($arrayList['in'] != '()') {
                $newList = array();
                if ( ArrayFunctions::is_array_access($_SESSION['viewable'][strtolower($module_name).'_in']) ) {
                    foreach($_SESSION['viewable'][strtolower($module_name).'_in'] as $name ) {
                        $newList[] = $GLOBALS['db']->quote($name);
                    }
                }
                if ( is_array($arrayList['list']) ) {
                    foreach ( $arrayList['list'] as $name ) {
                        $newList[] = $GLOBALS['db']->quote($name);
                    }
                }
                $_SESSION['viewable'][strtolower($module_name).'_in'] = "('" . implode("', '", $newList) . "')";
            }
        }else{
            $_SESSION['viewable'][strtolower($module_name).'_in'] = $arrayList['in'];
        }
}

/*
 * Given the user auth, attempt to log the user in.
 * used by SoapPortalUsers.php
 */
function login_user($portal_auth){
    $error = new SoapError();
    $user = User::getUserDataByNameAndPassword($portal_auth['user_name'], $portal_auth['password']);

    if ($user && $user['portal_only'] == 1) {
            global $current_user;
            $bean = BeanFactory::getBean('Users', $user['id']);
            $current_user = $bean;
            $sessionManager = new SessionManager();
            if(!$sessionManager->canAddSession()){
                //not able to add another session right now
                $GLOBALS['log']->debug("Unable to add new session");
                return 'sessions_exceeded';
            }
            return 'success';
    } else {
            $GLOBALS['log']->fatal('SECURITY: User authentication for '. $portal_auth['user_name']. ' failed');
            return 'fail';
    }
}


function portal_get_entry_list_limited($session, $module_name,$where, $order_by, $select_fields, $row_offset, $limit){
    global  $beanList, $beanFiles, $portal_modules;
    $error = new SoapError();
    if(! portal_validate_authenticated($session)){
        $error->set_error('invalid_session');
        return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
    }
    if($_SESSION['type'] == 'lead' ){
        $error->set_error('no_access');
        return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
    }
    if(empty($beanList[$module_name])){
        $error->set_error('no_module');
        return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());
    }
    if($module_name == 'Cases'){

        //if the related cases have not yet been loaded into the session object,
        //then call the methods that will load the cases related to the contact/accounts for this user
        if(!isset($_SESSION['viewable'][$module_name])){
            //retrieve the contact/account id's for this user
            $c =get_contacts_in();
            $a = get_accounts_in();
           if(!empty($c)) {get_cases_in_contacts($c);}
           if(!empty($a)) { get_cases_in_accounts($a);}
        }

        $sugar = BeanFactory::newBean('Cases');

        $list = array();
        //if no Cases have been loaded into the session as viewable, then do not issue query, just return empty list
        //issuing a query with no cases loaded in session will return ALL the Cases, which is not a good thing
        if(!empty($_SESSION['viewable'][$module_name])){
            $list =  get_related_list(get_module_in($module_name), BeanFactory::newBean('Cases'), $where,$order_by, $row_offset, $limit);
        }

    }else if($module_name == 'Contacts'){
            $sugar = BeanFactory::newBean('Contacts');
            $list =  get_related_list(get_module_in($module_name), BeanFactory::newBean('Contacts'), $where,$order_by);
    }else if($module_name == 'Accounts'){
            $sugar = BeanFactory::newBean('Accounts');
            $list =  get_related_list(get_module_in($module_name), BeanFactory::newBean('Accounts'), $where,$order_by);
    }else if($module_name == 'Bugs'){

        //if the related bugs have not yet been loaded into the session object,
        //then call the methods that will load the bugs related to the contact/accounts for this user
            if(!isset($_SESSION['viewable'][$module_name])){
                //retrieve the contact/account id's for this user
                $c =get_contacts_in();
                $a = get_accounts_in();
                if(!empty($c)) {get_bugs_in_contacts($c);}
                if(!empty($a)) {get_bugs_in_accounts($a);}
            }

        $list = array();
        //if no Bugs have been loaded into the session as viewable, then do not issue query, just return empty list
        //issuing a query with no bugs loaded in session will return ALL the Bugs, which is not a good thing
        if(!empty($_SESSION['viewable'][$module_name])){
            $list = get_related_list(get_module_in($module_name), BeanFactory::newBean('Bugs'), $where, $order_by, $row_offset, $limit);
        }
    } else{
        $error->set_error('no_module_support');
        return array('result_count'=>-1, 'entry_list'=>array(), 'error'=>$error->get_soap_array());

    }

    $output_list = Array();
    $field_list = array();
    foreach($list as $value)
    {
        $output_list[] = get_return_value($value, $module_name);
        $_SESSION['viewable'][$module_name][$value->id] = $value->id;
        if(empty($field_list)){
            $field_list = get_field_list($value);
        }
    }
    $output_list = filter_return_list($output_list, $select_fields, $module_name);
    $field_list = filter_field_list($field_list,$select_fields, $module_name);

    return array('result_count'=>sizeof($output_list), 'next_offset'=>0,'field_list'=>$field_list, 'entry_list'=>$output_list, 'error'=>$error->get_soap_array());
}

$invalid_contact_fields = array('portal_password'=>1);
$valid_modules_for_contact = array('Contacts'=>1, 'Cases'=>1, 'Notes'=>1, 'Bugs'=>1, 'Accounts'=>1, 'Leads'=>1);
