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
$object_name = strtolower($object_name);
 $app_list_strings = array (

  $object_name.'_type_dom' =>
  array (
  	'Administration' => 'Administration',
    'Product' => '商品',
    'User' => 'アサイン先',
  ),
   $object_name.'_status_dom' =>
  array (
    'New' => '新規',
    'Assigned' => 'アサイン済み',
    'Closed' => '完了',
    'Pending Input' => '保留中',
    'Rejected' => '拒否',
    'Duplicate' => '複製',
  ),
  $object_name.'_priority_dom' =>
  array (
    'P1' => '高',
    'P2' => '中',
    'P3' => '低',
  ),
  $object_name.'_resolution_dom' =>
  array (
  	'' => '',
  	'Accepted' => '受領',
    'Duplicate' => '複製',
    'Closed' => '完了',
    'Out of Date' => '期限切れ',
    'Invalid' => '無効',
  ),
  );
?>