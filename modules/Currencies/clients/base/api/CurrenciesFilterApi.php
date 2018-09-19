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

require_once 'data/BeanFactory.php';
require_once 'clients/base/api/FilterApi.php' ;
class CurrenciesFilterApi extends FilterApi
{
    public function registerApiRest()
    {
        return array(
            'currenciesGet' => array(
                'reqType' => 'GET',
                'path' => array('Currencies'),
                'pathVars' => array('module'),
                'method' => 'currenciesGet',
                'jsonParams' => array(),
                'shortHelp' => 'Filter records from a single module',
                'longHelp' => 'modules/Currencies/clients/base/api/help/CurrenciesGet.html',
            ),
        );
    }

    /**
     * Currencies API Handler
     *
     * @param ServiceBase $api
     * @param array $args
     * @return array
     */
    public function currenciesGet(ServiceBase $api, array $args)
    {
        // get the currencies from the base parent API class
        $currencies = parent::filterList($api, $args);

        // get the default currency
        $defaultCurrency = BeanFactory::getBean('Currencies', -99);
        $defaultCurrencyResult = $this->formatBean($api, $args, $defaultCurrency);

        // add system default to the top
        array_unshift($currencies['records'], $defaultCurrencyResult);

        return $currencies;
    }
}
