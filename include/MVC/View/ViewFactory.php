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
  * ViewFactory
  *
  * View factory class. This file is used by the controller along with a view paramter to build the
  * requested view.
  */


/**
 * Sugar view factory
 * @api
 */
class ViewFactory{
	/**
	 * load the correct view
	 * @param string $type View Type
	 * @return valid view
	 */
    public static function loadView(
        $type,
        $module,
        $bean = null,
        $view_object_map = array(),
        $target_module = ''
    ) {
		$type = strtolower($type);

		//first let's check if the module handles this view

		$view = null;

        //Check to see if we should load a custom parent view instance
        loadParentView($type);

		if(!empty($target_module)) {
		    $view_file = SugarAutoLoader::existingCustomOne('modules/'.$target_module.'/views/view.'.$type.'.php');
		    $view_module = $target_module;
		} else {
		    $view_module = $module;
		}

		if(empty($view_file)) {
		    $view_file = SugarAutoLoader::existingCustomOne('modules/'.$module.'/views/view.'.$type.'.php');
		}
		if(empty($view_file)) {
			$view_file = SugarAutoLoader::existingCustomOne('include/MVC/View/views/view.'.$type.'.php');
		}
		if(!empty($view_file)) {
            $view = ViewFactory::buildFromFile($view_file, $bean, $view_object_map, $type, $view_module);
		}

		if(empty($view)) {
    		// Default to SugarView if still nothing found/built
		    $view = new SugarView();
		}

		ViewFactory::_loadConfig($view, $type);
		return $view;
	}

	/**
	 * Load the view_<view>_config.php file which holds options used by the view.
	 */
    static function _loadConfig(&$view, $type)
    {
		$view_config_custom = array();
		$view_config_module = array();
		$view_config_root_cstm = array();
		$view_config_root = array();
		$view_config_app = array();
		$config_file_name = 'view.'.$type.'.config.php';
		$view_config = sugar_cache_retrieve("VIEW_CONFIG_FILE_".$view->module."_TYPE_".$type);
		if(!$view_config){
		    $view_config_all = array('actions' => array(), 'req_params' => array(),);
		    foreach(SugarAutoLoader::existingCustom(
		        'include/MVC/View/views/view.config.php',
		        'include/MVC/View/views/'.$config_file_name,
		        'modules/'.$view->module.'/views/'.$config_file_name
		    ) as $file) {
		        $view_config = array();
		        require $file;
		        if(!empty($view_config['actions'])) {
		            $view_config_all['actions'] = array_merge($view_config_all['actions'], $view_config['actions']);
		        }
		        if(!empty($view_config['req_params'])) {
		        	$view_config_all['req_params'] = array_merge($view_config_all['req_params'], $view_config['req_params']);
		        }
		    }
		    $view_config = $view_config_all;

			sugar_cache_put("VIEW_CONFIG_FILE_".$view->module."_TYPE_".$type, $view_config);
		}
		$action = strtolower($view->action);
		$config = null;
		if(!empty($view_config['req_params'])){
			//try the params first
			foreach($view_config['req_params'] as $key => $value){
			    if(!empty($_REQUEST[$key]) && $_REQUEST[$key] == "false") {
			        $_REQUEST[$key] = false;
			    }
				if(!empty($_REQUEST[$key])){

					if(!is_array($value['param_value'])){
						if($value['param_value'] ==  $_REQUEST[$key]){
							$config = $value['config'];
							break;
						}
					}else{

						foreach($value['param_value'] as $v){
							if($v ==  $_REQUEST[$key]){
								$config = $value['config'];
								break;
							}

						}

					}



				}
			}
		}
		if($config == null && !empty($view_config['actions']) && !empty($view_config['actions'][$action])){
				$config = $view_config['actions'][$action];
		}
		if($config != null)
			$view->options = $config;
	}

	/**
	 * This is a private function which just helps the getView function generate the
	 * proper view object
	 *
	 * @return a valid SugarView
	 */
    private static function buildFromFile($file, &$bean, $view_object_map, $type, $module)
    {
		require_once($file);
		//try ModuleViewType first then try ViewType if that fails then use SugarView
		$class = SugarAutoLoader::customClass(ucfirst($module).'View'.ucfirst($type));

		if(class_exists($class)){
            return ViewFactory::buildClass($class, $bean, $view_object_map);
		}
		//Now try the next set of possibilites if it was none of the above
		//It can be expensive to load classes this way so it's not recommended
		$class = SugarAutoLoader::customClass('View'.ucfirst($type));
		if(class_exists($class)){
            return ViewFactory::buildClass($class, $bean, $view_object_map);
		}
		//Now check if there is a custom SugarView for generic handling
		// autoloader will check filesystem
        $class = SugarAutoLoader::customClass('SugarView');
		//if all else fails return SugarView
		return new $class($bean, $view_object_map);

	}

	/**
	 * instantiate the correct view and call init to pass on any obejcts we need to
	 * from the controller.
	 *
	 * @param string class - the name of the class to instantiate
	 * @param object bean = the bean to pass to the view
	 * @param array view_object_map - the array which holds obejcts to pass between the
	 *                                controller and the view.
	 *
	 * @return SugarView
	 */
    private static function buildClass($class, $bean, $view_object_map)
    {
		$view = new $class();
		$view->init($bean, $view_object_map);
		if($view instanceof SugarView){
			return $view;
		}else
			return new SugarView($bean, $view_object_map);
	}
}

