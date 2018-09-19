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
require_once('modules/ACLActions/actiondefs.php');
class ACLController
{

    /**
     * Check access to given action
     * @api
     * TODO: convert to SugarACL, temporary function to allow less code changes
     * @param string $category Module name
     * @param string $action
     * @param bool $is_owner Should we assume current user is owner of the record?
     * @param string $type ACL type, usually module but can be different for DCE and Trackers
     * @return bool
     */
    public static function checkAccess($category, $action, $is_owner = false, $type = 'module')
    {
        return SugarACL::checkAccess($category, $action, $is_owner?array("owner_override" => true):array());
    }

    /**
     * Check ACLs for given module
     * @internal
     * @param string $category Module name
     * @param string $action
     * @param bool $is_owner Should we assume current user is owner of the record?
     * @param string $type ACL type, usually module but can be different for DCE and Trackers
     * @return bool
     */
    public static function checkAccessInternal($category, $action, $is_owner = false, $type = 'module')
    {
		global $current_user;
		if(is_admin($current_user))return true;
		//calendar is a special case since it has 3 modules in it (calls, meetings, tasks)

		if($category == 'Calendar'){
			return ACLAction::userHasAccess($current_user->id, 'Calls', $action,$type, $is_owner) || ACLAction::userHasAccess($current_user->id, 'Meetings', $action,'module', $is_owner) || ACLAction::userHasAccess($current_user->id, 'Tasks', $action,'module', $is_owner);
		}
		if($category == 'Activities'){
			return ACLAction::userHasAccess($current_user->id, 'Calls', $action,$type, $is_owner) || ACLAction::userHasAccess($current_user->id, 'Meetings', $action,'module', $is_owner) || ACLAction::userHasAccess($current_user->id, 'Tasks', $action,'module', $is_owner)|| ACLAction::userHasAccess($current_user->id, 'Emails', $action,'module', $is_owner)|| ACLAction::userHasAccess($current_user->id, 'Notes', $action,'module', $is_owner);
		}
        if ($category == 'Employees') {
            return ACLAction::userHasAccess($current_user->id, 'Users', $action, $type, $is_owner);
        }
		return ACLAction::userHasAccess($current_user->id, $category, $action,$type, $is_owner);
	}

	/**
	 * Does ACL require ownership?
	 * @internal
	 * @param string $category
	 * @param string $value
	 * @param string $type
	 */
    public static function requireOwner($category, $value, $type = 'module')
	{
			global $current_user;
			if(is_admin($current_user))return false;
			return ACLAction::userNeedsOwnership($current_user->id, $category, $value,$type);
	}

	/**
	 * Filter list of modules
	 * @internal
	 * @param string $moduleList
	 * @param bool $by_value
	 */
	function filterModuleList(&$moduleList, $by_value=true){

		global $aclModuleList, $current_user;
		if(is_admin($current_user)) return;
		$actions = ACLAction::getUserActions($current_user->id, false);

		$compList = array();
		if($by_value){
			foreach($moduleList as $key=>$value){
				$compList[$value]= $key;
			}
		}else{
			$compList =& $moduleList;
		}
		foreach($actions as $action_name=>$action){

			if(!empty($action['module'])){
				$aclModuleList[$action_name] = $action_name;
				if(isset($compList[$action_name])){
					if($action['module']['access']['aclaccess'] < ACL_ALLOW_ENABLED){
						if($by_value){
							unset($moduleList[$compList[$action_name]]);
						}else{
							unset($moduleList[$action_name]);
						}
					}
				}
			}
		}
		if(isset($compList['Calendar']) &&
			!( ACLController::checkModuleAllowed('Calls', $actions) || ACLController::checkModuleAllowed('Meetings', $actions) || ACLController::checkModuleAllowed('Tasks', $actions)))
	    {
			if($by_value){
				unset($moduleList[$compList['Calendar']]);
			}else{
				unset($moduleList['Calendar']);
			}
			if(isset($compList['Activities']) && !ACLController::checkModuleAllowed('Notes', $actions)){
				if($by_value){
					unset($moduleList[$compList['Activities']]);
				}else{
					unset($moduleList['Activities']);
				}
			}
		}

	}

	/**
	 * Check to see if the module is available for this user.
	 * @internal
	 * @param String $module_name
	 * @return true if they are allowed.  false otherwise.
	 */
	protected function checkModuleAllowed($module_name, $actions)
	{
	    if(!empty($actions[$module_name]['module']['access']['aclaccess']) &&
			ACL_ALLOW_ENABLED == $actions[$module_name]['module']['access']['aclaccess'])
		{
			return true;
		}

		return false;
	}

	/**
	 * Get list of disabled modules
	 * @internal
	 */
	static function disabledModuleList($moduleList, $by_value=true,$view='list'){
		global $aclModuleList, $current_user;
		if(is_admin($GLOBALS['current_user'])) return array();
		$actions = ACLAction::getUserActions($current_user->id, false);
		$disabled = array();
		$compList = array();

		if($by_value){
			foreach($moduleList as $key=>$value){
				$compList[$value]= $key;
			}
		}else{
			$compList =& $moduleList;
		}
		if(isset($moduleList['ProductTemplates'])){
			$moduleList['Products'] ='Products';
		}

		foreach($actions as $action_name=>$action){

			if(!empty($action['module'])){
				$aclModuleList[$action_name] = $action_name;
				if(isset($compList[$action_name])){
					if($action['module']['access']['aclaccess'] < ACL_ALLOW_ENABLED || $action['module'][$view]['aclaccess'] < 0){
						if($by_value){
							$disabled[$compList[$action_name]] =$compList[$action_name] ;
						}else{
							$disabled[$action_name] = $action_name;
						}
					}
				}
			}
		}
		if(isset($compList['Calendar'])  && !( ACL_ALLOW_ENABLED == $actions['Calls']['module']['access']['aclaccess'] || ACL_ALLOW_ENABLED == $actions['Meetings']['module']['access']['aclaccess'] || ACL_ALLOW_ENABLED == $actions['Tasks']['module']['access']['aclaccess'])){
			if($by_value){
							$disabled[$compList['Calendar']]  = $compList['Calendar'];
			}else{
							$disabled['Calendar']  = 'Calendar';
			}
			if(isset($compList['Activities'])  &&!( ACL_ALLOW_ENABLED == $actions['Notes']['module']['access']['aclaccess'] || ACL_ALLOW_ENABLED == $actions['Notes']['module']['access']['aclaccess'] )){
				if($by_value){
							$disabled[$compList['Activities']]  = $compList['Activities'];
				}else{
							$disabled['Activities']  = 'Activities';
				}
			}
		}
		if(isset($disabled['Products'])){
			$disabled['ProductTemplates'] = 'ProductTemplates';
		}


		return $disabled;

	}



	/**
	 * @internal
	 * Add ACL javascript
	 */
	function addJavascript($category,$form_name='', $is_owner=false)
	{
		$jscontroller = new ACLJSController($category, $form_name, $is_owner);
		echo $jscontroller->getJavascript();
	}

	/**
	 * Check if module supports ACLs
	 * @api
	 * @param string $module
	 * @return bool
	 */
	public static function moduleSupportsACL($module)
	{
	    // FIXME: add support for non-bean ACLs
	    if(!isset($GLOBALS['beanList'][$module])) return false;
	    // Always use ACLs via SugarACL
	    return SugarACL::moduleSupportsACL($module);
	}

    /**
     * Display "access denied" message
     * @api
     */
    public static function displayNoAccess($redirect_home = false)
    {
        echo '<script>function set_focus(){}</script><p class="error">' . translate('LBL_NO_ACCESS', 'ACL') . '</p>';
        if ($redirect_home) {
            $script = navigateToSidecar(buildSidecarRoute('Home'));
            // FIXME this old ugly code should go away from here...
            echo translate('LBL_REDIRECT_TO_HOME', 'ACL') .
                ' <span id="seconds_left">3</span> ' .
                translate('LBL_SECONDS', 'ACL') .
                "<script>
                function redirect_countdown(left){
                    document.getElementById('seconds_left').innerHTML = left;
                    if (left == 0) {
                        $script
                    } else {
                      left--;
                      setTimeout('redirect_countdown(' + left + ')', 1000);
                    }
                };
                setTimeout('redirect_countdown(3)', 1000);
                </script>";
        }
    }
}
