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
 * Class SetValueAction
 *
 * SugarLogic Action to set a value to a field
 */
class SetValueAction extends AbstractAction
{
    /**
     * Store the expression
     *
     * @var mixed|string
     */
    protected $expression = '';

    /**
     * The field we are targeting
     *
     * @var string
     */
    protected $targetField = '';

    /**
     * What should be put on the model when an error happens?
     *
     * @var null|string
     */
    protected $errorValue = null;

    /**
     * Constructor
     *
     * @param array $params
     */
    public function __construct($params)
    {
        $this->targetField = $params['target'];
        $this->expression = str_replace("\n", '', $params['value']);
        $this->errorValue = array_key_exists('errorValue', $params) ? $params['errorValue'] : null;
    }

    /**
     * Returns the javascript class equivalent to this php class
     *
     * @return string javascript.
     */
    public static function getJavascriptClass()
    {
        return <<<JS
		SUGAR.forms.SetValueAction = function(target, valExpr) {
			if (_.isObject(target)){
			    this.expr = target.value;
			    this.target = target.target;
			    this.errorValue = !_.isUndefined(target.errorValue) ? target.errorValue : null;
			} else {
                this.expr = valExpr;
                this.target = target;
			}
		};
		SUGAR.util.extend(SUGAR.forms.SetValueAction, SUGAR.forms.AbstractAction, {
			exec : function(context)
			{
				if (typeof(context) == 'undefined') {
				    context = this.context;
                }

				try {
				    // set the target for rollup expressions
				    context.target = this.target;

				    var val = this.evalExpression(this.expr, context),
				        cVal = context.getValue(this.target).evaluate();
                    // only set the value if the two numbers are different
                    // get rid of the flash
                    if (!_.isUndefined(val) && val !== cVal && this.canSetValue(context)) {
                        context.setValue(this.target, val);
				    }
				} catch (e) {
				    if (!_.isUndefined(this.errorValue) && !_.isNull(this.errorValue)) {
				        context.setValue(this.target, this.errorValue);
				    }
			    }
	       }
		});
JS;
    }


    /**
     * Returns the javascript code to generate this actions equivalent.
     *
     * This is used for BWC modules only
     *
     * @return string javascript.
     */
    public function getJavascriptFire()
    {
        return "new SUGAR.forms.SetValueAction('{$this->targetField}','" . addslashes($this->expression) . "')";
    }

    /**
     * Applies the Action to the target.
     *
     * @param SugarBean $target
     */
    public function fire(&$target)
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            $GLOBALS['log']->fatal("Error evaluating expression: {$errstr}\nLine {$errline} of file {$errfile}");
        }, E_ERROR);

        try {
            $result = Parser::evaluate($this->expression, $target)->evaluate();
        } catch (Exception $e) {
            $GLOBALS['log']->fatal(
                "Exception evaluating expression in SetValueAction, {$this->expression} : {$e->getMessage()}\n{$e->getTraceAsString()}"
            );
            $result = '';
        }
        restore_error_handler();
        $field = $this->targetField;
        $def = array();
        if (!empty($target->field_defs[$field])) {
            $def = $target->field_defs[$field];
        }
        if ($result instanceof DateTime) {
            global $timedate;
            if (isset($def['type']) && ($def['type'] == 'datetime' || $def['type'] == 'datetimecombo')) {
                $result = DateExpression::roundTime($result);
                $target->$field = $timedate->asDb($result);
            } else {
                if (isset($def['type']) && $def['type'] == 'date') {
                    $result = DateExpression::roundTime($result);
                    $target->$field = $timedate->asDbDate($result);
                } else {
                    //If the target field isn't a date, convert it to a user formated string
                    if (isset($result->isDate) && $result->isDate) {
                        $target->$field = $timedate->asUserDate($result);
                    } else {
                        $target->$field = $timedate->asUser($result);
                    }
                }
            }
        } else {
            if (isset($def['type']) && $def['type'] == 'bool') {
                $target->$field = $result === true || $result === AbstractExpression::$TRUE;
            } else {
                if (is_array($result) && $def['type'] != 'multienum') {
                    $target->$field = implode(', ', $result);
                } else {
                    $target->$field = $result;
                }
            }
        }
    }

    /**
     * Returns the definition of this action in array format.
     *
     * @return array
     */
    public function getDefinition()
    {
        return array(
            'action' => $this->getActionName(),
            'params' => array(
                'target' => $this->targetField,
                'value' => $this->expression,
                'errorValue' => $this->errorValue
            )
        );
    }

    /**
     * The Action Name
     *
     * @return string
     */
    public static function getActionName()
    {
        return 'SetValue';
    }
}
