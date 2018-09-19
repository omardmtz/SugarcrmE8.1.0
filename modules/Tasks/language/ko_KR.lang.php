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

$mod_strings = array (
  // Dashboard Names
  'LBL_TASKS_LIST_DASHBOARD' => '작업 목록 대시보드',

  'LBL_MODULE_NAME' => '업무내역',
  'LBL_MODULE_NAME_SINGULAR' => '업무',
  'LBL_TASK' => '업무내역',
  'LBL_MODULE_TITLE' => '업무내역:홈',
  'LBL_SEARCH_FORM_TITLE' => '업무내역 검색',
  'LBL_LIST_FORM_TITLE' => '업무내역 목록',
  'LBL_NEW_FORM_TITLE' => '신규 업무 추가하기',
  'LBL_NEW_FORM_SUBJECT' => '주제',
  'LBL_NEW_FORM_DUE_DATE' => '마감 날짜:',
  'LBL_NEW_FORM_DUE_TIME' => '마감 시간:',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => '닫기',
  'LBL_LIST_SUBJECT' => '제목',
  'LBL_LIST_CONTACT' => '연락처',
  'LBL_LIST_PRIORITY' => '중요도',
  'LBL_LIST_RELATED_TO' => '연관된 정보',
  'LBL_LIST_DUE_DATE' => '마감일',
  'LBL_LIST_DUE_TIME' => '마감 시간',
  'LBL_SUBJECT' => '제목:',
  'LBL_STATUS' => '상태:',
  'LBL_DUE_DATE' => '마감일:',
  'LBL_DUE_TIME' => '마감 시간:',
  'LBL_PRIORITY' => '중요도:',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => '마감 날짜와 시간',
  'LBL_START_DATE_AND_TIME' => '시작 날짜와 시간',
  'LBL_START_DATE' => '시작일:',
  'LBL_LIST_START_DATE' => '시작일',
  'LBL_START_TIME' => '시작 시간:',
  'LBL_LIST_START_TIME' => '시작 시간:',
  'DATE_FORMAT' => '(yyyy-mm-dd)',
  'LBL_NONE' => '없음',
  'LBL_CONTACT' => '연락처:',
  'LBL_EMAIL_ADDRESS' => '이메일 주소:',
  'LBL_PHONE' => '전화번호:',
  'LBL_EMAIL' => '이메일 주소:',
  'LBL_DESCRIPTION_INFORMATION' => '상세설명',
  'LBL_DESCRIPTION' => '내용:',
  'LBL_NAME' => '이름:',
  'LBL_CONTACT_NAME' => '연락처명',
  'LBL_LIST_COMPLETE' => '완료:',
  'LBL_LIST_STATUS' => '상태',
  'LBL_DATE_DUE_FLAG' => '마감일 미정',
  'LBL_DATE_START_FLAG' => '시작날짜 미정',
  'ERR_DELETE_RECORD' => '연락처를 삭제하기 위해선 정확한 자료 고유번호를 입력하셔야합니다.',
  'ERR_INVALID_HOUR' => '0에서 24까지의 유효한 시간을 입력해 주십시요',
  'LBL_DEFAULT_PRIORITY' => '보통',
  'LBL_LIST_MY_TASKS' => '나의 진행중인 업무내역목록',
  'LNK_NEW_TASK' => '업무 추가하기',
  'LNK_TASK_LIST' => '업무내역 보기',
  'LNK_IMPORT_TASKS' => '업무내역 가져오기',
  'LBL_CONTACT_FIRST_NAME'=>'연락처의 이름',
  'LBL_CONTACT_LAST_NAME'=>'연락처의 성',
  'LBL_LIST_ASSIGNED_TO_NAME' => '담당자',
  'LBL_ASSIGNED_TO_NAME'=>'담당자:',
  'LBL_LIST_DATE_MODIFIED' => '수정일자',
  'LBL_CONTACT_ID' => '연락처 ID:',
  'LBL_PARENT_ID' => '상위 ID:',
  'LBL_CONTACT_PHONE' => '연락처 전화번호:',
  'LBL_PARENT_NAME' => '상위 형태:',
  'LBL_ACTIVITIES_REPORTS' => '활동 보고서',
  'LBL_EDITLAYOUT' => '레이아웃 편집하기' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => '전체 개요',
  'LBL_HISTORY_SUBPANEL_TITLE' => '노트',
  'LBL_REVENUELINEITEMS' => '매출 품목',
  //For export labels
  'LBL_DATE_DUE' => '마감 날짜',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => '담당자명',
  'LBL_EXPORT_ASSIGNED_USER_ID' => '담당자 ID',
  'LBL_EXPORT_MODIFIED_USER_ID' => '수정자 ID',
  'LBL_EXPORT_CREATED_BY' => '생성자 ID',
  'LBL_EXPORT_PARENT_TYPE' => '모듈과 관련',
  'LBL_EXPORT_PARENT_ID' => 'ID와 관련',
  'LBL_TASK_CLOSE_SUCCESS' => '업무가 성공적으로 완료되었습니다.',
  'LBL_ASSIGNED_USER' => '지정자',

    'LBL_NOTES_SUBPANEL_TITLE' => '메모 목록',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{plural_module_name}} 모듈 항목, 또는 완료를 요구 활동의 다른 유형을 수행에 - 유연한 동작으로 구성되어 있습니다. {{module_name}} 레코드 플렉스를 통해 대부분의 모듈에 하나의 레코드와 관련 될 수는 필드 관해서 또한 단일 {{contacts_singular_module}} 관련 될 수있다.{{module_name}} 기록이 생성되면, 당신이 볼 수있는 등, {{plural_module_name}} 를 가져 오는 등의 {{plural_module_name}} 모듈, 복제를 통해 같이 설탕에 {{plural_module_name}} 를 만들 수있는 다양한 방법이 있습니다 그리고 {{plural_module_name}} 기록보기를 통해 {{{module_name}} 에 관한 정보를 편집 할 수 있습니다.{{module_name}} 에 대한 세부 사항에 따라, 당신은 또한 일정 모듈을 통해 {{module_name}} 정보를보고 편집 할 수 있습니다. 각 {{module_name}} 기록은 다음과 같은 {{accounts_module}} {{contacts_module}} {{opportunities_module}}, 그리고 많은 다른 사람과 같은 다른 설탕 레코드와 관련 될 수있다.',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{plural_module_name}} 모듈 항목, 또는 완료를 요구 활동의 다른 유형을 수행에 - 유연한 동작으로 구성되어 있습니다. - 개인 필드 또는 편집 버튼을 클릭하여이 레코드의 필드를 편집합니다. -보기 또는 아래 왼쪽 창에 "데이터보기"를 전환하여 서브 패널에있는 다른 기록에 대한 링크를 수정합니다. - 확인 및보기 사용자의 의견과 왼쪽 하단 창에 "작업 스트림"을 전환하여 {{activitystream_singular_module}}의 기록 변화의 역사. - 레코드 이름의 오른쪽에 아이콘을 사용하여이 기록을 따르거나 좋아. - 추가 작업은 편집 버튼의 오른쪽에있는 드롭 다운 동작 메뉴에서 사용할 수 있습니다.',

    // Create View Help Text
    'LBL_HELP_CREATE' => '{{plural_module_name}} 모듈은 유연한 활동들, 해야할 항목들, 또는 완성할 필요가 있는 다른 유형의 활동들로 구성됩니다.

{{module_name}} 만들기:
1. 원하는필드의값을 지정합니다.
- "필수"로표시된 필드는 저장하기 전에완료해야 합니다. 
- 필요한경우추가필드를노출하려면 "더보기"를클릭합니다. 
2. “저장하기”를 클릭하여 새 기록을 완성하고 이전 페이지로 돌아갑니다.',

);
