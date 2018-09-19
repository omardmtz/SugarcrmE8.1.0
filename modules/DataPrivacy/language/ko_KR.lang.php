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
    'LBL_MODULE_NAME' => '데이터 프라이버시',
    'LBL_MODULE_NAME_SINGULAR' => '데이터 프라이버시',
    'LBL_NUMBER' => '숫자',
    'LBL_TYPE' => '종류',
    'LBL_SOURCE' => '소스',
    'LBL_REQUESTED_BY' => '요청됨',
    'LBL_DATE_OPENED' => '오픈 날짜',
    'LBL_DATE_DUE' => '마감 날짜',
    'LBL_DATE_CLOSED' => '휴무 날짜',
    'LBL_BUSINESS_PURPOSE' => '동의한 사업 목적',
    'LBL_LIST_NUMBER' => '숫자',
    'LBL_LIST_SUBJECT' => '제목',
    'LBL_LIST_PRIORITY' => '우선 순위',
    'LBL_LIST_STATUS' => '상태',
    'LBL_LIST_TYPE' => '종류',
    'LBL_LIST_SOURCE' => '소스',
    'LBL_LIST_REQUESTED_BY' => '요청됨',
    'LBL_LIST_DATE_DUE' => '마감 날짜',
    'LBL_LIST_DATE_CLOSED' => '휴무 날짜',
    'LBL_LIST_DATE_MODIFIED' => '수정 날짜',
    'LBL_LIST_MODIFIED_BY_NAME' => '수정됨',
    'LBL_LIST_ASSIGNED_TO_NAME' => '지정된 사용자',
    'LBL_SHOW_MORE' => '추가 데이터 프라이버시 활동 보기',
    'LNK_DATAPRIVACY_LIST' => '데이터 프라이버시 활동 보기',
    'LNK_NEW_DATAPRIVACY' => '데이터 프라이버시 활동 생성',
    'LBL_LEADS_SUBPANEL_TITLE' => '리드',
    'LBL_CONTACTS_SUBPANEL_TITLE' => '연락처',
    'LBL_PROSPECTS_SUBPANEL_TITLE' => '타겟',
    'LBL_ACCOUNTS_SUBPANEL_TITLE' => '계정',
    'LBL_LISTVIEW_FILTER_ALL' => '모든 데이터 프라이버시 활동',
    'LBL_ASSIGNED_TO_ME' => '내 데이터 프라이버시 활동',
    'LBL_SEARCH_AND_SELECT' => '데이터 프라이버시 활동 검색 및 선택',
    'TPL_SEARCH_AND_ADD' => '데이터 프라이버시 활동 검색 및 추가',
    'LBL_WARNING_ERASE_CONFIRM' => '{0}개의 필드를 영구적으로 삭제합니다. 삭제가 완료된 후 이 데이터를 복구할 수 없습니다. 계속 하시겠습니까?',
    'LBL_WARNING_REJECT_ERASURE_CONFIRM' => '삭제하기로 표시된 {0}개의 필드가 있습니다. 확인을 누르시면 삭제하기를 중단하고 모든 데이터를 보존하며 이 요청을 거절로 표시합니다. 계속 하시겠습니까?',
    'LBL_WARNING_COMPLETE_CONFIRM' => '이 요청을 완료하려 합니다. 이는 영구적으로 상태를 완료로 설정하며 다시 열 수 없습니다. 계속 하시겠습니까?',
    'LBL_WARNING_REJECT_REQUEST_CONFIRM' => '이 요청을 거부하려고 합니다. 영구적으로 상태를 거부됨으로 설정되며 다시 열 수 없습니다. 계속 하시겠습니까?',
    'LBL_RECORD_SAVED_SUCCESS' => '<a href="#{{buildRoute model=this}}"> {{name}} </a> 데이터 프라이버시 활동을 생성했습니다.', // use when a model is available
    'LBL_REJECT_BUTTON_LABEL' => '거절',
    'LBL_COMPLETE_BUTTON_LABEL' => '완료',
    'LBL_ERASE_COMPLETE_BUTTON_LABEL' => '삭제 및 완료',
    'LBL_ERASE_SUBPANEL_FIELDS_LABEL' => '서브 패널을 통해 선택된 필드 삭제',
    'LBL_COUNT_FIELDS_MARKED' => '삭제하기로 표시된 필드',
    'LBL_NO_RECORDS_MARKED' => '삭제로 표시된 필드 또는 기록이 없습니다.',
    'LBL_DATA_PRIVACY_RECORD_DASHBOARD' => '데이터 개인 정보 기록 대시 보드',

    // list view
    'LBL_HELP_RECORDS' => '데이터 프라이버시 모듈은 개인 정보 보호 활동(동의 및 정보 요청 포함)을 추적하여 조직의 개인 정보 보호 절차를 지원합니다. 동의 기록을 추적하거나 개인 정보 요청에 대한 조치를 취하기 위해 개인 기록(예:연락처)과 관련된 데이터 개인 정보 기록을 만듭니다.',
    // record view
    'LBL_HELP_RECORD' => '데이터 프라이버시 모듈은 개인 정보 보호 활동 (동의 및 정보 요청 포함)을 추적하여 조직의 개인 정보 보호 절차를 지원합니다. 동의 기록을 추적하거나 개인 정보 요청에 대한 조치를 취하기 위해 개인 기록 (예:연락처)과 관련된 데이터 개인 정보 기록을 만듭니다. 필요한 작업이 완료되면 데이터 개인 정보 매니저(Data Privacy Manager)역할의 사용자는 "완료"또는 "거부"를 클릭하여 상태를 업데이트할 수 있습니다. 삭제 요청의 경우, 아래의 서브 패널에 나열된 개인 기록 각각에 대해 "삭제하기로 표시"를 선택하십시오. 원하는 필드가 모두 선택되면 "삭제 및 완료"를 클릭하면 필드의 값이 영구적으로 제거되고 데이터 개인 정보 기록이 완료된 것으로 표시됩니다.',
);
