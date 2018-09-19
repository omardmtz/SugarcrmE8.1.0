<?php
Activity::disable();
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
require_once('modules/Teams/TeamSetManager.php');

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;

global $mod_strings, $app_strings;
$mod_strings_users = $mod_strings;

global $current_user;
if(!$GLOBALS['current_user']->isAdminForModule('Users')) {
	sugar_die($app_strings['EXCEPTION_NOT_AUTHORIZED']);
}

global $locale, $dictionary;

$db = DBManagerFactory::getInstance();

$return_module = InputValidation::getService()->getValidInputRequest('return_module', 'Assert\Mvc\ModuleName', '');
$return_action = InputValidation::getService()->getValidInputRequest('return_action', '');
$return_id = InputValidation::getService()->getValidInputRequest('return_id', 'Assert\Guid', '');
$fromuser = InputValidation::getService()->getValidInputRequest('fromuser', 'Assert\Guid');
$touser = InputValidation::getService()->getValidInputRequest('touser', 'Assert\Guid');

if(!empty($return_module)) {
    $cancel_location = "index.php?module={$return_module}&action={$return_action}&record={$return_id}";
} else {
    $cancel_location = "index.php?module=Users&action=index";
}

echo "<h2 class='moduleTitle' style=\"margin-bottom:0px;\">{$mod_strings_users['LBL_REASS_SCRIPT_TITLE']}</h2>";

// Include Metadata for processing
foreach(SugarAutoLoader::existingCustom('modules/Users/metadata/reassignScriptMetadata.php', 'modules/Users/reassignScriptMetadata_override.php') as $file) {
    include $file;
}

$record = InputValidation::getService()->getValidInputRequest('record', 'Assert\Guid');
if(!empty($record)){
	unset($_SESSION['reassignRecords']);
	$_SESSION['reassignRecords']['fromuser'] = $record;
}

if(empty($fromuser) && !isset($_GET['execute'])){
///////////////////// BEGIN STEP 1 - Select users/modules /////////////////////////
	$exclude_modules = array(
		"ImportMap",
		"UsersLastImport",
		"SavedSearch",
		"UserPreference",
	    "SugarFavorites",
	    'OAuthKey',
	    'OAuthToken',
	);

	if(isset($_GET['clear']) && $_GET['clear'] == 'true'){
		unset($_SESSION['reassignRecords']);
	}
?>
<form method=post action="index.php?module=Users&action=reassignUserRecords" name='EditView' id='EditView'>
<table cellspacing='1' cellpadding='1' border='0'>
<tr>
    <td><?php echo $mod_strings_users['LBL_REASS_DESC_PART1']."<BR><br>"?></td>
</tr>
<tr>
<td>
<input type=submit class="button" value="<?php echo $mod_strings_users['LBL_REASS_BUTTON_CONTINUE']; ?>" name=steponesubmit>
&nbsp;<input type=button class="button" value="<?php echo $mod_strings_users['LBL_REASS_BUTTON_CLEAR']; ?>" onclick='clearCurrentRecords();'>
<input type=button class="button" value="<?php echo $app_strings['LBL_CANCEL_BUTTON_LABEL']; ?>" onclick='document.location="<?php echo $cancel_location ?>"'>
</td>
</tr>
</table>
<table border='0' cellspacing='0' cellpadding='0'  class='edit view'>
<tr>
<td>
<BR>
<?php echo $mod_strings_users['LBL_REASS_USER_FROM']; ?>
<BR>
<select name="fromuser" id='fromuser'>
<?php
$all_users = User::getAllUsers();
echo get_select_options_with_id($all_users, isset($_SESSION['reassignRecords']['fromuser']) ? $_SESSION['reassignRecords']['fromuser'] : '');
?>
</select>
<BR>
<BR>
<?php echo $mod_strings_users['LBL_REASS_USER_TO']; ?>
<BR>
<select name="touser" id="touser">
<?php
if(isset($_SESSION['reassignRecords']['fromuser']) && isset($all_users[$_SESSION['reassignRecords']['fromuser']]))
{
	unset($all_users[$_SESSION['reassignRecords']['fromuser']]);
}

echo get_select_options_with_id($all_users, isset($_SESSION['reassignRecords']['touser']) ? $_SESSION['reassignRecords']['touser'] : '');
?>
</select>
<?php
?>
<BR>
<BR>
<?php echo $mod_strings_users['LBL_REASS_TEAM_TO']; ?>
<BR>

<?php
$teamSetField = new SugarFieldTeamset('Teamset');
$lead = BeanFactory::newBean('Leads');
$teamSetField->initClassicView($lead->field_defs, 'EditView');
$sqs_objects = $teamSetField->getClassicViewQS();

$json = getJSONobj();
$quicksearch_js = '<script type="text/javascript" language="javascript">sqs_objects = ' . $json->encode($sqs_objects) . '</script>';
echo $teamSetField->getClassicView();
?>

<?php
?>
<BR>
<?php echo $mod_strings_users['LBL_REASS_MOD_REASSIGN']; ?>
<BR>
<select size="6" name='modules[]' multiple="true" id='modulemultiselect' onchange="updateDivDisplay(this);">
<?php
if(!isset($_SESSION['reassignRecords']['assignedModuleListCache'])){

    $beanListDup = $beanList;

    unset($beanListDup['ForecastManagerWorksheets']);

    foreach ($beanListDup as $m => $p) {
        if (empty($beanFiles[$p])) {
            unset($beanListDup[$m]);
        } else {
            $obj = BeanFactory::newBean($m);
            if (!isset($obj->field_defs['assigned_user_id']) || (
                    isset($obj->field_defs['assigned_user_id']) &&
                    isset($obj->field_defs['assigned_user_id']['source']) &&
                    $obj->field_defs['assigned_user_id']['source'] == "non-db"
                ) || (
                    isset($dictionary[$obj->object_name]['reassignable']) &&
                    !isTruthy($dictionary[$obj->object_name]['reassignable'])
                )
            ) {
                unset($beanListDup[$m]);
            }
        }
    }

    //Get the list of beans without the excluded modules
	$beanListDup = array_diff($beanListDup, $exclude_modules);

	//Leon bug 20739
	$beanListDupDisp=array();
	foreach($beanListDup as $m => $p){
        $beanListDupDisp[$m] = isset($app_list_strings['moduleList'][$m]) ? $app_list_strings['moduleList'][$m] : $p;
	}

        asort($beanListDupDisp, SORT_STRING);

	$_SESSION['reassignRecords']['assignedModuleListCache'] = $beanListDup;
	$_SESSION['reassignRecords']['assignedModuleListCacheDisp'] = $beanListDupDisp;
}

$selected = array();

if(!empty($_SESSION['reassignRecords']['modules'])) {
	foreach($_SESSION['reassignRecords']['modules'] as $key => $mod) {
		$selected[] = $key;
    }
}

    echo get_select_options_with_id_separate_key(
        $_SESSION['reassignRecords']['assignedModuleListCacheDisp'],
        $_SESSION['reassignRecords']['assignedModuleListCacheDisp'],
        $selected
    );
?>
</select>
<BR>
</td>
</tr>
<tr>
<td>
<?php
foreach($moduleFilters as $modFilter => $fieldArray){
	$display = (!empty($fieldArray['display_default']) && $fieldArray['display_default'] == true ? "block" : "none");
	//Leon bug 20739
	$t_mod_strings=return_module_language($GLOBALS['current_language'], $modFilter);
	echo "<div id=\"reassign_{$modFilter}\" style=\"display:$display\">\n";
	echo "<h5 style=\"padding-left:0px; margin-bottom:4px;\">{$app_list_strings['moduleList'][$modFilter]} ", " {$mod_strings_users['LBL_REASS_FILTERS']}</h5>\n";

    foreach($fieldArray['fields'] as $meta){
		$multi = "";
		$name = (!empty($meta['name']) ? $meta['name'] : "");
		$size = (!empty($meta['size']) ? "size=\"{$meta['size']}\"" : "");
		//Leon bug 20739
		echo $t_mod_strings[$meta['vname']] ."\n<BR>\n";
		switch($meta['type']){
			case "text":
				$tag = "input";
				break;
			case "multiselect":
				$multi = "multiple=\"true\"";
				$name .= "[]";
				// NO BREAK - Continue into select
			case "select":
				$tag = "select";
				$sel = '';
				if(!empty($_SESSION['reassignRecords']['filters'][$meta['name']])){
					$sel = $_SESSION['reassignRecords']['filters'][$meta['name']];
				}
				$extra = get_select_options_with_id($meta['dropdown'], $sel);
				$extra .= "\n</select>";
				break;
			default:
				continue;
		}
		echo "<$tag $size name=\"$name\" $multi>\n$extra";
		echo "<BR>\n";
	}
	echo "</div>\n";
}
?>
</td>
</tr>
</table>
<table cellspacing='1' cellpadding='1' border='0'>
<tr>
<td>
<input type=submit class="button" value="<?php echo $mod_strings_users['LBL_REASS_BUTTON_CONTINUE']; ?>" name=steponesubmit>
&nbsp;<input type=button class="button" value="<?php echo $mod_strings_users['LBL_REASS_BUTTON_CLEAR']; ?>" onclick='clearCurrentRecords();'>
<input type=button class="button" value="<?php echo $app_strings['LBL_CANCEL_BUTTON_LABEL']; ?>" onclick='document.location="<?php echo $cancel_location ?>"'>
</td>
</tr>
</table>
</form>

<?php
///////////////////// END STEP 1 - Select users/modules /////////////////////////
}
else if(!isset($_GET['execute'])){
///////////////////// BEGIN STEP 2 - Confirm Selections /////////////////////////
	$modules = InputValidation::getService()->getValidInputRequest('modules', array('Assert\All' => array('constraints' => 'Assert\Mvc\ModuleName')));
	if(empty($modules)){
		sugar_die($mod_strings_users['ERR_REASS_SELECT_MODULE']);
	}

    if($fromuser == $touser){
		sugar_die($mod_strings_users['ERR_REASS_DIFF_USERS']);
	}

	global $current_user;
	// Set the from and to user names so that we can display them in the results
	$query = "select user_name, id from users where id in (".$db->quoted($fromuser).", ".$db->quoted($touser).")";

	$res = $db->query($query);
	while($row = $db->fetchByAssoc($res)){
		if($row['id'] == $fromuser)
			$fromusername = $row['user_name'];
		if($row['id'] == $touser)
			$tousername = $row['user_name'];
	}
	//rrs bug: 31056 - instead of setting the team_id let's set the team_set_id and set the team_id as the primary
    $sugarFieldTeamSet = new SugarFieldTeamset('Teamset');
    $teams = $sugarFieldTeamSet->getTeamsFromRequest('team_name');
	$team_ids = array_keys($teams);
    $team_id = SugarFieldTeamset::getPrimaryTeamIdFromRequest('team_name', $_REQUEST);
	$teamSet = BeanFactory::newBean('TeamSets');
	$team_set_id = $teamSet->addTeams($team_ids);
    $teamSetSelectedId = null;

    $tbaConfigurator = new TeamBasedACLConfigurator();
    if ($tbaConfigurator->isEnabledGlobally()) {
        $selectedIds = $sugarFieldTeamSet->getSelectedTeamIdsFromRequest('team_name', $_REQUEST);
        if (!empty($selectedIds)) {
            $teamSetSelectedId = $teamSet->addTeams($selectedIds);
        }
    }

	$toteamname = TeamSetManager::getCommaDelimitedTeams($team_set_id,$team_id,true);
    echo "{$mod_strings_users['LBL_REASS_DESC_PART2']}\n";
	echo "<form action=\"index.php?module=Users&action=reassignUserRecords&execute=true\" method=post>\n";
	echo "<BR>{$mod_strings_users['LBL_REASS_NOTES_TITLE']}\n";
	echo "<ul>\n";
	echo "<li>* {$mod_strings_users['LBL_REASS_NOTES_ONE']}\n";
	echo "<li>* {$mod_strings_users['LBL_REASS_NOTES_TWO']}\n";
	echo "<li>* {$mod_strings_users['LBL_REASS_NOTES_THREE']}\n";
	echo "</ul>\n";
	require_once('include/SugarSmarty/plugins/function.sugar_help.php');
	$sugar_smarty = new Sugar_Smarty();
        $help_img = smarty_function_sugar_help(array("text"=>$mod_strings['LBL_REASS_VERBOSE_HELP']),$sugar_smarty);
	echo "<BR><input type=checkbox name=verbose> {$mod_strings_users['LBL_REASS_VERBOSE_OUTPUT']}".$help_img."<BR>\n";

	unset($_SESSION['reassignRecords']['modules']);
	$beanListFlip = $_SESSION['reassignRecords']['assignedModuleListCache'];

    foreach($modules as $module) {
		if(!array_key_exists($module, $beanListFlip)){
			continue;
		}

        $object = BeanFactory::newBean($module);

        if(empty($object->table_name)){
			continue;
		}

        $moduleLabel = isset($app_list_strings['moduleList'][$module]) ? $app_list_strings['moduleList'][$module] : $module;

		echo "<h5>{$mod_strings_users['LBL_REASS_ASSESSING']} {$moduleLabel}</h5>";
		echo "<table border='0' cellspacing='0' cellpadding='0'  class='detail view'>\n";
		echo "<tr>\n";
		echo "<td>\n";

		$q_select = "select id";
		$q_update = "update ";
		$q_set = " set assigned_user_id = ".$db->quoted($touser).", ".
			      "date_modified = '". TimeDate::getInstance()->nowDb() . "'";

        //Add modified_user_id clause if it exists in the table
        if(isset($obj->field_defs['modified_user_id'])) {
            $q_set .= ", modified_user_id = '{$current_user->id}' ";
        }

        //make sure team_id and team_set_id columns are available
		if(!empty($team_id) && isset($object->field_defs['team_id']))
        {
			$q_set .= ", team_id = '{$team_id}', team_set_id = '{$team_set_id}' ";
		}
        if ($teamSetSelectedId && $tbaConfigurator->isEnabledForModule($module)) {
            $q_set .= ", acl_team_set_id = '{$teamSetSelectedId}' ";
        }
		$q_tables   = " {$object->table_name} ";
		$q_where  = "where {$object->table_name}.deleted=0 and {$object->table_name}.assigned_user_id = ".$db->quoted($fromuser);

		// Process conditions based on metadata
		if(isset($moduleFilters[$module]['fields']) && is_array($moduleFilters[$module]['fields'])){
			$custom_added = false;
			foreach($moduleFilters[$module]['fields'] as $meta) {

                $metaName = $meta['name'];

				$filter = InputValidation::getService()->getValidInputRequest($metaName, 'Assert\InputParameters');
				if(!empty($filter)) {
					$_SESSION['reassignRecords']['filters'][$metaName] = $filter;
                }

				$is_custom = isset($meta['custom_table']) && $meta['custom_table'] == true;

				if($is_custom && !$custom_added) {
					$q_tables .= "inner join {$object->table_name}_cstm on {$object->table_name}.id = {$object->table_name}_cstm.id_c ";
					$custom_added = true;
				}

				$addcstm = ($is_custom ? "_cstm" : "");

                switch($meta['type']){
					case "text":
					case "select":
                        $q_where .= sprintf(
                            ' and %s.%s = %s ',
                            $object->table_name.$addcstm,
                            $meta['dbname'],
                            $db->quoted($_POST[$metaName])
                        );

						break;
					case "multiselect":

                        if(!isset($_POST[$metaName]) || empty($_POST[$metaName])){
                            continue;
                        }

                        // Also check condition where default selected was the
                        // only thing and set to none. However, we need to
                        // exclude '0', since '0' is considered by php
                        // to be empty, but in our logic, it is a valid value.
                        if (count($_POST[$metaName]) == 1 &&
                            empty($_POST[$metaName][0]) &&
                            $_POST[$metaName][0] !== '0') {
                            continue;
                        }

						$in_string = "";
						$empty_check = "";
						foreach($_POST[$metaName] as $onevalue) {
							if(empty($onevalue)) {
								$empty_check .= " OR {$object->table_name}{$addcstm}.{$meta['dbname']} is null ";
                            }
                            $in_string .= $db->quoted($onevalue).", ";
						}
						$in_string = substr($in_string, 0, count($in_string) - 3);
						$q_where .= " and ({$object->table_name}{$addcstm}.{$meta['dbname']} in ($in_string) $empty_check)";
						break;
					default:
						continue;
						break;
				}
			}
		}
		$query = "$q_select from $q_tables $q_where";
		$countquery = "select count(*) AS count from $q_tables $q_where";
		$updatequery = "$q_update $q_tables $q_set $q_where";

		$_SESSION['reassignRecords']['toteam'] = $team_id;
		$_SESSION['reassignRecords']['toteamsetid'] = $team_set_id;
		$_SESSION['reassignRecords']['toteamname'] = $toteamname;
		$_SESSION['reassignRecords']['fromuser'] = $fromuser;
		$_SESSION['reassignRecords']['touser'] = $touser;
		$_SESSION['reassignRecords']['fromusername'] = $fromusername;
		$_SESSION['reassignRecords']['tousername'] = $tousername;
		$_SESSION['reassignRecords']['modules'][$module]['query'] = $query;
		$_SESSION['reassignRecords']['modules'][$module]['update'] = $updatequery;

		$res = $db->query($countquery);
		$row = $db->fetchByAssoc($res);

		echo "{$row['count']} {$mod_strings_users['LBL_REASS_RECORDS_FROM']} {$moduleLabel} {$mod_strings_users['LBL_REASS_WILL_BE_UPDATED']}\n<BR>\n";
		echo "<input type=checkbox name={$module}_workflow> {$mod_strings_users['LBL_REASS_WORK_NOTIF_AUDIT']}<BR>\n";
		echo "</td></tr></table>\n";
	}

	echo "<BR><input type=button class=\"button\" value=\"{$mod_strings_users['LBL_REASS_BUTTON_GO_BACK']}\" onclick='document.location=\"index.php?module=Users&action=reassignUserRecords\"'>\n";
	echo "&nbsp;<input type=submit class=\"button\" value=\"{$mod_strings_users['LBL_REASS_BUTTON_CONTINUE']}\">\n";
	echo "&nbsp;<input type=button class=\"button\" value=\"{$mod_strings_users['LBL_REASS_BUTTON_RESTART']}\" onclick='document.location=\"index.php?module=Users&action=reassignUserRecords&clear=true\"'>\n";

	echo "</form>\n";

	// debug
///////////////////// END STEP 2 - Confirm Selections /////////////////////////
}
/////////////////// BEGIN STEP 3 - Execute reassignment ///////////////////////
else if(isset($_GET['execute']) && $_GET['execute'] == true) {
	$fromuser = $_SESSION['reassignRecords']['fromuser'];
	$touser = $_SESSION['reassignRecords']['touser'];
	$fromusername = $_SESSION['reassignRecords']['fromusername'];
	$tousername = $_SESSION['reassignRecords']['tousername'];
	$toteam = $_SESSION['reassignRecords']['toteam'];
	$toteamsetid = $_SESSION['reassignRecords']['toteamsetid'];
	$toteamname = $_SESSION['reassignRecords']['toteamname'];

	foreach($_SESSION['reassignRecords']['modules'] as $module => $queries){

        $moduleLabel = isset($app_list_strings['moduleList'][$module]) ? $app_list_strings['moduleList'][$module] : $module;

		$workflow = isset($_POST[$module."_workflow"]) && $_POST[$module."_workflow"] = "on";

		$query = $workflow ? $queries['query'] : $queries['update'];

		echo "<h5>{$mod_strings_users['LBL_PROCESSING']} {$moduleLabel}</h5>";

        // nutmeg sfa-219 : Fix reassignment of records when user set to Inactive
        if ($module == 'ForecastWorksheets') {
            $affected_rows = ForecastWorksheet::reassignForecast($fromuser, $touser);
            echo "{$mod_strings_users['LBL_UPDATE_FINISH']}: $affected_rows {$mod_strings_users['LBL_AFFECTED']}<BR>\n";
            continue;
        } else {
            $res = $db->query($query, true);
            $affected_rows = $db->getAffectedRowCount($res);
        }

		echo "<table border='0' cellspacing='0' cellpadding='0'  class='detail view'>\n";
		echo "<tr>\n";
		echo "<td>\n";
		if(!$workflow) {
			echo "{$mod_strings_users['LBL_UPDATE_FINISH']}: $affected_rows {$mod_strings_users['LBL_AFFECTED']}<BR>\n";
		} else {
			$successarr = array();
			$failarr = array();

			while($row = $db->fetchByAssoc($res)) {

                if(empty($row['id'])){
					continue;
				}
				$bean = BeanFactory::getBean($module, $row['id']);

				// So that we don't create new blank records.
				if(empty($bean->id)){
					continue;
				}

				$bean->assigned_user_id = $touser;

				if($toteam != '0'){
					$bean->team_id = $toteam;
				}
				if($toteamsetid != '0'){
					$bean->team_set_id = $toteamsetid;
				}
                $linkname = "record with id {$bean->id}";
                if(!empty($bean->name)){
                    $linkname = $bean->name;
                }
                else if(!empty($bean->last_name)){
                    $linkname = $locale->formatName($bean);
                }
                else if(!empty($bean->document_name)){
                    $linkname = $bean->document_name;
                }
				if($bean->save()){
					$successstr = "{$mod_strings_users['LBL_REASS_SUCCESS_ASSIGN']} {$bean->object_name} \"<i><a href=\"index.php?module={$bean->module_dir}&action=DetailView&record={$bean->id}\">$linkname</a></i>\" {$mod_strings_users['LBL_REASS_FROM']} $fromusername {$mod_strings_users['LBL_REASS_TO']} $tousername";
					$successstr .= ($toteam != '0' ? ", {$mod_strings_users['LBL_REASS_TEAM_SET_TO']} $toteamname." : ".");
					$successarr[] = $successstr;
				} else {
					$failarr[] = "{$mod_strings_users['LBL_REASS_FAILED_SAVE']} \"<i><a href=\"index.php?module={$bean->module_dir}&action=DetailView&record={$bean->id}\">$linkname</a></i>\".";
				}
			}

			if(isset($_POST['verbose']) && $_POST['verbose'] == "on"){
				echo "<h5>{$mod_strings_users['LBL_REASS_THE_FOLLOWING']} {$app_list_strings['moduleList'][$module]} {$mod_strings_users['LBL_REASS_HAVE_BEEN_UPDATED']}</h5>\n";
				foreach($successarr as $ord){
					echo "$ord\n<BR>\n";
				}
				if(empty($successarr)) {
					echo "{$mod_strings_users['LBL_REASS_NONE']}\n<BR>\n";
                }
				echo "<h5>{$mod_strings_users['LBL_REASS_THE_FOLLOWING']} {$app_list_strings['moduleList'][$module]} {$mod_strings_users['LBL_REASS_CANNOT_PROCESS']}</h5>\n";
				foreach($failarr as $failure){
					echo $failure."\n<BR>\n";
				}
				if(empty($failarr)) {
					echo "{$mod_strings_users['LBL_REASS_NONE']}\n<BR>\n";
                }
			} else {
				echo "{$mod_strings_users['LBL_REASS_UPDATE_COMPLETE']}\n<BR>\n";
				echo "&nbsp;&nbsp;".count($successarr)." {$mod_strings_users['LBL_REASS_SUCCESSFUL']}\n<BR>\n";
				echo "&nbsp;&nbsp;".count($failarr)." {$mod_strings_users['LBL_REASS_FAILED']}\n";
			}
			echo "<BR>\n";
		}
		echo "</td></tr></table>\n";
	}
    Activity::restoreToPreviousState();
	echo "<BR><input type=button class=\"button\" value=\"{$mod_strings_users['LBL_REASS_BUTTON_RETURN']}\" onclick='document.location=\"index.php?module=Users&action=reassignUserRecords\"'>\n";

/////////////////// END STEP 3 - Execute reassignment ///////////////////////
}
if(!empty($quicksearch_js)){
	//rrs - bug: 31056 - move to end to allow for form field to render
	echo $quicksearch_js;
}
?>
<script type="text/javascript">

function clearCurrentRecords()
{
    var callback = {
                success: function(){
                    document.getElementById('fromuser').selectedIndex = 0;
                    document.getElementById('touser').selectedIndex = 0;
                    document.getElementById('modulemultiselect').selectedIndex = -1;
                    updateDivDisplay(document.getElementById('modulemultiselect'));
                }
            };

    YAHOO.util.Connect.asyncRequest('POST', 'index.php?module=Users&action=clearreassignrecords&to_pdf=1', callback, null);
}

var allselected = [];
function updateDivDisplay(multiSelectObj){
    for(var i = 0; i < multiSelectObj.options.length; i++){
        if(multiSelectObj.options[i].selected != allselected[i]){
            allselected[i] = multiSelectObj.options[i].selected;

            if(allselected[i]){
                theElement = document.getElementById('reassign_'+multiSelectObj.options[i].value);
                if(theElement != null){
                    theElement.style.display = 'block';
                }
            } else {
                theElement = document.getElementById('reassign_'+multiSelectObj.options[i].value);
                if(theElement != null){
                    theElement.style.display = 'none';
                }
            }
        }
    }
}
<?php
if(empty($fromuser) && !isset($_GET['execute'])){
?>
updateDivDisplay(document.getElementById('modulemultiselect'));
<?php
}
?>
</script>
