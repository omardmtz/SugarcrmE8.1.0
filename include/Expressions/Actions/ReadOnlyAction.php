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


class ReadOnlyAction extends AbstractAction
{
    protected $expression = "";

    /*
     * array Array of actions on which the Expression Action is not allowed
     */
    protected $disallowedActions = array('view');

    public function __construct($params)
    {
        $this->params = $params;
        $this->targetField = $params['target'];
        $this->expression = str_replace("\n", "", $params['value']);
    }

    /**
     * Returns the javascript class equavalent to this php class
     *
     * @return string javascript.
     */
    public static function getJavascriptClass()
    {
        return "
        SUGAR.forms.ReadOnlyAction = function(target, expr) {
            this.afterRender = true;
            if (_.isObject(target)){
                expr = target.value;
                target = target.target
            }
            this.target = target;
            this.expr = expr;
        }

        SUGAR.util.extend(SUGAR.forms.ReadOnlyAction, SUGAR.forms.AbstractAction, {
            /**
             * Triggers the style dependencies.
             */
            exec: function(context) {
                if (typeof(context) == 'undefined') context = this.context;
                var val = this.evalExpression(this.expr, context),
                    readOnly = val == SUGAR.expressions.Expression.TRUE;
                
                if (context.view) {
                    //We may get triggered before the view has rendered with the full field list.
                    //If that occurs wait for the next render to apply.
                    if (_.isEmpty(context.view.fields)) {
                        context.view.once('render', function(){this.exec(context);}, this);
                        return;
                    }

                    context.setFieldDisabled(this.target, readOnly);
                } else {
                    this.bwcExec(context, readOnly);
                }

            },

            bwcExec: function(context, readonly) {
                var el = SUGAR.forms.AssignmentHandler.getElement(this.target);
                if (!el) {
                    return;
                }
                this.setReadOnly(el, readonly);
                this.setDateField(el, readonly);
            },

            setReadOnly: function(el, set)
            {
                var D = YAHOO.util.Dom;
                var property = el.type == 'checkbox' || 'select' ? 'disabled' : 'readonly';
                el[property] = set;
                if (set)
                {
                    D.setStyle(el, 'background-color', '#EEE');
                    if (!SUGAR.isIE)
                       D.setStyle(el, 'color', '#22');
                } else
                {
                    D.setStyle(el, 'background-color', '');
                        if (!SUGAR.isIE)
                            D.setStyle(el, 'color', '');
                }
            },

            setDateField: function(el, set)
            {
                var D = YAHOO.util.Dom, id = el.id, trig = D.get(id + '_trigger');
                if(!trig) return;
                var fields = [
                    D.get(id + '_date'),
                    D.get(id + '_minutes'),
                    D.get(id + '_meridiem'),
                    D.get(id + '_hours')];

                for (var i in fields)
                    if (fields[i] && fields[i].id)
                        this.setReadOnly(fields[i], set);

                if (set)
                    D.setStyle(trig, 'display', 'none');
                else
                    D.setStyle(trig, 'display', '');
            }
        });";
    }

    /**
     * Returns the javascript code to generate this actions equivalent.
     *
     * @return string javascript.
     */
    public function getJavascriptFire()
    {
        return "new SUGAR.forms.ReadOnlyAction('{$this->targetField}','{$this->expression}')";
    }

    /**
     * Applies the Action to the target.
     *
     * @param SugarBeam $target
     */
    public function fire(&$target)
    {
        //This is a no-op under PHP
    }

    public static function getActionName()
    {
        return "ReadOnly";
    }
}
