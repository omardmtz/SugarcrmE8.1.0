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
 * SugarCurrency
 *
 * A class for manipulating currencies and currency amounts
 *
 */
class SugarCurrency
{

    /**
     * get a currency object
     *
     * @access protected
     * @param  string $currencyId Optional if empty, base currency is returned
     * @return object   currency object
     */
    protected static function _getCurrency( $currencyId = null ) {
        if(empty($currencyId)) {
            $currencyId = '-99';
        }
        $currency = BeanFactory::getBean('Currencies', $currencyId);
        //$currency->retrieve($currencyId);
        return $currency;
    }

    /**
     * convert a currency from one to another
     *
     * @access public
     * @param  float  $amount
     * @param  string $fromId source currency_id
     * @param  string $toId target currency_id
     * @param  int    $precision Optional decimal precision
     * @return float   converted amount
     */
    public static function convertAmount( $amount, $fromId, $toId, $precision = 6 ) {
        if($fromId == $toId) {
            return $amount;
        }
        $currency1 = self::_getCurrency($fromId);
        $currency2 = self::_getCurrency($toId);
        // if either conversion_rate is 0 or not defined, we just return the amount
        if(empty($currency1->conversion_rate) || empty($currency2->conversion_rate)) {
            return $amount;
        }
        // NOTE: database defines precision to 6 by default
        return self::convertWithRate($amount, $currency1->conversion_rate, $currency2->conversion_rate, $precision);
    }

    /**
     * convenience function: convert a currency to base currency
     *
     * @access public
     * @param  float  $amount
     * @param  string $fromId source currency_id
     * @param  int    $precision Optional decimal precision
     * @return float   converted amount
     */
    public static function convertAmountToBase( $amount, $fromId, $precision = 6 ) {
        return self::convertAmount($amount, $fromId, '-99', $precision);
    }

    /**
     * convenience function: convert a currency from base currency
     *
     * @access public
     * @param  float  $amount
     * @param  string $toId source currency_id
     * @param  int    $precision Optional decimal precision
     * @return float   converted amount
     */
    public static function convertAmountFromBase( $amount, $toId, $precision = 6 ) {
        return self::convertAmount($amount, '-99', $toId, $precision);
    }

    /**
     * convert a currency with a given rate
     *
     * @access public
     * @param  float  $amount
     * @param  float  $fromRate rate to convert from (default base rate)
     * @param  float  $toRate rate to convert to (default base rate)
     * @param  int    $precision Optional decimal precision
     * @return float   converted amount
     */
    public static function convertWithRate( $amount, $fromRate = 1.0, $toRate = 1.0, $precision = 6 ) {
        // if rate is 0 or null, just return the amount
        if(empty($fromRate) || empty($toRate)) {
            return $amount;
        }

        // handle the use case for when a currency field get added to a module, where no default is set
        // and if the $amount is passed in as a string but $amount is an empty string or $amount is null,
        // we should default then we should return as the conversion is not needed
        if ((is_string($amount) && $amount === '') || is_null($amount)) {
            return '0';
        }

        return SugarMath::init(0, $precision)->exp('?/?*?',array($amount,$fromRate,$toRate))->result();
    }

    /**
     * format a currency amount with symbol and defined formatting
     *
     * @access public
     * @param  float  $amount
     * @param  string $currencyId
     * @param  int    $decimalPrecision Optional the number of decimal places to use
     * @param  string $decimalSeparator Optional the string to use as decimal separator
     * @param  string $numberGroupingSeparator Optional the string to use for thousands separator
     * @param  bool   $showSymbol Optional show symbol along with currency default true
     * @param  string $symbolSeparator Optional string between symbol and amount
     * @return string  formatted amount
     */
    public static function formatAmount(
        $amount,
        $currencyId,
        $decimalPrecision = 2,
        $decimalSeparator = '.',
        $numberGroupingSeparator = ',',
        $showSymbol = true,
        $symbolSeparator = ''
    ) {
        if (!is_numeric($amount)) {
            $GLOBALS['log']->warn('Trying to format a non-numeric amount: ' . $amount);
            return $amount;
        }

        $formatted = number_format($amount, $decimalPrecision, $decimalSeparator, $numberGroupingSeparator);

        if ($showSymbol) {
            $currency = self::_getCurrency($currencyId);
            $formatted = $currency->symbol . $symbolSeparator . $formatted;
        }

        return $formatted;
    }

    /**
     * format a currency amount with symbol and user defined formatting
     *
     * @access public
     * @param  float  $amount
     * @param  string $currencyId
     * @param  bool   $showSymbol Optional show symbol along with currency default true
     * @param  string $symbolSeparator Optional string between symbol and amount
     * @return string  formatted amount
     */
    public static function formatAmountUserLocale(
        $amount,
        $currencyId,
        $showSymbol=true,
        $symbolSeparator = ''
    ) {
        global $locale;
        // get user defined preferences
        $decimalPrecision = $locale->getPrecision();
        $decimalSeparator = $locale->getDecimalSeparator();
        $numberGroupingSeparator = $locale->getNumberGroupingSeparator();

        return self::formatAmount($amount, $currencyId, $decimalPrecision, $decimalSeparator, $numberGroupingSeparator, $showSymbol, $symbolSeparator);
    }

    /**
     * get system base currency object
     *
     * @access public
     * @return object  currency object
     */
    public static function getBaseCurrency( ) {
        // the base currency has a hard-coded currency id of -99
        return self::_getCurrency('-99');
    }

    /**
     * get a currency object by currency id
     *
     * @access public
     * @param  string $currencyId
     * @return Currency  currency object
     */
    public static function getCurrencyByID($currencyId = null)
    {
        return self::_getCurrency($currencyId);
    }

    /**
     * get a currency object by ISO
     *
     * @access public
     * @param  string $ISO ISO4217 value
     * @return object  currency object
     */
    public static function getCurrencyByISO( $ISO ) {
        $currency = self::_getCurrency('-99');
        $currencyId = $currency->retrieveIDByISO($ISO);
        $currency = self::_getCurrency($currencyId);
        return $currency;
    }

    /**
     * get a currency object by currency symbol
     *
     * @access public
     * @param  string $symbol currency symbol
     * @return object  currency object
     */
    public static function getCurrencyBySymbol( $symbol ) {
        $currency = self::_getCurrency('-99');
        $currencyId = $currency->retrieveIDBySymbol($symbol);
        $currency = self::_getCurrency($currencyId);
        return $currency;
    }

    /**
     * Get a currency object by user preferences.  If no user is supplied, attempt to use the global $current_user.
     * If global $current_user object is empty then use default system currency id (-99).
     *
     * @access public
     * @param  object $user Optional the user object
     * @return Currency  currency object
     */
    public static function getUserLocaleCurrency($user = null)
    {
        if (empty($user)) {
            global $current_user;
            $user = $current_user;
        }

        $currencyId = empty($user) ? '-99' : $user->getPreference('currency');
        return self::_getCurrency($currencyId);
    }

    /**
     * Verify that the currency_base_rate is set and updated as it should when the bean is saving
     *
     * @see SugarBean::save()
     * @param SugarBean $bean
     * @param bool @isUpdate This is bean in an update save()?
     */
    public static function verifyCurrencyBaseRateSet(SugarBean $bean, $isUpdate = true)
    {
        if ($bean->getFieldDefinition('currency_id')
            && $bean->getFieldDefinition('base_rate')
            && !empty($bean->currency_id)
        ) {
            $currency = static::getCurrency($bean);

            // check if the bean has the updateCurrencyBaseRate method as an additional check
            $beanCurrencyCheck = true;
            if (method_exists($bean, 'updateCurrencyBaseRate')) {
                $beanCurrencyCheck = call_user_func(array($bean, 'updateCurrencyBaseRate'));
            }

            // we should only check if the currencyId has changed if we the be is doing an update, not an insert
            if ($beanCurrencyCheck || !isset($bean->base_rate) || (static::hasCurrencyIdChanged($bean) && $isUpdate)) {
                $bean->base_rate = $currency->conversion_rate;
            }
        }

    }

    /**
     * Get the Currency object for the bean, if one is not set, set it to the users preferd currency
     *
     * @param SugarBean $bean
     * @return Currency
     */
    public static function getCurrency(SugarBean $bean)
    {
        if (empty($bean->currency_id)) {
            // use user preferences for currency
            $currency = static::getUserLocaleCurrency();
            $bean->currency_id = $currency->id;
        } else {
            $currency = static::getCurrencyByID($bean->currency_id);
        }

        return $currency;
    }

    /**
     * To check whether currency_id field is changed during save.
     *
     * @param SugarBean $bean
     * @return bool true if currency_id is changed, false otherwise
     */
    protected static function hasCurrencyIdChanged($bean)
    {


        // if both are defined, compare
        if (isset($bean->currency_id) && isset($bean->fetched_row['currency_id'])) {
            if ($bean->currency_id != $bean->fetched_row['currency_id']) {
                return true;
            }
        }
        // one is not defined, the other one is not empty, means changed
        if (!isset($bean->currency_id) && !empty($bean->fetched_row['currency_id'])) {
            return true;
        }
        if (!isset($bean->fetched_row['currency_id']) && !empty($bean->currency_id)) {
            return true;
        }

        return false;
    }
}
