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
    'Product' => '产品',
    'User' => '用户',
  ),
   $object_name.'_status_dom' =>
  array (
    'New' => '新建',
    'Assigned' => '已分配',
    'Closed' => '已关闭',
    'Pending Input' => '待输入',
    'Rejected' => '已拒绝',
    'Duplicate' => '复制',
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
  	'Accepted' => '已接受',
    'Duplicate' => '复制',
    'Closed' => '已关闭',
    'Out of Date' => '过期',
    'Invalid' => '无效',
  ),
  );
?>