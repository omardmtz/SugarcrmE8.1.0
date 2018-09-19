<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/

$mod_strings = array(
	'LBL_BASIC_SEARCH'					=> '基礎搜尋',
	'LBL_ADVANCED_SEARCH'				=> '進階搜尋',
	'LBL_BASIC_TYPE'					=> '基本類型',
	'LBL_ADVANCED_TYPE'					=> '進階類型',
	'LBL_SYSOPTS_1'						=> '從下列系統設定選項中選取。',
    'LBL_SYSOPTS_2'                     => '您要安裝的 Sugar 實例將使用哪種資料庫？',
	'LBL_SYSOPTS_CONFIG'				=> '系統設定',
	'LBL_SYSOPTS_DB_TYPE'				=> '',
	'LBL_SYSOPTS_DB'					=> '指定資料庫類型',
    'LBL_SYSOPTS_DB_TITLE'              => '資料庫類型',
	'LBL_SYSOPTS_ERRS_TITLE'			=> '請修復下列錯誤並繼續：',
	'LBL_MAKE_DIRECTORY_WRITABLE'      => '請將下列目錄狀態更改為可寫入：',


    'ERR_DB_LOGIN_FAILURE_IBM_DB2'		=> '所提供的資料庫主機、使用者名稱和/或密碼無效，無法與資料庫建立連接。請輸入有效主機、使用者名稱和密碼。',
    'ERR_DB_IBM_DB2_CONNECT'			=> '所提供的資料庫主機、使用者名稱和/或密碼無效，無法與資料庫建立連接。請輸入有效主機、使用者名稱和密碼。',
    'ERR_DB_IBM_DB2_VERSION'			=> 'Sugar 不支援您的 DB2 (%s) 版本。您必須安裝與 Sugar 應用程式相容的版本。請查詢「發行版本附註」中的「相容性矩陣」，了解支援的 DB2 版本。',

	'LBL_SYSOPTS_DB_DIRECTIONS'			=> '如選取 Oracle，您必須安裝並設定 Oracle 用戶端。',
	'ERR_DB_LOGIN_FAILURE_OCI8'			=> '所提供的資料庫主機、使用者名稱和/或密碼無效，無法與資料庫建立連接。請輸入有效主機、使用者名稱和密碼。',
	'ERR_DB_OCI8_CONNECT'				=> '所提供的資料庫主機、使用者名稱和/或密碼無效，無法與資料庫建立連接。請輸入有效主機、使用者名稱和密碼。',
	'ERR_DB_OCI8_VERSION'				=> 'Sugar 不支援您的 Oracle (%s) 版本。您必須安裝與 Sugar 應用程式相容的版本。請查詢「發行版本附註」中的「相容性矩陣」，了解支援的 Oracle 版本。',
    'LBL_DBCONFIG_ORACLE'               => '請提供資料庫的名稱。此名稱將成為指派給您的使用者的預設資料表空間（來自 tnsnames.ora 的 SID）。',
	// seed Ent Reports
	'LBL_Q'								=> '商機查詢',
	'LBL_Q1_DESC'						=> '按類型區分的商機',
	'LBL_Q2_DESC'						=> '按帳戶區分的商機',
	'LBL_R1'							=> '6 個月銷售案源報表',
	'LBL_R1_DESC'						=> '按月份和類型查詢未來 6 個月的商機',
	'LBL_OPP'							=> '商機資料集',
	'LBL_OPP1_DESC'						=> '您可在此處變更自訂查詢的外觀與風格',
	'LBL_OPP2_DESC'						=> '此查詢將排列於報表第一個查詢下方',
    'ERR_DB_VERSION_FAILURE'			=> '無法檢查資料庫版本',

	'DEFAULT_CHARSET'					=> 'UTF-8',
    'ERR_ADMIN_USER_NAME_BLANK'         => '請提供 Sugar 管理員使用者的使用者名稱。',
	'ERR_ADMIN_PASS_BLANK'				=> '請提供 Sugar 管理員使用者的密碼。',

    'ERR_CHECKSYS'                      => '相容性檢查時偵測到錯誤。為確保 SugarCRM 安裝正常工作，請採取適當步驟解決下列問題，然後按一下「重新檢查」按鈕或嘗試重新安裝。',
    'ERR_CHECKSYS_CALL_TIME'            => 'Allow Call Time Pass Reference 已開啟（在 php.ini 中此選項應設定為關閉）',

	'ERR_CHECKSYS_CURL'					=> '未找到: Sugar 計畫程式將運行有限的功能。電子郵件存檔服務將無法運行。',
    'ERR_CHECKSYS_IMAP'					=> '未找到：輸入電子郵件和宣傳活動（電子郵件）要求使用 IMAP 庫。兩者均不能正常工作。',
	'ERR_CHECKSYS_MSSQL_MQGPC'			=> '使用 MS SQL Server 時，Magic Quotes GPC 不能轉為「On」。',
	'ERR_CHECKSYS_MEM_LIMIT_0'			=> '警告：',
	'ERR_CHECKSYS_MEM_LIMIT_1'			=> '（在 php.ini file 中將此設定為 ',
	'ERR_CHECKSYS_MEM_LIMIT_2'			=> 'M 或更大值）',
	'ERR_CHECKSYS_MYSQL_VERSION'		=> '最低版本 4.1.2 - 已找到：',
	'ERR_CHECKSYS_NO_SESSIONS'			=> '寫入和讀取工作階段變數失敗。無法繼續安裝。',
	'ERR_CHECKSYS_NOT_VALID_DIR'		=> '非有效目錄',
	'ERR_CHECKSYS_NOT_WRITABLE'			=> '警告：不可寫入',
	'ERR_CHECKSYS_PHP_INVALID_VER'		=> 'Sugar 不支援您的 PHP 版本。您需要安裝一個與 Sugar 應用程式相容的版本。請查詢「發行版本附註」中的「相容性矩陣」，了解支援的 PHP 版本。您的版本為 ',
	'ERR_CHECKSYS_IIS_INVALID_VER'      => 'Sugar 不支援您的 IIS 版本。您需要安裝一個與 Sugar 應用程式相容的版本。請查詢「發行版本附註」中的「相容性矩陣」，了解支援的 IIS 版本。您的版本為 ',
	'ERR_CHECKSYS_FASTCGI'              => '我們偵測到您沒有使用 PHP 適用的 FastCGI 常式對應。您需要安裝/設定一個與 Sugar 應用程式相容的版本。請查詢「發行版本附註」中的「相容性矩陣」，了解支援的版本。請查看 <a href="http://www.iis.net/php/" target="_blank">http://www.iis.net/php/</a>  了解詳細資料。',
	'ERR_CHECKSYS_FASTCGI_LOGGING'      => '如需獲得最佳體驗，請使用 IIS/FastCGI sapi，並在 php.ini 檔案中將 fastcgi.logging 設定為 0。',
    'ERR_CHECKSYS_PHP_UNSUPPORTED'		=> '已安裝不支援的 PHP 版本：（版本',
    'LBL_DB_UNAVAILABLE'                => '資料庫不可用',
    'LBL_CHECKSYS_DB_SUPPORT_NOT_AVAILABLE' => '未找到數據庫支援。請確認您已為所支援的以下任一數據庫類型安裝所需的驅動程式：MySQL、MS SQLServer、Oracle，或 DB2。您可能需要移除 php.ini 文件中有關擴展的註解，或使用正確的二進制文件重新編譯，具體視您的 PHP 版本而定。更多關於如何啟用數據庫支援的資訊，請參考您的PHP手冊。',
    'LBL_CHECKSYS_XML_NOT_AVAILABLE'        => '未找到 Sugar 應用程式需要的與 XML 剖析器程式庫關聯的函數。您可能需要根據您的 PHP 版本，取消註解 php.ini 檔案的擴充，或使用正確的二進位檔案重新編譯。請參考 PHP 手冊了解更多資訊。',
    'LBL_CHECKSYS_CSPRNG' => '亂數產生器',
    'ERR_CHECKSYS_MBSTRING'             => '未找到 Sugar 應用程式需要的與多位元組字串 PHP 擴充 (mbstring) 關聯的函數。<br/><br/>一般，mbstring 模組在 PHP 中預設為不啟用，它必須在建立 PHP 二進位時用 --enable-mbstring 啟用。請參考 PHP 手冊了解如何啟用 mbstring 支援的更多資訊。',
    'ERR_CHECKSYS_MCRYPT'               => "Mcrypt module isn't loaded. Please refer to your PHP Manual for more information on how to load mcrypt module.",
    'ERR_CHECKSYS_SESSION_SAVE_PATH_NOT_SET'       => 'PHP 設定檔 (php.ini) 中的 session.save_path 未設定或設定為不存在的資料夾。您需要在 php.ini 中設定 save_path setting 或確認 save_path 中設定的資料夾是否存在。',
    'ERR_CHECKSYS_SESSION_SAVE_PATH_NOT_WRITABLE'  => 'PHP 設定檔 (php.ini) 中的 session.save_path 設定為不可寫入的資料夾。請採取必要的步驟將此資料夾設定為可寫入。<br>根據您採用的作業系統，這可能需要您運行 chmod 766 變更權限，或在檔案名上按右鍵以存取屬性並取消核取只讀選項。',
    'ERR_CHECKSYS_CONFIG_NOT_WRITABLE'  => '設定檔存在但不可寫入。請採取必要的步驟將此檔案設定為可寫入。根據您採用的作業系統，這可能需要您運行 chmod 766 變更權限，或在檔案名上按右鍵以存取屬性並取消核取只讀選項。',
    'ERR_CHECKSYS_CONFIG_OVERRIDE_NOT_WRITABLE'  => '設定覆寫檔案已存在但不可寫入。請採取必要的步驟將此檔案設定為可寫入。根據您採用的作業系統，這可能需要您運行 chmod 766 變更權限，或在檔案名上按右鍵以存取屬性並取消核取只讀選項。',
    'ERR_CHECKSYS_CUSTOM_NOT_WRITABLE'  => '自訂目錄已存在但不可寫入。根據您採用的作業系統，這可能需要您變更其權限 (chmod 766)，或在檔案名上按右鍵以存取屬性並取消核取只讀選項。請採取必要的步驟將此檔案設定為可寫入。',
    'ERR_CHECKSYS_FILES_NOT_WRITABLE'   => "下列檔案和目錄不可寫入或丟失且不可建立。根據您採用的作業系統，修正此問題可能需要您變更檔案或上層目錄的權限 (chmod 755)，或在檔案名上按右鍵以存取屬性並取消核取「只讀」選項，並應用至所有子資料夾。",
	'ERR_CHECKSYS_SAFE_MODE'			=> '安全模式已啟用（您可能要在 php.ini 停用）',
    'ERR_CHECKSYS_ZLIB'					=> '未找到 ZLib 支援：使用 zlib 壓縮可為 SugarCRM 帶來巨大的性能提升。',
    'ERR_CHECKSYS_ZIP'					=> '未找到 ZIP 支援：SugarCRM 需要 ZIP 支援以處理壓縮檔。',
    'ERR_CHECKSYS_BCMATH'				=> '未找到 BCMATH 支援：SugarCRM 需要 BCMATH 支援以進行任意精度數學運算。',
    'ERR_CHECKSYS_HTACCESS'             => '.htaccess 重寫測試失敗。這通常意味著您沒有為 Sugar 目錄設定 AllowOverride。',
    'ERR_CHECKSYS_CSPRNG' => 'CSPRNG 例外狀況',
	'ERR_DB_ADMIN'						=> '提供的資料庫管理員使用者名稱和/或密碼無效，無法與資料庫建立連接。請輸入有效的使用者名稱和密碼（錯誤：',
    'ERR_DB_ADMIN_MSSQL'                => '提供的資料庫管理員使用者名稱和/或密碼無效，無法與資料庫建立連接。請輸入有效的使用者名稱和密碼。',
	'ERR_DB_EXISTS_NOT'					=> '指定的資料庫不存在。',
	'ERR_DB_EXISTS_WITH_CONFIG'			=> '資料庫及設定資料已存在。如需使用選取的資料庫運行安裝，請重新運行安裝，並選擇「刪除並重新建立現有 SugarCRM 表格？」如需升級，請使用管理員主控台的升級精靈。請在<a href="http://www.sugarforge.org/content/downloads/" target="_new">此處</a>閱讀升級檔案。',
	'ERR_DB_EXISTS'						=> '提供的資料庫名稱已存在 -- 無法用相同名稱建立另一個資料庫。',
    'ERR_DB_EXISTS_PROCEED'             => '提供的資料庫名稱已存在。您可以<br>1. 按一下「返回」按鈕，並選擇一個新的資料庫名稱<br>2. 按一下「下一步」繼續建立，但將刪除此資料庫中的所有現有表格。<strong>這表示所有表格和資料都將被刪除。</strong>',
	'ERR_DB_HOSTNAME'					=> '主機名稱不得為空。',
	'ERR_DB_INVALID'					=> '選取的資料庫類型無效。',
	'ERR_DB_LOGIN_FAILURE'				=> '所提供的資料庫主機、使用者名稱和/或密碼無效，無法與資料庫建立連接。請輸入有效主機、使用者名稱和密碼。',
	'ERR_DB_LOGIN_FAILURE_MYSQL'		=> '所提供的資料庫主機、使用者名稱和/或密碼無效，無法與資料庫建立連接。請輸入有效主機、使用者名稱和密碼。',
	'ERR_DB_LOGIN_FAILURE_MSSQL'		=> '所提供的資料庫主機、使用者名稱和/或密碼無效，無法與資料庫建立連接。請輸入有效主機、使用者名稱和密碼。',
	'ERR_DB_MYSQL_VERSION'				=> 'Sugar 不支援您的 MySQL 版本 (%s)。您需要安裝一個與 Sugar 應用程式相容的版本。請查詢「發行版本附註」中的「相容性矩陣」，了解支援的 MySQL 版本。',
	'ERR_DB_NAME'						=> '資料庫名稱不得為空。',
	'ERR_DB_NAME2'						=> "資料庫名稱不能包含 '\\'、'/' 或 '.'",
    'ERR_DB_MYSQL_DB_NAME_INVALID'      => "資料庫名稱不能包含 '\\'、'/' 或 '.'",
    'ERR_DB_MSSQL_DB_NAME_INVALID'      => "資料庫名稱不能以數字、'#' 或 '@' 開頭，且不能包含空格、 '\"'、\"'\"、'*'、'/'、'\\'、'?'、':'、'<', '>'、'&'、'!' 或  '-'",
    'ERR_DB_OCI8_DB_NAME_INVALID'       => "資料庫名稱只能由英數字元和 '#'、'_'、 '-'、':'、'.'、'/' 或 '$' 等符號組成",
	'ERR_DB_PASSWORD'					=> '為 Sugar 資料庫管理員提供的密碼不一致。請在密碼欄位重新輸入相同密碼。',
	'ERR_DB_PRIV_USER'					=> '請提供資料庫管理員使用者名稱。它將在首次連接資料庫時使用。',
	'ERR_DB_USER_EXISTS'				=> 'Sugar 資料庫的使用者名稱已存在 -- 無法用相同名稱建立另一個資料庫。請輸入新的使用者名稱。',
	'ERR_DB_USER'						=> '輸入 Sugar 資料庫管理員的使用者名稱。',
	'ERR_DBCONF_VALIDATION'				=> '請修復下列錯誤並繼續：',
    'ERR_DBCONF_PASSWORD_MISMATCH'      => '為 Sugar 資料庫使用者提供的密碼不一致。請在密碼欄位重新輸入相同密碼。',
	'ERR_ERROR_GENERAL'					=> '發生下列錯誤：',
	'ERR_LANG_CANNOT_DELETE_FILE'		=> '無法刪除檔案：',
	'ERR_LANG_MISSING_FILE'				=> '找不到檔案：',
	'ERR_LANG_NO_LANG_FILE'			 	=> 'include/language 內未找到任何語言套件檔案︰',
	'ERR_LANG_UPLOAD_1'					=> '您的上載有錯誤。請再試一次。',
	'ERR_LANG_UPLOAD_2'					=> '語言套件必須為 ZIP 封存。',
	'ERR_LANG_UPLOAD_3'					=> 'PHP 無法將暫存檔移動至升級目錄。',
	'ERR_LICENSE_MISSING'				=> '缺少必填欄位',
	'ERR_LICENSE_NOT_FOUND'				=> '未找到授權檔案！',
	'ERR_LOG_DIRECTORY_NOT_EXISTS'		=> '提供的記錄目錄非有效目錄。',
	'ERR_LOG_DIRECTORY_NOT_WRITABLE'	=> '提供的記錄目錄非可寫入目錄。',
	'ERR_LOG_DIRECTORY_REQUIRED'		=> '如需指定自己的記錄目錄，則記錄目錄不能為空。',
	'ERR_NO_DIRECT_SCRIPT'				=> '無法直接處理指令碼。',
	'ERR_NO_SINGLE_QUOTE'				=> '無法將單引號用於',
	'ERR_PASSWORD_MISMATCH'				=> '為 Sugar 管理員使用者提供的密碼不一致。請在密碼欄位重新輸入相同密碼。',
	'ERR_PERFORM_CONFIG_PHP_1'			=> '無法寫入至 <span class=stop>config.php</span> 檔案。',
	'ERR_PERFORM_CONFIG_PHP_2'			=> '您可以手動建立 config.php 檔案，並複製以下設定資訊至 config.php 檔案，以繼續此次安裝。但在繼續下一步之前，您<strong>必須</strong>建立 config.php 檔案。',
	'ERR_PERFORM_CONFIG_PHP_3'			=> '您是否記得建立 config.php 檔案？',
	'ERR_PERFORM_CONFIG_PHP_4'			=> '警告：無法寫入至 config.php 檔案。請確保該檔案存在。',
	'ERR_PERFORM_HTACCESS_1'			=> '無法寫入至',
	'ERR_PERFORM_HTACCESS_2'			=> '檔案。',
	'ERR_PERFORM_HTACCESS_3'			=> '如果您要安全儲存記錄檔，防止透過瀏覽器存取，可在記錄目錄中建立一個 .htaccess 檔案，並將以下行包括在內：',
	'ERR_PERFORM_NO_TCPIP'				=> '<b>無法偵測到網際網路連線。</b> 如果沒有連線，請造訪 <a href="http://www.sugarcrm.com/home/index.php?option=com_extended_registration&task=register">http://www.sugarcrm.com/home/index.php?option=com_extended_registration&task=register</a>註冊 SugarCRM。請讓我們了解您的公司計劃如何使用 SugarCRM，以確保我們始終根據您的業務需求提供正確的應用程式。',
	'ERR_SESSION_DIRECTORY_NOT_EXISTS'	=> '提供的工作階段目錄非有效目錄。',
	'ERR_SESSION_DIRECTORY'				=> '提供的工作階段目錄非可寫入目錄。',
	'ERR_SESSION_PATH'					=> '如需指定自己的工作階段路徑，則此路徑不能為空。',
	'ERR_SI_NO_CONFIG'					=> '您沒有在文件根目錄中包含 config_si.php，或您沒有在 config.php 中定義 $sugar_config_si',
	'ERR_SITE_GUID'						=> '如需指定自己的應用程式 ID，則此 ID 不能為空。',
    'ERROR_SPRITE_SUPPORT'              => "目前我們無法尋找 GD 庫，這將導致您無法使用 CSS 精靈功能。",
	'ERR_UPLOAD_MAX_FILESIZE'			=> '警告：您的 PHP 設定應更改為允許上載至少 6MB 的檔案。',
    'LBL_UPLOAD_MAX_FILESIZE_TITLE'     => '上載檔案大小',
	'ERR_URL_BLANK'						=> '為 Sugar 實例提供基礎 URL。',
	'ERR_UW_NO_UPDATE_RECORD'			=> '無法尋找安裝記錄',
    'ERROR_FLAVOR_INCOMPATIBLE'         => '所上傳的文件不兼容此 Sugar 版本（專業版、企業版或旗艦版）： ',
	'ERROR_LICENSE_EXPIRED'				=> "錯誤：授權已到期",
	'ERROR_LICENSE_EXPIRED2'			=> " 天前。請前往位於管理員螢幕的<a href='index.php?action=LicenseSettings&module=Administration'>「授權管理」</a>輸入新的授權金鑰。如果您沒有在授權金鑰過期 30 天內輸入新的授權金鑰，您將無法登入此應用程式。",
	'ERROR_MANIFEST_TYPE'				=> '資訊清單檔必須指定封裝類型。',
	'ERROR_PACKAGE_TYPE'				=> '資訊清單檔指定無法識別的封裝類型',
	'ERROR_VALIDATION_EXPIRED'			=> "錯誤：驗證金鑰已到期",
	'ERROR_VALIDATION_EXPIRED2'			=> " 天前。請前往位於管理員螢幕的<a href='index.php?action=LicenseSettings&module=Administration'>「授權管理」</a>輸入新的驗證金鑰。如果您沒有在驗證金鑰過期 30 天內輸入新的驗證金鑰，您將無法登入此應用程式。",
	'ERROR_VERSION_INCOMPATIBLE'		=> '已上載的檔案與此 Sugar 版本不相容：',

	'LBL_BACK'							=> '返回',
    'LBL_CANCEL'                        => '取消',
    'LBL_ACCEPT'                        => '我接受',
	'LBL_CHECKSYS_1'					=> '為讓 SugarCRM 安裝正常工作，請確保以下所有系統檢查項目均為綠色。如果有任何紅色項目，請採取不要的步驟修復。<BR><BR>如需有關此類系統檢查的說明，請造訪 <a href="http://www.sugarcrm.com/crm/installation" target="_blank"> Sugar Wiki</a>。',
	'LBL_CHECKSYS_CACHE'				=> '可寫入快取子目錄',
    'LBL_DROP_DB_CONFIRM'               => '提供的資料庫名稱已存在。<br>您可以：<br>1.  按一下「取消」按鈕並選取新的資料庫名稱，或<br>2.  按一下「接受」按鈕並繼續。資料庫中的所有已存在表格都將被刪除。<strong>這表示所有表格和已存在資料都將被刪除。</strong>',
	'LBL_CHECKSYS_CALL_TIME'			=> 'PHP Allow Call Time Pass Reference 已關閉',
    'LBL_CHECKSYS_COMPONENT'			=> '元件',
	'LBL_CHECKSYS_COMPONENT_OPTIONAL'	=> '選擇性元件',
	'LBL_CHECKSYS_CONFIG'				=> '可寫入 SugarCRM 設定檔案 (config.php)',
	'LBL_CHECKSYS_CONFIG_OVERRIDE'		=> '可寫入 SugarCRM 設定檔案 (config_override.php)',
	'LBL_CHECKSYS_CURL'					=> 'cURL 模組',
    'LBL_CHECKSYS_SESSION_SAVE_PATH'    => '工作階段儲存路徑設定',
	'LBL_CHECKSYS_CUSTOM'				=> '可寫入自訂目錄',
	'LBL_CHECKSYS_DATA'					=> '可寫入資料子目錄',
	'LBL_CHECKSYS_IMAP'					=> 'IMAP 模組',
	'LBL_CHECKSYS_MQGPC'				=> 'Magic Quotes GPC',
	'LBL_CHECKSYS_MBSTRING'				=> 'MB 字串模組',
    'LBL_CHECKSYS_MCRYPT'               => 'MCrypt Module',
	'LBL_CHECKSYS_MEM_OK'				=> '確定（無限制）',
	'LBL_CHECKSYS_MEM_UNLIMITED'		=> '確定（無限）',
	'LBL_CHECKSYS_MEM'					=> 'PHP 記憶體限制',
	'LBL_CHECKSYS_MODULE'				=> '可寫入模組子目錄和檔案',
	'LBL_CHECKSYS_MYSQL_VERSION'		=> 'MySQL 版本',
	'LBL_CHECKSYS_NOT_AVAILABLE'		=> '不可用',
	'LBL_CHECKSYS_OK'					=> '確定',
	'LBL_CHECKSYS_PHP_INI'				=> 'PHP 設定檔 (php.ini) 位置：',
	'LBL_CHECKSYS_PHP_OK'				=> '確定（版本',
	'LBL_CHECKSYS_PHPVER'				=> 'PHP 版本',
    'LBL_CHECKSYS_IISVER'               => 'IIS 版本',
    'LBL_CHECKSYS_FASTCGI'              => 'FastCGI',
	'LBL_CHECKSYS_RECHECK'				=> '重新檢查',
	'LBL_CHECKSYS_SAFE_MODE'			=> 'PHP 安全模式已關閉',
	'LBL_CHECKSYS_SESSION'				=> '可寫入工作階段儲存路徑（',
	'LBL_CHECKSYS_STATUS'				=> '狀態',
	'LBL_CHECKSYS_TITLE'				=> '系統檢查接受',
	'LBL_CHECKSYS_VER'					=> '已找到：（版本',
	'LBL_CHECKSYS_XML'					=> 'XML 剖析',
	'LBL_CHECKSYS_ZLIB'					=> 'ZLIB 壓縮模組',
	'LBL_CHECKSYS_ZIP'					=> 'ZIP 處理模組',
    'LBL_CHECKSYS_BCMATH'				=> '任意精度數學運算模組',
    'LBL_CHECKSYS_HTACCESS'				=> '.htaccess 的 AllowOverride 設定',
    'LBL_CHECKSYS_FIX_FILES'            => '請修復下列檔案或目錄以繼續：',
    'LBL_CHECKSYS_FIX_MODULE_FILES'     => '請修復下列模組目錄及其中的檔案以繼續：',
    'LBL_CHECKSYS_UPLOAD'               => '可寫入上載目錄',
    'LBL_CLOSE'							=> '關閉',
    'LBL_THREE'                         => '3',
	'LBL_CONFIRM_BE_CREATED'			=> '被建立',
	'LBL_CONFIRM_DB_TYPE'				=> '資料庫類型',
	'LBL_CONFIRM_DIRECTIONS'			=> '請確認下列設定。如果您想要變更任何值，請按一下「返回」以編輯。否則，請按一下「下一步」開始安裝。',
	'LBL_CONFIRM_LICENSE_TITLE'			=> '授權資訊',
	'LBL_CONFIRM_NOT'					=> '非',
	'LBL_CONFIRM_TITLE'					=> '確認設定',
	'LBL_CONFIRM_WILL'					=> '將',
	'LBL_DBCONF_CREATE_DB'				=> '建立資料庫',
	'LBL_DBCONF_CREATE_USER'			=> '建立使用者',
	'LBL_DBCONF_DB_DROP_CREATE_WARN'	=> '注意：核取此方塊<br>將清除所有 Sugar 資料',
	'LBL_DBCONF_DB_DROP_CREATE'			=> '丟棄並重新建立現有 Sugar 表格？',
    'LBL_DBCONF_DB_DROP'                => '丟棄表格',
    'LBL_DBCONF_DB_NAME'				=> '資料庫名稱',
	'LBL_DBCONF_DB_PASSWORD'			=> 'Sugar 資料庫使用者密碼',
	'LBL_DBCONF_DB_PASSWORD2'			=> '重新輸入 Sugar 資料庫使用者密碼',
	'LBL_DBCONF_DB_USER'				=> 'Sugar 資料庫使用者名稱',
    'LBL_DBCONF_SUGAR_DB_USER'          => 'Sugar 資料庫使用者名稱',
    'LBL_DBCONF_DB_ADMIN_USER'          => '資料庫管理員使用者名稱',
    'LBL_DBCONF_DB_ADMIN_PASSWORD'      => '資料庫管理員密碼',
	'LBL_DBCONF_DEMO_DATA'				=> '用示範資料填充資料庫？',
    'LBL_DBCONF_DEMO_DATA_TITLE'        => '選擇示範資料',
	'LBL_DBCONF_HOST_NAME'				=> '主機名稱',
	'LBL_DBCONF_HOST_INSTANCE'			=> '主機實例',
	'LBL_DBCONF_HOST_PORT'				=> '連接埠',
    'LBL_DBCONF_SSL_ENABLED'            => '啟用 SSL 連線',
	'LBL_DBCONF_INSTRUCTIONS'			=> '請在下方輸入您的資料庫設定資訊。如果您不確定如何填寫，建議您使用預設值。',
	'LBL_DBCONF_MB_DEMO_DATA'			=> '在示範資料中使用多位元組文字？',
    'LBL_DBCONFIG_MSG2'                 => '儲存資料庫的網路伺服器或電腦（主機）名稱（如 localhost 或 www.mydomain.com）：',
    'LBL_DBCONFIG_MSG3'                 => '將包含您要建立的 Sugar 實例資料的資料庫名稱：',
    'LBL_DBCONFIG_B_MSG1'               => '管理員可建立資料庫表格和使用者，並可在資料庫中寫入資料。需要資料庫管理員使用名稱和密碼以設定 Sugar 資料庫。',
    'LBL_DBCONFIG_SECURITY'             => '出於安全考慮，您可以指定一名獨佔資料庫使用者以連接至 Sugar 資料庫。該使用者必須能夠在即將為此實例建立的 Sugar 資料庫中寫入、更新和抓取資料。該使用者可以是上述資料庫管理員，您亦可提供新的或現有資料庫使用者資訊。',
    'LBL_DBCONFIG_AUTO_DD'              => '請幫我執行',
    'LBL_DBCONFIG_PROVIDE_DD'           => '提供現有使用者',
    'LBL_DBCONFIG_CREATE_DD'            => '定義要建立的使用者',
    'LBL_DBCONFIG_SAME_DD'              => '與管理員使用者相同',
	//'LBL_DBCONF_I18NFIX'              => 'Apply database column expansion for varchar and char types (up to 255) for multi-byte data?',
    'LBL_FTS'                           => '全文字搜尋',
    'LBL_FTS_INSTALLED'                 => '已安裝',
    'LBL_FTS_INSTALLED_ERR1'            => '未安裝全文字搜尋功能。',
    'LBL_FTS_INSTALLED_ERR2'            => '您依然可安裝但將無法使用全文字搜尋功能。請參閱資料庫伺服器安裝指南了解如何執行此操作，或聯絡您的管理員。',
	'LBL_DBCONF_PRIV_PASS'				=> '特殊資料庫使用者密碼',
	'LBL_DBCONF_PRIV_USER_2'			=> '上述資料庫帳戶是特殊使用者嗎？',
	'LBL_DBCONF_PRIV_USER_DIRECTIONS'	=> '此特殊權限資料庫使用者必須擁有適當的權限來建立資料庫、刪除/建立表格以及建立使用者。此特殊權限使用者只會被用於在安裝過程中執行必要的任務。如果該使用者擁有足夠的權限，您亦可使用和以上一樣的資料庫使用者。',
	'LBL_DBCONF_PRIV_USER'				=> '特殊權限資料庫使用者名稱',
	'LBL_DBCONF_TITLE'					=> '資料庫設定',
    'LBL_DBCONF_TITLE_NAME'             => '提供資料庫名稱',
    'LBL_DBCONF_TITLE_USER_INFO'        => '提供資料庫使用者資訊',
	'LBL_DISABLED_DESCRIPTION_2'		=> '此變更生效之後，您可以按一下下方的「開始」按鈕開始安裝。<i>完成安裝後，您可能要將 \'installer_locked\' 的值變更為 \'true\'。</i>',
	'LBL_DISABLED_DESCRIPTION'			=> '安裝程式已運行過一次。出於安全考慮，它被禁止運行第二次。如果您確定要再次運行，請前往 config.php 檔案並尋找（或新增）名為 「installer_locked」的變數，並將其值設定為 「假值」。此行應如下所示：',
	'LBL_DISABLED_HELP_1'				=> '如需安裝說明，請造訪 SugarCRM',
    'LBL_DISABLED_HELP_LNK'               => 'http://www.sugarcrm.com/forums/',
	'LBL_DISABLED_HELP_2'				=> '支援論壇',
	'LBL_DISABLED_TITLE_2'				=> '已停用 SugarCRM 安裝',
	'LBL_DISABLED_TITLE'				=> '停用 SugarCRM 安裝',
	'LBL_EMAIL_CHARSET_DESC'			=> '您的地區設定中最經常使用的字元集',
	'LBL_EMAIL_CHARSET_TITLE'			=> '輸出電子郵件設定',
    'LBL_EMAIL_CHARSET_CONF'            => '外寄電子郵件字元集',
	'LBL_HELP'							=> '說明',
    'LBL_INSTALL'                       => '安裝',
    'LBL_INSTALL_TYPE_TITLE'            => '安裝選項',
    'LBL_INSTALL_TYPE_SUBTITLE'         => '選擇安裝類型',
    'LBL_INSTALL_TYPE_TYPICAL'          => ' <b>一般安裝</b>',
    'LBL_INSTALL_TYPE_CUSTOM'           => ' <b>自訂安裝</b>',
    'LBL_INSTALL_TYPE_MSG1'             => '一般應用程式功能需要金鑰，但安裝不需要。您現在不需要輸入金鑰，但必須在安裝應用程序之後提供金鑰。',
    'LBL_INSTALL_TYPE_MSG2'             => '安裝需要最少資訊。建議新使用者使用。',
    'LBL_INSTALL_TYPE_MSG3'             => '安裝期間提供需要設定的額外選項。大部分選項亦可在安裝之後從管理員螢幕設定。建議進階使用者使用。',
	'LBL_LANG_1'						=> '如需在 Sugar 使用預設語言（美國英文）以外的其他語言，您現在可以上載並安裝語言套件。您亦可上載並安裝 Sugar 應用程式內部的語言套件。如需跳過此步驟，按一下「下一步」。',
	'LBL_LANG_BUTTON_COMMIT'			=> '安裝',
	'LBL_LANG_BUTTON_REMOVE'			=> '移除',
	'LBL_LANG_BUTTON_UNINSTALL'			=> '解除安裝',
	'LBL_LANG_BUTTON_UPLOAD'			=> '上載',
	'LBL_LANG_NO_PACKS'					=> '無',
	'LBL_LANG_PACK_INSTALLED'			=> '已安裝下列語言套件：',
	'LBL_LANG_PACK_READY'				=> '已準備好安裝下列語言套件：',
	'LBL_LANG_SUCCESS'					=> '語言套件成功上載。',
	'LBL_LANG_TITLE'			   		=> '語言套件',
    'LBL_LAUNCHING_SILENT_INSTALL'     => '立即安裝 Sugar。這可能需要幾分鐘完成。',
	'LBL_LANG_UPLOAD'					=> '上載語言套件',
	'LBL_LICENSE_ACCEPTANCE'			=> '授權接受',
    'LBL_LICENSE_CHECKING'              => '檢查系統相容性。',
    'LBL_LICENSE_CHKENV_HEADER'         => '檢查環境',
    'LBL_LICENSE_CHKDB_HEADER'          => '驗證 DB、FTS 認證。',
    'LBL_LICENSE_CHECK_PASSED'          => '系統已通過相容性檢查。',
    'LBL_LICENSE_REDIRECT'              => '重新導向',
	'LBL_LICENSE_DIRECTIONS'			=> '如果您有授權資訊，請在下列欄位中輸入。',
	'LBL_LICENSE_DOWNLOAD_KEY'			=> '輸入下載金鑰',
	'LBL_LICENSE_EXPIRY'				=> '到期日',
	'LBL_LICENSE_I_ACCEPT'				=> '我接受',
	'LBL_LICENSE_NUM_USERS'				=> '使用者數量',
	'LBL_LICENSE_PRINTABLE'				=> '可列印檢視表',
    'LBL_PRINT_SUMM'                    => '列印摘要',
	'LBL_LICENSE_TITLE_2'				=> 'SugarCRM 授權',
	'LBL_LICENSE_TITLE'					=> '授權資訊',
	'LBL_LICENSE_USERS'					=> '授權使用者',

	'LBL_LOCALE_CURRENCY'				=> '目前設定',
	'LBL_LOCALE_CURR_DEFAULT'			=> '預設貨幣',
	'LBL_LOCALE_CURR_SYMBOL'			=> '貨幣符號',
	'LBL_LOCALE_CURR_ISO'				=> '貨幣代碼 (ISO 4217)',
	'LBL_LOCALE_CURR_1000S'				=> '千位分隔符',
	'LBL_LOCALE_CURR_DECIMAL'			=> '小數點分隔符',
	'LBL_LOCALE_CURR_EXAMPLE'			=> '範例',
	'LBL_LOCALE_CURR_SIG_DIGITS'		=> '有效數字',
	'LBL_LOCALE_DATEF'					=> '預設日期格式',
	'LBL_LOCALE_DESC'					=> 'Sugar 實例內指定的地區設定將應用至整個應用程式。',
	'LBL_LOCALE_EXPORT'					=> '匯入/匯出字元集<br><i>（電子郵件、.csv、vCard、PDF、資料匯入）</i>',
	'LBL_LOCALE_EXPORT_DELIMITER'		=> '匯出 (.csv) 分隔符',
	'LBL_LOCALE_EXPORT_TITLE'			=> '匯入/匯出設定',
	'LBL_LOCALE_LANG'					=> '預設語言',
	'LBL_LOCALE_NAMEF'					=> '預設名稱格式',
	'LBL_LOCALE_NAMEF_DESC'				=> 's = 稱呼<br />f = 名字<br />l = 姓氏',
	'LBL_LOCALE_NAME_FIRST'				=> 'David',
	'LBL_LOCALE_NAME_LAST'				=> 'Livingstone',
	'LBL_LOCALE_NAME_SALUTATION'		=> 'Dr.',
	'LBL_LOCALE_TIMEF'					=> '預設時間格式',
	'LBL_LOCALE_TITLE'					=> '地區設定',
    'LBL_CUSTOMIZE_LOCALE'              => '自訂地區設定',
	'LBL_LOCALE_UI'						=> '使用者介面',

	'LBL_ML_ACTION'						=> '動作',
	'LBL_ML_DESCRIPTION'				=> '描述',
	'LBL_ML_INSTALLED'					=> '安裝日期',
	'LBL_ML_NAME'						=> '名稱',
	'LBL_ML_PUBLISHED'					=> '發佈日期',
	'LBL_ML_TYPE'						=> '類型',
	'LBL_ML_UNINSTALLABLE'				=> '可解除安裝',
	'LBL_ML_VERSION'					=> '版本',
	'LBL_MSSQL'							=> 'SQL 伺服器',
	'LBL_MSSQL_SQLSRV'				    => 'SQL 伺服器（適用於 PHP 的 Microsoft SQL 伺服器驅動）',
	'LBL_MYSQL'							=> 'MySQL',
    'LBL_MYSQLI'						=> 'MySQL（mysqli 擴充）',
	'LBL_IBM_DB2'						=> 'IBM DB2',
	'LBL_NEXT'							=> '下一步',
	'LBL_NO'							=> '否',
    'LBL_ORACLE'						=> 'Oracle',
	'LBL_PERFORM_ADMIN_PASSWORD'		=> '設定網站管理員密碼',
	'LBL_PERFORM_AUDIT_TABLE'			=> '稽核表格/ ',
	'LBL_PERFORM_CONFIG_PHP'			=> '正在建立 Sugar 設定檔',
	'LBL_PERFORM_CREATE_DB_1'			=> '<b>正在建立資料庫</b> ',
	'LBL_PERFORM_CREATE_DB_2'			=> ' <b>在</b> ',
	'LBL_PERFORM_CREATE_DB_USER'		=> '正在建立資料庫使用者名稱和密碼...',
	'LBL_PERFORM_CREATE_DEFAULT'		=> '正在建立預設 Sugar 資料',
	'LBL_PERFORM_CREATE_LOCALHOST'		=> '正在建立 localhost 的資料庫使用者名稱和密碼...',
	'LBL_PERFORM_CREATE_RELATIONSHIPS'	=> '正在建立 Sugar 關係表格',
	'LBL_PERFORM_CREATING'				=> '正在建立/ ',
	'LBL_PERFORM_DEFAULT_REPORTS'		=> '正在建立預設報表',
	'LBL_PERFORM_DEFAULT_SCHEDULER'		=> '正在建立預設排程器工作',
	'LBL_PERFORM_DEFAULT_SETTINGS'		=> '正在插入預設設定',
	'LBL_PERFORM_DEFAULT_USERS'			=> '正在建立預設使用者',
	'LBL_PERFORM_DEMO_DATA'				=> '正在用示範資料填充資料庫表格（這可能會花一些時間）',
	'LBL_PERFORM_DONE'					=> '完成<br>',
	'LBL_PERFORM_DROPPING'				=> '正在刪除/ ',
	'LBL_PERFORM_FINISH'				=> '完成',
	'LBL_PERFORM_LICENSE_SETTINGS'		=> '正在更新授權資訊 ',
	'LBL_PERFORM_OUTRO_1'				=> 'Sugar 設定',
	'LBL_PERFORM_OUTRO_2'				=> '現已完成！',
	'LBL_PERFORM_OUTRO_3'				=> '總時間：',
	'LBL_PERFORM_OUTRO_4'				=> ' 秒。',
	'LBL_PERFORM_OUTRO_5'				=> '大約使用的記憶體：',
	'LBL_PERFORM_OUTRO_6'				=> ' 位元。',
	'LBL_PERFORM_OUTRO_7'				=> '您的系統已完成安裝和設定，已可使用。',
	'LBL_PERFORM_REL_META'				=> '關係中繼資料 ... ',
	'LBL_PERFORM_SUCCESS'				=> '成功！',
	'LBL_PERFORM_TABLES'				=> '建立 Sugar 應用程式表格、稽核表格和關係中繼資料。',
	'LBL_PERFORM_TITLE'					=> '執行設定',
	'LBL_PRINT'							=> '列印',
	'LBL_REG_CONF_1'					=> '請完成下方的簡短表格，以接收來自 SugarCRM 的產品佈告、訓練新聞、特別優惠和特殊活動邀請。我們不會向第三方銷售、租借、共用或以其他方式散發此處收集的資訊。',
	'LBL_REG_CONF_2'					=> '只有您的名稱和電子郵件地址是註冊的必填欄位。所有其他欄位可選擇填寫，但對我們很有幫助。我們不會向第三方銷售、租借、共用或以其他方式散發收集的資訊。',
	'LBL_REG_CONF_3'					=> '感謝您註冊。按一下「完成」按鈕登入至 SugarCRM。初次登入需要使用「admin」用戶名以及您在第 2 步輸入的密碼。',
	'LBL_REG_TITLE'						=> '註冊',
    'LBL_REG_NO_THANKS'                 => '不，謝謝',
    'LBL_REG_SKIP_THIS_STEP'            => '跳過此步驟',
	'LBL_REQUIRED'						=> '* 必填欄位',

    'LBL_SITECFG_ADMIN_Name'            => 'Sugar 應用程式管理員名稱',
	'LBL_SITECFG_ADMIN_PASS_2'			=> '重新輸入 Sugar 管理員使用者密碼',
	'LBL_SITECFG_ADMIN_PASS_WARN'		=> '注意：這會複寫之前任何安裝過程中的管理員密碼',
	'LBL_SITECFG_ADMIN_PASS'			=> 'Sugar 管理員使用者密碼',
	'LBL_SITECFG_APP_ID'				=> '應用程式 ID',
	'LBL_SITECFG_CUSTOM_ID_DIRECTIONS'	=> '若選取，您必須提供應用程式 ID 以複寫自動產生的 ID。此 ID 可確保一個 Sugar 實例的工作階段不會被其他實例使用。如果您有 Sugar 安裝，請務必使用同一個應用程式 ID。',
	'LBL_SITECFG_CUSTOM_ID'				=> '提供自己的應用程式 ID',
	'LBL_SITECFG_CUSTOM_LOG_DIRECTIONS'	=> '若選取，您必須指定記錄目錄，以複寫 Sugar 記錄的預設目錄。無論記錄檔案保存在哪個位置，均可使用 .htaccess 重新導向限制透過網路瀏覽器存取這些記錄。',
	'LBL_SITECFG_CUSTOM_LOG'			=> '使用自訂記錄目錄',
	'LBL_SITECFG_CUSTOM_SESSION_DIRECTIONS'	=> '若選取，您必須提供安全資料夾以儲存 Sugar 工作階段資訊。這可保證工作階段資料在共用伺服器上的安全。',
	'LBL_SITECFG_CUSTOM_SESSION'		=> '在 Sugar 中使用自訂工作階段目錄',
	'LBL_SITECFG_DIRECTIONS'			=> '請在下方輸入您的網站設定資訊。如果您不確定如何填寫這些欄位，我們建議您使用預設值。',
	'LBL_SITECFG_FIX_ERRORS'			=> '<b>請修復下列錯誤並繼續：</b>',
	'LBL_SITECFG_LOG_DIR'				=> '記錄目錄',
	'LBL_SITECFG_SESSION_PATH'			=> '工作階段目錄路徑<br>（必須可寫入）',
	'LBL_SITECFG_SITE_SECURITY'			=> '選取安全選項',
	'LBL_SITECFG_SUGAR_UP_DIRECTIONS'	=> '若選取，系統將定期檢查應用程式的更新版本。',
	'LBL_SITECFG_SUGAR_UP'				=> '自動檢查更新？',
	'LBL_SITECFG_SUGAR_UPDATES'			=> 'Sugar 更新設定',
	'LBL_SITECFG_TITLE'					=> '網站設定',
    'LBL_SITECFG_TITLE2'                => '識別管理員使用者',
    'LBL_SITECFG_SECURITY_TITLE'        => '網站安全',
	'LBL_SITECFG_URL'					=> 'Sugar 實例 URL',
	'LBL_SITECFG_USE_DEFAULTS'			=> '使用預設值？',
	'LBL_SITECFG_ANONSTATS'             => '傳送匿名使用者統計資料？',
	'LBL_SITECFG_ANONSTATS_DIRECTIONS'  => '若選取，Sugar 會在每次系統檢查新版本時，將有關您安裝的<b>匿名</b>統計資料傳送至 SugarCRM Inc.。此資訊將幫助我們更好理解您對應用程式的使用方式並幫助我們改善產品。',
    'LBL_SITECFG_URL_MSG'               => '輸入安裝後用來存取 Sugar 實例的 URL。此 URL 也將用作 Sugar 應用程式頁面的基礎 URL。URL 必須包含網頁伺服器或電腦名稱或 IP 位址。',
    'LBL_SITECFG_SYS_NAME_MSG'          => '輸入系統名稱。此名稱將在使用者造訪 Sugar 應用程式時顯示於瀏覽器標題列。',
    'LBL_SITECFG_PASSWORD_MSG'          => '安裝後，您需要使用 Sugar 管理員使用者（預設使用者名稱 = admin）登入 Sugar 實例。為此管理員使用者輸入密碼。密碼可在首次登入後變更。除提供的預設帳戶外，您還可以輸入另一個管理員使用者名稱。',
    'LBL_SITECFG_COLLATION_MSG'         => '為系統選取定序（排序）設定。此設定將以您使用的特定語言建立表格。如果您的語言不需要特定設置，請使用預設值。',
    'LBL_SPRITE_SUPPORT'                => '精靈支援',
	'LBL_SYSTEM_CREDS'                  => '系統認證',
    'LBL_SYSTEM_ENV'                    => '系統環境',
	'LBL_START'							=> '開始',
    'LBL_SHOW_PASS'                     => '顯示密碼',
    'LBL_HIDE_PASS'                     => '隱藏密碼',
    'LBL_HIDDEN'                        => '<i>（已隱藏）</i>',
//	'LBL_NO_THANKS'						=> 'Continue to installer',
	'LBL_CHOOSE_LANG'					=> '<b>選擇您的語言</b>',
	'LBL_STEP'							=> '步驟',
	'LBL_TITLE_WELCOME'					=> '歡迎使用 SugarCRM',
	'LBL_WELCOME_1'						=> '安裝程式建立 SugarCRM 資料庫表格並設定您開始使用需要的設定變數。整個流程將花費約十分鐘時間。',
    //welcome page variables
    'LBL_TITLE_ARE_YOU_READY'            => '您確定要安裝嗎？',
    'REQUIRED_SYS_COMP' => '需要的系統元件',
    'REQUIRED_SYS_COMP_MSG' =>
                    '開始之前，請確保您擁有以下受支援的系統元件版本：
                      <br>
                      <ul>
                      <li> 資料庫/資料庫管理系統（如：MySQL、SQL Server、Oracle、DB2）</li>
                      <li> 網路伺服器（Apache、IIS）</li>
                      <li> Elasticsearch</li>
                      </ul>
                      請參考版本說明中的相容性矩陣圖，
                      了解與您正在安裝的 Sugar 版本相容的系統元件。<br>',
    'REQUIRED_SYS_CHK' => '初始化系統檢查',
    'REQUIRED_SYS_CHK_MSG' =>
                    '開始安裝流程後，將在儲存 Sugar 檔案的網路伺服器執行系統檢查，
                      確保系統正確設定，並具有所有必要元件
                     以成功完成安裝。 <br><br>
                      系統將檢查以下所有項目：<br>
                      <ul>
                      <li><b>PHP 版本</b> &#8211; 必須與應用程式相容</li>
                                        <li><b>工作階段變數</b> &#8211; 必須正常工作</li>
                                            <li> <b>MB 字串</b> &#8211; 必須安裝並在 php.ini 中啟用</li>

                      <li> <b>資料庫支援</b> &#8211; 必須存在以供 MySQL、SQL
                      Server、Oracle 或 DB2 使用</li>

                      <li> <b>Config.php</b> &#8211; 必須存在且必須擁有適當的權限以供寫入
                                  </li>
					  <li>下列 Sugar 檔案必須可寫入：<ul><li><b>/custom</li>
<li>/cache</li>
<li>/modules</li>
<li>/upload</b></li></ul></li></ul>
                                  如果檢查失敗，您將無法繼續安裝。螢幕將顯示錯誤訊息，說明為何您的系統
                                  沒有通過檢查。
                                  做出必要的變更後，您可再次執行系統檢查，繼續安裝。<br>',
    'REQUIRED_INSTALLTYPE' => '一般或自訂安裝',
    'REQUIRED_INSTALLTYPE_MSG' =>
                    "系統檢查之後，您可以選擇
                      一般或自訂安裝。<br><br>
                      對於<b>一般</b>和<b>自訂</b>安裝，您均需了解以下資訊：<br>
                      <ul>
                      <li><b>資料庫類型</b>，將用於存放 Sugar 資料庫<ul><li>相容的資料庫
                      類型：MySQL、MS SQL Server、Oracle、DB2<br><br></li></ul></li>
                      <li> <b>網路伺服器</b>或電腦（主機）名稱，將用於儲存資料庫
                      <ul><li>如果資料庫儲存在本機電腦，它可以是 <i>localhost</i>，與儲存 Sugar 檔案位於同一網路伺服器或同一電腦上<br><br></li></ul></li>
                      <li><b>資料庫名稱</b>，您可能要用它來存放 Sugar 資料</li>
                        <ul>
                          <li> 您可能已經有一個現有資料庫想要使用。
                          如果您提供現有資料庫的名稱，在安裝過程中定義 Sugar 資料庫時，此資料庫中的表格將被清除</li>
                          <li>如果您還沒有資料庫，您提供的名稱將用於在安裝期間為實例建立的新資料庫。<br><br></li>
                        </ul>
                      <li><b>資料庫管理員使用者名稱和密碼</b> <ul><li>資料庫管理員應該能夠建立表格和使用者，並在資料庫中寫入資料。</li><li>如果資料庫未儲存在您的本機電腦和/或如果您不是資料庫管理員，您可能要
                      連絡資料庫管理員取得此資訊。<br><br></ul></li></li>
                      <li> <b>Sugar 資料庫使用者名稱和密碼</b>
                      </li>
                        <ul>
                          <li> 該使用者可以是資料庫管理員，或者，您可以提供另一名現有資料庫使用者的名稱。
                         </li>
                          <li>如果您想建立一名新的資料庫使用者，
                          您將可以在安裝過程中提供新的使用者名稱和密碼，
                          使用者將在安裝過程中建立。</li>
                        </ul>
                    <li> <b>Elasticsearch 主機和連接埠</b>
                      </li>
                        <ul>
                          <li> Elasticsearch 主機是運行搜尋引擎的主機。它將預設為 localhost，假設與 Sugar 在同一個伺服器上運行搜尋引擎。</li>
                          <li> Elasticsearch 連接埠是將 Sugar 連接至搜尋引擎的連接埠編號。預設為 9200，這是 elasticsearch 的預設值</li>
                        </ul>
                        </ul><p>

                      對於<b>自訂</b>設置，您也需要了解下列資訊：<br>
                      <ul>
                      <li> <b>完成安裝後，URL 將用於存取 Sugar 實例</b>。
                      此 URL 應包含網路伺服器或電腦名稱或 IP 位址。<br><br></li>
                                  <li> [可選] <b>工作階段目錄的路徑</b>如果您想要為 Sugar 資訊使用自訂
                                  工作階段目錄，以保證工作階段資料在共用伺服器上的安全。<br><br></li>
                                  <li> [可選] <b>自訂記錄目錄的路徑</b>如果您希望複寫 Sugar 記錄的預設目錄。<br><br></li>
                                  <li> [可選] <b>應用程式 ID</b> 如果您希望複寫自動產生的 
                                  ID 以確保一個 Sugar 實例的工作階段不會被其他實例使用。<br><br></li>
                                  <li><b>字元集</b>通常在地區設定中使用。<br><br></li></ul>
                                  如需了解詳盡資訊，請參閱安裝指南。
                                ",
    'LBL_WELCOME_PLEASE_READ_BELOW' => '請在繼續安裝前閱讀以下重要資訊。下列資訊將有助於您確定現在是否已準備好安裝該應用程式。',


	'LBL_WELCOME_2'						=> '如需安裝文檔，請訪問 <a href="http://www.sugarcrm.com/crm/installation" target="_blank">Sugar Wiki</a>。<BR><BR> 如需連絡一名 SugarCRM 支援工程師取得安裝幫助，請登入 <a target="_blank" href="http://support.sugarcrm.com">SugarCRM Support Portal</a>，并提交一份支援案例。',
	'LBL_WELCOME_CHOOSE_LANGUAGE'		=> '<b>選擇您的語言</b>',
	'LBL_WELCOME_SETUP_WIZARD'			=> '設定精靈',
	'LBL_WELCOME_TITLE_WELCOME'			=> '歡迎使用 SugarCRM',
	'LBL_WELCOME_TITLE'					=> 'SugarCRM 設定精靈',
	'LBL_WIZARD_TITLE'					=> 'Sugar 設定精靈：',
	'LBL_YES'							=> '是',
    'LBL_YES_MULTI'                     => '是 - 多位元組',
	// OOTB Scheduler Job Names:
	'LBL_OOTB_WORKFLOW'		=> '處理工作流程工作',
	'LBL_OOTB_REPORTS'		=> '執行報表產生排程工作',
	'LBL_OOTB_IE'			=> '檢查輸入信箱',
	'LBL_OOTB_BOUNCE'		=> '夜間執行過流程已退回推廣活動電子郵件',
    'LBL_OOTB_CAMPAIGN'		=> '夜間執行大量電子郵件推廣活動',
	'LBL_OOTB_PRUNE'		=> '在月份第 1 天剪除資料庫',
    'LBL_OOTB_TRACKER'		=> '剪除追蹤器表格',
    'LBL_OOTB_SEND_EMAIL_REMINDERS' => '執行電子郵件提醒通知',
    'LBL_UPDATE_TRACKER_SESSIONS' => '更新 tracker_sessions 表格',
    'LBL_OOTB_CLEANUP_QUEUE' => '清理工作佇列',


    'LBL_FTS_TABLE_TITLE'     => '提供全文字搜尋設定',
    'LBL_FTS_HOST'     => '主機',
    'LBL_FTS_PORT'     => '連接埠',
    'LBL_FTS_TYPE'     => '搜尋引擎類型',
    'LBL_FTS_HELP'      => '如需啟用全文字搜尋，輸入搜索引擎託管的主機和連接埠。Sugar 為 Elasticsearch 引擎提供內設支援。',
    'LBL_FTS_REQUIRED'    => '需使用彈性搜尋。',
    'LBL_FTS_CONN_ERROR'    => '無法連接至全文搜尋伺服器，請驗證您的設定',
    'LBL_FTS_NO_VERSION_AVAILABLE'    => '沒有可用全文搜尋伺服器版本，請驗證您的設定。',
    'LBL_FTS_UNSUPPORTED_VERSION'    => '檢測到不支援的彈性搜尋版本。請使用版本：%s',

    'LBL_PATCHES_TITLE'     => '安裝最新修補程式',
    'LBL_MODULE_TITLE'      => '安裝語言套件',
    'LBL_PATCH_1'           => '如需跳過此步驟，按一下「下一步」。',
    'LBL_PATCH_TITLE'       => '系統修補程式',
    'LBL_PATCH_READY'       => '已準備好安裝下列修補程式：',
	'LBL_SESSION_ERR_DESCRIPTION'		=> "當連接至此網路伺服器時，SugarCRM 依賴 PHP 工作階段儲存重要資訊。您的 PHP 安裝未正確設定工作階段資訊。
											<br><br>常見的錯誤設定是 <b>'session.save_path'</b> 指示詞沒有指向有效目錄。<br>
											<br> 請在下列位置的 php.ini 檔案中修正您的 <a target=_new href='http://us2.php.net/manual/en/ref.session.php'>PHP 設定</a>",
	'LBL_SESSION_ERR_TITLE'				=> 'PHP 工作階段設定錯誤',
	'LBL_SYSTEM_NAME'=>'系統名稱',
    'LBL_COLLATION' => '定序設定',
	'LBL_REQUIRED_SYSTEM_NAME'=>'請提供 Sugar 實例的系統名稱',
	'LBL_PATCH_UPLOAD' => '請從本機電腦選取一個修補檔。',
	'LBL_BACKWARD_COMPATIBILITY_ON' => '「Php 回溯相容模式」已開啟。請將 zend.ze1_compatibility_mode 設定為「關閉」以繼續',

    'advanced_password_new_account_email' => array(
        'subject' => '新帳戶資訊',
        'description' => '此範本將在系統管理員向使用者傳送新密碼時使用。',
        'body' => '<div><table border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\" width="550" align=\\"\\&quot;\\&quot;center\\&quot;\\&quot;\\"><tbody><tr><td colspan=\\"2\\"><p>以下是您的帳戶使用者名稱和臨時密碼：</p><p>使用者名稱：$contact_user_user_name </p><p>密碼：$contact_user_user_hash </p><br><p><a href="$config_site_url">$config_site_url</a></p><br><p>使用上述密碼登入後，可能要求您將其重設為自己選擇的密碼。</p> </td> </tr><tr><td colspan=\\"2\\"></td> </tr> </tbody></table> </div>',
        'txt_body' =>
'
以下是您的帳戶使用者名稱和臨時密碼：
使用者名稱：$contact_user_user_name
密碼：$contact_user_user_hash

$config_site_url

使用上述密碼登入後，可能要求您將其重設為自己選擇的密碼。',
        'name' => '系統產生的密碼電子郵件',
        ),
    'advanced_password_forgot_password_email' => array(
        'subject' => '重設您的帳戶密碼',
        'description' => "此範本用於向使用者傳送連結，按一下此連結可重設使用者帳戶密碼。",
        'body' => '<div><table border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\" width="550" align=\\"\\&quot;\\&quot;center\\&quot;\\&quot;\\"><tbody><tr><td colspan=\\"2\\"><p>您近期請求 $contact_user_pwd_last_changed 以重設您的帳戶密碼。</p><p>按一下下列連結即可重設密碼：</p><p> <a href="$contact_user_link_guid">$contact_user_link_guid</a> </p> </td> </tr><tr><td colspan=\\"2\\"></td> </tr> </tbody></table> </div>',
        'txt_body' =>
'
您近期請求 $contact_user_pwd_last_changed 以重設您的帳戶密碼。

按一下下列連結即可重設密碼：

$contact_user_link_guid',
        'name' => '忘記密碼電子郵件',
        ),
);
