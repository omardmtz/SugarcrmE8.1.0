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


class SugarFieldFloat extends SugarFieldInt 
{
    public function formatField($rawField, $vardef){
        // A null precision uses the user prefs / system prefs by default
        $precision = null;
        if ( isset($vardef['precision']) ) {
            $precision = $vardef['precision'];
        }
        
        if ( $rawField === '' || $rawField === NULL ) {
            return '';
        }

        return format_number($rawField,$precision,$precision);
    }

    /**
     * {@inheritDoc}
     */
    public function apiFormatField(
        array &$data,
        SugarBean $bean,
        array $args,
        $fieldName,
        $properties,
        array $fieldList = null,
        ServiceBase $service = null
    ) {
        $this->ensureApiFormatFieldArguments($fieldList, $service);

        $data[$fieldName] = isset($bean->$fieldName) && is_numeric($bean->$fieldName)
                            ? (float)$bean->$fieldName : null;
    }

    public function unformatField($formattedField, $vardef){
        if ( $formattedField === '' || $formattedField === NULL ) {
            return '';
        }
        if (is_array($formattedField)) {
            $formattedField = array_shift($formattedField);
        }
        return (float)unformat_number($formattedField);
    }

    /**
     * @see SugarFieldBase::importSanitize()
     */
    public function importSanitize(
        $value,
        $vardef,
        $focus,
        ImportFieldSanitize $settings
        )
    {
        $value = str_replace($settings->num_grp_sep,"",$value);
        $dec_sep = $settings->dec_sep;
        if ( $dec_sep != '.' ) {
            $value = str_replace($dec_sep,".",$value);
        }
        if ( !is_numeric($value) ) {
            return false;
        }
        
        return $value;
    }

    /**
     * For Floats we need to round down to the precision of the passed in value, since the db's could be showing
     * something different
     *
     * {@inheritdoc}
     */
    public function fixForFilter(
        &$value,
        $columnName,
        SugarBean $bean,
        SugarQuery $q,
        SugarQuery_Builder_Where $where,
        $op
    ) {
        // if we have an array, pull the first value
        if (is_array($value)) {
            $v = $value[1];
        } else {
            $v = $value;
        }

        $decimal_separator_location = substr(strrchr($v, '.'), 1);
        // if we don't have a decimal, just use the normal methods back up the chain
        // since it's a whole number that is being searched on
        if ($decimal_separator_location === false) {
            return true;
        }
        // ROUND(<value>, <precision>) is the standard across all DB's we support
        $field = "ROUND($columnName, ". strlen($decimal_separator_location) . ")";

        switch($op){
            case '$equals':
                $q->whereRaw("$field = $value");
                return false;
            case '$not_equals':
                $q->whereRaw("$field != $value");
                return false;
            case '$between':
                if (!is_array($value) || count($value) != 2) {
                    throw new SugarApiExceptionInvalidParameter(
                        '$between requires an array with two values.'
                    );
                }
                $q->whereRaw("$field BETWEEN $value[0] AND $value[1]");
                return false;
            case '$lt':
                $q->whereRaw("$field < $value");
                return false;
            case '$lte':
                $q->whereRaw("$field <= $value");
                return false;
            case '$gt':
                $q->whereRaw("$field > $value");
                return false;
            case '$gte':
                $q->whereRaw("$field >= $value");
                return false;
        }

        return true;
    }

    /**
     * Currently not supported.
     * {@inheritDoc}
     */
    public function apiValidate(SugarBean $bean, array $params, $field, $properties)
    {
        return true;
    }

    /**
     * Currently not supported.
     * {@inheritDoc}
     */
    protected function getFieldRange($vardef)
    {
        return false;
    }
}
