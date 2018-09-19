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
 * <b>getDropdownValue(String list_name, String key)</b><br>
 * Returns the translated value for the given <i>key</i><br/>
 * found in the <i>list_name</i> DropDown list<br/>
 * This list must be defined in the DropDown editor.<br/>
 * ex: <i>getDropdownValue("my_list", "foo")</i>
 */
class SugarDropDownValueExpression extends StringExpression {
	
	/**
	 * Returns the negative of the expression that it contains.
	 */
	function evaluate() {
		global $app_list_strings;
		$params = $this->getParameters();
        $list = $params[0]->evaluate();
        $key = $params[1]->evaluate();
		
        if (isset($app_list_strings[$list]) && is_array($app_list_strings[$list]) 
                && isset($app_list_strings[$list][$key])) 
        {
            return $app_list_strings[$list][$key];
        }
        
        
        
        return ""; 
	}
	
	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
		    var params = this.getParameters();
		    var list = params[0].evaluate();
		    var key = params[1].evaluate();
            var arr = this.context.getAppListStrings(list);
            if (arr == "undefined") return "";
            for (var i in arr) {
                if (typeof i == "string" && i == key)
                    return arr[i];
            }
            return "";
EOQ;
	}
	
	/**
	 * Returns the opreation name that this Expression should be
	 * called by.
	 */
	static function getOperationName() {
		return array("getDropdownValue", "getDDValue");
	}

	/**
	 * Returns the maximum number of parameters needed.
	 */
	static function getParamCount() {
		return 2;
	}
}
?>
