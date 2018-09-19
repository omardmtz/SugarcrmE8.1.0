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
    'LBL_MODULE_NAME' => '이메일 보관',
    'LBL_SNIP_SUMMARY' => "이메일 보관은  우편고객이나 Sugar제공 이메일 주소로부터 사용자가 Sugar로 이메일 가져올수 있도록 하는 자동 가져오기 서비스입니다.  각각의 SUgar예는 고유위 이메일 주소를 가지고 있습니다. 이메일을 가져오려면 사용자가 받는사람, 참조, 숨은참조 필드를 사용해 입력된 이메일을 보냅니다. 이메일 보관 서비스는 Sugar예로 이메일을 보냅니다. 이 서비스는 첨부물, 이미지와 이벤트달력과 함께 이메일을 전송하며 일치하는 이메일 주소에 근거한 기존 기록과 연관된 어플리케이션내 신규 기록을 만듭니다. <br /><br />예 : 사용자로서 거래처 보기시 거래처안의 기록의 이메일 주소에 근거한 거래처 관련 전체 이메일을 볼수 있습니다. 또한 거래처와 관련된 연락처 연관 이메일도 볼수 있습니다.",
	'LBL_REGISTER_SNIP_FAIL' => '이메일 보관 서비스로의 접촉이 실패하였습니다. %',
	'LBL_CONFIGURE_SNIP' => '이메일 보관',
    'LBL_DISABLE_SNIP' => '중지',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => '어플리케이션 유니크 키',
    'LBL_SNIP_USER' => '이메일 보관 사용자',
    'LBL_SNIP_PWD' => '이메일 보관 비밀번호',
    'LBL_SNIP_SUGAR_URL' => 'Sugar 예시 URL',
	'LBL_SNIP_CALLBACK_URL' => '이메일 보관 서비스 URL',
    'LBL_SNIP_USER_DESC' => '이메일 보관 사용자',
    'LBL_SNIP_KEY_DESC' => '이메일 보관 인증키.',
    'LBL_SNIP_STATUS_OK' => '작동',
    'LBL_SNIP_STATUS_OK_SUMMARY' => '다음의 Sugar 예가 이메일 보관 서버에 성공적으로 연결되었습니다.',
    'LBL_SNIP_STATUS_ERROR' => '오류:',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => '이 예시는 유효한 이메일 보관 서비스 라이센스를 가지고 있습니다만 서버에서 다음의 오류 메세지가 돌아왔습니다.',
    'LBL_SNIP_STATUS_FAIL' => '이메일 보관 서버를 등록할수 없습니다.',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => '이메일 보관 서비스를 현재 사용할수 없습니다. 서비스가 불가하거나 Sugar 예시로의 연결이 실패하였습니다.',
    'LBL_SNIP_GENERIC_ERROR' => '이메일 보관 서비스는 현재 사용할수 없습니다. 서비스가 불가하거나 Sugar 예시로의 연결이 실패하였습니다.',

	'LBL_SNIP_STATUS_RESET' => '아직 실행전입니다.',
	'LBL_SNIP_STATUS_PROBLEM' => '문제:%',
    'LBL_SNIP_NEVER' => "사용하지않음",
    'LBL_SNIP_STATUS_SUMMARY' => "이메일 보관 서비스 상태",
    'LBL_SNIP_ACCOUNT' => "거래처",
    'LBL_SNIP_STATUS' => "상태",
    'LBL_SNIP_LAST_SUCCESS' => "최근 성공적 실행",
    "LBL_SNIP_DESCRIPTION" => "이메일 보관 서비스는 자동 이메일 보관 시스템입니다.",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "이는 수동으로 가져오거나 이메일에 링크하지 않고 SugarSRM안의 연락처로부터 또는 연락처로 보내진 이메일을 볼수있도록 합니다.",
    "LBL_SNIP_PURCHASE_SUMMARY" => "이메일 보관 서비스를 사용하려면 SugarCRM 예시로부터 라이센스를 구입해야합니다.",
    "LBL_SNIP_PURCHASE" => "구입하려면 이곳을 클릭하십시오,",
    'LBL_SNIP_EMAIL' => '이메일 보관 주소',
    'LBL_SNIP_AGREE' => "본인은 상기 조항과 개인보호 조약에 동의합니다.",
    'LBL_SNIP_PRIVACY' => '개인보호 조약',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Pingback 이 실패하였습니다.',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => '이메일 보관 서버가 Sugar예로의 연결이 불가는합니다.  재시도 하거나 고객지원팀에 문의하십시오,',

    'LBL_SNIP_BUTTON_ENABLE' => '이메일 보관 작동',
    'LBL_SNIP_BUTTON_DISABLE' => '이메일 보관 중기',
    'LBL_SNIP_BUTTON_RETRY' => '연결 재시도',
    'LBL_SNIP_ERROR_DISABLING' => '이메일 보관 서버와의 대화 시도중 오류가 발생하였으며 서비스를 중지할수 없습니다.',
    'LBL_SNIP_ERROR_ENABLING' => '이메일 보관 서버와의 대화 시도중 오류가 발생하였으며 서비스를 중지할수 없습니다.',
    'LBL_CONTACT_SUPPORT' => '재시도 하거나 SugarCRM 지원에 문의하십시오',
    'LBL_SNIP_SUPPORT' => 'ugarCRM 지원에 문의하십시오',
    'ERROR_BAD_RESULT' => '서비스로부터 좋지않은 결과가 왔습니다.',
	'ERROR_NO_CURL' => 'cURL확장이 필요합니다만 작동되지 않았습니다.',
	'ERROR_REQUEST_FAILED' => '서버에 접촉할수 없습니다.',

    'LBL_CANCEL_BUTTON_TITLE' => '취소',

    'LBL_SNIP_MOUSEOVER_STATUS' => '이것은 귀하의 예시의 이메일 보관 서비스 상태입니다. 이 상태는 이메일 보관 서버와 Sugar예시의 연결 성공여부를 나타냅니다.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => '이메일 보관 이메일 주소는 Sugar로 이메일 가져오기를 위해 보내지는 이메일 입니다.',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => '이것은 이메일 보관 서버의 URL 입니다.  이메일 보관 서비스를 작동하거나 중지하는 것과 같은 모든 요청은 이 URL을 통해 중계됩니다.',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => '이는 귀하의 Sugar 예시의 웹서버 URL입니다. 이메일 보관 서버는 이 URL을 통하여 귀하의 서버로 연결됩니다.',
);
