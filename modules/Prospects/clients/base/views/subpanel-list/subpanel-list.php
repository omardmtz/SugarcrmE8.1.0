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
$viewdefs['Prospects']['base']['view']['subpanel-list'] = array(
  'panels' => array(
    array(
      'name' => 'panel_header',
      'label' => 'LBL_PANEL_1',
      'fields' => array(
        array(
            'name' => 'name',
            'type' => 'fullname',
            'fields' => array(
                'salutation',
                'first_name',
                'last_name',
            ),
            'link' => true,
          'label' => 'LBL_LIST_NAME',
          'enabled' => true,
          'default' => true,
        ),
        array(
          'label' => 'LBL_LIST_TITLE',
          'enabled' => true,
          'default' => true,
          'name' => 'title',
        ),
        array(
          'label' => 'LBL_LIST_EMAIL_ADDRESS',
          'enabled' => true,
          'default' => true,
          'name' => 'email',
          'sortable' => false,
        ),
        array(
          'label' => 'LBL_LIST_PHONE',
          'enabled' => true,
          'default' => true,
          'name' => 'phone_work',
        ),
      ),
    ),
  ),
);
