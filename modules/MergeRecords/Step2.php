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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/




global $app_strings;
global $app_list_strings;
global $current_language;
global $urlPrefix;
global $currentModule;
global $theme;

$current_module_strings = return_module_language($current_language, 'MergeRecords');


$focus = BeanFactory::newBean('MergeRecords');
$focus->load_merge_bean($_REQUEST['merge_module'], true, $_REQUEST['record']);

$this->bean = $focus->merge_bean;

$params = array();
$params[] = "<a href='index.php?module={$focus->merge_bean->module_dir}&action=index'>{$GLOBALS['app_list_strings']['moduleList'][$focus->merge_bean->module_dir]}</a>";
$params[] = $mod_strings['LBL_STEP2_FORM_TITLE'];
$params[] = $focus->merge_bean->name;
echo getClassicModuleTitle($focus->merge_bean->module_dir, $params, true);

       $order_by_name = $focus->merge_module.'2_'.strtoupper($focus->merge_bean->object_name).'_ORDER_BY' ;
       $lvso = isset($_REQUEST['lvso'])?$_REQUEST['lvso']:"";
       $request_order_by_name = isset($_REQUEST[$order_by_name])?$_REQUEST[$order_by_name]:"";

echo '<form onsubmit="return check_form(\'MassUpdate\');" id="MassUpdate" name="MassUpdate" method="post" action="index.php">'
    .'<input type="hidden" value="Step2" name="action"/>'
    .'<input type="hidden" value="true" name="massupdate"/>'
    .'<input type="hidden" value="false" name="delete"/>'
    .'<input type="hidden" value="false" name="merge"/>'
    .'<input type="hidden" value="MergeRecords" name="module"/>'
    ."<input type='hidden' name='lvso' value='{$lvso}' />"
    ."<input type='hidden' name='{$order_by_name}' value='{$request_order_by_name}' />";

$focus->populate_search_params($_REQUEST);
echo $focus->get_inputs_for_search_params($_REQUEST);

$where_clauses = Array();
$where_clauses = $focus->create_where_statement();
$where = $focus->generate_where_statement($where_clauses);

$ListView = new ListViewSmarty();
$ListView->should_process = true;
$ListView->mergeduplicates = false;
$ListView->export = false;
$ListView->delete = false;
$module = $_REQUEST['merge_module'];
$metadataFile = SugarAutoLoader::loadWithMetafiles($module, 'listviewdefs');
if($metadataFile) {
    require $metadataFile;
}

$displayColumns = array();
if(!empty($_REQUEST['displayColumns'])) {
    foreach(explode('|', $_REQUEST['displayColumns']) as $num => $col) {
        if(!empty($listViewDefs[$module][$col]))
            $displayColumns[$col] = $listViewDefs[$module][$col];
    }
}
else {
    foreach($listViewDefs[$module] as $col => $params) {
        if(!empty($params['default']) && $params['default'])
            $displayColumns[$col] = $params;
    }
}
$params = array('massupdate' => true, 'export' => false, 'handleMassupdate' => false );
$ListView->displayColumns = $displayColumns;
$ListView->lvd->listviewName = $focus->merge_module; //27633, this will make the $module to be merge_module instead of 'MergeRecords'. Then the key of  offset and orderby will be correct.
$where = $focus->generate_where_statement($focus->create_where_statement());
$ListView->showMassupdateFields=false;
$ListView->email=false;
$ListView->setup($this->bean, 'include/ListView/ListViewGeneric.tpl', $where, $params);
$ListView->force_mass_update=true;
$ListView->show_mass_update_form=false;
$ListView->show_export_button=false;
$ListView->keep_mass_update_form_open=true;

$return_id = $_REQUEST['record'];
$merge_module = $focus->merge_module;

$button_title = $current_module_strings['LBL_PERFORM_MERGE_BUTTON_TITLE'];
$button_key = $current_module_strings['LBL_PERFORM_MERGE_BUTTON_KEY'];
$button_label = $current_module_strings['LBL_PERFORM_MERGE_BUTTON_LABEL'];

$cancel_title=$app_strings['LBL_CANCEL_BUTTON_TITLE'];
$cancel_key=$app_strings['LBL_CANCEL_BUTTON_KEY'];
$cancel_label=$app_strings['LBL_CANCEL_BUTTON_LABEL'];

echo ($ListView->display());

$error_select=$current_module_strings['LBL_SELECT_ERROR'];
$form_top = <<<EOQ

            <input type="hidden" id="selectCount" name="selectCount[]" value=0>
			<input type="hidden" name="merge_module" value="$merge_module">
			<input type="hidden" name="record" value="$return_id">
			<input type="hidden" name="return_module" value="$focus->merge_module">
			<input type="hidden" name="return_id" value="$return_id">
			<input type="hidden" name="return_action" value="DetailView">
			<input title="$button_title" class="button" onclick="return verify_selection(this);" type="submit" name="button" value="  $button_label  " id="perform_merge_button">
            <input title="$cancel_title" accessKey="$cancel_key" class="button" onclick="this.form.action.value='DetailView';this.form.module.value='$focus->merge_module';this.form.module.record='$return_id'" type="submit" name="button" value=" $cancel_label   " id="cancel_merge_button" >
		</form>
        <script>
           function verify_selection(theElement) {
                theElement.form.action.value='Step3';
                var selcount=document.getElementById('selectCountTop');
                if (parseInt(selcount.value) >0 ) {
                    return true;
                } else {
                    alert("$error_select");
                    return false;
                }
           }
           sugarListView.prototype.order_checks = function(order,orderBy,moduleString){
                checks = sugarListView.get_checks();
                eval('document.MassUpdate.' + moduleString + '.value = orderBy');
                document.MassUpdate.lvso.value = order;
                if(typeof document.MassUpdate.massupdate != 'undefined') {
                   document.MassUpdate.massupdate.value = 'false';
                }

                document.MassUpdate.return_module.value='';
                document.MassUpdate.return_action.value='';
                document.MassUpdate.submit();

                return !checks;
            }

            sugarListView.prototype.save_checks = function(offset, moduleString) {
                checks = sugarListView.get_checks();
                eval('document.MassUpdate.' + moduleString + '.value = offset');

                if(typeof document.MassUpdate.massupdate != 'undefined') {
                   document.MassUpdate.massupdate.value = 'false';
                }

                document.MassUpdate.return_module.value='';
                document.MassUpdate.return_action.value='';
                document.MassUpdate.submit();

                return !checks;
            }
        </script>
EOQ;
echo $form_top;
?>