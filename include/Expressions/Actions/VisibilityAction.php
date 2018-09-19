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

class VisibilityAction extends AbstractAction
{
    protected $targetField = array();
    protected $expression = "";

    public function __construct($params)
    {
        $this->params = $params;
        $this->targetField = $params['target'];
        $this->expression = str_replace("\n", "", $params['value']);
        $this->view = isset($params['view']) ? $params['view'] : "";
    }

    /**
     * Returns the javascript class equavalent to this php class
     *
     * @return string javascript.
     */
    static function getJavascriptClass()
    {
        return "
        var App = App || null;
        SUGAR.forms.SetVisibilityAction = function(target, expr, view)
        {
            this.afterRender = true;
            if (_.isObject(target)) {
                expr = target.value;
                target = target.target
            }
            this.target = target;
            this.expr    = 'cond(' + expr + ', \"\", \"none\")';
            this.view = view;

            if (!SUGAR.forms.SetVisibilityAction.initialized) {
                var head = document.getElementsByTagName('head')[0];
                var cssdef = 'span.vis_action_hidden, .vis_action_hidden * { visibility:hidden}'
                var newStyle = document.createElement('style');
                newStyle.setAttribute('type', 'text/css');
                if (newStyle.styleSheet)
                    newStyle.styleSheet.cssText = cssdef;
                else
                    newStyle.innerHTML = cssdef;
                head.appendChild(newStyle);
                SUGAR.forms.SetVisibilityAction.initialized = true;
            }
        }
        
        /**
         * Triggers this dependency to be re-evaluated again.
         */
        SUGAR.util.extend(SUGAR.forms.SetVisibilityAction, SUGAR.forms.AbstractAction, {
        
            /**
             * Triggers the style dependencies.
             */
            exec: function(context) {
                if (typeof(context) == 'undefined')
                    context = this.context;
                try {
                    var target = context && context.getElement && context.getElement(this.target) || null;
                    if (target == null) {
                        return;
                    }
                    var exp = this.evalExpression(this.expr, context);
                    var hide =  exp == 'none' || exp == 'hidden';
                    if (SUGAR.App) {
                        this.sidecarExec(context, target, hide);
                    }
                    else {
                        this.legacyExec(context, target, hide);
                    }
                } catch (e) {
                    if (console && console.log) console.log(e);
                }
            },
            sidecarExec : function(context, target, hide) {
                var inv_class = 'vis_action_hidden',
                    panel_class = 'record-panel-content',
                    wasHidden = $(target).hasClass(inv_class),
                    field = context.view.getField(this.target),
                    row = $(target).parents('.panel_body')[0];

                if (field && _.isUndefined(field.wasRequired)) {
                    field.wasRequired = field.def.required;
                }
                if (hide) {
                    context.addClass(this.target, inv_class, true);
                    //Disable the field to prevent tabbing into the edit mode of the field
                    context.setFieldDisabled(this.target, true);
                    if (field.wasRequired === true) {
                        context.setFieldRequired(this.target, false);
                    }
                }
                else {
                    context.removeClass(this.target, inv_class, true);
                    if (!field.def.calculated || !field.def.enforced) {
                        context.setFieldDisabled(this.target, false);
                    }
                    if (wasHidden) {
                        SUGAR.forms.FlashField(target, null, this.target);
                    }
                    if (field.wasRequired === true) {
                        context.setFieldRequired(this.target, true);
                    }
                }
                if (row) {
                    this.checkRowSidecar(row, inv_class);
                    this.checkPanelSidecar(row.parentElement, inv_class, panel_class);
                }
            },
            legacyExec : function(context, target, hide) {
                var Dom = YAHOO.util.Dom,
                    inv_class = 'vis_action_hidden',
                    inputTD = Dom.getAncestorByTagName(target, 'TD'),
                    labelTD = Dom.getPreviousSiblingBy(inputTD, function(e){
                        return e.tagName == 'TD'
                    });
                this.wrapContent(labelTD);
                this.wrapContent(inputTD);
                var wasHidden = Dom.hasClass(labelTD, inv_class);
                if (hide) {
                    Dom.addClass(labelTD, inv_class);
                    Dom.addClass(inputTD, inv_class);
                }
                else {
                    Dom.removeClass(labelTD, inv_class);
                    Dom.removeClass(inputTD, inv_class);
                    if (wasHidden && this.view == 'EditView') {
                        SUGAR.forms.FlashField(target);
                    }
                }
                this.checkRow(Dom.getAncestorByTagName(inputTD, 'TR'), inv_class);
            },
            //we need to wrap plain text nodes in a span in order to hide the contents without hiding the TD itesef
            wrapContent: function(el) {
                if (el && this.containsPlainText(el))
                {
                    var span = document.createElement('SPAN');
                    var nodes = [];
                    for(var i = 0; i < el.childNodes.length ; i++)
                    {
                        nodes[i] = el.childNodes[i];
                    }
                    for(var i = 0 ; i < nodes.length; i++)
                    {
                        span.appendChild(nodes[i]);
                    }
                    el.appendChild(span);
                }
            },
            containsPlainText: function(el) {
                for(var i = 0; i < el.childNodes.length; i++) {
                    var node = el.childNodes[i];
                    if (node.nodeName == '#text' && YAHOO.lang.trim(node.textContent) != '') {
                        return true;
                    }
                }
                return false;
            },
            checkRow: function(el, inv_class) {
                var hide = true;
                for(var i = 0; i < el.children.length; i++) {
                    var node = el.children[i];
                    //For each row, check if the column has the inv_class class attribute, if not, do not hide
                    if (node.tagName.toLowerCase() == 'td' && !YAHOO.util.Dom.hasClass(node, inv_class)) {
                        hide = false;
                        break;
                    }
                }
                el.style.display = hide ? 'none' : '';
            },
            checkRowSidecar: function(el, inv_class) {
                if (!el || el.children) {
                    return;
                }
                var hide = true;
                for(var i = 0; i < el.children.length; i++) {
                    var node = el.children[i];
                    // Hide row
                    if (node.tagName.toLowerCase() == 'div' &&
                        !$(node).hasClass(inv_class) &&  // all members contain the invisible class
                        $(node).children().length > 1
                    ) { // is a valid non-empty field
                        hide = false;
                        break;
                    }
                }
                el.style.display = hide ? 'none' : '';
            },
            checkPanelSidecar: function(el, inv_class, panel_class) {
                if (!el || !el.children) {
                    return;
                }

                var hide = true;

                // check if each row has its elements hidden
                for (var i = 0; i < el.children.length; i++) {
                    var node = el.children[i];
                    var inv_nodes = $(node).children('.' + inv_class);
                    // visible non-empty nodes (i.e. data-type not empty)
                    var vis_nodes = $(node).children(':not([data-type=\"\"])');
                    hide &= inv_nodes.length == vis_nodes.length;
                }

                if (hide) {
                    $(el).removeClass(panel_class);
                } else {
                    $(el).addClass(panel_class);
                }
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
        return "new SUGAR.forms.SetVisibilityAction('{$this->targetField}','{$this->expression}', '{$this->view}')";
    }

    /**
     * Applies the Action to the target.
     *
     * @param SugarBean $target
     */
    function fire(&$target)
    {
        $result = Parser::evaluate($this->expression, $target)->evaluate();
        if ($result === AbstractExpression::$FALSE) {
            $target->field_defs[$this->targetField]['hidden'] = true;
        } else {
            $target->field_defs[$this->targetField]['hidden'] = false;
        }
    }

    static function getActionName()
    {
        return "SetVisibility";
    }

}
