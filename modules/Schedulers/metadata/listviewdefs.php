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

$listViewDefs ['Schedulers'] =
array (
  'NAME' =>
  array (
    'width' => '35',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'sortable' => true,
    'default' => true,
  ),
  'JOB_INTERVAL' =>
  array (
    'width' => '20',
    'label' => 'LBL_LIST_JOB_INTERVAL',
    'default' => true,
  	'sortable' => false,
  ),
  'DATE_TIME_START' =>
  array (
    'width' => '25',
    'label' => 'LBL_LIST_RANGE',
  	'customCode' => '{$DATE_TIME_START} - {$DATE_TIME_END}',
    'default' => true,
  	'related_fields' => array('date_time_end'),
  ),
  'STATUS' =>
  array (
    'width' => '15',
    'label' => 'LBL_LIST_STATUS',
    'default' => true,
  ),
);
