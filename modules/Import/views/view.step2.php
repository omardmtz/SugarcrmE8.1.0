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

 * Description: view handler for step 2 of the import process
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 ********************************************************************************/


class ImportViewStep2 extends ImportView
{
 	protected $pageTitleKey = 'LBL_STEP_2_TITLE';


 	/**
     * @see SugarView::display()
     */
 	public function display()
    {
        global $mod_strings, $app_list_strings, $app_strings, $current_user, $import_bean_map, $import_mod_strings;

        $this->instruction = 'LBL_SELECT_UPLOAD_INSTRUCTION';
        $this->ss->assign('INSTRUCTION', $this->getInstruction());

        $this->ss->assign("MODULE_TITLE", $this->getModuleTitle(false));
        $this->ss->assign("IMP", $import_mod_strings);
        $this->ss->assign("CURRENT_STEP", $this->currentStep);

        $importType = $this->request->getValidInputRequest('type', null, '');

        $singleCharConstraints = array('Assert\Type' => array('type' => 'string'), 'Assert\Length' => array('min' => 1));
        $customEnclosure = $this->request->getValidInputRequest('custom_enclosure', $singleCharConstraints, '');
        $customEnclosureOther = $this->request->getValidInputRequest('custom_enclosure_other', null, '');

        $this->ss->assign("TYPE",( !empty($importType) ? $importType : "import" ));

        $delimiters = $this->getDelimitersFromRequest();
        $this->ss->assign("CUSTOM_DELIMITER", $delimiters['custom']);
        $this->ss->assign("CUSTOM_DELIMITER_OTHER", $delimiters['other']);
        $this->ss->assign("CUSTOM_ENCLOSURE",htmlentities(( !empty($customEnclosure) && $customEnclosure != 'other' ? $customEnclosure : $customEnclosureOther )));

        $importModule = $this->request->getValidInputRequest('import_module', 'Assert\Mvc\ModuleName', false);
        $this->ss->assign("IMPORT_MODULE", $importModule);
        $this->ss->assign("HEADER", $app_strings['LBL_IMPORT']." ". $mod_strings['LBL_MODULE_NAME']);
        $this->ss->assign("JAVASCRIPT", $this->_getJS());
        $this->ss->assign("SAMPLE_URL", "<a href=\"javascript: void(0);\" onclick=\"window.location.href='index.php?entryPoint=export&module=".urlencode($importModule)."&action=index&all=true&sample=true'\" >".$mod_strings['LBL_EXAMPLE_FILE']."</a>"); //FIXME

        $displayBackBttn = isset($_REQUEST['action']) && $_REQUEST['action'] == 'Step2' && isset($_REQUEST['current_step']) && $_REQUEST['current_step']!=='2'? TRUE : FALSE; //bug 51239
        $this->ss->assign("displayBackBttn", $displayBackBttn);

        // get user defined import maps
        $is_admin = is_admin($current_user);
        if($is_admin)
            $savedMappingHelpText = $mod_strings['LBL_MY_SAVED_ADMIN_HELP'];
        else
            $savedMappingHelpText = $mod_strings['LBL_MY_SAVED_HELP'];

        $this->ss->assign('savedMappingHelpText',$savedMappingHelpText);
        $this->ss->assign('is_admin',$is_admin);

        $import_map_seed = BeanFactory::newBean('Import_1');
        $custom_imports_arr = $import_map_seed->retrieve_all_by_string_fields( array('assigned_user_id' => $current_user->id, 'is_published' => 'no','module' => $importModule));

        if( count($custom_imports_arr) )
        {
            $custom = array();
            foreach ( $custom_imports_arr as $import)
            {
                $custom[] = array( "IMPORT_NAME" => $import->name,"IMPORT_ID"   => $import->id);
            }
            $this->ss->assign('custom_imports',$custom);
        }

        // get globally defined import maps
        $this->ss->assign('published_imports',self::getSavedImportSourceOptions(true));
        //End custom mapping

        // add instructions for anything other than custom_delimited
        $instructions = array();
        $lang_key = "CUSTOM";

        for ($i = 1; isset($mod_strings["LBL_{$lang_key}_NUM_$i"]);$i++)
        {
            $instructions[] = array(
                "STEP_NUM"         => $mod_strings["LBL_NUM_$i"],
                "INSTRUCTION_STEP" => $mod_strings["LBL_{$lang_key}_NUM_$i"],
            );
        }
        $this->ss->assign("INSTRUCTIONS_TITLE",$mod_strings["LBL_IMPORT_{$lang_key}_TITLE"]);
        $this->ss->assign("instructions",$instructions);

        $content = $this->ss->fetch('modules/Import/tpls/step2.tpl');
        $this->ss->assign("CONTENT",$content);
        $this->ss->display('modules/Import/tpls/wizardWrapper.tpl');
    }

    /**
     * Returns JS used in this view
     */
    private function _getJS()
    {
        global $mod_strings;

        return <<<EOJAVASCRIPT

if( document.getElementById('goback') )
{
    document.getElementById('goback').onclick = function()
    {
        document.getElementById('importstep2').action.value = 'Step1';
        return true;
    }
}

document.getElementById('gonext').onclick = function(){
    // warning message that tells user that updates can not be undone
    if(document.getElementById('import_update').checked)
    {
        ret = confirm(SUGAR.language.get("Import", 'LBL_CONFIRM_IMPORT'));
        if (!ret) {
            return false;
        }
    }
    clear_all_errors();
    var isError = false;
    // be sure we specify a file to upload
    if (document.getElementById('importstep2').userfile.value == "") {
        add_error_style(document.getElementById('importstep2').name,'userfile',"{$mod_strings['ERR_MISSING_REQUIRED_FIELDS']} {$mod_strings['ERR_SELECT_FILE']}");
        isError = true;
    }

    return !isError;

}

function publishMapping(elem, publish, mappingId)
{
    if( typeof(elem.publish) != 'undefined' )
        publish = elem.publish;

    var url = 'index.php?action=mapping&module=Import&publish=' + publish + '&import_map_id=' + mappingId;
    var callback = {
                        success: function(o)
                        {
                            var r = YAHOO.lang.JSON.parse(o.responseText);
                            if( r.message != '')
                                alert(r.message);
                        },
                        failure: function(o) {}
                   };
    YAHOO.util.Connect.asyncRequest('GET', url, callback);
    //Toggle the button title
    if(publish == 'yes')
    {
        var newTitle = SUGAR.language.get('Import','LBL_UNPUBLISH');
        var newPublish = 'no';
    }
    else
    {
        var newTitle = SUGAR.language.get('Import','LBL_PUBLISH');
        var newPublish = 'yes';
    }

    elem.value = newTitle;
    elem.publish = newPublish;

}
function deleteMapping(elemId, mappingId )
{
    var elem = document.getElementById(elemId);
    var table = elem.parentNode;
    table.deleteRow(elem.rowIndex);

    var url = 'index.php?action=mapping&module=Import&delete_map_id=' + mappingId;
    var callback = {
                        success: function(o)
                        {
                            var r = YAHOO.lang.JSON.parse(o.responseText);
                            if( r.message != '')
                                alert(r.message);
                        },
                        failure: function(o) {}
                   };
    YAHOO.util.Connect.asyncRequest('GET', url, callback);
}
var deselectEl = document.getElementById('deselect');
if(deselectEl)
{
    deselectEl.onclick = function() {
        var els = document.getElementsByName('source');
        for(i=0;i<els.length;i++)
        {
            els[i].checked = false;
        }
    }
}

EOJAVASCRIPT;
    }
}


