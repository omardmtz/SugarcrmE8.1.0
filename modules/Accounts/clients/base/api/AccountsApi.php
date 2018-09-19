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


class AccountsApi extends ModuleApi
{
    public function registerApiRest()
    {
        return array(
            'opportunity_stats' => array(
                'reqType' => 'GET',
                'path' => array('Accounts','?', 'opportunity_stats'),
                'pathVars' => array('module', 'record'),
                'method' => 'opportunityStats',
                'shortHelp' => 'Get opportunity statistics for current record',
                'longHelp' => '',
            ),
        );
    }

    public function opportunityStats(ServiceBase $api, array $args)
    {
        // TODO make all APIs wrapped on tries and catches
        // TODO: move this to own module (in this case accounts)

        // TODO: Fix information leakage if user cannot list or view records not
        // belonging to them. It's hard to tell if the user has access if we
        // never get the bean.

        // Check for permissions on both Accounts and opportunities.
        // Load up the bean
        $record = BeanFactory::getBean($args['module'], $args['record']);
        if (!$record->ACLAccess('view')) {
            return;
        }

        // Load up the relationship
        if (!$record->load_relationship('opportunities')) {
            // The relationship did not load, I'm guessing it doesn't exist
            return;
        }

        // Figure out what is on the other side of this relationship, check permissions
        $linkModuleName = $record->opportunities->getRelatedModuleName();
        $linkSeed = BeanFactory::newBean($linkModuleName);
        if (!$linkSeed->ACLAccess('view')) {
            return;
        }

        $status_field = $this->getOpportunityStatusField();

        $query = new SugarQuery();
        $query->select(array($status_field, 'amount_usdollar'));
        $query->from($linkSeed);
        // making this more generic so we can use this on contacts also as soon
        // as we move it to a proper module
        $query->join('accounts', array('alias' => 'record'));
        $query->where()->equals('record.id', $record->id);
        // FIXME add the security query here!!!
        // TODO: When we can sum on the database side through SugarQuery, we can
        // use the group by statement.

        $results = $query->execute();

        // TODO this can't be done this way since we can change the status on
        // studio and add more
        $data = array(
            'won' => array('amount_usdollar' => 0, 'count' => 0),
            'lost' => array('amount_usdollar' => 0, 'count' => 0),
            'active' => array('amount_usdollar' => 0, 'count' => 0)
        );

        foreach ($results as $row) {
            $map = array(
                'Closed Lost' => 'lost',
                'Closed Won' => 'won',
            );
            if (array_key_exists($row[$status_field], $map)) {
                $status = $map[$row[$status_field]];
            } else {
                $status = 'active';
            }
            $data[$status]['amount_usdollar'] += $row['amount_usdollar'];
            $data[$status]['count']++;
        }
        return $data;
    }

    /**
     * Figure out which Opportunity Status Field To Use based on the `opps_view_by` setting
     *
     * @return string
     */
    protected function getOpportunityStatusField()
    {
        $status_field = 'sales_stage';
        // get the opp config
        $opp_config = Opportunity::getSettings();
        if ($opp_config['opps_view_by'] === 'RevenueLineItems') {
            $status_field = 'sales_status';
        }

        return $status_field;
    }
}
