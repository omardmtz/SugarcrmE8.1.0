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

 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
global $sugar_config;

$mod_strings = array (
// OOTB Scheduler Job Names:
'LBL_OOTB_WORKFLOW'		=> 'งานของเวิร์กโฟลว์ในกระบวนการ',
'LBL_OOTB_REPORTS'		=> 'เรียกใช้งานตามกำหนดการสำหรับการสร้างรายงาน',
'LBL_OOTB_IE'			=> 'ตรวจสอบกล่องจดหมายขาเข้า',
'LBL_OOTB_BOUNCE'		=> 'เรียกใช้อีเมลของแคมเปญที่ตีกลับของกระบวนการในช่วงกลางคืน',
'LBL_OOTB_CAMPAIGN'		=> 'เรียกใช้แคมเปญของอีเมลเป็นกลุ่มในช่วงกลางคืน',
'LBL_OOTB_PRUNE'		=> 'ล้างฐานข้อมูลในวันที่ 1 ของเดือน',
'LBL_OOTB_TRACKER'		=> 'ล้างข้อมูลตารางของเครื่องมือติดตาม',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'ล้างข้อมูลรายการระเบียนเก่า',
'LBL_OOTB_REMOVE_TMP_FILES' => 'นำไฟล์ชั่วคราวออก',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'นำไฟล์ของเครื่องมือวินิจฉัยออก',
'LBL_OOTB_REMOVE_PDF_FILES' => 'นำไฟล์ PDF ชั่วคราวออก',
'LBL_UPDATE_TRACKER_SESSIONS' => 'อัปเดตตาราง tracker_sessions',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'เรียกใช้การแจ้งเตือนทางอีเมล',
'LBL_OOTB_CLEANUP_QUEUE' => 'ล้างคิวงาน',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'สร้างช่วงเวลาในอนาคต',
'LBL_OOTB_HEARTBEAT' => 'สถานะของ Sugar',
'LBL_OOTB_KBCONTENT_UPDATE' => 'อัปเดตบทความ KBContent',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'เผยแพร่บทความที่อนุมัติและกำหนดให้บทความ KB หมดอายุ',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'งานที่จัดลำดับไว้ของ Advanced Workflow',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'สร้างข้อมูลความปลอดภัยของทีมที่ถูกดีนอร์มัลไลซ์ใหม่',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'ช่วงเวลา:',
'LBL_LIST_LIST_ORDER' => 'เครื่องมือวางกำหนดการ:',
'LBL_LIST_NAME' => 'เครื่องมือวางกำหนดการ:',
'LBL_LIST_RANGE' => 'ช่วง:',
'LBL_LIST_REMOVE' => 'นำออก:',
'LBL_LIST_STATUS' => 'สถานะ:',
'LBL_LIST_TITLE' => 'รายการกำหนดการ:',
'LBL_LIST_EXECUTE_TIME' => 'จะเรียกใช้เมื่อ:',
// human readable:
'LBL_SUN'		=> 'วันอาทิตย์',
'LBL_MON'		=> 'วันจันทร์',
'LBL_TUE'		=> 'วันอังคาร',
'LBL_WED'		=> 'วันพุธ',
'LBL_THU'		=> 'วันพฤหัสบดี',
'LBL_FRI'		=> 'วันศุกร์',
'LBL_SAT'		=> 'วันเสาร์',
'LBL_ALL'		=> 'ทุกวัน',
'LBL_EVERY_DAY'	=> 'ทุกวัน ',
'LBL_AT_THE'	=> 'ที่ ',
'LBL_EVERY'		=> 'ทุก ',
'LBL_FROM'		=> 'จาก ',
'LBL_ON_THE'	=> 'ใน ',
'LBL_RANGE'		=> ' ถึง ',
'LBL_AT' 		=> ' ที่ ',
'LBL_IN'		=> ' ใน ',
'LBL_AND'		=> ' และ ',
'LBL_MINUTES'	=> ' นาที ',
'LBL_HOUR'		=> ' ชั่วโมง',
'LBL_HOUR_SING'	=> ' ชั่วโมง',
'LBL_MONTH'		=> ' เดือน',
'LBL_OFTEN'		=> ' บ่อยที่สุดเท่าที่จะทำได้',
'LBL_MIN_MARK'	=> ' เครื่องหมายสำหรับนาที',


// crontabs
'LBL_MINS' => 'นาที',
'LBL_HOURS' => 'ชม.',
'LBL_DAY_OF_MONTH' => 'วันที่',
'LBL_MONTHS' => 'เดือน',
'LBL_DAY_OF_WEEK' => 'วัน',
'LBL_CRONTAB_EXAMPLES' => 'ข้อมูลข้างต้นใช้สัญลักษณ์ crontab มาตรฐาน',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'ข้อกำหนด cron จะทำงานตามเขตเวลาของเซิร์ฟเวอร์ (',
'LBL_CRONTAB_SERVER_TIME_POST' => ') โปรดระบุเวลาเรียกใช้งานของเครื่องมือวางกำหนดการตามเวลาดังกล่าว',
// Labels
'LBL_ALWAYS' => 'เสมอ',
'LBL_CATCH_UP' => 'เรียกใช้ถ้าพลาด',
'LBL_CATCH_UP_WARNING' => 'ไม่เลือกตัวเลือกนี้ถ้างานนี้ใช้เวลานานในการทำงาน',
'LBL_DATE_TIME_END' => 'วันที่และเวลาสิ้นสุด',
'LBL_DATE_TIME_START' => 'วันที่และเวลาเริ่มต้น',
'LBL_INTERVAL' => 'ช่วงเวลา',
'LBL_JOB' => 'งาน',
'LBL_JOB_URL' => 'URL ของงาน',
'LBL_LAST_RUN' => 'เรียกใช้สำเร็จครั้งล่าสุด',
'LBL_MODULE_NAME' => 'เครื่องมือวางกำหนดการ Sugar',
'LBL_MODULE_NAME_SINGULAR' => 'เครื่องมือวางกำหนดการ Sugar',
'LBL_MODULE_TITLE' => 'เครื่องมือวางกำหนดการ',
'LBL_NAME' => 'ชื่องาน',
'LBL_NEVER' => 'ไม่มี',
'LBL_NEW_FORM_TITLE' => 'กำหนดการใหม่',
'LBL_PERENNIAL' => 'ถาวร',
'LBL_SEARCH_FORM_TITLE' => 'การค้นหาเครื่องมือวางกำหนดการ',
'LBL_SCHEDULER' => 'เครื่องมือวางกำหนดการ:',
'LBL_STATUS' => 'สถานะ',
'LBL_TIME_FROM' => 'ใช้งานจาก',
'LBL_TIME_TO' => 'ใช้งานถึง',
'LBL_WARN_CURL_TITLE' => 'คำเตือน cURL:',
'LBL_WARN_CURL' => 'คำเตือน:',
'LBL_WARN_NO_CURL' => 'ระบบนี้ไม่มีไลบรารี cURL เปิดใช้งาน/คอมไพล์เป็นโมดูล PHP (--with-curl=/path/to/curl_library) โปรดติดต่อผู้ดูแลระบบของคุณเพื่อแก้ไขปัญหานี้ หากไม่มีฟังก์ชันการทำงานของ cURL เครื่องมือวางกำหนดการไม่สามารถเธรดงานนี้',
'LBL_BASIC_OPTIONS' => 'การตั้งค่าพื้นฐาน',
'LBL_ADV_OPTIONS'		=> 'ตัวเลือกขั้นสูง',
'LBL_TOGGLE_ADV' => 'แสดงตัวเลือกขั้นสูง',
'LBL_TOGGLE_BASIC' => 'แสดงตัวเลือกพื้นฐาน',
// Links
'LNK_LIST_SCHEDULER' => 'เครื่องมือวางกำหนดการ',
'LNK_NEW_SCHEDULER' => 'สร้างเครื่องมือวางกำหนดการ',
'LNK_LIST_SCHEDULED' => 'งานตามกำหนดการ',
// Messages
'SOCK_GREETING' => "\nนี่คืออินเทอร์เฟซสำหรับบริการเครื่องมือวางกำหนดการ SugarCRM\n[ คำสั่ง daemon ที่ใช้ได้: start|restart|shutdown|status ]\nในการออก ให้พิมพ์ 'quit'  ในการปิดการทำงานของบริการ ให้พิมพ์ 'shutdown'\n",
'ERR_DELETE_RECORD' => 'คุณต้องระบุเลขที่ระเบียนเพื่อลบกำหนดการ',
'ERR_CRON_SYNTAX' => 'รูปแบบคำสั่ง Cron ไม่ถูกต้อง',
'NTC_DELETE_CONFIRMATION' => 'คุณแน่ใจหรือไม่ว่าต้องการลบระเบียนนี้',
'NTC_STATUS' => 'ตั้งค่าสถานะเป็นไม่ใช้งาน เพื่อลบกำหนดการนี้ออกจากรายการดรอปดาวน์ของเครื่องมือวางกำหนดการ',
'NTC_LIST_ORDER' => 'กำหนดลำดับที่กำหนดการนี้จะปรากฏในรายการแบบดรอปดาวน์ของเครื่องมือวางกำหนดการ',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'ในการตั้งค่า Windows Scheduler',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'ในการตั้งค่า Crontab',
'LBL_CRON_LINUX_DESC' => 'หมายเหตุ: ในการเรียกใช้เครื่องมือวางกำหนดการ Sugar ให้เพิ่มบรรทัดต่อไปนี้ในไฟล์ crontab: ',
'LBL_CRON_WINDOWS_DESC' => 'หมายเหตุ: ในการเรียกใช้เครื่องมือวางกำหนดการ Sugar ให้สร้างไฟล์แบทช์เพื่อเรียกใช้ผ่าน Windows Scheduled Tasks ไฟล์แบทช์นี้ควรมีคำสั่งต่อไปนี้: ',
'LBL_NO_PHP_CLI' => 'ถ้าโฮสต์ของคุณไม่มีไบนารี PHP ที่ใช้ได้ คุณสามารถใช้ wget หรือ curl เพื่อเริ่มต้นงานของคุณ<br>สำหรับ wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>สำหรับ curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> 'ล็อกของงาน',
'LBL_EXECUTE_TIME'			=> 'เวลาเรียกใช้',

//jobstrings
'LBL_REFRESHJOBS' => 'รีเฟรชงาน',
'LBL_POLLMONITOREDINBOXES' => 'ตรวจสอบบัญชีอีเมลขาเข้า',
'LBL_PERFORMFULLFTSINDEX' => 'ระบบดัชนีการค้นหาข้อความแบบเต็ม',
'LBL_SUGARJOBREMOVEPDFFILES' => 'นำไฟล์ PDF ชั่วคราวออก',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'เผยแพร่บทความที่อนุมัติและกำหนดให้บทความ KB หมดอายุ.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'เครื่องมือวางกำหนดการคิว Elasticsearch',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'นำไฟล์ของเครื่องมือวินิจฉัยออก',
'LBL_SUGARJOBREMOVETMPFILES' => 'นำไฟล์ชั่วคราวออก',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'สร้างข้อมูลความปลอดภัยของทีมที่ถูกดีนอร์มัลไลซ์ใหม่',

'LBL_RUNMASSEMAILCAMPAIGN' => 'เรียกใช้แคมเปญของอีเมลเป็นกลุ่มในช่วงกลางคืน',
'LBL_ASYNCMASSUPDATE' => 'ดำเนินการอัปเดตเป็นกลุ่มแบบอะซิงโครนัส',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => 'เรียกใช้อีเมลของแคมเปญที่ตีกลับของกระบวนการในช่วงกลางคืน',
'LBL_PRUNEDATABASE' => 'ล้างฐานข้อมูลในวันที่ 1 ของเดือน',
'LBL_TRIMTRACKER' => 'ล้างข้อมูลตารางของเครื่องมือติดตาม',
'LBL_PROCESSWORKFLOW' => 'งานของเวิร์กโฟลว์ในกระบวนการ',
'LBL_PROCESSQUEUE' => 'เรียกใช้งานตามกำหนดการสำหรับการสร้างรายงาน',
'LBL_UPDATETRACKERSESSIONS' => 'อัปเดตตารางเซสชันของเครื่องมือติดตาม',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'สร้างช่วงเวลาในอนาคต',
'LBL_SUGARJOBHEARTBEAT' => 'สถานะของ Sugar',
'LBL_SENDEMAILREMINDERS'=> 'เรียกใช้การส่งการแจ้งเตือนทางอีเมล',
'LBL_CLEANJOBQUEUE' => 'ล้างข้อมูลคิวงาน',
'LBL_CLEANOLDRECORDLISTS' => 'ล้างข้อมูลรายการระเบียนเก่า',
'LBL_PMSEENGINECRON' => 'ตัวจัดลำดับของ Advanced Workflow',
);

