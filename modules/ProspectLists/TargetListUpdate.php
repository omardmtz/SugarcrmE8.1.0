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

/*
 ARGS:

 $_REQUEST['module'] : the module associated with this Bean instance (will be used to get the class name)
 $_REQUEST['prospect_lists'] : the id of the prospect list
 $_REQUEST['uids'] : the ids of the records to be added to the prospect list, separated by ','

 */
use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

require_once 'include/formbase.php';

$module = InputValidation::getService()->getValidInputRequest('module', 'Assert\Mvc\ModuleName');
$focus = BeanFactory::newBean($module);

$uids = array();
if($_REQUEST['select_entire_list'] == '1'){
    $query = InputValidation::getService()->getValidInputRequest(
        'current_query_by_page',
        array('Assert\PhpSerialized' => array('base64Encoded' => true))
    );
	$mass = new MassUpdate();
	$mass->generateSearchWhere($module, $query);
    $query = $focus->create_new_list_query('', $mass->where_clauses, $mass->searchFields);
	$result = $GLOBALS['db']->query($query,true);
	$uids = array();
	while($val = $GLOBALS['db']->fetchByAssoc($result,false))
	{
		array_push($uids, $val['id']);
	}
}
else{
	$uids = explode ( ',', $_POST['uids'] );
}

// find the relationship to use
$relationship = '';
foreach($focus->get_linked_fields() as $field => $def) {
    if ($focus->load_relationship($field)) {
        if ( $focus->$field->getRelatedModuleName() == 'ProspectLists' ) {
            $relationship = $field;
            break;
        }
    }
}

if ( $relationship != '' ) {
    foreach ( $uids as $id) {
        $focus->retrieve($id);
        $focus->load_relationship($relationship);
        $focus->prospect_lists->add( $_REQUEST['prospect_list'] );
    }
}
handleRedirect();
exit;
?>
