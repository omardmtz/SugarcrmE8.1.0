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
 * <b>rollupMin(Relate <i>link</i>, String <i>field</i>)</b><br>
 * Returns the lowest value of <i>field</i> in records related by <i>link</i><br/>
 * ex: <i>rollupMin($opportunities, "amount")</i> in Accounts would return the <br/>
 * lowest amount of any Opportunity related to this Account.
 */
class MinRelatedExpression extends NumericExpression
{
    /**
     * Returns the entire enumeration bare.
     */
    public function evaluate()
    {
        $params = $this->getParameters();
        //This should be of relate type, which means an array of SugarBean objects
        $linkField = $params[0]->evaluate();
        $relfield = $params[1]->evaluate();

        if (!is_array($linkField) || empty($linkField)) {
            return 0;
        }

        $ret = false;

        if (!isset($this->context)) {
            //If we don't have a context provided, we have to guess. This can be a large performance hit.
            $this->setContext();
        }
        $toRate = isset($this->context->base_rate) ? $this->context->base_rate : null;
        $checkedTypeForCurrency = false;
        $relFieldIsCurrency = false;

        foreach ($linkField as $bean) {
            // only check the target field once to see if it's a currency field.
            if ($checkedTypeForCurrency === false) {
                $checkedTypeForCurrency = true;
                $relFieldIsCurrency = $this->isCurrencyField($bean, $relfield);
            }
            if (!empty($bean->$relfield)) {
                $value = $bean->$relfield;
                // if we have a currency field, it needs to convert the value into the rate of the row it's
                // being returned to.
                if ($relFieldIsCurrency) {
                    $value = SugarCurrency::convertWithRate($value, $bean->base_rate, $toRate);
                }
                if ($ret === false || $ret > $value) {
                    $ret = $value;
                }
            }
        }

        return $ret;
    }

    /**
     * Returns the JS Equivalent of the evaluate function.
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
            rel_field = params[1].evaluate();
        var model = this.context.relatedModel || App.data.createRelatedBean(this.context.model, null, relationship),
            // has the model been removed from it's collection
            isCurrency = (model.fields[rel_field].type === 'currency'),
            sortByDesc = function (lhs, rhs) { return parseFloat(lhs) > parseFloat(rhs) ? 1 : parseFloat(lhs) < parseFloat(rhs) ? -1 : 0; },
            hasModelBeenRemoved = this.context.isRemoveEvent || false,
            current_value = this.context.getRelatedField(relationship, 'rollupMin', rel_field) || '',
            finite_values = {},
            rollup_value = '0';

        if (!_.isUndefined(this.context.relatedModel)) {
            this.context.updateRelatedCollectionValues(
                this.context.model,
                relationship,
                'rollupMin',
                rel_field,
                model,
                (hasModelBeenRemoved ? 'remove' : 'add')
            );
        }
        var all_values = this.context.getRelatedCollectionValues(this.context.model, relationship, 'rollupMin', rel_field) || {};

        if (_.size(all_values) > 0) {
            finite_values = _.map(_.values(all_values), _.partial(parseInt, _, 10));
            finite_values = _.filter(finite_values, _.isFinite);

            // get all the values and sort them so the highest is on top
            rollup_value = finite_values.sort(sortByDesc)[0];

            if (isCurrency) {
                rollup_value = App.currency.convertFromBase(
                    rollup_value,
                    this.context.model.get('currency_id')
                );
            }
        }

        if (!_.isEqual(rollup_value, current_value)) {
            this.context.model.set(target, rollup_value);
            this.context.updateRelatedFieldValue(
                relationship,
                'rollupMin',
                rel_field,
                rollup_value,
                this.context.model.isNew()
            );
        }

        return rollup_value;
JS;
    }

    /**
     * Returns the operation name that this Expression should be
     * called by.
     */
    public static function getOperationName()
    {
        return array("rollupMin");
    }

    /**
     * The first parameter is a number and the second is the list.
     */
    public static function getParameterTypes()
    {
        return array(AbstractExpression::$RELATE_TYPE, AbstractExpression::$STRING_TYPE);
    }

    /**
     * Returns the maximum number of parameters needed.
     */
    public static function getParamCount()
    {
        return 2;
    }

    /**
     * Returns the String representation of this Expression.
     */
    public function toString()
    {
    }
}
