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
 * <b>subStr(String s, Number from, Number length)</b><br>
 * Returns <i>length</i> characters starting at 0-based index <i>from</i>.<br />
 * ex: <em>subStr("Hello", 1, 3)</em> = "ell"
 */
class SubStrExpression extends StringExpression 
{
    /**
    * Returns itself when evaluating.
    */
    function evaluate() 
    {
        $params = $this->getParameters();
        $str = $params[0]->evaluate();
        $fromIdx = $params[1]->evaluate();
        $strLength = $params[2]->evaluate();

        if (!is_numeric($fromIdx))
          throw new Exception($this->getOperationName() . ": Parameter FROM must be a number.");

        if (!is_numeric($strLength))
          throw new Exception($this->getOperationName() . ": Parameter LENGTH must be a number.");

        return substr($str, $fromIdx, $strLength);
    }

    /**
    * Returns the JS Equivalent of the evaluate function.
    */
    static function getJSEvaluate() 
    {
        return <<<EOQ
            var params = this.getParameters();
            var str = params[0].evaluate() + "";
            var fromIdx = params[1].evaluate();
            var strLength = params[2].evaluate();
            return str.substr(fromIdx, strLength);
EOQ;
    }

    /**
    * Returns the opreation name that this Expression should be
    * called by.
    */
    static function getOperationName() 
    {
        return "subStr";
    }

    /**
    * Any generic type will suffice.
    */
    static function getParameterTypes()
    {
        return array("string", "number", "number");
    }

    /**
    * Returns the exact number of parameters needed.
    */
    static function getParamCount() 
    {
        return 3;
    }

    /**
    * Returns the String representation of this Expression.
    */
    function toString() 
    {
    }
}
