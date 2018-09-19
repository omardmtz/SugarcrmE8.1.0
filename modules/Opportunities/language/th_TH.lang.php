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

$mod_strings = array(
    // Dashboard Names
    'LBL_OPPORTUNITIES_LIST_DASHBOARD' => 'แดชบอร์ดรายการโอกาส',
    'LBL_OPPORTUNITIES_RECORD_DASHBOARD' => 'แดชบอร์ดบันทึกโอกาส',

    'LBL_MODULE_NAME' => 'โอกาสทางการขาย',
    'LBL_MODULE_NAME_SINGULAR' => 'โอกาสทางการขาย',
    'LBL_MODULE_TITLE' => 'โอกาสทางการขาย: หน้าแรก',
    'LBL_SEARCH_FORM_TITLE' => 'การค้นหาโอกาสทางการขาย',
    'LBL_VIEW_FORM_TITLE' => 'มุมมองโอกาสทางการขาย',
    'LBL_LIST_FORM_TITLE' => 'รายการโอกาสทางการขาย',
    'LBL_OPPORTUNITY_NAME' => 'ชื่อโอกาสทางการขาย:',
    'LBL_OPPORTUNITY' => 'โอกาสทางการขาย:',
    'LBL_NAME' => 'ชื่อโอกาสทางการขาย',
    'LBL_INVITEE' => 'ที่อยู่ติดต่อ',
    'LBL_CURRENCIES' => 'สกุลเงิน',
    'LBL_LIST_OPPORTUNITY_NAME' => 'ชื่อ',
    'LBL_LIST_ACCOUNT_NAME' => 'ชื่อบัญชี',
    'LBL_LIST_DATE_CLOSED' => 'วันที่ปิดที่คาดไว้',
    'LBL_LIST_AMOUNT' => 'เป็นไปได้',
    'LBL_LIST_AMOUNT_USDOLLAR' => 'จำนวนเงินที่แปลง',
    'LBL_ACCOUNT_ID' => 'ID บัญชี',
    'LBL_CURRENCY_RATE' => 'อัตราสกุลเงิน',
    'LBL_CURRENCY_ID' => 'ID สกุลเงิน',
    'LBL_CURRENCY_NAME' => 'ชื่อสกุลเงิน',
    'LBL_CURRENCY_SYMBOL' => 'สัญลักษณ์สกุลเงิน',
//DON'T CONVERT THESE THEY ARE MAPPINGS
    'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
    'db_name' => 'LBL_NAME',
    'db_amount' => 'LBL_LIST_AMOUNT',
    'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
    'UPDATE' => 'โอกาสทางการขาย - การอัปเดตสกุลเงิน',
    'UPDATE_DOLLARAMOUNTS' => 'อัปเดตจำนวนเงินดอลลาร์สหรัฐ',
    'UPDATE_VERIFY' => 'ยืนยันจำนวนเงิน',
    'UPDATE_VERIFY_TXT' => 'ยืนยันว่าค่าจำนวนเงินของโอกาสทางการขายเป็นตัวเลขฐานสิบที่ถูกต้อง โดยมีอักขระตัวเลข (0-9) และจุดทศนิยม (.) เท่านั้น',
    'UPDATE_FIX' => 'แก้ไขจำนวนเงิน',
    'UPDATE_FIX_TXT' => 'พยายามแก้ไขจำนวนเงินที่ไม่ถูกต้องด้วยการสร้างค่าฐานสิบที่ถูกต้องจากจำนวนเงินปัจจุบัน จำนวนเงินที่แก้ไขจะมีการสำรองไว้ในฟิลด์ฐานข้อมูล amount_backup ถ้าคุณเรียกใช้คำสั่งนี้และพบบัก โปรดอย่าเรียกใช้ซ้ำโดยไม่เรียกคืนจากการสำรองข้อมูล มิฉะนั้นอาจมีการเขียนทับข้อมูลสำรองด้วยข้อมูลใหม่ที่ไม่ถูกต้อง',
    'UPDATE_DOLLARAMOUNTS_TXT' => 'อัปเดตจำนวนเงินเป็นดอลลาร์สหรัฐสำหรับโอกาสทางการขายโดยอ้างอิงอัตราแลกเปลี่ยนของสกุลเงินชุดปัจจุบัน ค่านี้ใช้คำนวณจำนวนเงินของสกุลเงินในกราฟและมุมมองรายการ',
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
    'LBL_CURRENCY' => 'สกุลเงิน:',
    'LBL_DATE_CLOSED' => 'วันที่ปิดที่คาดไว้:',
    'LBL_DATE_CLOSED_TIMESTAMP' => 'เวลาระบบของวันที่ปิดที่คาดไว้',
    'LBL_TYPE' => 'ประเภท:',
    'LBL_CAMPAIGN' => 'แคมเปญ:',
    'LBL_NEXT_STEP' => 'ขั้นตอนถัดไป:',
    'LBL_LEAD_SOURCE' => 'ที่มาของผู้สนใจ',
    'LBL_SALES_STAGE' => 'ขั้นตอนการขาย',
    'LBL_SALES_STATUS' => 'สถานะ',
    'LBL_PROBABILITY' => 'ความน่าจะเป็น (%)',
    'LBL_DESCRIPTION' => 'คำอธิบาย',
    'LBL_DUPLICATE' => 'โอกาสทางการขายอาจซ้ำกัน',
    'MSG_DUPLICATE' => 'ระเบียนโอกาสทางการขายที่คุณต้องการสร้างอาจซ้ำกับระเบียนโอกาสทางการขายที่มีอยู่แล้ว ด้านล่างนี้เป็นรายชื่อของระเบียนโอกาสทางการขายที่มีชื่อคล้ายกัน<br>คุณสามารถคลิกบันทึกเพื่อสร้างโอกาสทางการขายใหม่นี้ต่อไป หรือคลิกยกเลิกเพื่อกลับสู่โมดูลโดยไม่สร้างโอกาสทางการขาย',
    'LBL_NEW_FORM_TITLE' => 'สร้างโอกาสทางการขาย',
    'LNK_NEW_OPPORTUNITY' => 'สร้างโอกาสทางการขาย',
    'LNK_CREATE' => 'สร้างข้อตกลง',
    'LNK_OPPORTUNITY_LIST' => 'ดูโอกาสทางการขาย',
    'ERR_DELETE_RECORD' => 'ต้องระบุเลขที่ระเบียนเพื่อลบโอกาสทางการขาย',
    'LBL_TOP_OPPORTUNITIES' => 'โอกาสทางการขายที่เปิดอันดับสูงสุดของฉัน',
    'NTC_REMOVE_OPP_CONFIRMATION' => 'คุณแน่ใจหรือไม่ว่าต้องการย้ายที่อยู่ติดต่อนี้ออกจากโอกาสทางการขาย',
    'OPPORTUNITY_REMOVE_PROJECT_CONFIRM' => 'คุณแน่ใจหรือไม่ว่าต้องการย้ายโอกาสทางการขายนี้ออกจากโครงการ',
    'LBL_DEFAULT_SUBPANEL_TITLE' => 'โอกาสทางการขาย',
    'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'กิจกรรม',
    'LBL_HISTORY_SUBPANEL_TITLE' => 'ประวัติ',
    'LBL_RAW_AMOUNT' => 'จำนวนเงินขั้นต้น',
    'LBL_LEADS_SUBPANEL_TITLE' => 'ผู้สนใจ',
    'LBL_CONTACTS_SUBPANEL_TITLE' => 'ที่อยู่ติดต่อ',
    'LBL_DOCUMENTS_SUBPANEL_TITLE' => 'เอกสาร',
    'LBL_PROJECTS_SUBPANEL_TITLE' => 'โครงการ',
    'LBL_ASSIGNED_TO_NAME' => 'ระบุให้:',
    'LBL_LIST_ASSIGNED_TO_NAME' => 'ผู้ใช้ที่ระบุ',
    'LBL_LIST_SALES_STAGE' => 'ขั้นตอนการขาย',
    'LBL_MY_CLOSED_OPPORTUNITIES' => 'โอกาสทางการขายที่ปิดของฉัน',
    'LBL_TOTAL_OPPORTUNITIES' => 'โอกาสทางการขายรวม',
    'LBL_CLOSED_WON_OPPORTUNITIES' => 'โอกาสทางการขายที่ปิดโดยได้รับการขาย',
    'LBL_ASSIGNED_TO_ID' => 'ผู้ใช้ที่ระบุ:',
    'LBL_CREATED_ID' => 'สร้างโดย ID',
    'LBL_MODIFIED_ID' => 'แก้ไขโดย ID',
    'LBL_MODIFIED_NAME' => 'แก้ไขโดยชื่อผู้ใช้',
    'LBL_CREATED_USER' => 'ผู้ใช้ที่สร้าง',
    'LBL_MODIFIED_USER' => 'ผู้ใช้ที่แก้ไข',
    'LBL_CAMPAIGN_OPPORTUNITY' => 'โอกาสทางการขายของแคมเปญ',
    'LBL_PROJECT_SUBPANEL_TITLE' => 'โครงการ',
    'LABEL_PANEL_ASSIGNMENT' => 'การมอบหมาย',
    'LNK_IMPORT_OPPORTUNITIES' => 'นำเข้าโอกาสทางการขาย',
    'LBL_EDITLAYOUT' => 'แก้ไขเลย์เอาต์' /*for 508 compliance fix*/,
    //For export labels
    'LBL_EXPORT_CAMPAIGN_ID' => 'ID แคมเปญ',
    'LBL_OPPORTUNITY_TYPE' => 'ประเภทโอกาสทางการขาย',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => 'ชื่อผู้ใช้ที่ระบุ',
    'LBL_EXPORT_ASSIGNED_USER_ID' => 'ID ผู้ใช้ที่ระบุ',
    'LBL_EXPORT_MODIFIED_USER_ID' => 'แก้ไขโดย ID',
    'LBL_EXPORT_CREATED_BY' => 'สร้างโดย ID',
    'LBL_EXPORT_NAME' => 'ชื่อ',
    // SNIP
    'LBL_CONTACT_HISTORY_SUBPANEL_TITLE' => 'อีเมลของผู้ติดต่อที่เกี่ยวข้อง',
    'LBL_FILENAME' => 'ไฟล์แนบ',
    'LBL_PRIMARY_QUOTE_ID' => 'การเสนอราคาหลัก',
    'LBL_CONTRACTS' => 'สัญญา',
    'LBL_CONTRACTS_SUBPANEL_TITLE' => 'สัญญา',
    'LBL_PRODUCTS' => 'รายการบรรทัดการเสนอราคา',
    'LBL_RLI' => 'รายการบรรทัดรายได้',
    'LNK_OPPORTUNITY_REPORTS' => 'ดูรายงานโอกาสทางการขาย',
    'LBL_QUOTES_SUBPANEL_TITLE' => 'การเสนอราคา',
    'LBL_TEAM_ID' => 'ID ทีม',
    'LBL_TIMEPERIODS' => 'ช่วงเวลา',
    'LBL_TIMEPERIOD_ID' => 'ID ช่วงเวลา',
    'LBL_COMMITTED' => 'คอมมิตแล้ว',
    'LBL_FORECAST' => 'รวมในประมาณการ',
    'LBL_COMMIT_STAGE' => 'ขั้นตอนที่คอมมิต',
    'LBL_COMMIT_STAGE_FORECAST' => 'ประมาณการ',
    'LBL_WORKSHEET' => 'เวิร์กชีท',

    'TPL_RLI_CREATE' => 'โอกาสทางการขายต้องมีรายการบรรทัดรายได้ที่เกี่ยวข้อง',
    'TPL_RLI_CREATE_LINK_TEXT' => 'สร้างรายการบรรทัดรายได้',
    'LBL_PRODUCTS_SUBPANEL_TITLE' => 'รายการบรรทัดการเสนอราคา',
    'LBL_RLI_SUBPANEL_TITLE' => 'รายการบรรทัดรายได้',

    'LBL_TOTAL_RLIS' => 'จำนวนรายการบรรทัดรายได้ทั้งหมด',
    'LBL_CLOSED_RLIS' => 'จำนวนรายการบรรทัดรายได้ที่ปิด',
    'NOTICE_NO_DELETE_CLOSED_RLIS' => 'คุณไม่สามารถลบโอกาสทางการขายที่มีรายการบรรทัดรายได้ที่ปิด',
    'WARNING_NO_DELETE_CLOSED_SELECTED' => 'ระเบียนที่เลือกไว้บางรายการมีรายการบรรทัดรายได้ที่ปิด และไม่สามารถลบได้',
    'LBL_INCLUDED_RLIS' => 'จำนวนรายการบรรทัดรายได้ที่รวม',

    'LBL_QUOTE_SUBPANEL_TITLE' => 'การเสนอราคา',

    // Config
    'LBL_OPPS_CONFIG_VIEW_BY_LABEL' => 'ลำดับชั้นของโอกาสทางการขาย',
    'LBL_OPPS_CONFIG_VIEW_BY_DATE_ROLLUP' => 'ตั้งค่าฟิลด์วันที่ปิดที่คาดไว้ในระเบียนโอกาสทางการขายที่ได้มาให้เป็นวันที่ปิดเร็วที่สุดหรือช้าที่สุดของรายการบรรทัดรายได้ที่มีอยู่',

    //Dashlet
    'LBL_PIPELINE_TOTAL_IS' => 'ผลรวมของกระบวนการขายคือ ',

    'LBL_OPPORTUNITY_ROLE'=>'บทบาทของโอกาสทางการขาย',
    'LBL_NOTES_SUBPANEL_TITLE' => 'บันทึก',

    // Help Text
    'LBL_OPPS_CONFIG_ALERT' => 'เมื่อคลิกยืนยัน คุณจะลบข้อมูลประมาณการทั้งหมดและเปลี่ยนมุมมองโอกาสทางการขาย ถ้าคุณไม่ได้ต้องการดำเนินการนี้ ให้คลิกยกเลิกเพื่อกลับสู่การตั้งค่าก่อนหน้า',
    'LBL_OPPS_CONFIG_ALERT_TO_OPPS' =>
        'เมื่อคลิกยืนยัน คุณจะลบข้อมูลประมาณการทั้งหมดและเปลี่ยนมุมมองโอกาสทางการขาย '
        .'และการกำหนดกระบวนการทั้งหมดที่มีโมดูลเป้าหมายเป็นรายการบรรทัดรายได้จะถูกปิดใช้งาน '
        .'ถ้าคุณไม่ได้ต้องการดำเนินการนี้ ให้คลิกยกเลิกเพื่อกลับสู่การตั้งค่าก่อนหน้า',
    'LBL_OPPS_CONFIG_SALES_STAGE_1a' => 'ถ้ามีการปิดรายการบรรทัดรายได้ทั้งหมด และมีอย่างน้อยหนึ่งรายการที่ได้รับการขาย',
    'LBL_OPPS_CONFIG_SALES_STAGE_1b' => 'ขั้นตอนการขายของโอกาสทางการขายถูกตั้งค่าเป็น "ปิดโดยได้รับการขาย"',
    'LBL_OPPS_CONFIG_SALES_STAGE_2a' => 'ถ้ารายการบรรทัดรายได้ทั้งหมดอยู่ในขั้นตอนการขาย "ปิดโดยไม่ได้รับการขาย"',
    'LBL_OPPS_CONFIG_SALES_STAGE_2b' => 'ขั้นตอนการขายของโอกาสทางการขายถูกตั้งค่าเป็น "ปิดโดยไม่ได้รับการขาย"',
    'LBL_OPPS_CONFIG_SALES_STAGE_3a' => 'ถ้ายังมีรายการบรรทัดรายได้ที่เปิดอยู่',
    'LBL_OPPS_CONFIG_SALES_STAGE_3b' => 'โอกาสทางการขายจะถูกทำเครื่องหมายเป็นขั้นตอนการขายที่เป็นขั้นต้นที่สุด',

// BEGIN ENT/ULT

    // Opps Config - View By Opportunities
    'LBL_HELP_CONFIG_OPPS' => 'หลังจากที่เริ่มต้นการเปลี่ยนแปลงนี้ หมายเหตุการสรุปรายการบรรทัดรายได้จะถูกสร้างขึ้นในพื้นหลัง เมื่อบันทึกเสร็จสมบูรณ์และใช้ได้ จะมีการส่งการแจ้งเตือนไปยังที่อยู่อีเมลในโปรไฟล์ผู้ใช้ของคุณ ถ้าอินสแตนซ์ของคุณมีการตั้งค่าสำหรับ {{forecasts_module}} ไว้ Sugar จะส่งการแจ้งเตือนถึงคุณเมื่อระเบียน {{module_name}} ของคุณซิงค์กับโมดูล {{forecasts_module}} และสามารถใช้ได้สำหรับ {{forecasts_module}} ใหม่ โปรดทราบว่าอินสแตนซ์ของคุณจะต้องมีการกำหนดค่าให้ส่งอีเมลผ่านทาง การดูแลระบบ > การตั้งค่าอีเมล เพื่อให้ระบบสามารถส่งอีเมลได้',

    // Opps Config - View By Opportunities And RLIs
    'LBL_HELP_CONFIG_RLIS' => 'หลังจากที่คุณเริ่มต้นการเปลี่ยนแปลงนี้ ระเบียนรายการบรรทัดรายได้จะได้รับการสร้างขึ้นสำหรับ {{module_name}} ที่มีอยู่แล้วแต่ละรายการในพื้นหลัง เมื่อรายการบรรทัดรายได้เสร็จสมบูรณ์และใช้ได้ จะมีการส่งการแจ้งเตือนไปยังที่อยู่อีเมลในโปรไฟล์ผู้ใช้ของคุณ โปรดทราบว่าอินสแตนซ์ของคุณจะต้องมีการกำหนดค่าให้ส่งอีเมลผ่านทาง การดูแลระบบ > การตั้งค่าอีเมล เพื่อให้ระบบสามารถส่งอีเมลได้',
    // List View Help Text
    'LBL_HELP_RECORDS' => 'โมดูล{{plural_module_name}}ช่วยให้คุณสามารถติดตามยอดขายแต่ละรายการได้ตั้งแต่ต้นจนจบ ระเบียน{{module_name}} แต่ละตัวแทนการขายที่คาดไว้และรวมข้อมูลการขายที่เกี่ยวข้องรวมถึงที่เกี่ยวข้องกับระเบียนสำคัญอื่นด้วย เช่น {{quotes_module}}, {{contacts_module}} ฯลฯ {{module_name}}ตามปกติจะดำเนินการผ่านหลายขั้นตอนการขายจนกระมั่งถูกระบุว่า "ปิดการขายสำเร็จ" หรือ "ปิดการขายไม่สำเร็จ" {{plural_module_name}}สามารถใช้ประโยชน์ได้มากยิ่งขึ้นโดยใช้โมดูล {{forecasts_singular_module}}ing ของ Sugar ในการทำความเข้าใจและคาดการณ์แนวโน้มการขายรวมถึงการมุ่งทำงานเพื่อให้ได้โควต้าการขาย',

    // Record View Help Text
    'LBL_HELP_RECORD' => 'โมดูล{{plural_module_name}} ช่วยให้คุณสามารถติดตามยอดขายแต่ละรายการได้ตั้งแต่ต้นจนจบ ระเบียน{{module_name}} แต่ละตัวแทนการขายที่คาดไว้และรวมข้อมูลการขายที่เกี่ยวข้องรวมถึงที่เกี่ยวข้องกับระเบียนสำคัญอื่นด้วย เช่น {{quotes_module}}, {{contacts_module}} ฯลฯ

- แก้ไขฟิลด์ของระเบียนนี้โดยคลิกที่ฟิลด์แต่ละตัวหรือคลิกที่ปุ่มแก้ไข
- ดูหรือแก้ไขลิงค์ไปยังระเบียนอื่นในซับพาเนลโดยสลับพาเนลล่างซ้ายเป็น "มุมมองข้อมูล"
- จัดทำและดูความคิดเห็นผู้ใช้และประวัติการเปลี่ยนระเบียนใน {{activitystream_singular_module}} โดยสลับพาเนลล่างซ้ายเป็น "สตรีมกิจกรรม"
- ติดตามหรื่อกำหนดระเบียนนี้เป็นรายการโปรดโดยใช้ไอคอนด้านขวาของชื่อระเบียน
- การดำเนินการอื่นๆจะอยู่ในเมนูการดำเนินการแบบดรอปดาวน์ด้านขวาของปุ่มแก้ไข',

    // Create View Help Text
    'LBL_HELP_CREATE' => 'โมดูล{{plural_module_name}} ช่วยให้คุณสามารถติดตามยอดขายแต่ละรายการและรายการบรรทัดที่อยู่ในการขายเหล่านั้นได้ตั้งแต่ต้นจนจบ ระเบียน{{module_name}} แต่ละตัวแทนการขายที่คาดไว้และรวมข้อมูลการขายที่เกี่ยวข้องรวมถึงที่เกี่ยวข้องกับระเบียนสำคัญอื่นด้วย เช่น {{quotes_module}}, {{contacts_module}} ฯลฯ

ในการสร้าง {{module_name}} ให้ทำดังนี้:
1. ระบุค่าสำหรับฟิลด์ตามที่ต้องการ
 - คุณต้องป้อนข้อมูลในฟิลด์ที่มีคำว่า "ต้องระบุ" กำกับไว้ ก่อนที่จะบันทึก
 - คลิก "แสดงเพิ่มเติม" เพื่อแสดงฟิลด์อื่นๆ หากจำเป็น
2. คลิก "บันทึก" เพื่อสิ้นสุดการสร้างระเบียนใหม่ และกลับสู่เพจก่อนหน้า',

// END ENT/ULT

    //Marketo
    'LBL_MKTO_SYNC' => 'ซิงค์กับ Marketo&reg;',
    'LBL_MKTO_ID' => 'ID ผู้สนใจของ Marketo',

    'LBL_DASHLET_TOP10_SALES_OPPORTUNITIES_NAME' => 'โอกาสทางการขาย 10 อันดับสูงสุด',
    'LBL_TOP10_OPPORTUNITIES_CHART_DESC' => 'แสดงโอกาสทางการขายสิบอันดับสูงสุดในแผนภูมิฟอง',
    'LBL_TOP10_OPPORTUNITIES_MY_OPP' => 'โอกาสทางการขายของฉัน',
    'LBL_TOP10_OPPORTUNITIES_MY_TEAMS_OPP' => "โอกาสทางการขายของทีม",
);
