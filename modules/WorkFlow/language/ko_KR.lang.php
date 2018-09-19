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

$mod_strings = array (
  'LBL_MODULE_NAME' => '작업흐름 정의',
  'LBL_MODULE_NAME_SINGULAR' => '작업흐름 정의',
  'LBL_MODULE_ID' => '작업흐름',  
  'LBL_MODULE_TITLE' => '작업 흐름 : 홈',
  'LBL_SEARCH_FORM_TITLE' => '작업흐름 검색',
  'LBL_LIST_FORM_TITLE' => '작업 흐름 목록',
  'LBL_NEW_FORM_TITLE' => '작업흐름 정의 새로 만들기',
  'LBL_LIST_NAME' => '고객명',
  'LBL_LIST_TYPE' => '실행 발생',
  'LBL_LIST_BASE_MODULE' => '목표 모듈',
  'LBL_LIST_STATUS' => '상태',
  'LBL_NAME' => '고객명',
  'LBL_DESCRIPTION' => '설명:',
  'LBL_TYPE' => '실행 발생',
  'LBL_STATUS' => '상태',
  'LBL_BASE_MODULE' => '목표 모듈',
  'LBL_LIST_ORDER' => '진행 순서',
  'LBL_FROM_NAME' => '발신인',
  'LBL_FROM_ADDRESS' => '발신 주소',  
  'LNK_NEW_WORKFLOW' => '작업흐름 정의 새로 만들기',
  'LNK_WORKFLOW' => '작업흐름 정의 목록', 
  
  
  'LBL_ALERT_TEMPLATES' => '알림 템플릿',
  'LBL_CREATE_ALERT_TEMPLATE' => '알림 템플릿 새로 만들기',
  'LBL_SUBJECT' => '제목',
  
  'LBL_RECORD_TYPE' => '적용',
 'LBL_RELATED_MODULE'=> '연관 모듈',
  
  
  'LBL_PROCESS_LIST' => '작업흐름 순서',
	'LNK_ALERT_TEMPLATES' => '알림 이메일 템플릿',
	'LNK_PROCESS_VIEW' => '작업흐름 순서',
  'LBL_PROCESS_SELECT' => '모듈하나를 선택하십시오.',
  'LBL_LACK_OF_TRIGGER_ALERT'=> '알림: 이 작업흐름의 목적기능을 위한 유발장치를 새로 만들어야합니다.',
  'LBL_LACK_OF_NOTIFICATIONS_ON'=> '공지 : 알림을 보내려면  관리자의 이메일 설정에 SMTP서버 정보를 입력하십시오.',
  'LBL_FIRE_ORDER' => '진행 순서',
  'LBL_RECIPIENTS' => '수신인',
  'LBL_INVITEES' => '초대자 목록',
  'LBL_INVITEE_NOTICE' => '주의, 새로 만들려면 최소 한명이상의 초대자를 선택해야 합니다.',
  'NTC_REMOVE_ALERT' => '이 작업흐름을 제거하시겠습니까?',
  'LBL_EDIT_ALT_TEXT' => 'Alt 본문',
  'LBL_INSERT' => '삽입',
  'LBL_SELECT_OPTION' => '선택사항중 하나를 선택하십시오.',
  'LBL_SELECT_VALUE' => '반드시 가치를 선택해야 합니다.',
  'LBL_SELECT_MODULE' => '관련 모듈을 선택하십시오.',
  'LBL_SELECT_FILTER' => '관련 모듈 필터가 있는 필드를 선택해야 합니다.',
  'LBL_LIST_UP' => '위로',
  'LBL_LIST_DN' => '아래로',
  'LBL_SET' => '설정',
  'LBL_AS' => 'as',
  'LBL_SHOW' => '보기',
  'LBL_HIDE' => '숨기기',
  'LBL_SPECIFIC_FIELD' => '특정 필드',
  'LBL_ANY_FIELD' => '아무 필드',
  'LBL_LINK_RECORD'=>'기록에 링크',
  'LBL_INVITE_LINK'=>'회의/전화 초대 링크',
  'LBL_PLEASE_SELECT'=>'선택해 주십시오',
  'LBL_BODY'=>'본문',
  'LBL__S'=>'&#39;s',
  'LBL_ALERT_SUBJECT'=>'작업흐름 알림',
  'LBL_ACTION_ERROR'=>'이 행동은 실행될수 없습니다. 모든 필드와 필드 가치가 유효하도록 편집하십시오.',
  'LBL_ACTION_ERRORS'=>'알림: 아래 하나 이상의 행동에 오류가 있습니다.',
  'LBL_ALERT_ERROR'=>'이 알림은 실행될수 없습니다. 설정이 유효하도록 알림을 편집하십시오.',
  'LBL_ALERT_ERRORS'=>'공지: 아래 하나 이상의 알림에 오류가 있습니다.',
  'LBL_TRIGGER_ERROR'=>'공지: 이 유발자는 유효하지 않은 가치를 포함하고 있으며 발표되지 않습니다.',
  'LBL_TRIGGER_ERRORS'=>'공지: 아래 하나 이상의 유발자에 오류가 있습니다.',
  'LBL_UP' => '위로' /*for 508 compliance fix*/,
  'LBL_DOWN' => '아래로' /*for 508 compliance fix*/,
  'LBL_EDITLAYOUT' => '지면 배치 편집하기' /*for 508 compliance fix*/,
  'LBL_EMAILTEMPLATES_TYPE_LIST_WORKFLOW' => array('workflow' => '작업흐름'),
  'LBL_EMAILTEMPLATES_TYPE' => '종류',

  // Workflow sunsetting message, updated for 7.9
  'LBL_WORKFLOW_SUNSET_NOTICE' => '<strong>Note:</strong> The Sugar Workflow and Workflow Management functionality will be removed in a future release of Sugar. Sugar Enterprise edition customers should begin to use the functionality provided by Sugar Advanced Workflow. Click <a href="http://www.sugarcrm.com/wf-eol" target="_blank">here</a> for more information.',
);

