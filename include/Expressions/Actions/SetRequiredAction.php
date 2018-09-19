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

class SetRequiredAction extends AbstractAction
{
    protected $expression = "";

    /**
     * array Array of actions on which the Expression Action is not allowed
     */
    protected $disallowedActions = array('view');

    public function __construct($params)
    {
        $this->params = $params;
        $this->targetField = $params['target'];
        $this->targetLabel = isset($params['label']) ? $params['label'] : '';
        $this->expression = str_replace("\n", "", $params['value']);
    }

    /**
     * Returns the javascript class equavalent to this php class
     *
     * @return string javascript.
     */
    static function getJavascriptClass()
    {
        return "
SUGAR.forms.SetRequiredAction = function(variable, expr, label) {
    if (_.isObject(variable)){
        expr = variable.value;
        label = variable.label;
        variable = variable.target;
    }
    this.variable = variable;
    this.expr = expr;
    this.label    = label;
    this._el_lbl  = document.getElementById(this.label);
    if (this._el_lbl)
        this.msg = this._el_lbl.innerText;
}

/**
 * Triggers this dependency to be re-evaluated again.
 */
SUGAR.util.extend(SUGAR.forms.SetRequiredAction, SUGAR.forms.AbstractAction, {

    /**
     * Triggers the required dependencies.
     */
    exec: function(context) {
        if (typeof(context) == 'undefined')
		    context = this.context;

        this.required = this.evalExpression(this.expr, context);
        if (context.view) {
            //We may get triggered before the view has rendered with the full field list.
            //If that occurs wait for the next render to apply.
            if (_.isEmpty(context.view.fields)) {
                context.view.once('render', function(){this.exec(context);}, this);
                return;
            }
            context.setFieldRequired(this.variable, this.required);
        } else {
            this.bwcExec(context, this.required);
        }

    },
     bwcExec : function(context, required) {
        var el = SUGAR.forms.AssignmentHandler.getElement(this.variable);
        if ( typeof(SUGAR.forms.FormValidator) != 'undefined' )
            SUGAR.forms.FormValidator.setRequired(el.form.name, el.name, this.required);
        if (this._el_lbl != null && el != null) {
            var p = this._el_lbl,
                els = YAHOO.util.Dom.getElementsBy( function(e) { return e.className == 'req'; }, \"span\", p),
                reqSpan = false,
                fName = el.name;

            if ( els != null && els[0] != null)
                reqSpan = els[0];

            if ( (this.required == true  || this.required == 'true')) {
                if (!reqSpan) {
                    var node = document.createElement(\"span\");
                    node.innerHTML = \"<font color='red'>*</font>\";
                    node.className = \"req\";
                    this._el_lbl.appendChild(node);

                    var i = this.findInValidate(context.formName, fName)
                    if (i > -1)
                        validate[context.formName][i][2] = true;
                    else
                        addToValidate(context.formName, fName, 'text', true, this.msg);
                }
            } else {
                if ( p != null  && reqSpan != false) {
                    reqSpan.parentNode.removeChild(reqSpan);
                }
                var i = this.findInValidate(context.formName, fName)
                if (i > -1)
                    validate[context.formName][i][2] = false;
            }
        }
     },
     findInValidate : function(form, field) {
         if (validate && validate[form]){
             for (var i in validate[form]){
                if (typeof(validate[form][i]) == 'object' && validate[form][i][0] == field)
                    return i;
             }
         }
         return -1;
     }
});";
    }

    /**
     * Returns the javascript code to generate this actions equivalent.
     *
     * @return string javascript.
     */
    function getJavascriptFire()
    {
        return "new SUGAR.forms.SetRequiredAction('{$this->targetField}','{$this->expression}', '{$this->targetLabel}')";
    }

    /**
     * Applies the Action to the target.
     *
     * @param SugarBean $target
     */
    function fire(&$target)
    {
        $value = Parser::evaluate($this->expression, $target)->evaluate();
        if (!empty($target->field_defs[$this->targetField]) && is_array($target->field_defs[$this->targetField])) {
            $target->field_defs[$this->targetField]['required'] = ($value == AbstractExpression::$TRUE);
        }
    }

    static function getActionName()
    {
        return "SetRequired";
    }

}

