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
$viewdefs['Products']['base']['view']['subpanel-for-accounts'] = array(
  'type' => 'subpanel-list',
  'panels' =>
  array(
    array(
      'name' => 'panel_header',
      'label' => 'LBL_PANEL_1',
      'fields' => array(
        array(
          'label' => 'LBL_LIST_NAME',
          'enabled' => true,
          'default' => true,
          'name' => 'name',
          'link' => 'true',
        ),
        array(
          'label' => 'LBL_LIST_STATUS',
          'enabled' => true,
          'default' => true,
          'name' => 'status',
        ),
        array(
          'target_record_key' => 'contact_id',
          'target_module' => 'Contacts',
          'label' => 'LBL_LIST_CONTACT_NAME',
          'enabled' => true,
          'default' => true,
          'name' => 'contact_name',
        ),
        array(
          'label' => 'LBL_LIST_DATE_PURCHASED',
          'enabled' => true,
          'default' => true,
          'name' => 'date_purchased',
        ),
        array(
          'label' => 'LBL_LIST_DISCOUNT_PRICE',
          'enabled' => true,
          'default' => true,
          'name' => 'discount_price',
        ),
        array(
          'label' => 'LBL_LIST_SUPPORT_EXPIRES',
          'enabled' => true,
          'default' => true,
          'name' => 'date_support_expires',
        ),
      ),
    ),
  ),
);
