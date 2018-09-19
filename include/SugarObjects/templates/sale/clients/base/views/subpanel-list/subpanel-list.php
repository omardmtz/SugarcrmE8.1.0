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
$module_name = '<module_name>';
$viewdefs[$module_name]['base']['view']['subpanel-list'] = array(
  'panels' => 
  array(
    array(
      'name' => 'panel_header',
      'label' => 'LBL_PANEL_1',
      'fields' => 
      array(
        array(
          'name' => 'name',
          'label' => 'LBL_LIST_SALE_NAME',
          'enabled' => true,
          'default' => true,
          'link' => true,
        ),
        array(
          'name' => 'sales_stage',
          'label' => 'LBL_LIST_SALE_STAGE',
          'enabled' => true,
          'default' => true,
        ),
        array(
          'name' => 'date_closed',
          'label' => 'LBL_LIST_DATE_CLOSED',
          'enabled' => true,
          'default' => true,
        ),
        array(
          'label' => 'LBL_LIST_AMOUNT',
          'enabled' => true,
          'default' => true,
          'name' => 'amount',
          'related_fields' => array(
              'currency_id',
              'base_rate'
          ),
        ),
        array(
          'name' => 'assigned_user_name',
          'target_record_key' => 'assigned_user_id',
          'target_module' => 'Employees',
          'label' => 'LBL_LIST_ASSIGNED_TO_NAME',
          'enabled' => true,
          'default' => true,
        ),
      ),
    ),
  ),
);
