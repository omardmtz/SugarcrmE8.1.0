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
$viewdefs['ForecastWorksheets']['base']['view']['dupecheck-list'] = array(
    'panels' => array(
        array(
            'label' => 'LBL_PANEL_1',
            'fields' => array(
                array(
                    'name' => 'commit_stage',
                    'type' => 'enum',
                    'searchBarThreshold' => 7,
                    'label' => 'LBL_FORECAST',
                    'sortable' => false,
                    'default' => true,
                    'enabled' => true,
                    'click_to_edit' => true
                ),
                array(
                    'name' => 'parent_name',
                    'label' => 'LBL_REVENUELINEITEM_NAME',
                    'link' => true,
                    'id' => 'parent_id',
                    'sortable' => true,
                    'default' => true,
                    'enabled' => true,
                    'display' => false,
                    'type' => 'parent',
                    'readonly' => true,
                ),
                array(
                    'name' => 'opportunity_name',
                    'label' => 'LBL_OPPORTUNITY_NAME',
                    'link' => true,
                    'id' => 'opportunity_id',
                    'id_name' => 'opportunity_id',
                    'module' => 'Opportunities',
                    'sortable' => true,
                    'default' => true,
                    'enabled' => true,
                    'type' => 'relate',
                    'readonly' => true
                ),
                array(
                    'name' => 'account_name',
                    'label' => 'LBL_ACCOUNT_NAME',
                    'link' => true,
                    'id' => 'account_id',
                    'id_name' => 'account_id',
                    'module' => 'Accounts',
                    'sortable' => true,
                    'default' => true,
                    'enabled' => true,
                    'type' => 'relate',
                    'readonly' => true
                ),
                array(
                    'name' => 'date_closed',
                    'label' => 'LBL_DATE_CLOSED',
                    'sortable' => true,
                    'default' => false,
                    'enabled' => true,
                    'type' => 'date',
                    'view' => 'detail',
                    'click_to_edit' => true
                ),
                array(
                    'name' => 'sales_stage',
                    'label' => 'LBL_SALES_STAGE',
                    'type' => 'enum',
                    'options' => 'sales_stage_dom',
                    'searchBarThreshold' => 7,
                    'sortable' => false,
                    'default' => false,
                    'enabled' => true,
                    'click_to_edit' => true
                ),
                array(
                    'name' => 'probability',
                    'label' => 'LBL_OW_PROBABILITY',
                    'type' => 'int',
                    'default' => false,
                    'enabled' => true,
                    'click_to_edit' => true,
                    'align' => 'right',
                ),
                array(
                    'name' => 'likely_case',
                    'label' => 'LBL_LIKELY',
                    'type' => 'currency',
                    'default' => false,
                    'enabled' => true,
                    'convertToBase' => true,
                    'showTransactionalAmount' => true,
                    'align' => 'right',
                    'click_to_edit' => true,
                ),
                array(
                    'name' => 'best_case',
                    'label' => 'LBL_BEST',
                    'type' => 'currency',
                    'default' => false,
                    'enabled' => true,
                    'convertToBase' => true,
                    'showTransactionalAmount' => true,
                    'align' => 'right',
                    'click_to_edit' => true,
                ),
            ),
        ),
    ),
);
