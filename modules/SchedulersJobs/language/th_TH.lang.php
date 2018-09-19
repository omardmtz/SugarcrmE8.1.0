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
    'LBL_MODULE_NAME' => 'คิวงาน',
    'LBL_MODULE_NAME_SINGULAR' => 'คิวงาน',
    'LBL_MODULE_TITLE' => 'คิวงาน: หน้าแรก',
    'LBL_MODULE_ID' => 'คิวงาน',
    'LBL_TARGET_ACTION' => 'การดำเนินการ',
    'LBL_FALLIBLE' => 'อาจมีข้อผิดพลาด',
    'LBL_RERUN' => 'เรียกใช้อีกครั้ง',
    'LBL_INTERFACE' => 'ส่วนติดต่อ',
    'LINK_SCHEDULERSJOBS_LIST' => 'ดูคิวงาน',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => 'การกำหนดค่า',
    'LBL_CONFIG_PAGE' => 'การตั้งค่าคิวงาน',
    'LBL_JOB_CANCEL_BUTTON' => 'ยกเลิก',
    'LBL_JOB_PAUSE_BUTTON' => 'หยุดชั่วคราว',
    'LBL_JOB_RESUME_BUTTON' => 'ทำงานต่อ',
    'LBL_JOB_RERUN_BUTTON' => 'เข้าคิวอีกครั้ง',
    'LBL_LIST_NAME' => 'ชื่อ',
    'LBL_LIST_ASSIGNED_USER' => 'ส่งคำขอโดย',
    'LBL_LIST_STATUS' => 'สถานะ',
    'LBL_LIST_RESOLUTION' => 'การแก้ไข',
    'LBL_NAME' => 'ชื่องาน',
    'LBL_EXECUTE_TIME' => 'เวลาเรียกใช้',
    'LBL_SCHEDULER_ID' => 'เครื่องมือวางกำหนดการ',
    'LBL_STATUS' => 'สถานะงาน',
    'LBL_RESOLUTION' => 'ผลลัพธ์',
    'LBL_MESSAGE' => 'ข้อความ',
    'LBL_DATA' => 'ข้อมูลงาน',
    'LBL_REQUEUE' => 'ลองซ้ำเมื่อการทำงานล้มเหลว',
    'LBL_RETRY_COUNT' => 'จำนวนครั้งที่ลองซ้ำสูงสุด',
    'LBL_FAIL_COUNT' => 'การทำงานล้มเหลว',
    'LBL_INTERVAL' => 'ช่วงเวลาต่ำสุดระหว่างการลองซ้ำ',
    'LBL_CLIENT' => 'ไคลเอนต์ที่เป็นเจ้าของ',
    'LBL_PERCENT' => 'เปอร์เซ็นต์ที่เสร็จสมบูรณ์',
    'LBL_JOB_GROUP' => 'กลุ่มงาน',
    'LBL_RESOLUTION_FILTER_QUEUED' => 'การแก้ไขอยู่ในคิวแล้ว',
    'LBL_RESOLUTION_FILTER_PARTIAL' => 'การแก้ไขเป็นบางส่วน',
    'LBL_RESOLUTION_FILTER_SUCCESS' => 'การแก้ไขเสร็จสมบูรณ์',
    'LBL_RESOLUTION_FILTER_FAILURE' => 'การแก้ไขล้มเหลว',
    'LBL_RESOLUTION_FILTER_CANCELLED' => 'การแก้ไขถูกยกเลิก',
    'LBL_RESOLUTION_FILTER_RUNNING' => 'การแก้ไขดำเนินการอยู่',
    // Errors
    'ERR_CALL' => "ไม่สามารถเรียกฟังก์ชัน: %s",
    'ERR_CURL' => "ไม่มี CURL - ไม่สามารถเรียกใช้งานของ URL",
    'ERR_FAILED' => "การทำงานล้มเหลวโดยไม่คาดหมาย โปรดตรวจสอบล็อก PHP และ sugarcrm.log",
    'ERR_PHP' => "%s [%d]: %s ใน %s ที่บรรทัด %d",
    'ERR_NOUSER' => "ไม่ได้ระบุ ID ผู้ใช้สำหรับงาน",
    'ERR_NOSUCHUSER' => "ไม่พบ ID ผู้ใช้ %s",
    'ERR_JOBTYPE' => "ไม่รู้จักประเภทงาน: %s",
    'ERR_TIMEOUT' => "บังคับให้เป็นการทำงานล้มเหลวเมื่อหมดเวลา",
    'ERR_JOB_FAILED_VERBOSE' => 'งาน %1$s (%2$s) ล้มเหลวในการเรียกใช้ CRON',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'ไม่สามารถโหลดบีนที่มี ID: %s',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => 'ไม่พบแฮนด์เลอร์สำหรับเส้นทาง %s',
    'ERR_CONFIG_MISSING_EXTENSION' => 'ไม่ได้ติดตั้งส่วนขยายสำหรับคิวนี้',
    'ERR_CONFIG_EMPTY_FIELDS' => 'บางฟิลด์ไม่มีข้อมูล',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => 'การตั้งค่าคิวงาน',
    'LBL_CONFIG_MAIN_SECTION' => 'การกำหนดค่าหลัก',
    'LBL_CONFIG_GEARMAN_SECTION' => 'การกำหนดค่า Gearman',
    'LBL_CONFIG_AMQP_SECTION' => 'การกำหนดค่า AMQP',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'การกำหนดค่า Amazon-sqs',
    'LBL_CONFIG_SERVERS_TITLE' => 'ความช่วยเหลือสำหรับการกำหนดค่าคิวงาน',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>ส่วนการกำหนดค่าหลัก</b></p>
<ul>
    <li>เครื่องมือเรียกใช้:
    <ul>
    <li><i>มาตรฐาน</i> - ใช้เพียงหนึ่งกระบวนการสำหรับเวิร์กเกอร์</li>
    <li><i>ขนาน</i> - ใข้หลายกระบวนการสำหรับเวิร์กเกอร์</li>
    </ul>
    </li>
    <li>อะแดปเตอร์:
    <ul>
    <li><i>คิวเริ่มต้น</i> - ใช้เฉพาะฐานข้อมูลของ Sugar โดยไม่มีคิวข้อความ</li>
    <li><i>Amazon SQS</i> - Amazon Simple Queue Service เป็นบริการรับส่งข้อความของคิวแบบกระจายที่มาจาก Amazon.com
    สนับสนุนการส่งข้อความทางโปรแกรมผ่านแอปพลิเคชันเว็บเซอร์วิสสำหรับเป็นช่องทางในการสื่อสารผ่านอินเทอร์เน็ต</li>
    <li><i>RabbitMQ</i> - เป็นซอฟต์แวร์ตัวแทนรับส่งข้อความแบบโอเพนซอร์ส (บางครั้งเรียกว่ามิดเดิลแวร์สำหรับข้อความ)
    ซึ่งนำ Advanced Message Queuing Protocol (AMQP) มาใช้</li>
    <li><i>Gearman</i> - เป็นเฟรมเวิร์กของแอปพลิเคชันโอเพนซอร์สที่ออกแบบมาเพื่อกระจายงานของคอมพิวเตอร์ที่เหมาะสม
    ไปยังคอมพิวเตอร์หลายเครื่อง เพื่อให้สามารถทำงานขนาดใหญ่ได้เร็วขึ้น</li>
    <li><i>ทันที</i> - เหมือนคิวเริ่มต้น แต่จะเรียกใช้งานทันทีหลังจากที่เพิ่ม</li>
    </ul>
    </li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'ความช่วยเหลือสำหรับการกำหนดค่า Amazon SQS',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>ส่วนการกำหนดค่า Amazon SQS</b></p>
<ul>
    <li>ID คีย์การเข้าถึง: <i>ป้อนเลขที่ของ ID คีย์การเข้าถึงสำหรับ Amazon SQS</i></li>
    <li>คีย์การเข้าถึงข้อมูลลับ: <i>ป้อนคีย์การเข้าถึงข้อมูลลับสำหรับ Amazon SQS</i></li>
    <li>พื้นที่: <i>ป้อนพื้นที่ของเซิร์ฟเวอร์ Amazon SQS</i></li>
    <li>ชื่อคิว: <i>ป้อนชื่อคิวของเซิร์ฟเวอร์ Amazon SQS</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'ความช่วยเหลือสำหรับการกำหนดค่า AMQP',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>ส่วนการกำหนดค่า AMQP</b></p>
<ul>
    <li>URL เซิร์ฟเวอร์: <i>ป้อน URL เซิร์ฟเวอร์ของคิวข้อความ</i></li>
    <li>ล็อกอิน: <i>ป้อนล็อกอินของคุณสำหรับ RabbitMQ</i></li>
    <li>รหัสผ่าน: <i>ป้อนรหัสผ่านของคุณสำหรับ RabbitMQ</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'ความช่วยเหลือสำหรับการกำหนดค่า Gearman',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>ส่วนการกำหนดค่า Gearman</b></p>
<ul>
    <li>URL ของเซิร์ฟเวอร์: <i>ป้อน URL เซิร์ฟเวอร์ของคิวข้อความ</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => 'อะแดปเตอร์',
    'LBL_CONFIG_QUEUE_MANAGER' => 'เครื่องมือเรียกใช้',
    'LBL_SERVER_URL' => 'URL เซิร์ฟเวอร์',
    'LBL_LOGIN' => 'ล็อกอิน',
    'LBL_ACCESS_KEY' => 'ID คีย์การเข้าถึง',
    'LBL_REGION' => 'พื้นที่',
    'LBL_ACCESS_KEY_SECRET' => 'คีย์การเข้าถึงข้อมูลลับ',
    'LBL_QUEUE_NAME' => 'ชื่ออะแดปเตอร์',
);
