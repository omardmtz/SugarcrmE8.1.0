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

class RevenueLineItemHooks
{
    /**
     * After the relationship is deleted, we need to resave the RLI.  This
     * will ensure that it will pick up an accout from the associated Opportunity.
     *
     * @param RevenueLineItem $bean
     * @param string $event
     * @param array $args
     */
    public static function afterRelationshipDelete($bean, $event, $args)
    {
        if ($event == 'after_relationship_delete') {
            if ($args['link'] == 'account_link' && $bean->deleted == 0) {
                $bean->save();
                return true;
            }
        }
        return false;
    }

    /**
     * Before we save, we need to check to see if this rli is in a closed state. If so,
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
}
