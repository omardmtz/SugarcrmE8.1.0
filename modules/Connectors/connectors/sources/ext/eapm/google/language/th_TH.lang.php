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

$connector_strings = array(
    'LBL_LICENSING_INFO' => '<table border="0" cellspacing="1"><tr><td valign="top" width="35%" class="dataLabel">
รับคีย์ API และข้อมูลลับจาก Google ด้วยการลงทะเบียนอินสแตนซ์ Sugar เป็นแอปพลิเคชันใหม่
<br/><br>ขั้นตอนในการลงทะเบียนอินสแตนซ์ของคุณ:
<br/><br/>
<ol>
<li>ไปที่ไซต์ Google Developers:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>ลงชื่อเข้าสู่ระบบโดยใช้บัญชี Google ที่คุณต้องการใช้ลงทะเบียนแอปพลิเคชัน</li>
<li>สร้างโครงการใหม่</li>
<li>ป้อนชื่อโครงการและคลิกสร้าง</li>
<li>เมื่อมีการสร้างโครงการแล้ว ให้เปิดใช้งาน Google Drive และ Google Contacts API</li>
<li>ในส่วน APIs & Auth > Credentials ให้สร้าง ID ไคลเอนต์ใหม่ </li>
<li>เลือก Web Application และคลิก Configure หน้าจอการยินยอม</li>
<li>ป้อนชื่อผลิตภัณฑ์ และคลิก Save</li>
<li>ในส่วน Authorized redirect URIs ให้ป้อน URL ต่อไปนี้: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>คลิกสร้าง ID ไคลเอนต์</li>
<li>คัดลอก ID ไคลเอนต์และข้อมูลลับของไคลเอนต์ลงในช่องด้านล่าง</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'ID ไคลเอนต์',
    'oauth2_client_secret' => 'ข้อมูลลับของไคลเอนต์',
);
