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
$dictionary['ForecastManagerWorksheet'] = array(
    'table' => 'forecast_manager_worksheets',
    'audited' => true,
    'fields' => array(
        'quota' =>
        array(
            'name' => 'quota',
            'vname' => 'LBL_QUOTA',
            'type' => 'currency',
            'is_base_currency' => true
        ),
        'best_case' =>
        array(
            'name' => 'best_case',
            'vname' => 'LBL_BEST',
            'type' => 'currency',
            'audited' => true,
            'is_base_currency' => true
        ),
        'best_case_adjusted' =>
        array(
            'name' => 'best_case_adjusted',
            'vname' => 'LBL_BEST_ADJUSTED',
            'type' => 'currency',
            'is_base_currency' => true
        ),
        'likely_case' =>
        array(
            'name' => 'likely_case',
            'vname' => 'LBL_LIKELY',
            'type' => 'currency',
            'audited' => true,
            'is_base_currency' => true
        ),
        'likely_case_adjusted' =>
        array(
            'name' => 'likely_case_adjusted',
            'vname' => 'LBL_LIKELY_ADJUSTED',
            'type' => 'currency',
            'is_base_currency' => true
        ),
        'worst_case' =>
        array(
            'name' => 'worst_case',
            'vname' => 'LBL_WORST',
            'type' => 'currency',
            'audited' => true,
            'is_base_currency' => true
        ),
        'worst_case_adjusted' =>
        array(
            'name' => 'worst_case_adjusted',
            'vname' => 'LBL_WORST_ADJUSTED',
            'type' => 'currency',
            'is_base_currency' => true
        ),
        'timeperiod_id' =>
        array(
            'name' => 'timeperiod_id',
            'vname' => 'LBL_FORECAST_TIME_ID',
            'type' => 'id',
        ),
        'draft' =>
        array(
            'name' => 'draft',
            'vname' => 'LBL_DRAFT',
            'type' => 'bool',
            'default' => 0,
        ),
        'is_manager' =>
        array(
            'name' => 'is_manager',
            'type' => 'bool',
            'source' => 'non-db',
            'comment' => 'needed for commitLog field logic'
        ),
        'user_id' =>
        array(
            'name' => 'user_id',
            'vname' => 'LBL_FS_USER_ID',
            'type' => 'id',
        ),
        'opp_count' =>
        array(
            'name' => 'opp_count',
            'vname' => 'LBL_FORECAST_OPP_COUNT',
            'type' => 'int',
            'len' => '5',
            'comment' => 'Number of opportunities represented by this forecast',
        ),
        'pipeline_opp_count' =>
        array(
            'name' => 'pipeline_opp_count',
            'vname' => 'LBL_FORECAST_OPP_COUNT',
            'type' => 'int',
            'len' => '5',
            'studio' => false,
            'default' => '0',
            'comment' => 'Number of opportunities minus closed won/closed lost represented by this forecast',
        ),
        'pipeline_amount' =>
        array(
            'name' => 'pipeline_amount',
            'vname' => 'LBL_PIPELINE_REVENUE',
            'type' => 'currency',
            'studio' => false,
            'default' => '0',
            'comment' => 'Total of opportunities minus closed won/closed lost represented by this forecast',
        ),
        'closed_amount' =>
        array(
            'name' => 'closed_amount',
            'vname' => 'LBL_CLOSED',
            'type' => 'currency',
            'studio' => false,
            'default' => "0",
            'comment' => 'Total of closed won items in the forecast',
        ),
        'manager_saved' =>
        array(
            'name' => 'manager_saved',
            'vname' => 'LBL_MANGER_SAVED',
            'type' => 'bool',
            'studio' => false,
            'default' => 0,
            'comments' => 'Once this is set to true, the rollovers will no longer happen'
        ),
        'show_history_log' =>
        array(
            'name' => 'show_history_log',
            'type' => 'int',
            'source' => 'non-db'
        ),
        'draft_save_type' =>
        array(
            'name' => 'draft_save_type',
            'type' => 'varchar',
            'source' => 'non-db'
        ),
    ),
    'relationships' => array(// relationships that might be needed: User_id -> users, quota_id -> Quota,
    ),
    'indices' => array(
        array(
            'name' => 'idx_manager_worksheets_user_timestamp_assigned_user',
            'type' => 'index',
            'fields' => array('assigned_user_id', 'user_id', 'timeperiod_id', 'draft', 'deleted')
        )
    ),
    // @TODO Fix the Default and Basic SugarObject templates so that Basic
    // implements Default. This would allow the application of various
    // implementations on Basic without forcing Default to have those so that
    // situations like this - implementing taggable - doesn't have to apply to
    // EVERYTHING. Since there is no distinction between basic and default for
    // sugar objects templates yet, we need to forecefully remove the taggable
    // implementation fields. Once there is a separation of default and basic
    // templates we can safely remove these as this module will implement
    // default instead of basic.
    'ignore_templates' => array(
        'taggable',
    ),
);

VardefManager::createVardef(
    'ForecastManagerWorksheets',
    'ForecastManagerWorksheet',
    array(
        'default',
        'assignable',
        'team_security',
        'currency'
    )
);
