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
require_once ('modules/ModuleBuilder/MB/ModuleBuilder.php') ;

class ExpressionEngineController extends SugarController
{
	var $action_remap = array ( ) ;
    var $non_admin_actions = array("functionDetail", "getRelatedValues");
	
	function process(){
    	$GLOBALS [ 'log' ]->info ( get_class($this).":" ) ;
        global $current_user;
        $access = get_admin_modules_for_user($current_user);
		//Non admins can still execute functions
        if((!empty($_REQUEST['action']) && in_array($_REQUEST['action'], $this->non_admin_actions))
           || $this->isModuleAdmin($access))
        {
            $this->hasAccess = true;
        }
        else
        {
            $this->hasAccess = false;
        }
        parent::process();
    }

    function isModuleAdmin($access)
    {
        global $current_user;
        //Global admins have full access
        if (is_admin($current_user))
            return true;

        $module = "";
        if (!empty($_REQUEST['targetModule']))
            $module = $_REQUEST['targetModule'];
        if (!empty($_REQUEST['tmodule']))
            $module = $_REQUEST['tmodule'];

        //If the user is an admin of some module, and no module was set, assume they have access.
        if (is_admin_for_any_module($current_user) && empty($module) && (isset($_REQUEST['action']) && $_REQUEST['action'] != 'package')){
            return true;
        }

        //If the module was set, check that the user has access
        if (!empty($module) && in_array($module, $access)) {
            return true;
        }
    }

	function action_editFormula ()
    {
     	$this->view = 'editFormula';  
    }

    function action_editDepDropdown ()
    {
     	$this->view = 'editDepDropdown';
    }
    
	function action_index ()
    {
     	$this->view = 'index';  
    }
    
	function action_cfTest ()
    {
     	$this->view = 'cfTest';  
    }
    
	function action_list ()
    {
     	$this->view = 'index';  
    }

    function action_relFields ()
    {
     	$this->view = 'relFields';  
    }

    function action_execFunction ()
    {
     	$this->view = 'execFunction';  
    }

    function action_functionDetail() {
    	$this->view = 'functionDetail'; 
    }

    function action_validateRelatedField(){
        $this->view = 'validateRelatedField';
    }

    function action_selectRelatedField() {
        $this->view ='selectRelatedField';
    }

    function action_rollupWizard() {
        $this->view ='rollupWizard';
    }

    /**
     * Used by the dependency manager to pre-load all the related fields required
     * to load an entire view.
     */
    public function action_getRelatedValues()
    {
        /** @var LoggerManager */
        global $log;

        $ret = array();

        if (empty($_REQUEST['tmodule']) || empty($_REQUEST['fields']))
            return;

        $fields = json_decode(html_entity_decode($_REQUEST['fields']), true);
        if (!is_array($fields)) {
            $log->fatal('"fields" is not a valid JSON string');
            $this->display($ret);
            return;
        }

        $module = $_REQUEST['tmodule'];
        $id = empty($_REQUEST['record_id']) ? null : $_REQUEST['record_id'];
        $focus = BeanFactory::retrieveBean($module, $id);

        if (!$focus) {
            $log->fatal('Unable to load bean');
            $this->display($ret);
            return;
        }

        foreach($fields as $rfDef)
        {
            if (!isset($rfDef['link'], $rfDef['type'])) {
                $log->fatal('At least one of "link" and "type" attributes is not specified');
                continue;
            }

            $link = $rfDef['link'];
            $type = $rfDef['type'];
            if (!isset($ret[$link]))
                $ret[$link] = array();
            if (empty($ret[$link][$type]))
                $ret[$link][$type] = array();

            switch($type){
                //The Related function is used for pulling a sing field from a related record
                case "related":
                    if (!isset($rfDef['relate'])) {
                        $log->fatal('"relate" attribute of related expression is not specified');
                        break;
                    }

                    //Default it to a blank value
                    $ret[$link]['related'][$rfDef['relate']] = "";

                    //If we have neither a focus id nor a related record id, we can't retrieve anything
                    if (!empty($id) || !empty($rfDef['relId']))
                    {
                        $relBean = null;
                        if (empty($rfDef['relId']) || empty($rfDef['relModule']))
                        {
                            //If the relationship is invalid, just move onto another field
                            if (!$focus->load_relationship($link))
                                break;
                            $beans = $focus->$link->getBeans(array("enforce_teams" => true));
                            //No related beans means no value
                            if (empty($beans))
                                break;
                            //Grab the first bean on the list
                            reset($beans);
                            $relBean = current($beans);
                        } else
                        {
                            $relBean = BeanFactory::getBean($rfDef['relModule'], $rfDef['relId']);
                        }
                        //If we found a bean and the current user has access to the related field, grab a value from it
                        if (!empty($relBean) && ACLField::hasAccess($rfDef['relate'], $relBean->module_dir, $GLOBALS['current_user']->id, true))
                        {
                            $validFields = FormulaHelper::cleanFields($relBean->field_defs, false, true, true);
                            if (isset($validFields[$rfDef['relate']]))
                            {
                                $ret[$link]['relId'] = $relBean->id;
                                $ret[$link]['related'][$rfDef['relate']] =
                                    FormulaHelper::getFieldValue($relBean, $rfDef['relate']);
                            }
                        }
                    }
                    break;
                case "count":
                    if(!empty($id) && $focus->load_relationship($link))
                    {
                        $ret[$link][$type] = count($focus->$link->get());
                    } else
                    {
                        $ret[$link][$type] = 0;
                    }
                    break;
                case "rollupSum":
                case "rollupAve":
                case "rollupMin":
                case "rollupMax":
                //If we are going to calculate one rollup, calculate all the rollups since there is so little cost
                if (!isset($rfDef['relate'])) {
                    $log->fatal('"relate" attribute of rollup expression is not specified');
                    break;
                }

                $rField = $rfDef['relate'];
                if(!empty($id) && $focus->load_relationship($link))
                {
                    $relBeans = $focus->$link->getBeans(array("enforce_teams" => true));
                    $sum = 0;
                    $count = 0;
                    $min = false;
                    $max = false;
                    if (!empty($relBeans))
                    {
                        //Check if the related record vardef has banned this field from formulas
                        $relBean = reset($relBeans);
                        $validFields = FormulaHelper::cleanFields($relBean->field_defs, false, true, true);
                        if (!isset($validFields[$rField])) {
                            break;
                        }

                    }
                    foreach($relBeans as $bean)
                    {
                        if (isset($bean->$rField) && is_numeric($bean->$rField) &&
                            //ensure the user can access the fields we are using.
                            ACLField::hasAccess($rField, $bean->module_dir, $GLOBALS['current_user']->id, true)
                        ) {
                            $count++;
                            $sum += floatval($bean->$rField);
                            if ($min === false || $bean->$rField < $min)
                                $min = floatval($bean->$rField);
                            if ($max === false || $bean->$rField > $max)
                                $max = floatval($bean->$rField);
                        }
                    }
                    if ($type == "rollupSum")
                        $ret[$link][$type][$rField] = $sum;
                    if ($type == "rollupAve")
                        $ret[$link][$type][$rField] = $count == 0 ? 0 : $sum / $count;
                    if ($type == "rollupMin")
                        $ret[$link][$type][$rField] = $min;
                    if ($type == "rollupMax")
                        $ret[$link][$type][$rField] = $max;
                } else
                {
                    $ret[$link][$type][$rField] = 0;
                }
                break;
            }
        }

        $this->display($ret);
    }

    /**
     * Displays result and disables further rendering
     *
     * @param mixed $result
     */
    protected function display($result)
    {
        $this->view = '';
        echo json_encode($result);
    }
}
