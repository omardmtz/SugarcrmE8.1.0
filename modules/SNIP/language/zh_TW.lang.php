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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array(
    'LBL_MODULE_NAME' => '電子郵件正在封存',
    'LBL_SNIP_SUMMARY' => "「電子郵件封存」是一種自動匯入服務，它允許使用者透過任何郵件用戶端或服務將電子郵件傳送至 Sugar 提供的電子郵件地址，從而將電子郵件匯入至 Sugar。每個 Sugar 實例都擁有自己唯一的電子郵件地址。如需匯入電子郵件，使用者可使用 TO、CC、BCC 欄位將其傳送至提供的電子郵件地址。「電子郵件封存」服務會將電子郵件匯入至 Sugar 實例。此項服務匯入電子郵件，以及任何附件、圖像和「行事曆」事件，並可基於匹配的電子郵件地址，在與現有記錄相關的應用程式中建立記錄。
    <br><br>例如：作為使用者，當檢視「帳戶」時，可以根據「帳戶」記錄中的電子郵件地址，查看與該帳戶關聯的所有電子郵件。也可以查看與該帳戶關聯的「連絡人」的相關電子郵件。
    <br><br>接受下列條款，並按一下「啟用」即可開始使用此服務。您亦可以隨時停用此服務 。服務一旦啟用，即會顯示此服務所使用的電子郵件地址。
    <br><br>",
	'LBL_REGISTER_SNIP_FAIL' => '連絡電子郵件封存服務：%s 失敗！<br>',
	'LBL_CONFIGURE_SNIP' => '電子郵件正在封存',
    'LBL_DISABLE_SNIP' => '停用',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => '應用程式唯一索引鍵',
    'LBL_SNIP_USER' => '電子郵件封存使用者',
    'LBL_SNIP_PWD' => '電子郵件封存密碼',
    'LBL_SNIP_SUGAR_URL' => '此 Sugar 實例 URL',
	'LBL_SNIP_CALLBACK_URL' => '電子郵件封存服務 URL',
    'LBL_SNIP_USER_DESC' => '電子郵件封存使用者',
    'LBL_SNIP_KEY_DESC' => '電子郵件封存 OAuth 金鑰。用於存取此實例，以匯入電子郵件。',
    'LBL_SNIP_STATUS_OK' => '已啟用',
    'LBL_SNIP_STATUS_OK_SUMMARY' => '此 Sugar 實例成功連接至「電子郵件封存」伺服器。',
    'LBL_SNIP_STATUS_ERROR' => ' 錯誤',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => '此實例擁有有效的「電子郵件封存」伺服器授權，但是伺服器返回以下錯誤訊息：',
    'LBL_SNIP_STATUS_FAIL' => '無法在「電子郵件封存」伺服器上註冊',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => '「電子郵件封存」服務目前不可用。服務關閉或此 Sugar 實例連接失敗。',
    'LBL_SNIP_GENERIC_ERROR' => '「電子郵件封存」服務目前不可用。服務關閉或此 Sugar 實例連接失敗。',

	'LBL_SNIP_STATUS_RESET' => '尚未執行',
	'LBL_SNIP_STATUS_PROBLEM' => '問題：%s',
    'LBL_SNIP_NEVER' => "從不",
    'LBL_SNIP_STATUS_SUMMARY' => "電子郵件封存服務狀態：",
    'LBL_SNIP_ACCOUNT' => "帳戶",
    'LBL_SNIP_STATUS' => "狀態",
    'LBL_SNIP_LAST_SUCCESS' => "上次成功執行",
    "LBL_SNIP_DESCRIPTION" => "「電子郵件封存」服務是一種自動電子郵件封存系統",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "透過它，您可查看 SugarCRM 中的傳送至連絡人或由連絡人傳送的電子郵件，而無需手動匯入和連結電子郵件",
    "LBL_SNIP_PURCHASE_SUMMARY" => "為使用「電子郵件封存」，必須購買 SugarCRM 實例授權",
    "LBL_SNIP_PURCHASE" => "按一下此處購買",
    'LBL_SNIP_EMAIL' => '電子郵件封存地址',
    'LBL_SNIP_AGREE' => "我同意以上條款和 <a href='http://www.sugarcrm.com/crm/TRUSTe/privacy.html' target='_blank'>隱私權合約</a>。",
    'LBL_SNIP_PRIVACY' => '隱私權合約',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Pingback 失敗',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => '「電子郵件封存」伺服器無法與 Sugar 實例建立連接。請重試或 <a href="http://www.sugarcrm.com/crm/case-tracker/submit.html?lsd=supportportal&tmpl=" target="_blank">連絡客戶支援部</a>。',

    'LBL_SNIP_BUTTON_ENABLE' => '啟用電子郵件封存',
    'LBL_SNIP_BUTTON_DISABLE' => '停用電子郵件封存',
    'LBL_SNIP_BUTTON_RETRY' => '重試連接',
    'LBL_SNIP_ERROR_DISABLING' => '嘗試與「電子郵件封存」伺服器通訊時出錯，且服務無法停用',
    'LBL_SNIP_ERROR_ENABLING' => '嘗試與「電子郵件封存」伺服器通訊時出錯，且服務無法啟用',
    'LBL_CONTACT_SUPPORT' => '請重試或連絡 SugarCRM 支援部。',
    'LBL_SNIP_SUPPORT' => '請連絡 SugarCRM 支援部，獲取協助。',
    'ERROR_BAD_RESULT' => '服務傳回不良結果',
	'ERROR_NO_CURL' => '需使用 cURL 延伸模組，但是它未啟用',
	'ERROR_REQUEST_FAILED' => '無法連絡伺服器',

    'LBL_CANCEL_BUTTON_TITLE' => '取消',

    'LBL_SNIP_MOUSEOVER_STATUS' => '這是實例中「電子郵件封存」服務的狀態。此狀態表明「電子郵件封存」伺服器與 Sugar 實例間的連接是否成功。',
    'LBL_SNIP_MOUSEOVER_EMAIL' => '這是要傳送到的「電子郵件封存」電子郵件地址，以將電子郵件匯入至 Sugar。',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => '這是「電子郵件封存」伺服器的 URL。所有請求，比如啟用和停用「電子郵件封存」服務，將透過此 URL 轉送。',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => '這是 Sugar 實例的 Web 服務 URL。「電子郵件封存」伺服器將透過此 URL 連接至您的伺服器。',
);
