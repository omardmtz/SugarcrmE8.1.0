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
 * <b>maxRelatedDate(Relate <i>link</i>, String <i>field</i>)</b><br>
 * Returns the highest value of <i>field</i> in records related by <i>link</i><br/>
 * ex: <i>maxRelatedDate($products, "date_closed_timestamp")</i> in Opportunities would return the <br/>
 * latest date_closed_timestamp of all related Revenue Line Items.
 */
class MaxRelatedDateExpression extends DateExpression
{

    /**
     * Returns the opreation name that this Expression should be
     * called by.
     */
    public static function getOperationName()
    {
        return array("maxRelatedDate");
    }

    public function evaluate()
    {
        $params = $this->getParameters();
        //This should be of relate type, which means an array of SugarBean objects
        $linkField = $params[0]->evaluate();
        $relfield = $params[1]->evaluate();
        $ret = 0;
        $isTimestamp = true;

        //if the field or relationship isn't defined, bail
        if (!is_array($linkField) || empty($linkField)) {
            return '';
        }

        foreach ($linkField as $bean) {
            // we have to use the fetched_row as it's still in db format
            // where as the $bean->$relfield is formatted into the users format.
            if (isset($bean->fetched_row[$relfield])) {
                $value = $bean->fetched_row[$relfield];
            } elseif (isset($bean->$relfield)) {
                if (is_int($bean->$relfield)) {
                    // if we have a timestamp field, just set the value
                    $value = $bean->relfield;
                } else {
                    // more than likely this is a date field, so try and un-format based on the users preferences
                    $td = TimeDate::getInstance();
                    // we pass false to asDbDate as we want the value that would be stored in the DB
                    $value = $td->fromString($bean->$relfield)->asDbDate(false);
                }
            } else {
                continue;
            }

            //if it isn't a timestamp, mark the flag as such and convert it for comparison
            if (!is_int($value)) {
                $isTimestamp = false;
                $value = strtotime($value);
            }

            //compare
            if ($ret < $value) {
                $ret = $value;
            }
        }

        //if nothing was done, return an empty string
        if ($ret == 0 && $isTimestamp) {
            return '';
        }

        //return the timestamp if the field started off that way
        if ($isTimestamp) {
            return $ret;
        }

        //convert the timestamp to a date and return
        $date = new DateTime();
        $date->setTimestamp($ret);

        return $date->format("Y-m-d");
    }

    //todo: javascript version here
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
            model_id = model.id || model.cid,
            // has the model been removed from it's collection
            sortByDateDesc = function (lhs, rhs) { return lhs < rhs ? 1 : lhs > rhs ? -1 : 0; },
            hasModelBeenRemoved = this.context.isRemoveEvent || false,
            current_value = this.context.getRelatedField(relationship, 'maxRelatedDate', rel_field) || '',
            new_value = model.get(rel_field) || '',
            dates = [],
            rollup_value = '';

        if (!_.isUndefined(this.context.relatedModel)) {
            this.context.updateRelatedCollectionValues(
                this.context.model,
                relationship,
                'maxRelatedDate',
                rel_field,
                model,
                (hasModelBeenRemoved ? 'remove' : 'add')
            );
        }

        var all_values = this.context.getRelatedCollectionValues(this.context.model, relationship, 'maxRelatedDate', rel_field) || {};

        if (_.isEqual(new_value, '')) {
            return current_value;
        }

        _.each(all_values, function(_date) {
            dates.push(new App.date(_date));
        });

        // now the furthest out is on top
        rollup_value = dates.sort(sortByDateDesc)[0].format('YYYY-MM-DD');

        if (!_.isEqual(rollup_value, current_value)) {
            this.context.model.set(target, rollup_value);
            this.context.updateRelatedFieldValue(
                relationship,
                'maxRelatedDate',
                rel_field,
                rollup_value,
                this.context.model.isNew()
            );
        }
JS;

    }

    /**
     * The first parameter is a link and the second is a string.
     */
    public static function getParameterTypes()
    {
        return array(AbstractExpression::$RELATE_TYPE, AbstractExpression::$STRING_TYPE);
    }
}
