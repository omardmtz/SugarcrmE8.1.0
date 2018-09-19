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
 * PersonFormBase.php
 *
 * @author Collin Lee
 *
 * This is an abstract class to handle separating some of the common logic used between the form base code.
 * One of the main common function shared was the checking of duplicate records.  This is now handled in the
 * checkForDuplicates method of this function.  When duplicates were found, we created an HTML block using
 * buildTableForm.
 *
 * @see LeadFormBase.php, ContactFormBase.php, ProspectFormBase.php
 */

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;
use Sugarcrm\Sugarcrm\Security\InputValidation\Request;

abstract class PersonFormBase extends FormBase {

var $moduleName;
var $objectName;

    /**
     * @var Request
     */
    protected $request;

/**
 * buildTableForm
 * 
 * This function creates a table with form data.  It is used by the form base code when checking for duplicates
 *
 * @param $rows Array of duplicate row data
 * @return $form The HTML form data
 */
function buildTableForm($rows)
{
	global $action;
    global $mod_strings;
	global $app_strings;

    $newLinkLabel = 'LNK_NEW_' . strtoupper($this->objectName);
    
	$cols = sizeof($rows[0]) * 2 + 1;
    
	if ($action != 'ShowDuplicates')
	{
        $duplicateLabel = string_format($app_strings['MSG_DUPLICATE'], array(strtolower($this->objectName), $this->moduleName));
		$form = '<table width="100%"><tr><td>'.$duplicateLabel.'</td></tr><tr><td height="20"></td></tr></table>';
		$form .= "<form action='index.php' method='post' name='dup{$this->moduleName}'><input type='hidden' name='selected{$this->objectName}' value=''>";
		$form .= getPostToForm('/emailAddress(PrimaryFlag|OptOutFlag|InvalidFlag)?[0-9]*?$/', true);
	} else {
        $duplicateLabel = string_format($app_strings['MSG_SHOW_DUPLICATES'], array(strtolower($this->objectName), $this->moduleName));
		$form = '<table width="100%"><tr><td>'.$duplicateLabel.'</td></tr><tr><td height="20"></td></tr></table>';
	}
    
	$form .= "<table width='100%' cellpadding='0' cellspacing='0' class='list view' border='0'><tr class='pagination'><td colspan='$cols'><table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td>";
	if ($action == 'ShowDuplicates')
	{
        $this->request = InputValidation::getService();
		$form .= "<input title='${app_strings['LBL_SAVE_BUTTON_TITLE']}' accessKey='${app_strings['LBL_SAVE_BUTTON_KEY']}' class='button' onclick=\"this.form.action.value='Save';\" type='submit' name='button' value='  ${app_strings['LBL_SAVE_BUTTON_LABEL']}  '>\n";
        if (!empty($_REQUEST['return_module']) && !empty($_REQUEST['return_action']) && !empty($_REQUEST['return_id']))
        {
            $form .= "<input title='${app_strings['LBL_CANCEL_BUTTON_TITLE']}' accessKey='${app_strings['LBL_CANCEL_BUTTON_KEY']}' class='button' onclick=\"this.form.module.value='" . $this->request->getValidInputRequest('return_module', 'Assert\Mvc\ModuleName') . "';this.form.action.value='" . htmlspecialchars(trim(json_encode($_REQUEST['return_action'], JSON_HEX_APOS), '"')) . "';this.form.record.value='" . $this->request->getValidInputRequest('return_id', 'Assert\Guid') . "'\" type='submit' name='button' value='  ${app_strings['LBL_CANCEL_BUTTON_LABEL']}  '>";
        } else if (!empty($_POST['return_module']) && !empty($_POST['return_action'])) {
            $form .= "<input title='${app_strings['LBL_CANCEL_BUTTON_TITLE']}' accessKey='${app_strings['LBL_CANCEL_BUTTON_KEY']}' class='button' onclick=\"this.form.module.value='" . $this->request->getValidInputPost('return_module', 'Assert\Mvc\ModuleName') . "';this.form.action.value='" . htmlspecialchars(trim(json_encode($_POST['return_action'], JSON_HEX_APOS), '"')) . "';\" type='submit' name='button' value='  ${app_strings['LBL_CANCEL_BUTTON_LABEL']}  '>";
        } else {
            $form .= "<input title='${app_strings['LBL_CANCEL_BUTTON_TITLE']}' accessKey='${app_strings['LBL_CANCEL_BUTTON_KEY']}' class='button' onclick=\"this.form.action.value='ListView';\" type='submit' type='submit' name='button' value='  ${app_strings['LBL_CANCEL_BUTTON_LABEL']}  '>";
        }
    } else {
		$form .= "<input type='submit' class='button' name='Continue{$this->objectName}' value='{$mod_strings[$newLinkLabel]}'>";
	}
	$form .= "</td></tr></table></td></tr><tr>";

    if ($action != 'ShowDuplicates')
	{
		$form .= "<td scope='col'>&nbsp;</td>";
	}

	require_once('include/formbase.php');

	if(isset($_POST['return_action']) && $_POST['return_action'] == 'SubPanelViewer') {
		$_POST['return_action'] = 'DetailView';
	} 
	
	if(isset($_POST['return_action']) && $_POST['return_action'] == 'DetailView' && empty($_REQUEST['return_id'])) {
		unset($_POST['return_action']);
	}
		
   $form .= getPostToForm();
	
	if(isset($rows[0])){
		foreach ($rows[0] as $key=>$value){
			if($key != 'id'){
			   $form .= "<td scope='col' >". $mod_strings[$mod_strings['db_'.$key]]. "</td>";
			}
		}
		$form .= "</tr>";
	}

	$rowColor = 'oddListRowS1';

    foreach($rows as $row){

		$form .= "<tr class='$rowColor'>";
		if ($action != 'ShowDuplicates')
		{
			$form .= "<td width='1%' nowrap='nowrap'><a href='#' onClick=\"document.forms['dup{$this->moduleName}'].selected{$this->objectName}.value='${row['id']}';document.forms['dup{$this->moduleName}'].submit() \">[{$app_strings['LBL_SELECT_BUTTON_LABEL']}]</a>&nbsp;&nbsp;</td>\n";
		}
		$wasSet = false;

		foreach ($row as $key=>$value){
				if($key != 'id'){
					if(isset($_POST['popup']) && $_POST['popup']==true){
						$form .= "<td scope='row'><a  href='#' onclick=\"window.opener.location='index.php?module={$this->moduleName}&action=DetailView&record=${row['id']}'\">$value</a></td>\n";
					}
					else if(!$wasSet){
						$form .= "<td scope='row'><a target='_blank' href='index.php?module={$this->moduleName}&action=DetailView&record=${row['id']}'>$value</a></td>\n";
						$wasSet = true;
					}else{
					    $form .= "<td><a target='_blank' href='index.php?module={$this->moduleName}&action=DetailView&record=${row['id']}'>$value</a></td>\n";
					}
				}
		}

		if($rowColor == 'evenListRowS1'){
			$rowColor = 'oddListRowS1';
		}else{
			 $rowColor = 'evenListRowS1';
		}
		$form .= "</tr>";
	}
	$form .= "<tr class='pagination'><td colspan='$cols'><table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td>";
	if ($action == 'ShowDuplicates')
	{
		$form .= "<input title='${app_strings['LBL_SAVE_BUTTON_TITLE']}' accessKey='${app_strings['LBL_SAVE_BUTTON_KEY']}' class='button' onclick=\"this.form.action.value='Save';\" type='submit' name='button' value='  ${app_strings['LBL_SAVE_BUTTON_LABEL']}  '>\n";
        if (!empty($_REQUEST['return_module']) && !empty($_REQUEST['return_action']) && !empty($_REQUEST['return_id']))
            $form .= "<input title='${app_strings['LBL_CANCEL_BUTTON_TITLE']}' accessKey='${app_strings['LBL_CANCEL_BUTTON_KEY']}' class='button' onclick=\"this.form.module.value='" . $this->request->getValidInputRequest('return_module', 'Assert\Mvc\ModuleName') . "';this.form.action.value='" . htmlspecialchars(trim(json_encode($_REQUEST['return_action'], JSON_HEX_APOS), '"')) . "';this.form.record.value='" . $this->request->getValidInputRequest('return_id', 'Assert\Guid') . "';\" type='submit' name='button' value='  ${app_strings['LBL_CANCEL_BUTTON_LABEL']}  '>";
        else if (!empty($_POST['return_module']) && !empty($_POST['return_action']))
            $form .= "<input title='${app_strings['LBL_CANCEL_BUTTON_TITLE']}' accessKey='${app_strings['LBL_CANCEL_BUTTON_KEY']}' class='button' onclick=\"this.form.module.value='" . $this->request->getValidInputPost('return_module', 'Assert\Mvc\ModuleName') . "';this.form.action.value='" . htmlspecialchars(trim(json_encode($_POST['return_action'], JSON_HEX_APOS), '"')) . "';\" type='submit' name='button' value='  ${app_strings['LBL_CANCEL_BUTTON_LABEL']}  '>";
        else
            $form .= "<input title='${app_strings['LBL_CANCEL_BUTTON_TITLE']}' accessKey='${app_strings['LBL_CANCEL_BUTTON_KEY']}' class='button' onclick=\"this.form.action.value='ListView';\" type='submit' type='submit' name='button' value='  ${app_strings['LBL_CANCEL_BUTTON_LABEL']}  '>";
    }
	else
	{
		$form .= "<input type='submit' class='button' name='Continue{$this->objectName}' value='{$mod_strings[$newLinkLabel]}'></form>";
	}
    $form .= "</td></tr></table></td></tr></table>";
	return $form;
}


/**
 * checkForDuplicates
 *
 * This function is used to locate any duplicate Leads that may be created when a new Lead is saved.  It is called from the handleSave method
 * of this class.
 *
 * @param $prefix String value of any prefix to the form input names
 * @return $rows Array of matching Leads entries found; null if none found
 */
function checkForDuplicates($prefix='')
{
	require_once('include/formbase.php');
    $focus = BeanFactory::newBean($this->moduleName);

	$query = $this->getDuplicateQuery($focus, $prefix);

    if(empty($query))
    {
       return null;
    }

	$rows = array();

    global $db;
	$result = $db->query($query);

    //Loop through the results and store
	while (($row = $db->fetchByAssoc($result)) != null)
    {
		if(!isset($rows[$row['id']])) {
		   $rows[]=$row;
		}
	}

    //Now check for duplicates using email values supplied
	$count = 0;
	$emails = array();
	$emailStr = '';
	while(isset($_POST["{$this->moduleName}{$count}emailAddress{$count}"]))
    {
	      $emailStr .= ",'" . strtoupper(trim($_POST["{$this->moduleName}{$count}emailAddress" . $count++])) . "'";
	} //while

	if(!empty($emailStr))
    {
		$emailStr = substr($emailStr, 1);
		$query = 'SELECT DISTINCT er.bean_id AS id FROM email_addr_bean_rel er, ' .
		         'email_addresses ea WHERE ea.id = er.email_address_id ' .
		         'AND ea.deleted = 0 AND er.deleted = 0 AND er.bean_module = \'' . $this->moduleName . '\' ' .
	             'AND email_address_caps IN (' . $emailStr . ')';

		$result = $db->query($query);
		while (($row = $db->fetchByAssoc($result)) != null)
        {
			if(!isset($rows[$row['id']])) {
			   $query2 = "SELECT id, first_name, last_name, title FROM {$focus->table_name} WHERE deleted=0 AND id = '" . $row['id'] . "'";
			   $result2 = $db->query($query2);
			   $r = $db->fetchByAssoc($result2);
               if(isset($r['id'])) {
			   	  $rows[]=$r;
			   }
			} //if
		}
	} //if

    return !empty($rows) ? $rows : null;
}


/**
 * getDuplicateQuery
 *
 * This is the function that subclasses should extend and return a SQL query used for the initial duplicate
 * check sequence.
 *
 * @see checkForDuplicates (method), ContactFormBase.php, LeadFormBase.php, ProspectFormBase.php
 * @param $focus sugarbean
 * @param $prefix String value of prefix that may be present in $_POST variables
 * @return SQL String of the query that should be used for the initial duplicate lookup check
 */
public function getDuplicateQuery($focus, $prefix='')
{
    return null;
}


}