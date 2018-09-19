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
 * Generic dependency
 * @api
 */
class Dependency
{
	protected $trigger;
	protected $actions = array();
	protected $falseActions = array();
	protected $id = "";
	protected $fireOnLoad = false;
    protected $hooks = array();
    protected $isRelated = false;
    protected $relatedFields = array();

    public function __construct($id)
    {
		$this->id = $id;
		$this->trigger = new Trigger('true');
	}

	function setFireOnLoad($onLoad) {
		$this->fireOnLoad = $onLoad;
	}

    /**
     * Set if this is a related Dependency
     *
     * @param boolean $isRelated Is this a related Dependency?
     */
    public function setIsRelated($isRelated)
    {
        $this->isRelated = $isRelated;
    }

    /**
     * Set related trigger fields, if there are field, when the watcher is setup on the front end, it will
     * trigger on these fields changing, otherwise it will trigger when the collection is changed
     *
     * @param array $fields
     */
    public function setRelatedFields(array $fields)
    {
        $this->relatedFields = $fields;
    }

	/**
	 * Sets the trigger expressions of this dependency or creates a new Trigger from the array if
	 * Trigger metadata is passed.
	 *
	 * @param Array/Action $action
	 */
	function setTrigger($trigger) {
		if (is_array($trigger)) {
			$this->trigger = new Trigger($trigger);
		} else {
			$this->trigger = $trigger;
		}
	}

	/**
	 * Adds a new action to this dependency or creates a new Action from the meta if
	 * Action metadata is passed.
	 *
	 * @param Array/Action $action
	 */
	function addAction($action) {
	if (empty($action)) {
	   return;
	}
	if (is_array($action)) {
			$this->actions[] = new Action($action);
		} else {
			$this->actions[] = $action;
		}
	}

	/**
	 * Adds a new action which will be fired when this dependency's trigger is false.
	 *
	 * @param Array/Action $action
	 */
	function addFalseAction($action) {
		if (is_array($action)) {
			$this->falseActions[] = new Action($action);
		} else {
			$this->falseActions[] = $action;
		}
	}

	/**
	 * Returns the javascript equivalent of this dependency.
	 */
	function getJavascript($form = "EditView") {
		if (empty($this->actions)) return "";

		$js = "var {$this->id}dep = new SUGAR.forms.Dependency(" .
			$this->trigger->getJavascript() . ", ";
		//Normal Actions
		$js .= "[";
		for ($i=0; $i < sizeOf($this->actions); $i++) {
			$js .= $this->actions[$i]->getJavascriptFire();
			if ($i < sizeOf($this->actions) - 1) {
				$js .= ",";
			}
		}
		$js .= "]";
		//False Actions
		$js .= ",[";
			for ($i=0; $i < sizeOf($this->falseActions); $i++) {
				$js .= $this->falseActions[$i]->getJavascriptFire();
				if ($i < sizeOf($this->falseActions) - 1) {
					$js .= ",";
				}
			}
		$js .= "]";
		if ($this->fireOnLoad) {
			$js .= ",true";
		} else {
            $js .= ",false";
        }

		$js .= ",'$form');\n";

		return $js;
	}

    /**
     * Returns the definition of the dependency in array format.
     * @return array
     */
    public function getDefinition() {
        $def = array (
            "name" => $this->id,
            "hooks" => !empty($this->hooks) ? $this->hooks : array("all"),
            "trigger" => $this->trigger->getCondition(),
            "triggerFields" => $this->trigger->getFields(),
            "relatedFields" => $this->relatedFields,
            "onload" => $this->fireOnLoad,
            "isRelated" => $this->isRelated,
            "actions" => array(),
            "notActions" => array(),
        );

        foreach($this->actions as $action) {
            $def['actions'][] = $action->getDefinition();
        }
        foreach($this->falseActions as $action) {
            $def['notActions'][] = $action->getDefinition();
        }

        return  $def;
    }

	/**
	 * Runs the dependency on the target bean.
	 *
	 * @param SugarBean $target
	 */
    public function fire(SugarBean $target)
    {
		try {
		  if ($this->trigger->evaluate($target) === true) {
			     $this->fireActions($target);
			} else {
				$this->fireActions($target, true);
			}
		} catch (Exception $e)
		{
			$GLOBALS['log']->fatal($e->getMessage());
			$GLOBALS['log']->debug("Trigger was : {$this->trigger->conditionFunction}");
		}
	}

	/**
	 * Performs the actions in this dependency on the target.
	 *
	 * @param SugarBean $target
	 * @param boolean $useFalse
	 */
    private function fireActions(SugarBean $target, $useFalse = false)
    {
		$action = "";
		try {
			$actions = $this->actions;
			if ($useFalse)
				$actions = $this->falseActions;
			foreach ($actions as $action) {
				$action->fire($target);
			}
		} catch (Exception $e)
        {
            $GLOBALS['log']->fatal($e->getMessage());
            $GLOBALS['log']->debug("Trigger was : {$this->trigger->conditionFunction}");
            $GLOBALS['log']->debug("Target was : " . print_r($action, true));
        }
	}

	function getFireOnLoad()
	{
		return $this->fireOnLoad;
	}

    function addHook(String $hook) {
        $this->hooks[] = $hook;
    }

}
