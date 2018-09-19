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
$viewdefs['Contracts']['base']['view']['subpanel-for-accounts'] = array(
  'type' => 'subpanel-list',
  'panels' =>
  array(
    array(
      'name' => 'panel_header',
      'label' => 'LBL_PANEL_1',
      'fields' =>
      array(
        array(
          'name' => 'name',
          'label' => 'LBL_LIST_NAME',
          'enabled' => true,
          'default' => true,
          'link' => true,
        ),
        array(
          'name' => 'start_date',
          'label' => 'LBL_LIST_START_DATE',
          'enabled' => true,
          'default' => true,
        ),
        array(
          'name' => 'end_date',
          'label' => 'LBL_LIST_END_DATE',
          'enabled' => true,
          'default' => true,
        ),
        array(
          'name' => 'status',
          'label' => 'LBL_LIST_STATUS',
          'enabled' => true,
          'default' => true,
        ),
        array(
          'name' => 'total_contract_value',
          'label' => 'LBL_LIST_CONTRACT_VALUE',
          'enabled' => true,
          'default' => true,
        ),
      ),
    ),
  ),
);
