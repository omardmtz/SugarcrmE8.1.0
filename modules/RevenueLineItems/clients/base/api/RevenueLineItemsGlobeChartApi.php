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
class RevenueLineItemsGlobeChartApi extends SugarApi
{
    /**
     * {@inheritdoc}
     */
    public function registerApiRest()
    {
        return array(
            'sales_by_country' => array(
                'reqType' => 'GET',
                'path' => array('RevenueLineItems','by_country'),
                'pathVars' => array('module', '', ''),
                'method' => 'salesByCountry',
                'shortHelp' => 'Get opportunities won by country',
                'longHelp' => '',
            ),
        );
    }


    public function salesByCountry(ServiceBase $api, array $args)
    {
        // TODO: Fix information leakage if user cannot list or view records not
        // belonging to them. It's hard to tell if the user has access if we
        // never get the bean.

        // Check for permissions on both Revenue line times and accounts.
        $seed = BeanFactory::newBean('RevenueLineItems');
        if (!$seed->ACLAccess('view')) {
            return;
        }

        // Load up the relationship
        if (!$seed->load_relationship('account_link')) {
            // The relationship did not load, I'm guessing it doesn't exist
            return;
        }

        // Figure out what is on the other side of this relationship, check permissions
        $linkModuleName = $seed->account_link->getRelatedModuleName();
        $linkSeed = BeanFactory::newBean($linkModuleName);
        if (!$linkSeed->ACLAccess('view')) {
            return;
        }

        $query = new SugarQuery();
        $query->from($seed);
        $account_link = $query->join('account_link');
        $query->select(array(
            $account_link->joinName() . '.billing_address_country',
            $account_link->joinName() . '.billing_address_state',
            'likely_case', 
            'base_rate'
        ));
        $query->where()->equals('sales_stage', 'Closed Won');

        // TODO: When we can sum on the database side through SugarQuery, we can
        // use the group by statement.

        $data = array();

        $results = $query->execute();
        foreach ($results as $row) {
            if (empty($data[$row['billing_address_country']])) {
                $data[$row['billing_address_country']] = array(
                    '_total' => 0
                );
            }
            if (empty($data[$row['billing_address_country']][$row['billing_address_state']])) {
                $data[$row['billing_address_country']][$row['billing_address_state']] = array(
                    '_total' => 0
                );
            }
            $data[$row['billing_address_country']]['_total'] += $row['likely_case']/$row['base_rate'];
            $data[$row['billing_address_country']][$row['billing_address_state']]['_total'] += $row['likely_case']/$row['base_rate'];
        }

        return $data;
    }
}
