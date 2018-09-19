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
 * <b>related(Relationship <i>link</i>, String <i>field</i>)</b><br>
 * Returns the value of <i>field</i> in the related module <i>link</i><br/>
 * ex: <i>related($accounts, "industry")</i>
 */
class RelatedFieldExpression extends GenericExpression
{
	/**
	 * Returns the entire enumeration bare.
	 */
	function evaluate() {
		$params = $this->getParameters();
        //This should be of relate type, which means an array of SugarBean objects
        $linkField = $params[0]->evaluate();
        $relfield = $params[1]->evaluate();

        if (empty($linkField)) {
            return "";
        }
        
        foreach($linkField as $id => $bean)
        {
            if (!empty($bean->field_defs[$relfield]) && isset($bean->$relfield))
            {
                if (!empty($bean->field_defs[$relfield]['type']))
                {
                    global $timedate;
                    if ($bean->field_defs[$relfield]['type'] == "date")
                    {
                        $ret = $timedate->fromDbDate($bean->$relfield);
                        if (!$ret) {
                            $ret = $timedate->fromUserDate($bean->$relfield);
                        }
                        if ($ret) {
                            $ret->isDate = true;
                            $ret->def = $bean->field_defs[$relfield];
                        }
                        return $ret;
                    }
                    if ($bean->field_defs[$relfield]['type'] == "datetime"
                        || $bean->field_defs[$relfield]['type'] == "datetimecombo") {
                        $ret = $timedate->fromDb($bean->$relfield);
                        if (!$ret)
                            $ret = $timedate->fromUser($bean->$relfield);
                        if ($ret) {
                            $ret->def = $bean->field_defs[$relfield];
                        }
                        return $ret;
                    }
                    if ($bean->field_defs[$relfield]['type'] == "bool")
                    {
                        if ($bean->$relfield)
                            return BooleanExpression::$TRUE;
                        else
                            return BooleanExpression::$FALSE;
                    }
                    //Currency values need to be converted to the current currency when the related value
                    //doesn't match this records currency
                    if ($bean->field_defs[$relfield]['type'] == "currency") {
                        if (!isset($this->context)) {
                            $this->setContext();
                        }
                        if (isset($this->context->base_rate) && isset($bean->base_rate) &&
                            $this->context->base_rate != $bean->base_rate
                        ) {
                            return SugarCurrency::convertWithRate(
                                $bean->$relfield,
                                $bean->base_rate,
                                $this->context->base_rate
                            );
                        }
                    }
                }
                return $bean->$relfield;
            }
        }
        
        return "";
	}

	/**
	 * Returns the JS Equivalent of the evaluate function.
	 */
	static function getJSEvaluate() {
		return <<<EOQ
		    var params = this.getParameters(),
			    linkField = params[0].evaluate(),
			    relField = params[1].evaluate();

			if (typeof(linkField) == "string" && linkField != "")
			{
                return this.context.getRelatedField(linkField, 'related', relField);
			} else if (typeof(rel) == "object") {
			    //Assume we have a Link object that we can delve into.
			    //This is mostly used for n level dives through relationships.
			    //This should probably be avoided on edit views due to performance issues.
			}

			return "";
EOQ;
	}

	/**
	 * Returns the opreation name that this Expression should be
	 * called by.
	 */
	static function getOperationName() {
		return array("related");
	}

	/**
	 * The first parameter is a number and the second is the list.
	 */
    static function getParameterTypes() {
		return array(AbstractExpression::$RELATE_TYPE, AbstractExpression::$STRING_TYPE);
	}

	/**
	 * Returns the maximum number of parameters needed.
	 */
	static function getParamCount() {
		return 2;
	}

	/**
	 * Returns the String representation of this Expression.
	 */
	function toString() {
	}
}

?>
