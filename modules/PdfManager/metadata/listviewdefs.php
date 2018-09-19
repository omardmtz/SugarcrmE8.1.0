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


$listViewDefs['PdfManager'] =
array (
  'NAME' =>
  array (
    'width' => '32',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'base_module' =>
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_BASE_MODULE',
    'width' => '10',
  ),
  'published' =>
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_PUBLISHED',
    'width' => '10',
  ),
  'DATE_ENTERED' =>
  array (
    'width' => '5',
    'label' => 'LBL_DATE_ENTERED',
    'default' => true,
  ),
  'team_name' =>
  array (
    'width' => '9',
    'label' => 'LBL_TEAM',
    'default' => false,
  ),
);
