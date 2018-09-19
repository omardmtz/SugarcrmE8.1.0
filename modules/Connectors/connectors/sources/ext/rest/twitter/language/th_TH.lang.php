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
* Description:
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
* Reserved. Contributor(s): contact@synolia.com - www.synolia.com
* *******************************************************************************/


$connector_strings = array (
    'LBL_LICENSING_INFO' => '<table border="0" cellspacing="1">
								<tr>
									<td valign="top" width="35%" class="dataLabel">
										รับคีย์ API และข้อมูลลับจาก Twitter ด้วยการลงทะเบียนอินสแตนซ์ Sugar เป็นแอปพลิเคชันใหม่<br/><br>ขั้นตอนในการลงทะเบียนอินสแตนซ์ของคุณ:<br/><br/>
										<ol>
											<li>ไปที่ไซต์นักพัฒนาของ Twitter: <a href=\'https://apps.twitter.com\' target=\'_blank\'>https://apps.twitter.com</a></li>
											<li>เข้าสู่ระบบโดยใช้บัญชี Twitter ที่คุณต้องการใช้ลงทะเบียนแอปพลิเคชัน</li>
											<li>ภายในฟอร์มการลงทะเบียน ให้ป้อนชื่อของแอปพลิเคชัน ซึ่งเป็นชื่อที่ผู้ใช้จะเห็นเมื่อตรวจสอบสิทธิ์บัญชี Twitter ของตนจากใน Sugar</li>
											<li>ป้อนคำอธิบาย</li>
											<li>ป้อน URL เว็บไซต์ของแอปพลิเคชัน</li>
											<li>ป้อน URL การติดต่อกลับ (สามารถป้อนค่าใดก็ได้ เนื่องจาก Sugar จะข้ามค่านี้ในการตรวจสอบสิทธิ์ ตัวอย่างเช่น: ป้อน URL ของไซต์ Sugar)</li>
											<li>ยอมรับข้อกำหนดในการให้บริการของ Twitter API</li>
											<li>คลิก "สร้างแอปพลิเคชัน Twitter"</li>
											<li>ภายในเพจแอปพลิเคชัน ให้ค้นหาคีย์ API และข้อมูลลับของ API ในแท็บ "API Keys" ป้อนคีย์และข้อมูลลับด้านล่าง</li>
										</ol>
									</td>
								</tr>
							</table>',
    'LBL_NAME' => 'ชื่อผู้ใช้ Twitter',
    'LBL_ID' => 'ชื่อผู้ใช้ Twitter',
	'company_url' => 'URL',
    'oauth_consumer_key' => 'คีย์ API',
    'oauth_consumer_secret' => 'ข้อมูลลับของ API',
);

?>
