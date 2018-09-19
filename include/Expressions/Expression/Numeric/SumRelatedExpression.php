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
 * <b>rollupSum(Relate <i>link</i>, String <i>field</i>)</b><br>
 * Returns the sum of the values of <i>field</i> in records related by <i>link</i><br/>
 * ex: <i>rollupSum($opportunities, "amount")</i> in Accounts would return the <br/>
 * sum of the amount of all the Opportunities related to this Account.
 */
class SumRelatedExpression extends NumericExpression
{
    /**
     * Finds any related records based on the `link` then takes the `field` and adds all the values up and returns
     * the sum.
     *
     * @return String
     */
    public function evaluate()
    {
        $params = $this->getParameters();
        //This should be of relate type, which means an array of SugarBean objects
        $linkField = $params[0]->evaluate();
        $relfield = $params[1]->evaluate();

        $ret = '0';

        if (!is_array($linkField) || empty($linkField)) {
            return $ret;
        }

        if (!isset($this->context)) {
            //If we don't have a context provided, we have to guess. This can be a large performance hit.
            $this->setContext();
        }
        $toRate = isset($this->context->base_rate) ? $this->context->base_rate : null;

        $checkedTypeForCurrency = false;
        $relFieldIsCurrency = false;

        $precision = 6;

        foreach ($linkField as $bean) {
            // only check the target field once to see if it's a currency field.
            if ($checkedTypeForCurrency === false) {
                $checkedTypeForCurrency = true;
                $relFieldIsCurrency = $this->isCurrencyField($bean, $relfield);
                if (!$relFieldIsCurrency) {
                    // only get the precision when we are not on a currency field, as currency should always be 6
                    $precision = $this->getFieldPrecision($bean, $relfield);
                }
            }
            if (!empty($bean->$relfield)) {
                $value = $bean->$relfield;
                // if we have a currency field, it needs to convert the value into the rate of the row it's
                // being returned to.
                if ($relFieldIsCurrency) {
                    $value = SugarCurrency::convertWithRate($value, $bean->base_rate, $toRate);
                }
                $ret = SugarMath::init($ret, $precision)->add($value)->result();
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
            precision = model.fields[rel_field].precision || 6,
            hasModelBeenRemoved = this.context.isRemoveEvent || false,
            current_value = this.context.getRelatedField(relationship, 'rollupSum', rel_field) || '',
            rollup_value = '0';

        if (!_.isUndefined(this.context.relatedModel)) {
            this.context.updateRelatedCollectionValues(
                this.context.model,
                relationship,
                'rollupSum',
                rel_field,
                model,
                (hasModelBeenRemoved ? 'remove' : 'add')
            );
        }

        var all_values = this.context.getRelatedCollectionValues(this.context.model, relationship, 'rollupSum', rel_field) || {};

        if (_.size(all_values) > 0) {
            rollup_value = _.reduce(all_values, function(memo, number) {
                return App.math.add(memo, number, precision, true);
            }, '0');

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
                'rollupSum',
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
        return array("rollupSum", "rollupCurrencySum");
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
