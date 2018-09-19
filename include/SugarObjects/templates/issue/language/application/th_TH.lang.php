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
  	'Administration' => 'การดูแลระบบ',
    'Product' => 'ผลิตภัณฑ์',
    'User' => 'ผู้ใช้',
  ),
   $object_name.'_status_dom' =>
  array (
    'New' => 'ใหม่',
    'Assigned' => 'ระบุแล้ว',
    'Closed' => 'ปิดแล้ว',
    'Pending Input' => 'รออินพุต',
    'Rejected' => 'ปฏิเสธ',
    'Duplicate' => 'ซ้ำ',
  ),
  $object_name.'_priority_dom' =>
  array (
    'P1' => 'สูง',
    'P2' => 'ปานกลาง',
    'P3' => 'ต่ำ',
  ),
  $object_name.'_resolution_dom' =>
  array (
  	'' => '',
  	'Accepted' => 'ยอมรับแล้ว',
    'Duplicate' => 'ซ้ำ',
    'Closed' => 'ปิดแล้ว',
    'Out of Date' => 'เก่าเกินไป',
    'Invalid' => 'ไม่ถูกต้อง',
  ),
  );
?>