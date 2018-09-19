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

class FormatedNameExpression extends StringExpression {
	/**
	 * Returns itself when evaluating.
	 */
	function evaluate() {
		$params	  = $this->getParameters();
		$sal =    $params[0]->evaluate();
		$first =  $params[1]->evaluate();
		$last =   $params[2]->evaluate();
		$title =  $params[3]->evaluate();
		
		global $locale;
		return $locale->getLocaleFormattedName($first, $last, $sal, $title);
		
	}

	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
var params	= this.getParameters();
var comp = {s:params[0].evaluate(), f:params[1].evaluate(), l:params[2].evaluate(), t:params[3].evaluate()};
var name = '';
for(i=0; i<name_format.length; i++) {
	if(comp[name_format.substr(i,1)] != undefined) {
    	name += comp[name_format.substr(i,1)];
	} else {
		name += name_format.substr(i,1);
	}
}
return name;
EOQ;
	}

	/**
	 * Returns the opreation name that this Expression should be
	 * called by.
	 */
	static function getOperationName() {
		return "formatName";
	}

	/**
	 * Returns the exact number of parameters needed.
	 */
	static function getParamCount() {
		return 4;
	}

	/**
	 * Returns the String representation of this Expression.
	 */
	function toString() {
	}
    static function getParameterTypes() {
		return AbstractExpression::$STRING_TYPE;
	}
}
?>