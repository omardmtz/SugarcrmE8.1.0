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

class OpportunityHooks extends AbstractForecastHooks
{
    /**
     * @return array
     */
    protected static function useRevenueLineItems()
    {
        // get the OpportunitySettings
        $settings = Opportunity::getSettings();
        return (isset($settings['opps_view_by']) && $settings['opps_view_by'] === 'RevenueLineItems');
    }

    /**
     * This is a general hook that takes the Opportunity and saves it to the forecast worksheet record.
     *
     * @param Opportunity $bean The bean we are working with
     * @param string $event Which event was fired
     * @param array $args Any additional Arguments
     * @return bool
     */
    public static function saveWorksheet(Opportunity $bean, $event, $args)
    {
        if (static::isForecastSetup() && !static::useRevenueLineItems()) {
            /* @var $worksheet ForecastWorksheet */
            $worksheet = BeanFactory::newBean('ForecastWorksheets');
            $worksheet->saveRelatedOpportunity($bean);
            return true;
        }

        return false;
    }

    /**
     * Mark all related RLI's on a given opportunity to be deleted
     *
     * @param Opportunity $bean
     * @param $event
     * @param $args
     */
    public static function deleteOpportunityRevenueLineItems(Opportunity $bean, $event, $args)
    {
        if (static::useRevenueLineItems()) {
            $rlis = $bean->get_linked_beans('revenuelineitems', 'RevenueLineItems');
            foreach ($rlis as $rli) {
                $rli->mark_deleted($rli->id);
            }
        }
    }

    /**
     * Set the Sales Status based on the associated RLI's sales_stage
     *
     * @param Opportunity $bean
     * @param string $event
     * @param array $args
     */
    public static function setSalesStatus(Opportunity $bean, $event, $args)
    {
        if (static::useRevenueLineItems() && $bean->ACLFieldAccess('sales_status', 'write')) {
            // Load forecast config so we have the sales_stage data.
            static::loadForecastSettings();

            // we don't have a new row, so figure out what we need to set it to
            $closed_won = static::$settings['sales_stage_won'];
            $closed_lost = static::$settings['sales_stage_lost'];

            $won_rlis = count(
                $bean->get_linked_beans(
                    'revenuelineitems',
                    'RevenueLineItems',
                    array(),
                    0,
                    -1,
                    0,
                    "sales_stage in ('" . join("', '", $closed_won) . "')"
                )
            );

            $lost_rlis = count(
                $bean->get_linked_beans(
                    'revenuelineitems',
                    'RevenueLineItems',
                    array(),
                    0,
                    -1,
                    0,
                    "sales_stage in ('" . join("', '", $closed_lost) . "')"
                )
            );

            $total_rlis = count($bean->get_linked_beans('revenuelineitems', 'RevenueLineItems'));

            if ($total_rlis > ($won_rlis + $lost_rlis)) {
                // still in progress
                $bean->sales_status = Opportunity::STATUS_IN_PROGRESS;
            } elseif ($total_rlis === 0) {
                $bean->sales_status = Opportunity::STATUS_NEW;
            } else {
                // they are equal so if the total lost == total rlis then it's closed lost,
                // otherwise it's always closed won
                if ($lost_rlis == $total_rlis) {
                    $bean->sales_status = Opportunity::STATUS_CLOSED_LOST;
                } else {
                    $bean->sales_status = Opportunity::STATUS_CLOSED_WON;
                }
            }
        }
    }

    /**
     * Before we save, we need to check to see if this opp is in a closed state. If so,
     * set it to the proper included/excluded state in case mass_update tried to set it to something wonky
     * @param RevenueLineItem $bean
     * @param string $event
     * @param array $args
     */
    public static function beforeSaveIncludedCheck($bean, $event, $args)
    {
        $settings = Forecast::getSettings(true);

        if ($settings['is_setup'] && $event == 'before_save') {
            $won = $settings['sales_stage_won'];
            $lost = $settings['sales_stage_lost'];

            //Check to see if we are in a won state. if so, set the probability to 100 and commit_stage to include.
            //if not, set the probability to 0 and commit_stage to exclude
            if (in_array($bean->sales_stage, $won)) {
                $bean->probability = 100;
                $bean->commit_stage = 'include';
            } else if (in_array($bean->sales_stage, $lost)) {
                $bean->probability = 0;
                $bean->commit_stage = 'exclude';
            }
        }
    }

    /**
     * If the account relationship on an opportunity is changed via merging accounts, we need to resave the opportunity
     * so that the worksheet will reflect the new account.
     * @param $bean
     * @param $event
     * @param $args
     */
    public static function fixWorksheetAccountAssignment($bean, $event, $args)
    {
        if (!empty($args)
            && $args['relationship'] == 'accounts_opportunities'
            && static::isForecastSetup()
            && !static::useRevenueLineItems()) {

            $bean->account_id = $args['related_id'];
            static::saveWorksheet($bean, $event, $args);
            return true;
        }

        return false;
    }
}
