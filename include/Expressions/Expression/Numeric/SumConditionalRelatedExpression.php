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
 * <b>rollupConditionalSum(Relate <i>link</i>, String <i>field</i>, String <i>conditionField</i>, List <i>conditionalValues</i>)</b><br>
 * Returns the sum of the values of <i>field</i> in records related by <i>link</i> where <i>conditionField</i> contains something from <i>conditionalValues</i> <br/>
 * ex: <i>rollupConditionalSum($products, "amount", "tax_cass", "Taxable")</i> in ProductBundles would return the <br/>
 * sum of the <i>amount</i> field where <i>tax_class</i> is equal to <i>Taxable</i>
 */
class SumConditionalRelatedExpression extends NumericExpression
{
    /**
     * Ability only rollup specific values from related records when a field on the related record is equal to
     * something.
     *
     * @return string
     */
    public function evaluate()
    {
        $params = $this->getParameters();
        // This should be of relate type, which means an array of SugarBean objects
        $linkField = $params[0]->evaluate();
        $relfield = $params[1]->evaluate();

        $conditionalField = $params[2]->evaluate();
        $conditionalValues = $params[3]->evaluate();

        if (!is_array($conditionalValues)) {
            $conditionalValues = array($conditionalValues);
        }

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

        foreach ($linkField as $bean) {
            if (!in_array($bean->$conditionalField, $conditionalValues)) {
                continue;
            }
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
                $ret = SugarMath::init($ret)->add($value)->result();
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
            rel_field = params[1].evaluate(),
            condition_field = params[2].evaluate(),

            //_.contains expects this to be an array, so convert it if it isn't already.
            condition_values = params[3].evaluate();
            if (!_.isArray(condition_values)) {
                condition_values = [condition_values];
            }

        var model = this.context.relatedModel || App.data.createRelatedBean(this.context.model, null, relationship);
        var precision = model.fields[rel_field].precision || 6;
        // has the model been removed from it's collection
        var hasModelBeenRemoved = this.context.isRemoveEvent || false;
        // did the condition field change at some point?
        var conditionChanged = _.has(model.changed, condition_field);
        
        var isCurrency = (model.fields[rel_field].type === 'currency');
        var current_value = this.context.getRelatedField(relationship, 'rollupConditionalSum', rel_field) || '0';
        var related_collection = this.context.model.getRelatedCollection(relationship);
        var rollup_value = '0';
        
        if (!_.isUndefined(this.context.relatedModel)) {
            this.context.updateRelatedCollectionValues(
                this.context.model,
                relationship,
                'rollupConditionalSum',
                rel_field,
                model,
                (hasModelBeenRemoved)? 'remove' : 'add'
            );
        }

        var all_values = this.context.getRelatedCollectionValues(this.context.model, relationship, 'rollupConditionalSum', rel_field) || {};

        if (_.size(all_values) > 0) {
            rollup_value = _.reduce(all_values, function(memo, number, key) {
                // Check the condition against the live model value
                // or assume the model is valid if the server included it.
                var rel_model = related_collection.get(key);
                if (!rel_model || _.contains(condition_values, rel_model.get(condition_field))) {
                    return App.math.add(memo, number, precision, true);
                }

                return memo;
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
                'rollupConditionalSum',
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
        return array("rollupConditionalSum");
    }

    /**
     * The first parameter is a number and the second is the list.
     */
    public static function getParameterTypes()
    {
        return array(
            AbstractExpression::$RELATE_TYPE,
            AbstractExpression::$STRING_TYPE,
            AbstractExpression::$STRING_TYPE,
            AbstractExpression::$GENERIC_TYPE
        );
    }

    /**
     * Returns the maximum number of parameters needed.
     */
    public static function getParamCount()
    {
        return 4;
    }
}
