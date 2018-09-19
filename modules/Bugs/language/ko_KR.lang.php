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
  'LBL_BUGS_LIST_DASHBOARD' => '버그 목록 대시보드',
  'LBL_BUGS_RECORD_DASHBOARD' => '버그 기록 대시보드',

  'LBL_MODULE_NAME' => '버그',
  'LBL_MODULE_NAME_SINGULAR'	=> '버그',
  'LBL_MODULE_TITLE' => '버그 트래커:홈',
  'LBL_MODULE_ID' => '버그',
  'LBL_SEARCH_FORM_TITLE' => '버그 검색',
  'LBL_LIST_FORM_TITLE' => '버그 목록',
  'LBL_NEW_FORM_TITLE' => '새 버그',
  'LBL_CONTACT_BUG_TITLE' => '연락처-버그:',
  'LBL_SUBJECT' => '제목',
  'LBL_BUG' => '버그',
  'LBL_BUG_NUMBER' => '버그 번호',
  'LBL_NUMBER' => '번호',
  'LBL_STATUS' => '상태',
  'LBL_PRIORITY' => '중요도',
  'LBL_DESCRIPTION' => '설명',
  'LBL_CONTACT_NAME' => '연락처명',
  'LBL_BUG_SUBJECT' => '버그명',
  'LBL_CONTACT_ROLE' => '역할',
  'LBL_LIST_NUMBER' => '번호',
  'LBL_LIST_SUBJECT' => '제목',
  'LBL_LIST_STATUS' => '상태',
  'LBL_LIST_PRIORITY' => '중요도',
  'LBL_LIST_RELEASE' => '릴리즈',
  'LBL_LIST_RESOLUTION' => '해결',
  'LBL_LIST_LAST_MODIFIED' => '최종 수정',
  'LBL_INVITEE' => '연락처',
  'LBL_TYPE' => '종류',
  'LBL_LIST_TYPE' => '종류',
  'LBL_RESOLUTION' => '해결책',
  'LBL_RELEASE' => '릴리즈',
  'LNK_NEW_BUG' => '버그 리포팅',
  'LNK_CREATE'  => '버그 리포팅',
  'LNK_CREATE_WHEN_EMPTY'    => '지금 버그 리포팅',
  'LNK_BUG_LIST' => '버그 보기',
  'LBL_SHOW_MORE' => '더 많은 버그 보기',
  'NTC_REMOVE_INVITEE' => '이 버그로부터 이 연락처를 삭제하시겠습니까?',
  'NTC_REMOVE_ACCOUNT_CONFIRMATION' => '거래처로부터 이 버그를 삭제하시겠습니까?',
  'ERR_DELETE_RECORD' => '결버를 삭제하시려면 정확한 고유번호를 입력하셔야합니다.',
  'LBL_LIST_MY_BUGS' => '나에게 배정된 버그',
  'LNK_IMPORT_BUGS' => '버그내역 가져오기',
  'LBL_FOUND_IN_RELEASE' => '다음 릴리즈에서 발견',
  'LBL_FIXED_IN_RELEASE' => '다음에 수정',
  'LBL_LIST_FIXED_IN_RELEASE' => '다음 릴리즈에서 수정',
  'LBL_WORK_LOG' => '활동 로그',
  'LBL_SOURCE' => '출처',
  'LBL_PRODUCT_CATEGORY' => '분류',

  'LBL_CREATED_BY' => '생성자',
  'LBL_DATE_CREATED' => '생성일',
  'LBL_MODIFIED_BY' => '최종 수정자',
  'LBL_DATE_LAST_MODIFIED' => '수정일',

  'LBL_LIST_EMAIL_ADDRESS' => '이메일',
  'LBL_LIST_CONTACT_NAME' => '연락처명',
  'LBL_LIST_ACCOUNT_NAME' => '고객명',
  'LBL_LIST_PHONE' => '전화번호',
  'NTC_DELETE_CONFIRMATION' => '이 버그로부처 연락처를 삭제하시겠습니까?',

  'LBL_DEFAULT_SUBPANEL_TITLE' => '버그 트래커',
  'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'예정된 활동',
  'LBL_HISTORY_SUBPANEL_TITLE'=>'완료된 활동',
  'LBL_CONTACTS_SUBPANEL_TITLE' => '연락처 목록',
  'LBL_ACCOUNTS_SUBPANEL_TITLE' => '거래처',
  'LBL_CASES_SUBPANEL_TITLE' => '사례',
  'LBL_PROJECTS_SUBPANEL_TITLE' => '프로젝트',
  'LBL_DOCUMENTS_SUBPANEL_TITLE' => '문서',
  'LBL_LIST_ASSIGNED_TO_NAME' => '담당자',
	'LBL_ASSIGNED_TO_NAME' => '담당자',

	'LNK_BUG_REPORTS' => '버그와 관련된 보고서 보기',
	'LBL_SHOW_IN_PORTAL' => '포탈에 보여주기',
	'LBL_BUG_INFORMATION' => '기본정보',

    //For export labels
	'LBL_FOUND_IN_RELEASE_NAME' => '다음 릴리즈에서 발견',
    'LBL_PORTAL_VIEWABLE' => '포탈 게시가능',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => '담당자명',
    'LBL_EXPORT_ASSIGNED_USER_ID' => '담당자 ID',
    'LBL_EXPORT_FIXED_IN_RELEASE_NAMR' => '다음 릴리즈에서 수정',
    'LBL_EXPORT_MODIFIED_USER_ID' => '수정자 ID',
    'LBL_EXPORT_CREATED_BY' => '생성자 ID',

    //Tour content
    'LBL_PORTAL_TOUR_RECORDS_INTRO' => '이 모듈은 버그를 관리하는 모듈입니다. 둘러보기를 위해 아래 화살표를 사용하십시요.',
    'LBL_PORTAL_TOUR_RECORDS_PAGE' => '이 페이지는 버그목록을 보여줍니다.',
    'LBL_PORTAL_TOUR_RECORDS_FILTER' => '검색어을 입력하여 버그를 검색할 수 있습니다.',
    'LBL_PORTAL_TOUR_RECORDS_FILTER_EXAMPLE' => '등록되었던 버그들을 찾는데 사용할수 있습니다.',
    'LBL_PORTAL_TOUR_RECORDS_CREATE' => '보고하고자 하는 신규 버그를 발견하였다면 이곳을 클릭하십시요.',
    'LBL_PORTAL_TOUR_RECORDS_RETURN' => '이곳을 클릭하면 언제든지 이곳 창으로 돌아올수 있습니다.',

    'LBL_NOTES_SUBPANEL_TITLE' => '메모 목록',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{plural_module_name}} 모듈은 내부적으로 발견 또는 고객이보고 하나, 일반적으로 {{plural_module_name}} 또는 결함이라 제품 관련 문제를 추적하고 관리하는 데 사용됩니다.{{}} plural_module_name 더 릴리스에서 발견하고 고정을 추적하여 triaged 할 수 있습니다.{{plural_module_name}} 모듈은 사용자에게 신속하게 모든 {{모듈 _}}의 세부 사항 및 프로세스가 그것을 조정하는 데 사용되는을 검토 할 수있는 방법을 제공합니다.{{}} 모듈 _이 작성 또는 제출되면, 당신은 {{모듈 _}}의 기록보기를 통해 {{모듈 _}}에 관한 정보를보고 편집 할 수 있습니다. 각 모듈 _ {{}} 기록은 다음과 같은 {{calls_module}} {{contacts_module}} {{cases_module}}, 그리고 많은 다른 사람과 같은 다른 설탕 레코드와 관련 될 수있다.',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{plural_module_name}} 모듈은 내부적으로 발견 또는 고객이보고 하나, 일반적으로 {{plural_module_name}} 또는 결함이라 제품 관련 문제를 추적하고 관리하는 데 사용됩니다. - 개인 필드 또는 편집 버튼을 클릭하여이 레코드의 필드를 편집합니다. -보기 또는 아래 왼쪽 창에 "데이터보기"를 전환하여 서브 패널에있는 다른 기록에 대한 링크를 수정합니다. - 확인 및보기 사용자의 의견과 왼쪽 하단 창에 "작업 스트림"을 전환하여 {{activitystream_singular_module}}의 기록 변화의 역사. - 레코드 이름의 오른쪽에 아이콘을 사용하여이 기록을 따르거나 좋아. - 추가 작업은 편집 버튼의 오른쪽에있는 드롭 다운 동작 메뉴에서 사용할 수 있습니다.',

    // Create View Help Text
    'LBL_HELP_CREATE' => '{{plural_module_name}} 모듈은내부적으로 발견되거나 고객들이 보고한 제품 관련 문제들을 추적하고 관리하는데 사용되며, 일반적으로 {{plural_module_name}} 또는결함으로 표시됩니다.

{{module_name}} 만들기:
1.원하는필드의값을 지정합니다.
- "필수"로표시된 필드는 저장하기 전에완료해야합니다. 
- 필요한경우추가필드를노출하려면 "더보기"를클릭합니다. 
2.“저장하기”를 클릭하여 새 기록을 완성하고 이전 페이지로 돌아갑니다.',
);
