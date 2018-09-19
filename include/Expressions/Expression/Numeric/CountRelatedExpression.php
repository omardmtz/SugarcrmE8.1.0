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
 * <b>count(Relate <i>link</i>)</b><br>
 * Returns the number of records related to this record by <i>link</i><br/>
 * ex: <i>count($contacts)</i> in Accounts would return the <br/>
 * number of contacts related to this account.
 */
class CountRelatedExpression extends NumericExpression
{
    /**
     * Returns the entire enumeration bare.
     */
    public function evaluate()
    {
        $linkField = $this->getParameters()->evaluate();
        //This should be of relate type, which means an array of SugarBean objects
        if (!is_array($linkField)) {
            return false;
        }

        return count($linkField);
    }

    /**
     * Returns the JS Equivalent of the evaluate function.
     */
    public static function getJSEvaluate()
    {
        return <<<JS

        var linkField = this.getParameters().evaluate();
        // if App is undefined, then we should still use what was there since it works in BWC mode.
        if (App === undefined) {

            if (typeof(linkField) == 'string' && linkField != '') {
                return this.context.getRelatedField(linkField, 'count');
            }

            return '';
        }

        // just the the length of the collection for the given linkField
        var target = this.context.target,
            current_value = 0,
            relatedColl = this.context.model.getRelatedCollection(linkField);

        // if the model is new, it will never have the dataFetched flag set on it, so we should always use what
        // was returned by the call above.
        if (relatedColl.dataFetched || this.context.model.isNew()) {
            current_value = relatedColl.length;
        } else if (this.context.model.has(linkField)) {
            // check the case where related data still hasn't been loaded
            // but we made a call to ExpressionEngine earlier
            var link = this.context.model.get(linkField);
            if (link.count) {
                current_value = link.count;
            }
        }

        this.context.model.set(target, current_value);
        // update the relationship defs on the model
        this.context.updateRelatedFieldValue(
            linkField,
            'count',
            '',
            current_value,
            this.context.model.isNew()
        );
        return current_value;
JS;
    }

    /**
     * Returns the operation name that this Expression should be
     * called by.
     */
    public static function getOperationName()
    {
        return array("count");
    }

    /**
     * The first parameter is a number and the second is the list.
     */
    public static function getParameterTypes()
    {
        return array(AbstractExpression::$RELATE_TYPE);
    }

    /**
     * Returns the maximum number of parameters needed.
     */
    public static function getParamCount()
    {
        return 1;
    }

    /**
     * Returns the String representation of this Expression.
     */
    public function toString()
    {
    }
}
