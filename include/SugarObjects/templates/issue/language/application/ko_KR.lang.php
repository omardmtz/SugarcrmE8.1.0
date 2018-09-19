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
    'Product' => '제품',
    'User' => '사용자',
  ),
   $object_name.'_status_dom' =>
  array (
    'New' => '신규',
    'Assigned' => '배정',
    'Closed' => '완료',
    'Pending Input' => '응답대기',
    'Rejected' => '거부',
    'Duplicate' => '복사하기',
  ),
  $object_name.'_priority_dom' =>
  array (
    'P1' => '높음',
    'P2' => '보통',
    'P3' => '낮음',
  ),
  $object_name.'_resolution_dom' =>
  array (
  	'' => '',
  	'Accepted' => '수락',
    'Duplicate' => '복사하기',
    'Closed' => '완료',
    'Out of Date' => '기간만료',
    'Invalid' => '무효',
  ),
  );
?>