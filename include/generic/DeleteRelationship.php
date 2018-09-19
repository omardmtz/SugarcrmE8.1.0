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
 Removes Relationships, input is a form POST

ARGS:
 $_REQUEST['module']; : the module associated with this Bean instance (will be used to get the class name)
 $_REQUEST['record']; : the id of the Bean instance
 $_REQUEST['linked_field']; : the linked field name of the Parent Bean
 $_REQUEST['linked_id']; : the id of the Related Bean instance to

 $_REQUEST['return_url']; : the URL to redirect to
  or use:
  1) $_REQUEST['return_id']; :
  2) $_REQUEST['return_module']; :
  3) $_REQUEST['return_action']; :
*/

require_once('include/formbase.php');

 $focus = BeanFactory::newBean($_REQUEST['module']);
 if (  empty($_REQUEST['linked_id']) || empty($_REQUEST['linked_field'])  || empty($_REQUEST['record']))
 {
	die("need linked_field, linked_id and record fields");
 }
 $linked_field = $_REQUEST['linked_field'];
 $record = $_REQUEST['record'];
 $linked_id = $_REQUEST['linked_id'];
 if($focus->object_name == 'Team')
 {
 	$focus->retrieve($record);
 	$focus->remove_user_from_team($linked_id);
 }
 else
 {
 	// cut it off:
 	$focus->load_relationship($linked_field);
 	if($focus->$linked_field->_relationship->relationship_name == 'quotes_contacts_shipto')
 		unset($focus->$linked_field->_relationship->relationship_role_column);
 	$focus->$linked_field->delete($record,$linked_id);
 }
 if ($focus->object_name == 'Campaign' and $linked_field=='prospectlists' ) {

 	$query="SELECT email_marketing_prospect_lists.id from email_marketing_prospect_lists ";
 	$query.=" left join email_marketing on email_marketing.id=email_marketing_prospect_lists.email_marketing_id";
    $query.=" where email_marketing.campaign_id=" . $focus->db->quoted($record);
    $query.=" and email_marketing_prospect_lists.prospect_list_id=" . $focus->db->quoted($linked_id);

 	$result=$focus->db->query($query);
	while (($row=$focus->db->fetchByAssoc($result)) != null) {
			$del_query =" update email_marketing_prospect_lists set email_marketing_prospect_lists.deleted=1, email_marketing_prospect_lists.date_modified=".$focus->db->convert("'".TimeDate::getInstance()->nowDb()."'",'datetime');
        $del_query.=" WHERE  email_marketing_prospect_lists.id=" . $focus->db->quoted($row['id']);
		 	$focus->db->query($del_query);
	}
 	$focus->db->query($query);
 }
if ($focus->object_name == "Meeting") {
    $focus->retrieve($record);
    $user = BeanFactory::getBean('Users', $linked_id);
    if (!empty($user->id)) {  //make sure that record exists. we may have a contact on our hands.

    	if($focus->update_vcal)
    	{
        	vCal::cache_sugar_vcal($user);
    	}
    }
}
if ($focus->object_name == "User" && $linked_field == 'eapm') {
    BeanFactory::deleteBean('EAPM', $linked_id);
}
SugarRelationship::resaveRelatedBeans();

if(!empty($_REQUEST['return_url'])){
	$_REQUEST['return_url'] =urldecode($_REQUEST['return_url']);
}
$GLOBALS['log']->debug("deleted relationship: bean: {$focus->object_name}, linked_field: $linked_field, linked_id:$linked_id" );
if(empty($_REQUEST['refresh_page'])){
	handleRedirect();
}


exit;
?>
