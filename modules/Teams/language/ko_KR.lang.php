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
    'ERR_ADD_RECORD' => '이 팀에 사용자를 추가하려면 반드시 기록번호를 명시해야 합니다.',
    'ERR_DUP_NAME' => '팀명이 이미 존재합니다. 다른 이름을 입력해 주십시오.',
    'ERR_DELETE_RECORD' => '이 아이템을 삭제하시려면 정확한 자료 고유번호를 입력하셔야합니다.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => '오류:선택하신 팀은 이미 제거하기 위해 선택되었습니다. 다른 팀을 선택하십시오',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => '오류:개인팀이 삭제되지 않은 사용자를 삭제하지 않았습니다.',
    'LBL_DESCRIPTION' => '설명:',
    'LBL_GLOBAL_TEAM_DESC' => '세계전체에서 보기가능',
    'LBL_INVITEE' => '팀 구성원',
    'LBL_LIST_DEPARTMENT' => '부서',
    'LBL_LIST_DESCRIPTION' => '설명:',
    'LBL_LIST_FORM_TITLE' => '팀 목록',
    'LBL_LIST_NAME' => '이름',
    'LBL_FIRST_NAME' => '이름:',
    'LBL_LAST_NAME' => '성:',
    'LBL_LIST_REPORTS_TO' => '다음 담당자에 보고:',
    'LBL_LIST_TITLE' => '직급',
    'LBL_MODULE_NAME' => '팀',
    'LBL_MODULE_NAME_SINGULAR' => '팀:',
    'LBL_MODULE_TITLE' => '팀:홈',
    'LBL_NAME' => '팀명',
    'LBL_NAME_2' => '팀명2',
    'LBL_PRIMARY_TEAM_NAME' => '기본 팀명',
    'LBL_NEW_FORM_TITLE' => '신규 팀',
    'LBL_PRIVATE' => '개인',
    'LBL_PRIVATE_TEAM_FOR' => '개인 팀',
    'LBL_SEARCH_FORM_TITLE' => '팀 검색',
    'LBL_TEAM_MEMBERS' => '팀 구성원',
    'LBL_TEAM' => '팀:',
    'LBL_USERS_SUBPANEL_TITLE' => '사용자',
    'LBL_USERS' => '사용자',
    'LBL_REASSIGN_TEAM_TITLE' => '다음 팀에 지정된 기록이 있습니다. <br />팀을 삭제하기전 이 기록을 새팀에 배정해야 합니다. 대체할 팀을 선택하십시오.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => '다시 지정',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => '다시 지정',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => '새 팀에 사용될 기록을 업데이트 하시겠습니까?',
    'LBL_REASSIGN_TABLE_INFO' => '테이블 업데이트중입니다.',
    'LBL_REASSIGN_TEAM_COMPLETED' => '작업이 성공적으로 완료되었습니다.',
    'LNK_LIST_TEAM' => '팀',
    'LNK_LIST_TEAMNOTICE' => '팀 알림',
    'LNK_NEW_TEAM' => '신규 팀 만들기',
    'LNK_NEW_TEAM_NOTICE' => '신규 팀 알림 만들기',
    'NTC_DELETE_CONFIRMATION' => '이 기록을 삭제하시겠습니까?',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => '이 사용자 멥버쉽을 제거하시겠습니까?',
    'LBL_EDITLAYOUT' => '지면 배치 편집하기' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => '담당부서 권한',
    'LBL_TBA_CONFIGURATION_DESC' => '담당부서 권한 활성화 및 모듈 접근 관리',
    'LBL_TBA_CONFIGURATION_LABEL' => '담당부서 권한 활성화',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => '활성화 모듈 선택',
    'LBL_TBA_CONFIGURATION_TITLE' => '담당부서 권한 활성화는 역할 관리를 통하여 개별 모델에 대한 부서 및 개인 사용자의 액세스 권한을 배정합니다.',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
담당부서 권한 비활성화는 프로세스 정의 및 모든 프로세스를 포함한 모듈의 권한과 연관된 데이터를 원상 복구합니다.
 이는 해당 모듈에 대한 "소유자 및 담당부서" 옵션을 이용하는 역할 및 담당부서 권한 기록 데이터를 포함합니다.
 모듈에 대한 담당부서 권한 비활성화 이후 빠른 수리 및 복구 툴을 이용하여 시스템 캐시를 정리하는 것을 권장합니다.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>경고:</strong> 담당부서 권한 비활성화는 프로세스 정의 및 모든 프로세스를 포함한 모듈의 권한과 연관된 데이터를 원상 복구합니다.
 이는 해당 모듈에 대한 "소유자 및 담당부서" 옵션을 이용하는 역할 및 담당부서 권한 기록 데이터를 포함합니다.
 모듈에 대한 담당부서 권한 비활성화 이후 빠른 수리 및 복구 툴을 이용하여 시스템 캐시를 정리하는 것을 권장합니다.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
담당부서 권한 비활성화는 프로세스 정의 및 모든 프로세스를 포함한 모듈의 권한과 연관된 데이터를 원상 복구합니다.
 이는 해당 모듈에 대한 "소유자 및 담당부서" 옵션을 이용하는 역할 및 담당부서 권한 기록 데이터를 포함합니다.
 모듈에 대한 담당부서 권한 비활성화 이후 빠른 수리 및 복구 툴을 이용하여 시스템 캐시를 정리하는 것을 권장합니다.
 빠른 수리 및 복구 툴에 접근이 제한될 시, 관리자에게 복구 메뉴 접근 권한을 요청하십시오.
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>경고:</strong> 담당부서 권한 비활성화는 프로세스 정의 및 모든 프로세스를 포함한 모듈의 권한과 연관된 데이터를 원상 복구합니다.
 이는 해당 모듈에 대한 "소유자 및 담당부서" 옵션을 이용하는 역할 및 담당부서 권한 기록 데이터를 포함합니다.
 모듈에 대한 담당부서 권한 비활성화 이후 빠른 수리 및 복구 툴을 이용하여 시스템 캐시를 정리하는 것을 권장합니다.
 빠른 수리 및 복구 툴에 접근이 제한될 시, 관리자에게 복구 메뉴 접근 권한을 요청하십시오.
STR
,
);
