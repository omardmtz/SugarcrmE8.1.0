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

class SetOptionsAction extends AbstractAction{
	protected $keysExpression =  "";
	protected $labelsExpressions =  "";

    /**
     * array Array of actions on which the Expression Action is not allowed
     */
    protected $disallowedActions = array('view');

    public function __construct($params)
    {
        $this->params = $params;
		$this->targetField = $params['target'];
		$this->keysExpression = str_replace("\n", "",$params['keys']);
		$this->labelsExpression = str_replace("\n", "",$params['labels']);
	}
	
	/**
	 * Returns the javascript class equavalent to this php class
	 *
	 * @return string javascript.
	 */
	static function getJavascriptClass() {
		return  "
		SUGAR.forms.SetOptionsAction = function(target, keyExpr, labelExpr) {
			this.afterRender = true;
			if (_.isObject(target)){
				labelExpr = target.labels;
				keyExpr = target.keys;
				target = target.target
			}
			this.keyExpr = keyExpr;
			this.labelExpr = labelExpr;
			this.target = target;
		};
				
		SUGAR.util.extend(SUGAR.forms.SetOptionsAction, SUGAR.forms.AbstractAction, {
			exec: function(context) {
				if (typeof(context) == 'undefined')
					context = this.context;

				var keys = this.evalExpression(this.keyExpr, context),
					labels = this.evalExpression(this.labelExpr, context),
					empty,
					selected = '';

				if (context.view)
				{
					var field = context.getField(this.target);
					//Cannot continue if the field does not exist on this view
					if (!field) {
					    return;
					}

                    selected = [].concat(field.model.get(this.target));
                    if (!this.canSetValue(context)) {
                        keys = _.uniq(keys.concat(selected));
                    }

                    empty = (_.size(keys) === 0 || _.size(keys) === 1) && (keys[0] == undefined || keys[0] === '');

					if (_.isString(labels))
						field.items = _.pick(App.lang.getAppListStrings(labels), keys);
					else
						field.items = _.object(keys, labels);

					slContext = context;

					field.model.fields[this.target].options = field.items;

					var visAction = new SUGAR.forms.SetVisibilityAction(this.target, (empty ? 'false' : 'true'), '');
					visAction.setContext(context);
					visAction.exec();

					//Remove from the selected options those options that are no longer available to select
					selected = _.filter(selected, function(key) {
					    return _.contains(keys, key);
					});

					if ((selected.length == 0 || (selected.length == 1 && selected[0] == '')) && field.model.fields[field.name].type != 'multienum') {
					    selected = [(empty ? '' : keys[0])];
					}

                    context.setValue(this.target, selected);
				}
				else {
					var field = context.getElement(this.target);
					if ( field == null )	return null;


					if (keys instanceof Array && field.options != null)
					{
						// get the options of this select
						var options = field.options;
						selected = [];

						for (var i = 0; i < options.length; i++) {
							if (options[i].selected)
								selected = selected.concat(options[i].value);
						}

						// empty the options
						while (options.length > 0) {
							field.remove(options[0]);
						}

						if (typeof(labels) == 'string') //get translated values from Sugar Language
						{
							var fullSet = SUGAR.language.get('app_list_strings', labels);
							labels = [];
							for (var i in keys)
							{
								labels[i] = fullSet[keys[i]];
							}
						}

						var new_opt;
						for (var i in keys) {
							if (labels instanceof Array)
							{
								if (typeof keys[i] == 'string')
								{
									if (typeof labels[i] == 'string') {
										new_opt = options[options.length] = new Option(labels[i], keys[i], keys[i] == selected);
									}
									else
									{
										new_opt = options[options.length] = new Option(keys[i], keys[i], keys[i] == selected);
									}
								}
							}
							else //Use the keys as labels
							{
								if (typeof keys[0] == 'undefined') {
									if (typeof(keys[i]) == 'string') {
										new_opt = options[options.length] = new Option(keys[i], i);
									}
								} else {
									if (typeof(value[i]) == 'string') {
										new_opt = options[options.length] = new Option(keys[i], keys[i]);
									}
								}
							}
							if (_.indexOf(selected, keys[i]) > -1) {
								new_opt.selected = true;
							}

						}

						if(!field.multiple && field.value != selected) {
							SUGAR.forms.AssignmentHandler.assign(this.target, field.value);
						}

						//Hide fields with empty lists

						var empty = (field.multiple && field.options.length == 0)
						 || (!field.multiple && field.options.length <= 1 && field.value == '');
						var visAction = new SUGAR.forms.SetVisibilityAction(this.target, (empty ? 'false' : 'true'), '');
						visAction.setContext(context);
						visAction.exec();

						if ( SUGAR.forms.AssignmentHandler.ANIMATE && !empty)
							SUGAR.forms.FlashField(field);
					}
					//Check if we are on a detailview and just need to hide the field
					else if (keys instanceof Array && (keys.length == 0 || (keys.length == 1 && keys[0] == ''))){
						//Use a normal visibility action to hide the field
						var va = new SUGAR.forms.SetVisibilityAction(this.target, 'false', '');
						va.exec(context);
					}
				}
			}
		});";
	}

	/**
	 * Returns the javascript code to generate this actions equivalent. 
	 *
	 * @return string javascript.
	 */
	function getJavascriptFire() {
		return  "new SUGAR.forms.SetOptionsAction('{$this->targetField}','{$this->keysExpression}', '{$this->labelsExpression}')";
	}
	
	
	
	/**
	 * Applies the Action to the target.
	 *
	 * @param SugarBean $target
	 * A NoOP on the PHP side for setoptions
	 */
	function fire(&$target) {
		
	}
	
	static function getActionName() {
		return "SetOptions";
	}
}
