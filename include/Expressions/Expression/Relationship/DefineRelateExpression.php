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

class DefineRelateExpression extends RelateExpression
{
	/**
	 * Returns the entire enumeration bare.
	 */
	function evaluate() {
		$fieldName = $this->getParameters()->evaluate();

        if (!isset($this->context))
        {
            //If we don't have a context provided, we have to guess. This can be a large performanc hit.
            $this->setContext();
        }

        if (empty($this->context->field_defs[$fieldName]))
            throw new Exception("Unable to find field {$fieldName}");

        if(!$this->context->load_relationship($fieldName))
            throw new Exception("Unable to load relationship $fieldName");

        if(empty($this->context->$fieldName))
            throw new Exception("Relationship $fieldName was not set");

        $rmodule = $this->context->$fieldName->getRelatedModuleName();

        //now we need a seed of the related module to load.
        $seed = $this->getBean($rmodule);

        return $this->context->$fieldName->getBeans($seed);
	}

    protected function setContext()
    {
        $module = $_REQUEST['module'];
        $id = $_REQUEST['record'];
        $focus = $this->getBean($module);
        $focus->retrieve($id);
        $this->context = $focus;
    }

    protected function getBean($module)
    {
        $bean = BeanFactory::newBean($module);
        if (empty($bean))
           throw new Exception("No bean for module $module");
        return $bean;
    }

	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return "";
	}

	/**
	 * Returns the opreation name that this Expression should be
	 * called by.
	 */
	static function getOperationName() {
		return "link";
	}

	/**
	 * All parameters have to be a string.
	 */
    static function getParameterTypes() {
		return array("string");
	}

	/**
	 * Returns the maximum number of parameters needed.
	 */
	static function getParamCount() {
		return 1;
	}

	/**
	 * Returns the String representation of this Expression.
	 */
	function toString() {
	}
}

?>