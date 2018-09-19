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

use Sugarcrm\Sugarcrm\DependencyInjection\Container;
use Sugarcrm\Sugarcrm\Security\Context;
use Sugarcrm\Sugarcrm\Security\Subject\LogicHook as Subject;

/**
 * Predefined logic hooks
 * after_ui_frame
 * after_ui_footer
 * after_save
 * before_save
 * before_retrieve
 * after_retrieve
 * process_record
 * before_delete
 * after_delete
 * before_restore
 * after_restore
 * server_roundtrip
 * before_logout
 * after_logout
 * before_login
 * after_login
 * login_failed
 * after_session_start
 * after_entry_point
 * before_filter
 *
 * @api
 */
class LogicHook{

    /**
     * @var SugarBean
     */
	var $bean = null;

	/**
	 * Static Function which returns and instance of LogicHook
	 *
	 * @return unknown
	 */
	static function initialize(){
		if(empty($GLOBALS['logic_hook']))
			$GLOBALS['logic_hook'] = new LogicHook();
		return $GLOBALS['logic_hook'];
	}

	function setBean($bean){
		$this->bean = $bean;
		return $this;
	}

	protected $hook_map = array();
	protected $hookscan = array();

	public function getHooksMap()
	{
	    return $this->hook_map;
	}

	public function getHooksList()
	{
	    return $this->hookscan;
	}

    public function scanHooksDir($extpath)
    {
		if(is_dir($extpath)){
		    $dir = dir($extpath);
			while($entry = $dir->read()){
				if($entry != '.' && $entry != '..' && strtolower(substr($entry, -4)) == ".php" && is_file($extpath.'/'.$entry)) {
				    unset($hook_array);
                    include($extpath.'/'.$entry);
                    if(!empty($hook_array)) {
                        foreach($hook_array as $type => $hookg) {
                            foreach($hookg as $index => $hook) {
                                $this->hookscan[$type][] = $hook;
                                $idx = count($this->hookscan[$type])-1;
                                $this->hook_map[$type][$idx] = array("file" => $extpath.'/'.$entry, "index" => $index);
                            }
                        }
                    }
				}
			}
		}
    }

	protected static $hooks = array();

    static public function refreshHooks()
    {
        self::$hooks = array();
    }

	public function loadHooks($module_dir)
	{
        $hook_array = array();
	    if(!empty($module_dir)) {
	        $custom = "custom/modules/$module_dir";
	    } else {
	        $custom = "custom/modules";
	    }
	    foreach(SugarAutoLoader::existing(
		    "$custom/logic_hooks.php",
	        SugarAutoLoader::loadExtension("logichooks", empty($module_dir)?"application":$module_dir)
	    ) as $file) {
            if(isset($GLOBALS['log'])){
	    	    $GLOBALS['log']->debug('Including hook file: '.$file);
            }
		    include $file;
		}
		return $hook_array;
	}

	public function getHooks($module_dir, $refresh = false)
	{
	    if($refresh || !isset(self::$hooks[$module_dir])) {
	        self::$hooks[$module_dir] = $this->loadHooks($module_dir);
	    }
	    return self::$hooks[$module_dir];
	}

	/**
	 * Provide a means for developers to create upgrade safe business logic hooks.
	 * If the bean is null, then we assume this call was not made from a SugarBean Object and
	 * therefore we do not pass it to the method call.
	 *
	 * @param string $module_dir
	 * @param string $event
	 * @param array $arguments
     */
    public function call_custom_logic($module_dir, $event, $arguments = array())
    {
        $origBean = $this->bean;

        if ($origBean === null) {
            $bean = BeanFactory::newBean($module_dir);
            if ($bean instanceOf SugarBean) {
                $this->setBean($bean);
            }
        }

        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->debug("Hook called: $module_dir::$event");
        }

        $modules = array(null);

        if ($module_dir) {
            array_unshift($modules, $module_dir);
        }

        foreach ($modules as $module) {
            $hooks = $this->getHooks($module);
            $this->process_hooks($hooks, $event, $arguments);
        }

        if ($origBean === null) {
            $this->setBean($origBean);
        }
	}

    /**
     * Apply sorting to the hooks using the sort index. Hooks with matching
     * sort indexes will be processed in no particular order.
     *
     * @param array $hookArray
     * @return array Sorted indices
     */
    protected function getProcessOrder(array $hookArray)
    {
        $sortedIndices = array();
        foreach ($hookArray as $idx => $hookDetails) {
            $sortedIndices[$idx] = $hookDetails[0];
        }
        asort($sortedIndices);
        return array_keys($sortedIndices);
    }

    /**
     * Prepare availability of given class
     * @param string $class Fully qualified class name
     * @param string $file Optional filename, only used for non namespaced classes
     * @return boolean
     */
    protected function loadHookClass($class, $file = null)
    {
        // Ignore file parameter if class is namespaced
        if (false !== strpos($class, '\\')) {
            $file = null;
        }

        // If no file is given, only autoloader can help us out
        if (empty($file)) {
            return class_exists($class);
        }

        // Chech if file exists
        if (!SugarAutoLoader::load($file)) {
            return false;
        }

        // Finally check if our class is available
        return class_exists($class);
    }

    /**
     * Log wrapper
     * @param string $level Log level
     * @param string $msg Log message
     */
    protected function log($level, $msg)
    {
        if (!empty($GLOBALS['log'])) {
            $GLOBALS['log']->{$level}($msg);
        }
    }

	/**
	 * This is called from call_custom_logic and actually performs the action as defined in the
	 * logic hook. If the bean is null, then we assume this call was not made from a SugarBean Object and
	 * therefore we do not pass it to the method call.
	 *
	 * @param array $hookArray
	 * @param string $event
	 * @param array $arguments
	 */
    public function process_hooks($hookArray, $event, $arguments)
    {
        // Skip if event is unknown
        if (empty($hookArray[$event])) {
            return;
        }

        // Apply sorting
        $processOrder = $this->getProcessOrder($hookArray[$event]);

        foreach ($processOrder as $hookIndex) {
            $hookDetails = $hookArray[$event][$hookIndex];
            $hookFile = $hookDetails[2];
            $hookClass = $hookDetails[3];
            $hookFunc = $hookDetails[4];

            if (!$this->loadHookClass($hookClass, $hookFile)) {
                $this->log("error", "Unable to load custom logic class '$hookClass'");
                continue;
            }

            $context = Container::getInstance()->get(Context::class);
            $subject = new Subject($hookClass, $hookFunc);
            $context->activateSubject($subject);

            try {
                if (strcasecmp($hookClass, $hookFunc) === 0) {
                    $this->log("debug", "Creating new instance of hook class '$hookClass' with parameters");
                    if (!is_null($this->bean)) {
                        new $hookClass($this->bean, $event, $arguments);
                    } else {
                        new $hookClass($event, $arguments);
                    }
                } else {
                    $this->log("debug", "Creating new instance of hook class '$hookClass' without parameters");
                    $hookObject = new $hookClass();

                    if (!is_null($this->bean)) {
                        // & is here because of BR-1345 and old broken hooks that use &$bean in args
                        $params = array_merge([&$this->bean, $event, $arguments], array_slice($hookDetails, 5));
                        call_user_func_array([$hookObject, $hookFunc], $params);
                    } else {
                        $hookObject->$hookFunc($event, $arguments);
                    }
                }
            } finally {
                $context->deactivateSubject($subject);
            }

            if ($this->bean && $event === 'before_save') {
                $this->bean->commitAuditedStateChanges($subject);
            }
        }
    }
}
