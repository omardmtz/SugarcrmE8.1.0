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

class StyleAction extends AbstractAction{
	protected $expression =  "";
	
    public function __construct($params)
    {
		$this->targetField = $params['target'];
		$this->attributes = array();
        foreach($params['attrs'] as $prop => $val)
        {
            $this->attributes[$prop] = str_replace("\n", "", $val);
        }
	}
	
	/**
	 * Returns the javascript class equavalent to this php class
	 *
	 * @return string javascript.
	 */
	static function getJavascriptClass() {
		return  "
/**
 * A style dependency is an object representation of a style change.
 */
SUGAR.forms.StyleAction = function(target, attrs)
{
    this.target = target;
    this.attrs  = attrs;
}

/**
 * Triggers this dependency to be re-evaluated again.
 */
SUGAR.util.extend(SUGAR.forms.StyleAction, SUGAR.forms.AbstractAction, {

    /**
     * Triggers the style dependencies.
     */
    exec: function(context)
    {

        //If we are running in sidecar, this action will not function
        if(SUGAR.App) return;

        if (typeof(context) == 'undefined')
            context = this.context;
        try {
            // a temp attributes array containing the evaluated version
            // of the original attributes array
            var temp = {};

            // evaluate the attrs, if needed
            for (var i in this.attrs)
            {
                temp[i] = this.evalExpression(this.attrs[i], context);
            }
            context.setStyle(this.target, temp);
        } catch (e) {return;}
    }
});";
	}

	/**
	 * Returns the javascript code to generate this actions equivalent. 
	 *
	 * @return string javascript.
	 */
	function getJavascriptFire() {
		return  "new SUGAR.forms.StyleAction('{$this->targetField}'," .json_encode($this->attributes) . ")";
	}
	
	
	
	/**
	 * Applies the Action to the target.
	 *
	 * @param SugarBean $target
	 */
	function fire(&$target) {

	}
	
	/**
	 * Returns the definition of this action in array format.
	 *
	 */
	function getDefinition() {
		return array(	
			"action" => $this->getActionName(), 
	        "target" => $this->targetField, 
	        "attrs" => $this->attributes,
	    );
	}
	
	static function getActionName() {
		return "Style";
	}
}

