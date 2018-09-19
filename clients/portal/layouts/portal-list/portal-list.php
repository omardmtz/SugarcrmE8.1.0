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

$viewdefs['portal']['layout']['portal-list'] = array (
  'components' => array (
    array (
      'view' => 'portal-list-top',
    ),
    array (
      'view' => 'filter',
    ),
    array (
      'view' => 'list',
    ),
    array (
      'view' => 'list-bottom',
    ),
  ),
  'type' => 'simple',
  'name' => 'dashboard-list',
  'span' => 12,
  'css_class' => 'thumbnail',
);
