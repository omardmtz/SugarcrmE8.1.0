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
/*********************************************************************************
 * $Id$
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
 $mod_strings = array (
  'LBL_MODULE_NAME' => 'การขาย',
  'LBL_MODULE_TITLE' => 'การขาย: หน้าแรก',
  'LBL_SEARCH_FORM_TITLE' => 'การค้นหาการขาย',
  'LBL_VIEW_FORM_TITLE' => 'มุมมองการขาย',
  'LBL_LIST_FORM_TITLE' => 'รายการขาย',
  'LBL_SALE_NAME' => 'ชื่อการขาย:',
  'LBL_SALE' => 'การขาย:',
  'LBL_NAME' => 'ชื่อการขาย',
  'LBL_LIST_SALE_NAME' => 'ชื่อ',
  'LBL_LIST_ACCOUNT_NAME' => 'ชื่อบัญชี',
  'LBL_LIST_AMOUNT' => 'จำนวนเงิน',
  'LBL_LIST_DATE_CLOSED' => 'ปิด',
  'LBL_LIST_SALE_STAGE' => 'ขั้นตอนการขาย',
  'LBL_ACCOUNT_ID'=>'ID บัญชี',
  'LBL_TEAM_ID' =>'ID ทีม',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => 'การขาย - การอัปเดตสกุลเงิน',
  'UPDATE_DOLLARAMOUNTS' => 'อัปเดตจำนวนเงินดอลลาร์สหรัฐ',
  'UPDATE_VERIFY' => 'ยืนยันจำนวนเงิน',
  'UPDATE_VERIFY_TXT' => 'ยืนยันว่าค่าจำนวนเงินของยอดขายเป็นตัวเลขฐานสิบที่ถูกต้อง โดยมีอักขระตัวเลข (0-9) และจุดทศนิยม (.) เท่านั้น',
  'UPDATE_FIX' => 'แก้ไขจำนวนเงิน',
  'UPDATE_FIX_TXT' => 'พยายามแก้ไขจำนวนเงินที่ไม่ถูกต้องด้วยการสร้างค่าฐานสิบที่ถูกต้องจากจำนวนเงินปัจจุบัน จำนวนเงินที่แก้ไขจะมีการสำรองไว้ในฟิลด์ฐานข้อมูล amount_backup ถ้าคุณเรียกใช้คำสั่งนี้และพบบัก โปรดอย่าเรียกใช้ซ้ำโดยไม่เรียกคืนจากการสำรองข้อมูล มิฉะนั้นอาจมีการเขียนทับข้อมูลสำรองด้วยข้อมูลใหม่ที่ไม่ถูกต้อง',
  'UPDATE_DOLLARAMOUNTS_TXT' => 'อัปเดตจำนวนเงินเป็นดอลลาร์สหรัฐสำหรับยอดขายโดยอ้างอิงอัตราแลกเปลี่ยนของสกุลเงินชุดปัจจุบัน ค่านี้ใช้คำนวณจำนวนเงินของสกุลเงินในกราฟและมุมมองรายการ',
  'UPDATE_CREATE_CURRENCY' => 'กำลังสร้างสกุลเงินใหม่:',
  'UPDATE_VERIFY_FAIL' => 'ระเบียนไม่ผ่านการยืนยัน:',
  'UPDATE_VERIFY_CURAMOUNT' => 'จำนวนเงินปัจจุบัน:',
  'UPDATE_VERIFY_FIX' => 'การเรียกใช้การแก้ไขจะทำให้',
  'UPDATE_INCLUDE_CLOSE' => 'รวมระเบียนที่ปิด',
  'UPDATE_VERIFY_NEWAMOUNT' => 'จำนวนใหม่:',
  'UPDATE_VERIFY_NEWCURRENCY' => 'สกุลเงินใหม่:',
  'UPDATE_DONE' => 'เสร็จสิ้น',
  'UPDATE_BUG_COUNT' => 'บักที่พบและพยายามแก้ไข:',
  'UPDATE_BUGFOUND_COUNT' => 'บักที่พบ:',
  'UPDATE_COUNT' => 'ระเบียนที่อัปเดต:',
  'UPDATE_RESTORE_COUNT' => 'จำนวนเงินของระเบียนที่เรียกคืน:',
  'UPDATE_RESTORE' => 'เรียกคืนจำนวนเงิน',
  'UPDATE_RESTORE_TXT' => 'เรียกคืนค่าของจำนวนเงินจากการสำรองข้อมูลที่สร้างขึ้นระหว่างการแก้ไข',
  'UPDATE_FAIL' => 'ไม่สามารถอัปเดต - ',
  'UPDATE_NULL_VALUE' => 'จำนวนเงินเป็นค่าว่างเปล่า กำลังตั้งค่าเป็น 0 -',
  'UPDATE_MERGE' => 'ผสานสกุลเงิน',
  'UPDATE_MERGE_TXT' => 'ผสานหลายสกุลเงินเป็นสกุลเงินเดียว ถ้ามีระเบียนสกุลเงินหลายระเบียนสำหรับสกุลเงินเดียวกัน คุณสามารถผสานรวมไว้ด้วยกัน ซึ่งจะทำให้ระบบผสานสกุลเงินสำหรับโมดูลอื่นๆ ทั้งหมดด้วย',
  'LBL_ACCOUNT_NAME' => 'ชื่อบัญชี:',
  'LBL_AMOUNT' => 'จำนวนเงิน:',
  'LBL_AMOUNT_USDOLLAR' => 'จำนวนเงิน USD:',
  'LBL_CURRENCY' => 'สกุลเงิน:',
  'LBL_DATE_CLOSED' => 'วันที่ปิดที่คาดไว้:',
  'LBL_TYPE' => 'ประเภท:',
  'LBL_CAMPAIGN' => 'แคมเปญ:',
  'LBL_LEADS_SUBPANEL_TITLE' => 'ผู้สนใจ',
  'LBL_PROJECTS_SUBPANEL_TITLE' => 'โครงการ',  
  'LBL_NEXT_STEP' => 'ขั้นตอนถัดไป:',
  'LBL_LEAD_SOURCE' => 'ที่มาของผู้สนใจ:',
  'LBL_SALES_STAGE' => 'ขั้นตอนการขาย:',
  'LBL_PROBABILITY' => 'ความน่าจะเป็น (%):',
  'LBL_DESCRIPTION' => 'คำอธิบาย:',
  'LBL_DUPLICATE' => 'การขายอาจซ้ำกัน',
  'MSG_DUPLICATE' => 'ระเบียนการขายที่คุณต้องการสร้างอาจซ้ำกับระเบียนการขายที่มีอยู่แล้ว ด้านล่างนี้เป็นรายชื่อของระเบียนการขายที่มีชื่อคล้ายกัน<br>คุณสามารถคลิกบันทึกเพื่อสร้างการขายใหม่นี้ต่อไป หรือคลิกยกเลิกเพื่อกลับสู่โมดูลโดยไม่สร้างการขาย',
  'LBL_NEW_FORM_TITLE' => 'สร้างการขาย',
  'LNK_NEW_SALE' => 'สร้างการขาย',
  'LNK_SALE_LIST' => 'การขาย',
  'ERR_DELETE_RECORD' => 'ต้องระบุเลขที่ระเบียนเพื่อลบการขาย',
  'LBL_TOP_SALES' => 'การขายที่เปิดอันดับสูงสุดของฉัน',
  'NTC_REMOVE_OPP_CONFIRMATION' => 'คุณแน่ใจหรือไม่ว่าต้องการย้ายที่อยู่ติดต่อนี้ออกจากการขาย',
	'SALE_REMOVE_PROJECT_CONFIRM' => 'คุณแน่ใจหรือไม่ว่าต้องการย้ายการขายนี้ออกจากโครงการ',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'กิจกรรม',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'ประวัติ',
    'LBL_RAW_AMOUNT'=>'จำนวนเงินขั้นต้น',


    'LBL_CONTACTS_SUBPANEL_TITLE' => 'ที่อยู่ติดต่อ',
	'LBL_ASSIGNED_TO_NAME' => 'ผู้ใช้:',
	'LBL_LIST_ASSIGNED_TO_NAME' => 'ผู้ใช้ที่ระบุ',
  'LBL_MY_CLOSED_SALES' => 'ยอดขายที่ปิดของฉัน',
  'LBL_TOTAL_SALES' => 'ยอดรวมการขาย',
  'LBL_CLOSED_WON_SALES' => 'การขายที่ปิดโดยได้รับการขาย',
  'LBL_ASSIGNED_TO_ID' =>'ระบุให้ ID',
  'LBL_CREATED_ID'=>'สร้างโดย ID',
  'LBL_MODIFIED_ID'=>'แก้ไขโดย ID',
  'LBL_MODIFIED_NAME'=>'แก้ไขโดยชื่อผู้ใช้',
  'LBL_SALE_INFORMATION'=>'ข้อมูลการขาย',
  'LBL_CURRENCY_ID'=>'ID สกุลเงิน',
  'LBL_CURRENCY_NAME'=>'ชื่อสกุลเงิน',
  'LBL_CURRENCY_SYMBOL'=>'สัญลักษณ์สกุลเงิน',
  'LBL_EDIT_BUTTON' => 'แก้ไข',
  'LBL_REMOVE' => 'นำออก',
  'LBL_CURRENCY_RATE' => 'อัตราสกุลเงิน',

);

