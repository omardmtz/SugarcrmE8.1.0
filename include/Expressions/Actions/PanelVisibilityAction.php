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

class PanelVisibilityAction extends AbstractAction{
	protected $targetPanel = "";
	protected $expression = "";
	
    public function __construct($params)
    {
        $this->params = $params;
        if (is_array($params)) {
            if (isset($params['target'])) {
                $this->targetPanel = $params['target'];
            }
            if (isset($params['value'])) {
                $this->expression = str_replace("\n", "", $params['value']);
            }
        }
    }
	
	/**
	 * Returns the javascript class equivalent to this php class
	 *
	 * @return string javascript.
	 */
	static function getJavascriptClass() {
		return <<<'EOQ'
/**
 * Completely hide or show a panel
 */
SUGAR.forms.SetPanelVisibilityAction = function(target, expr)
{
    this.afterRender = true;

    if (_.isObject(target)){
        expr = target.value;
        target = target.target;
    }
    //BWC
    if (_.isString(target) && _.isUndefined(SUGAR.App)) {
       var parents = $('#' + target).parents('div');
       if(parents.length) {
          target = parents.attr('id');
       }
    }

    this.target = target;
    this.expr   = 'cond(' + expr + ', "", "none")';
}


/**
 * Triggers this dependency to be re-evaluated again.
 */
SUGAR.util.extend(SUGAR.forms.SetPanelVisibilityAction, SUGAR.forms.AbstractAction, {
    hideChildren: function() {
        if (typeof(SUGAR.forms.SetPanelVisibilityAction.hiddenFields) == "undefined")
        {
            this.createFieldBin();
        }
        var target = document.getElementById(this.target);
        var field_table = target.getElementsByTagName('table')[0];
        if (field_table != null) 
        {
            field_table.id = this.target + "_tbl";
            SUGAR.forms.SetPanelVisibilityAction.hiddenFields.appendChild(field_table);
        }
    },
    
    showChildren: function() {
        var target = document.getElementById(this.target);
        var field_table = document.getElementById(this.target + "_tbl");
        if (field_table != null)
            target.appendChild(field_table);
    },
    
    createFieldBin: function() {
        var tmpElem = document.createElement('div');
        tmpElem.id = 'panelHiddenFields';
        tmpElem.style.display = 'none';
        document.body.appendChild(tmpElem);
        SUGAR.forms.SetPanelVisibilityAction.hiddenFields = tmpElem;
    },
    
    /**
     * Triggers the style dependencies.
     */
    exec: function(context)
    {
        if (typeof(context) == 'undefined')
            context = this.context;

        if (context.view)
            return this.sidecarExec(context);
        try {
            var visibility = this.evalExpression(this.expr, context);
            var target = document.getElementById(this.target);
            if (target != null) {               
                if (target.style.display != 'none')
                 SUGAR.forms.animation.sizes[this.target] = target.clientHeight;
                       
                if (SUGAR.forms.AssignmentHandler.ANIMATE) {
                    if (visibility == 'none' && target.style.display != 'none') {
                       SUGAR.forms.animation.Collapse(this.target, this.hideChildren, this);
                       return;
                    } 
                    else if (visibility != 'none' && target.style.display == 'none') 
                    {
                        this.showChildren();
                        SUGAR.forms.animation.Expand(this.target);
                        return;
                    }
                }
                
                if (visibility == 'none')
                    this.hideChildren();
                else
                    this.showChildren();
                target.style.display = visibility;
            }
        } catch (e) {if (console && console.log) console.log(e);}
    },
    sidecarExec : function(context) {
        var hide = (this.evalExpression(this.expr, context) === 'none'),
            tab = context.view.$(".tab." + this.target),
            panel = context.view.$("div.record-panel[data-panelname='" + this.target + "']"),
            isActive = tab && tab.hasClass("active");

        //If we can't find a tab, just look for a panel
        if (!tab || !tab.length) {
            //Hide/show a panel (No need to worry about the active tab)
            if (panel.length > 0) {
                if (hide) {
                    panel.hide();
                } else {
                    panel.show();
                }
                this.triggerFieldsVisibility(context, this.target, hide);
            } else {
                //If we got here it means the panel name/id was probably invalid.
                console.log("unable to find panel " + this.target);
            }
        } else {
            //Hide/show tabs
            if (hide) {
                tab.hide();
                //If we are hiding the active tab, show the first visible tab instead.
                if (isActive) {
                    var tabs = context.view.$("li.tab:visible");
                    if (tabs.length > 0 && context.view.setActiveTab) {
                        //setActiveTab currently expects an event. This may change in the future
                        context.view.setActiveTab({currentTarget:tabs[0].children[0]});
                        context.view.handleActiveTab();
                    }
                }
            } else {
                tab.show();
            }
            this.triggerFieldsVisibility(context, this.target, hide);
        }

    },
    triggerFieldsVisibility : function(context, target, hide) {

        _.each(this.getPanelFieldNames(context, target), function(fieldName) {
            var field = context.view.getField(fieldName);
            if (field && _.isUndefined(field.wasRequired)) {
                field.wasRequired = field.def.required;
            }
            context.setFieldDisabled(fieldName, hide);
            if (field.wasRequired === true)
                context.setFieldRequired(fieldName, !hide);

        });

    },
    getPanelFieldNames : function(context, panelName) {
      var panel = _.find(context.view.meta.panels, function(panel) {
        return panel.name === panelName;
      });

      return _.pluck(panel.fields, 'name');
    }
});

SUGAR.forms.animation.sizes = { };

SUGAR.forms.animation.Collapse = function(target, callback, scope)
{
    var t = document.getElementById(target);
    if (t == null) return;
    
    SUGAR.forms.animation.sizes[target] = t.clientHeight;
    t.style.overflow = "hidden";
    
    // Create a new ColorAnim instance
    var collapseAnim = new YAHOO.util.Anim(target, { height: { to: 0 } }, 0.5, YAHOO.util.Easing.easeBoth);
    collapseAnim.onComplete.subscribe(function () {
        t.style.display = 'none';
        callback.call(scope);
    });
    collapseAnim.animate();
};

SUGAR.forms.animation.Expand = function(target)
{
    var t = document.getElementById(target);
    if (t == null) return;
    
    
    t.style.overflow = "hidden";
    t.style.height = "0px";
    t.style.display = "";
    
    var expandAnim = new YAHOO.util.Anim(target, { height: { to: SUGAR.forms.animation.sizes[target]  } },
        0.5, YAHOO.util.Easing.easeBoth);
    
    expandAnim.onComplete.subscribe(function () {
        t.style.height = 'auto';
    });
    
    expandAnim.animate();
};
EOQ;

    }


	/**
	 * Returns the javascript code to generate this actions equivalent. 
	 *
	 * @return string javascript.
	 */
	function getJavascriptFire() {
		return "new SUGAR.forms.SetPanelVisibilityAction('{$this->targetPanel}','{$this->expression}')";
	}
	
    /**
    * Applies the Action to the target.
    *
    * @param SugarBean $target
    *
    * Should only be fired when saving from an edit view and the expression is false.
    */
    public function fire(&$target)
    {
        $result = Parser::evaluate($this->expression, $target)->evaluate();
        if ($result === AbstractExpression::$FALSE) {
            require_once 'modules/ModuleBuilder/parsers/constants.php';
            $view = isModuleBWC($target->module_name) ? MB_EDITVIEW : MB_RECORDVIEW;
            $parser = ParserFactory::getParser($view, $target->module_dir);
            $fields = $parser->getFieldsInPanel($this->targetPanel);
            foreach ($fields as $field) {
                unset($target->$field);
            }
        }
    }
	
	static function getActionName() {
		return "SetPanelVisibility";
	}
	
}
