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
$viewdefs['Accounts']['base']['view']['subpanel-for-prospectlists'] = array(
  'type' => 'subpanel-list',
  'panels' =>
  array(
    array(
      'name' => 'panel_header',
      'label' => 'LBL_PANEL_1',
      'fields' => array(
       array(
          'name' => 'name',
          'label' => 'LBL_NAME',
          'enabled' => true,
          'default' => true,
          'link' => true,
        ),
        array(
          'label' => 'LBL_LIST_PHONE',
          'enabled' => true,
          'default' => true,
          'name' => 'phone_office',
        ),
        array(
          'label' => 'LBL_LIST_EMAIL',
          'enabled' => true,
          'default' => true,
          'name' => 'email',
        ),
        array(
          'label' => 'LBL_ASSIGNED_TO',
          'enabled' => true,
          'default' => true,
          'name' => 'assigned_user_name',
        ),
      ),
    ),
  ),
);
