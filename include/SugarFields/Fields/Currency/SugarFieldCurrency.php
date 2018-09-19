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


class SugarFieldCurrency extends SugarFieldFloat 
{
    function getListViewSmarty($parentFieldArray, $vardef, $displayParams, $col) {
        global $current_user;
        $tabindex = 1;
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex, false);

        $baseCurrency = SugarCurrency::getBaseCurrency();
        $amount = $parentFieldArray[strtoupper($vardef['name'])];
        $currencyId = !empty($parentFieldArray['CURRENCY_ID']) ?
            $parentFieldArray['CURRENCY_ID']
            : $baseCurrency->id;
        $currencySymbol = !empty($parentFieldArray['CURRENCY_SYMBOL']) ?
            $parentFieldArray['CURRENCY_SYMBOL']
            : SugarCurrency::getCurrencyByID($currencyId)->symbol;

        if (empty($currencyId) || !empty($vardef['is_base_currency'])) {
            // this is a base USDOLLAR field
            if ($current_user->getPreference('currency_show_preferred')) {
                // display base amount in user preferred currency
                $userCurrency = SugarCurrency::getUserLocaleCurrency();
                $currencyId = $userCurrency->id;
                $currencySymbol = $userCurrency->symbol;
                if (!empty($parentFieldArray['BASE_RATE']) && $parentFieldArray['BASE_RATE'] <> 1) {
                    $amount = SugarCurrency::convertWithRate($amount, 1.0, $parentFieldArray['BASE_RATE']);
                } else {
                    $amount = SugarCurrency::convertWithRate($amount, 1.0, $userCurrency->conversion_rate);
                }
            } else {
                // display in base currency
                $currencyId = $baseCurrency->id;
                $currencySymbol = $baseCurrency->symbol;
            }
        }

        $this->ss->assign('currency_id', $currencyId);
        $this->ss->assign('currency_symbol', $currencySymbol);
        $this->ss->assign('amount', $amount);

        return $this->fetch($this->findTemplate('ListView'));
    }
    
    /**
     * @see SugarFieldBase::importSanitize()
     */
    public function importSanitize($value, $vardef, $focus, ImportFieldSanitize $settings)
    {
        /** @var Currency $base_currency */
        $base_currency = SugarCurrency::getBaseCurrency();
        $currency_id = $settings->currency_id;

        // Remove the grouping separator
        $value = str_replace($settings->num_grp_sep, '', $value);

        // change the decimal separator to a . if it's not already one
        if ($settings->dec_sep != '.') {
            $value = str_replace($settings->dec_sep, '.', $value);
        }

        if (isset($vardef['convertToBase']) && $vardef['convertToBase']) {
            // convert amount from base
            $value = str_replace($base_currency->symbol, '', $value);
            try {
                $value = SugarCurrency::convertAmountFromBase($value, $settings->currency_id);
            } catch (SugarMath_Exception $sme) {
                $GLOBALS['log']->error('Currency Field Import Error: ' . $sme->getMessage());
                return false;
            }
        } elseif (isset($vardef['is_base_currency']) && $vardef['is_base_currency']) {
            $value = str_replace($base_currency->symbol, '', $value);
            $currency_id = $base_currency->id;
        } else {
            $value = str_replace($settings->currency_symbol, '', $value);
        }

        // last check, if for some reason we get here and the value is not numeric, we should just fail out.
        if (!is_numeric($value)) {
            return false;
        }

        return SugarCurrency::formatAmount($value, $currency_id, 6, '.', '', false);
    }

    /**
     * Handles export field sanitizing for field type
     *
     * @param $value string value to be sanitized
     * @param $vardef array representing the vardef definition
     * @param $focus SugarBean object
     * @param $row Array of a row of data to be exported
     *
     * @return string sanitized value
     */
    public function exportSanitize($value, $vardef, $focus, $row = array())
    {
        // If $value is null, default to zero to prevent conversion errors.
        $value = is_null($value) ? 0 : $value;

        if (isset($vardef['convertToBase']) && $vardef['convertToBase']) {
            // convert amount to base
            $baseRate = isset($row['base_rate']) ? $row['base_rate'] : $focus->base_rate;
            $value = SugarCurrency::convertWithRate($value, $baseRate);
            $currency_id = '-99';
        } elseif (isset($vardef['is_base_currency']) && $vardef['is_base_currency']) {
            $currency_id = '-99';
        } else {
            //If the row has a currency_id set, use that instead of the $focus->currency_id value
            $currency_id = isset($row['currency_id']) ? $row['currency_id'] : $focus->currency_id;
        }
        return SugarCurrency::formatAmountUserLocale($value, $currency_id);
    }

    /**
	 * format the currency field based on system locale values for currency
     * Note that this may be different from the precision specified in the vardefs.
	 * @param string $rawfield value of the field
     * @param string $somewhere vardef for the field being processed
	 * @return number formatted according to currency settings
	 */
    public function formatField($rawField, $vardef){
        // for currency fields, use the user or system precision, not the precision in the vardef
        //this is achived by passing in $precision as null
        $precision = null;

        if ( $rawField === '' || $rawField === NULL ) {
            return '';
        }
        return format_number($rawField,$precision,$precision);
    }

    /**
     * BWC modules always unformat server-side
     *
     * @param string $formattedField
     * @param array $vardef
     * @return null|string
     */
    public function unformatField($formattedField, $vardef)
    {
        if ($formattedField === '' || $formattedField === null) {
            return '';
        }
        if (is_array($formattedField)) {
            $formattedField = array_shift($formattedField);
        }
        return (string)unformat_number($formattedField);
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
        if (isset($bean->$fieldName) && $bean->$fieldName !== 'NULL') {
            $data[$fieldName] = $bean->$fieldName;
        } else {
            $data[$fieldName] = '';
        }
    }

    /**
     * Since SugarFieldFloat override this method, we need to do the same here and always return true as we want
     * to use the default processing
     *
     * {@inheritdoc}
     */
    public function fixForFilter(&$value, $columnName, SugarBean $bean, SugarQuery $q, SugarQuery_Builder_Where $where, $op)
    {
        return true;
    }
}
