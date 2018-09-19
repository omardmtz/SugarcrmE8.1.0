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
 * <b>countConditional(Relate <i>link</i>, Field <i>string</i>, Values <i>list</i>)</b><br>
 * Returns the number of records related to this record by <i>link</i> and that matches the values for a specific field
 * ex: <i>count($contacts, 'first_name', array('Joe'))</i> in Accounts would return the <br/>
 * number of contacts related to this account with the first name of 'Joe'
 */
class CountConditionalRelatedExpression extends NumericExpression
{
    /**
     * Returns the entire enumeration bare.
     */
    public function evaluate()
    {
        $params = $this->getParameters();
        //This should be of relate type, which means an array of SugarBean objects
        $linkField = $params[0]->evaluate();
        $field = $params[1]->evaluate();
        $values = $params[2]->evaluate();
        //This should be of relate type, which means an array of SugarBean objects
        if (!is_array($linkField)) {
            return false;
        }

        if (!is_array($values)) {
            $values = array($values);
        }

        $count = 0;
        foreach ($linkField as $link) {
            if (in_array($link->$field, $values)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Returns the JS Equivalent of the evaluate function.
     *
     * Currently there is no JS Equivalent as this is a server side only method
     */
    public static function getJSEvaluate()
    {
        return <<<JS

        // this is only supported in Sidecar
        if (App === undefined) {
            return SUGAR.expressions.Expression.FALSE;
        }
        
        var params = this.params,
            view = this.context.view,
            target = this.context.target,
            relationship = params[0].evaluate(),
            condition_field = params[1].evaluate(),
            condition_values = params[2].evaluate();

        //_.contains expects this to be an array, so convert it if it isn't already.
        if (!_.isArray(condition_values)) {
            condition_values = [condition_values];
        }

        var model = this.context.relatedModel || App.data.createRelatedBean(this.context.model, null, relationship);
        // has the model been removed from it's collection
        var hasModelBeenRemoved = this.context.isRemoveEvent || false;

        if (!_.isUndefined(this.context.relatedModel)) {
            this.context.updateRelatedCollectionValues(
                this.context.model,
                relationship,
                'countConditional',
                target,
                model,
                (hasModelBeenRemoved) ? 'remove' : 'add'
            );
        }

        // get the updated values array/object and get the size of it, which will be the correct count
        var rollup_value = _.size(this.context.getRelatedCollectionValues(this.context.model, relationship, 'countConditional', target));

        // rollup_value won't exist if we didn't do any math, so just ignore this
        if (!_.isUndefined(rollup_value) && _.isFinite(rollup_value)) {
            // update the model
            this.context.model.set(target, rollup_value);
            // update the relationship defs on the model
            this.context.updateRelatedFieldValue(
                relationship,
                'countConditional',
                target,
                rollup_value,
                this.context.model.isNew()
            );
        }
JS;
    }

    /**
     * Returns the operation name that this Expression should be
     * called by.
     */
    public static function getOperationName()
    {
        return array("countConditional");
    }

    /**
     * The first parameter is a number and the second is the list.
     */
    public static function getParameterTypes()
    {
        return array(
            AbstractExpression::$RELATE_TYPE,
            AbstractExpression::$STRING_TYPE,
            AbstractExpression::$GENERIC_TYPE
        );
    }

    /**
     * Returns the maximum number of parameters needed.
     */
    public static function getParamCount()
    {
        return 3;
    }

    /**
     * Returns the String representation of this Expression.
     */
    public function toString()
    {
    }
}
