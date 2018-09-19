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
 * Generic expression
 * @api
 */
abstract class GenericExpression extends AbstractExpression
{
	/**
	 * Parameters May be anything
	 */
    static function getParameterTypes() {
		return array(AbstractExpression::$ENUM_TYPE, AbstractExpression::$STRING_TYPE, AbstractExpression::$BOOLEAN_TYPE,
					 AbstractExpression::$DATE_TYPE, AbstractExpression::$NUMERIC_TYPE,  AbstractExpression::$GENERIC_TYPE);
	}

	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
			var params = this.getParameters();
			return params;
EOQ;
	}
}

?>