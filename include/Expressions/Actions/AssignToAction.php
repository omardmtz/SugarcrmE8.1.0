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

class AssignToAction extends AbstractAction
{
    protected $expression = '';

    /**
     * array Array of actions on which the Expression Action is not allowed
     */
    protected $disallowedActions = array('view');

    public function __construct($params)
    {
        $this->expression = str_replace("\n", '', $params['value']);
    }

    /**
     * Returns the javascript class equavalent to this php class
     *
     * @return string javascript.
     */
    public static function getJavascriptClass()
    {
        return "
        SUGAR.forms.AssignToAction = function(expr) {
            if (_.isObject(expr)) {
                expr = expr.value;
            }
            this.expr = expr;
            this.target = 'assigned_user_name';
            if (_.isUndefined(SUGAR.App)) {
                // Initialize data source only for BWC
                this.dataSource = new YAHOO.util.DataSource('index.php?', {
                    responseType: YAHOO.util.XHRDataSource.TYPE_JSON,
                    responseSchema: {
                        resultsList: 'fields',
                        total: 'totalCount',
                        metaNode: 'fields',
                        metaFields: {total: 'totalCount', fields:'fields'}
                    },
                    connMethodPost: true
                });
            }
        };
        SUGAR.util.extend(SUGAR.forms.AssignToAction, SUGAR.forms.AbstractAction, {
            exec: function(context) {
                if (typeof(context) == 'undefined') {
                    context = this.context;
                }

                this.userName = this.evalExpression(this.expr, context);
                if (context.view) {
                    //We may get triggered before the view has rendered with the full field list.
                    //If that occurs wait for the next render to apply.
                    if (_.isEmpty(context.view.fields)) {
                        context.view.once('render', function(){this.exec(context);}, this);
                        return;
                    }
                    context.setAssignedUserName(this.target, this.userName);
                } else {
                    this.bwcExec(context);
                }
            },
            bwcExec: function(context) {
                if (typeof(context) == 'undefined') {
                    context = this.context;
                }

                var params = SUGAR.util.paramsToUrl({
                    to_pdf: 'true',
                    module: 'Home',
                    action: 'quicksearchQuery',
                    data: YAHOO.lang.JSON.stringify(sqs_objects['EditView_' + this.target]),
                    query: this.userName
                });

                this.sqs = sqs_objects['EditView_' + this.target];
                this.dataSource.sendRequest(params, {
                    success: function(param, resp) {
                        if(resp.results.length > 0) {
                            var match = resp.results[0];
                            for (var i = 0; i < this.sqs.field_list.length; i++) {
                                SUGAR.forms.AssignmentHandler.assign(
                                    this.sqs.populate_list[i],
                                    match[this.sqs.field_list[i]]
                                );
                            }
                        }
                    },
                    scope: this
                });
            },
            targetUrl: 'index.php?module=Home&action=TaxRate&to_pdf=1'
        });";
    }

    /**
     * Returns the javascript code to generate this actions equivalent.
     *
     * @return string javascript.
     */
    public function getJavascriptFire()
    {
        return "new SUGAR.forms.AssignToAction('{$this->expression}')";
    }

    /**
     * Applies the Action to the target.
     *
     * @param SugarBean $target
     */
    public function fire(&$target)
    {
        // No-op for sidecar
        if (isModuleBWC($target)) {
            require_once 'modules/Home/quicksearchQuery.php';
            $json = getJSONobj();
            $userName = Parser::evaluate($this->expression, $target)->evaluate();
            $qsd = QuickSearchDefaults::getQuickSearchDefaults();
            $data = $qsd->getQSUser();
            $data['modules'] = array('Users');
            $data['conditions'][0]['value'] = $userName;
            $qs = new QuickSearchQuery();
            $result = $qs->query($data);
            $resultBean = $json->decodeReal($result);
            print_r($resultBean);
        }
    }

    /**
     * Returns the definition of this action in array format.
     *
     */
    public function getDefinition()
    {
        return array(
            'action' => $this->getActionName(),
            'params' => array(
                'value' => $this->expression,
            )
        );
    }

    public static function getActionName()
    {
        return 'AssignTo';
    }
}
