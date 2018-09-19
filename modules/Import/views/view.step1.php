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

 * Description: view handler for step 1 of the import process
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 ********************************************************************************/

class ImportViewStep1 extends ImportView
{

    protected $pageTitleKey = 'LBL_STEP_1_TITLE';

    public function __construct($bean = null, $view_object_map = array())
    {
        parent::__construct($bean, $view_object_map);

        $this->currentStep = isset($_REQUEST['current_step']) ? ($_REQUEST['current_step'] + 1) : 1;

        $this->importModule = $this->request->getValidInputRequest('import_module', 'Assert\Mvc\ModuleName', '');

        if( isset($_REQUEST['from_admin_wizard']) &&  $_REQUEST['from_admin_wizard'] )
        {
            $this->importModule = 'Administration';
        }
 	}

 	/**
	 * @see SugarView::_getModuleTitleParams()
	 */
	protected function _getModuleTitleParams($browserTitle = false)
	{
	    global $mod_strings, $app_list_strings;

        $importModule = $this->request->getValidInputRequest('import_module', 'Assert\Mvc\ModuleName', false);
	    $iconPath = $this->getModuleTitleIconPath($this->module);
	    $returnArray = array();
	    if (!empty($iconPath) && !$browserTitle) {
	        $returnArray[] = "<a href='index.php?module={$importModule}&action=index'><!--not_in_theme!--><img src='{$iconPath}' alt='{$app_list_strings['moduleList'][$importModule]}' title='{$app_list_strings['moduleList'][$importModule]}' align='absmiddle'></a>";
    	}
    	else {
    	    $returnArray[] = $app_list_strings['moduleList'][$importModule];
    	}
	    $returnArray[] = "<a href='index.php?module=Import&action=Step1&import_module={$importModule}'>".$mod_strings['LBL_MODULE_NAME']."</a>";
	    $returnArray[] = $mod_strings['LBL_STEP_1_TITLE'];

	    return $returnArray;
    }

 	/**
     * @see SugarView::display()
     */
 	public function display()
    {
        global $mod_strings, $app_strings, $current_user;
        global $sugar_config;

        $importModule = $this->request->getValidInputRequest('import_module', 'Assert\Mvc\ModuleName', false);

        $this->ss->assign("MODULE_TITLE", $this->getModuleTitle(false));
        $this->ss->assign("DELETE_INLINE_PNG",  SugarThemeRegistry::current()->getImage('delete_inline','align="absmiddle" border="0"',null,null,'.gif',$app_strings['LNK_DELETE']));
        $this->ss->assign("PUBLISH_INLINE_PNG",  SugarThemeRegistry::current()->getImage('publish_inline','align="absmiddle" border="0"', null,null,'.gif',$mod_strings['LBL_PUBLISH']));
        $this->ss->assign("UNPUBLISH_INLINE_PNG",  SugarThemeRegistry::current()->getImage('unpublish_inline','align="absmiddle" border="0"', null,null,'.gif',$mod_strings['LBL_UNPUBLISH']));
        $this->ss->assign("IMPORT_MODULE", $importModule);

        $showModuleSelection = ($this->importModule == 'Administration');
        $importableModulesOptions = array();
        $importablePersonModules = array();
        //If we are coming from the admin link, get the module list.
        if($showModuleSelection)
        {
            $tmpImportable = Importer::getImportableModules();
            $importableModulesOptions = get_select_options_with_id($tmpImportable, '');
            $importablePersonModules = $this->getImportablePersonModulesJS();
            $this->ss->assign("IMPORT_MODULE", key($tmpImportable));
        }
        else
        {
            $this->instruction = 'LBL_SELECT_DS_INSTRUCTION';
            $this->ss->assign('INSTRUCTION', $this->getInstruction());
        }
        $this->ss->assign("FROM_ADMIN", $showModuleSelection);
        $this->ss->assign("PERSON_MODULE_LIST", json_encode($importablePersonModules));
        $this->ss->assign("showModuleSelection", $showModuleSelection);
        $this->ss->assign("IMPORTABLE_MODULES_OPTIONS", $importableModulesOptions);

        $this->ss->assign("EXTERNAL_APIS", $this->getExternalApis());
        $this->ss->assign("EXTERNAL_SOURCES", $this->getAllImportableExternalEAPMs());
        $this->ss->assign("EXTERNAL_AUTHENTICATED_SOURCES", json_encode($this->getAuthenticatedImportableExternalEAPMs()) );

        $application = $this->request->getValidInputRequest('application', null, '');
        $selectExternal = !empty($application) ? $application : '';
        $this->ss->assign("selectExternalSource", $selectExternal);

        $content = $this->ss->fetch('modules/Import/tpls/step1.tpl');

        $submitContent = "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td align=\"right\">";
        $submitContent .= "<input title=\"".$mod_strings['LBL_IMPORT_COMPLETE']."\" onclick=\"SUGAR.importWizard.closeDialog();\" class=\"button\" type=\"submit\" name=\"finished\" value=\"  ".$mod_strings['LBL_IMPORT_COMPLETE']."  \" id=\"finished\">";
        $submitContent .= "<input title=\"".$mod_strings['LBL_NEXT']."\" class=\"button primary\" type=\"submit\" name=\"button\" value=\"  ".$mod_strings['LBL_NEXT']."  \"  id=\"gonext\"></td></tr></table>";

        $this->ss->assign("JAVASCRIPT",$this->_getJS() );
        $this->ss->assign("CONTENT",$content);
        $this->ss->display('modules/Import/tpls/wizardWrapper.tpl');

    }

    protected function getExternalApis()
    {
        $apis = ExternalAPIFactory::listAPI('Import', true);
        foreach ($apis as $name => $_) {
            if ($name == 'Google') {
                $api = new ExtAPIGoogle();
                $client = $api->getClient();
                $loginUrl = $client->createAuthUrl();
            } else {
                $loginUrl = 'index.php?' . http_build_query(array(
                    'module' => 'EAPM',
                    'action' => 'EditView',
                    'application' => $name,
                ));
            }
            $apis[$name]['loginUrl'] = $loginUrl;
        }

        return $apis;
    }

    private function getImportablePersonModulesJS()
    {
        global $beanList;
        $results = array();
        foreach ($beanList as $moduleName => $beanName)
        {
            $tmp = BeanFactory::newBean($moduleName);
            if( !empty($tmp->importable) && ($tmp instanceof Person)) {
                $results[$moduleName] = $moduleName;
            }
        }

        return $results;
    }

    private function getAllImportableExternalEAPMs()
    {
        ExternalAPIFactory::clearCache();
        return ExternalAPIFactory::getModuleDropDown('Import', TRUE, FALSE);
    }

    private function getAuthenticatedImportableExternalEAPMs()
    {
        return ExternalAPIFactory::getModuleDropDown('Import', FALSE, FALSE);
    }
    /**
     * Returns JS used in this view
     */
    private function _getJS($sourceType = false)
    {
        global $mod_strings;
        $EXTERNAL_AUTHENTICATED_SOURCES = json_encode($this->getAuthenticatedImportableExternalEAPMs());
        $selectExternalSource = !empty($_REQUEST['application']) ? $_REQUEST['application'] : '';

        $showModuleSelection = ($this->importModule == 'Administration');
        $importableModulesOptions = array();
        $importablePersonModules = array();
        //If we are coming from the admin link, get the module list.
        if($showModuleSelection)
        {
		    $importablePersonModules = $this->getImportablePersonModulesJS();
        }


        $PERSON_MODULE_LIST = json_encode($importablePersonModules);

        return <<<EOJAVASCRIPT


document.getElementById('gonext').onclick = function()
{
    clear_all_errors();
    var csvSourceEl = document.getElementById('csv_source');
    var isCsvSource = csvSourceEl ? csvSourceEl.checked : true;
    if( isCsvSource )
    {
        document.getElementById('importstep1').action.value = 'Step2';
        return true;
    }
    else
    {
        if(selectedExternalSource == '')
        {
            add_error_style('importstep1','external_source',"{$mod_strings['ERR_MISSING_REQUIRED_FIELDS']} {$mod_strings['LBL_EXTERNAL_SOURCE']}");
            return false;
        }

        document.getElementById('importstep1').action.value = 'ExtStep1';
        document.getElementById('importstep1').external_source.value = selectedExternalSource;

        return true;
    }
}

YAHOO.util.Event.onDOMReady(function(){

    var oButtonGroup = new YAHOO.widget.ButtonGroup("smtpButtonGroup");

    function toggleExternalSource(el)
    {
        var trEl = document.getElementById('external_sources_tr');
        var externalSourceBttns = oButtonGroup.getButtons();

        if(this.value == 'csv')
        {
            trEl.style.display = 'none';
            document.getElementById('gonext').disabled = false;
            document.getElementById('ext_source_sign_in_bttn').style.display = 'none';

            //Turn off ext source selection
            oButtonGroup.set("checkedButton", null, true);
            for(i=0;i<externalSourceBttns.length;i++)
            {
                externalSourceBttns[i].set("checked", true, true);
            }
            selectedExternalSource = '';
        }
        else
        {
            trEl.style.display = '';
            document.getElementById('gonext').disabled = true;

            //Highlight the first selection by default
            if(externalSourceBttns.length >= 1)
            {
                if(selectedExternalSource == '')
                    oButtonGroup.check(0);
            }
        }
    }

    YAHOO.util.Event.addListener(['ext_source','csv_source'], "click", toggleExternalSource);

    function isExtSourceAuthenticated(source)
    {
        if( typeof(auth_sources[source]) != 'undefined')
            return true;
        else
            return false;
    }

    function isExtSourceValid(v)
    {
        if(v == '')
        {
            document.getElementById('ext_source_sign_in_bttn').style.display = 'none';
            return '';
        }
        if( !isExtSourceAuthenticated(v) )
        {
            document.getElementById('ext_source_sign_in_bttn').style.display = '';
            document.getElementById('ext_source_sign_out_bttn').style.display = 'none';
            document.getElementById('gonext').disabled = true;
        }
        else
        {
            document.getElementById('ext_source_sign_in_bttn').style.display = 'none';
            if (v == "Google") {
                document.getElementById('ext_source_sign_out_bttn').style.display = '';
            }
            document.getElementById('gonext').disabled = false;
        }
    }

    function openExtAuthWindow()
    {
        var api = externalApis[selectedExternalSource];
        if (!api) {
            return;
        }

        if (api.authMethod === "oauth2") {
            openOauth2Window(api.loginUrl);
        } else {
            var import_module = document.getElementById('importstep1').import_module.value;
            var url = api.loginUrl + "&return_module=Import&return_action=" + import_module;
            document.location = url;
        }
    }

    function openOauth2Window(url)
    {
        window.open(url, '_blank', "width=600,height=400,centerscreen=1,resizable=1");
    }

    function setImportModule()
    {
        var selectedModuleEl = document.getElementById('admin_import_module');
        if(!selectedModuleEl)
        {
            return;
        }

        //Check if the module selected by the admin is a person type module, if not hide
        //the external source.
        var selectedModule = selectedModuleEl.value;
        document.getElementById('importstep1').import_module.value = selectedModule;
        if( personModules[selectedModule] )
        {
            document.getElementById('ext_source_tr').style.display = '';
            document.getElementById('ext_source_help').style.display = '';
            document.getElementById('ext_source_csv').style.display = '';
        }
        else
        {
            document.getElementById('ext_source_tr').style.display = 'none';
            document.getElementById('external_sources_tr').style.display = 'none';
            document.getElementById('ext_source_help').style.display = 'none';
            document.getElementById('ext_source_csv').style.display = 'none';
            document.getElementById('csv_source').checked = true;
        }
    }

    YAHOO.util.Event.addListener('ext_source_sign_in_bttn', "click", openExtAuthWindow);
    YAHOO.util.Event.addListener("ext_source_sign_out_bttn", "click", function() {
        $.post(
            "index.php?module=Import&action=RevokeAccess&application=" + encodeURIComponent(selectedExternalSource),
            function(response) {
                if (response.result) {
                    auth_sources = response.sources;
                    isExtSourceValid(selectedExternalSource);
                } else {
                    alert("Unable to sign out");
                }
            }
        );
    });
    YAHOO.util.Event.addListener('admin_import_module', "change", setImportModule);

    oButtonGroup.subscribe('checkedButtonChange', function(e)
    {
        selectedExternalSource = e.newValue.get('value');
        isExtSourceValid(selectedExternalSource);
    });

    $(window).on("message", function(e) {
        var data = $.parseJSON(e.originalEvent.data);
        if (data.result) {
            if (!data.hasRefreshToken) {
                alert("The application is unable to work in offline mode. Please sign out and sign in again.");
            }
            $.get("index.php?module=Import&action=AuthenticatedSources", function(sources) {
                auth_sources = sources;
                isExtSourceValid(selectedExternalSource);
            });
        } else {
            alert("Unable to connect to the data source");
        }
    });

    function initExtSourceSelection()
    {
        var el1 = YAHOO.util.Dom.get('ext_source');
        if(selectedExternalSource == '')
            return;

        el1.checked = true;
        toggleExternalSource();
        isExtSourceValid(selectedExternalSource);
    }
    initExtSourceSelection();

    setImportModule();
});


var auth_sources = {$EXTERNAL_AUTHENTICATED_SOURCES}
var selectedExternalSource = '{$selectExternalSource}';
var personModules = {$PERSON_MODULE_LIST};

EOJAVASCRIPT;
    }
}

?>
