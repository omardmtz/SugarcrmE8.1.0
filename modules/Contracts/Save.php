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

// $Id: Save.php 55779 2010-04-02 19:51:23Z jmertic $


require_once ('include/formbase.php');



global $timedate;
if(!empty($_POST['expiration_notice_time_meridiem']) && !empty($_POST['expiration_notice_time'])) {
	$_POST['expiration_notice_time'] = $timedate->merge_time_meridiem($_POST['expiration_notice_time'],$timedate->get_time_format(), $_POST['expiration_notice_time_meridiem']);
}


$sugarbean = BeanFactory::newBean('Contracts');
$sugarbean = populateFromPost('', $sugarbean);

if (!$sugarbean->ACLAccess('Save')) {
	ACLController :: displayNoAccess(true);
	sugar_cleanup(true);
}
if(empty($sugarbean->id)) {
    $sugarbean->id = create_guid();
    $sugarbean->new_with_id = true;
}

$check_notify = isset($GLOBALS['check_notify']) ? $GLOBALS['check_notify'] : false;
$sugarbean->save($check_notify);
$return_id = $sugarbean->id;

if (!empty($_POST['type']) && $_POST['type'] !== $_POST['old_type']) {
	//attach all documents from contract type into contract.
	$ctype = BeanFactory::getBean('ContractTypes', $_POST['type']);
	if (!empty($ctype->id)) {
		$ctype->load_relationship('documents');
		$doc = BeanFactory::newBean('Documents');
		$documents=$ctype->documents->getBeans($doc);
		if (count($documents) > 0) {
			$sugarbean->load_relationship('contracts_documents');
			foreach($documents as $document) {
				$sugarbean->contracts_documents->add($document->id,array('document_revision_id'=>$document->document_revision_id));
			}			
		}	
	}
}
handleRedirect($return_id, 'Contracts');
?>