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
    // Dashboard Names
    'LBL_NOTES_LIST_DASHBOARD' => '노트 목록 대시보드',

    'ERR_DELETE_RECORD' => '거래처를 삭제하시려면 정확한 자료 고유번호를 입력하셔야합니다.',
    'LBL_ACCOUNT_ID' => '거래처 ID',
    'LBL_CASE_ID' => '사례ID',
    'LBL_CLOSE' => '닫기',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => '연락처 ID:',
    'LBL_CONTACT_NAME' => '연락처',
    'LBL_DEFAULT_SUBPANEL_TITLE' => '메모 목록',
    'LBL_DESCRIPTION' => '설명',
    'LBL_EMAIL_ADDRESS' => '이메일 주소',
    'LBL_EMAIL_ATTACHMENT' => '이메일 첨부',
    'LBL_EMAIL_ATTACHMENT_FOR' => '이메일 첨부:',
    'LBL_FILE_MIME_TYPE' => 'Mime유형',
    'LBL_FILE_EXTENSION' => '파일 확장',
    'LBL_FILE_SOURCE' => '파일 소스',
    'LBL_FILE_SIZE' => '파일 크기',
    'LBL_FILE_URL' => '파일URL',
    'LBL_FILENAME' => '첨부파일',
    'LBL_LEAD_ID' => '관심고객 ID',
    'LBL_LIST_CONTACT_NAME' => '연락처',
    'LBL_LIST_DATE_MODIFIED' => '최종 수정',
    'LBL_LIST_FILENAME' => '첨부',
    'LBL_LIST_FORM_TITLE' => '메모 목록',
    'LBL_LIST_RELATED_TO' => '관련된 자료',
    'LBL_LIST_SUBJECT' => '제목',
    'LBL_LIST_STATUS' => '상태',
    'LBL_LIST_CONTACT' => '연락처',
    'LBL_MODULE_NAME' => '메모',
    'LBL_MODULE_NAME_SINGULAR' => '메모',
    'LBL_MODULE_TITLE' => '메모관리:홈',
    'LBL_NEW_FORM_TITLE' => '새 메모 생성 혹은 첨부 추가하기',
    'LBL_NEW_FORM_BTN' => '메모 추가',
    'LBL_NOTE_STATUS' => '메모',
    'LBL_NOTE_SUBJECT' => '제목',
    'LBL_NOTES_SUBPANEL_TITLE' => '노트와 첨부 목록',
    'LBL_NOTE' => '메모',
    'LBL_OPPORTUNITY_ID' => '영업기회 ID',
    'LBL_PARENT_ID' => '상위 ID:',
    'LBL_PARENT_TYPE' => '상위 유형:',
    'LBL_EMAIL_TYPE' => '이메일 유형',
    'LBL_EMAIL_ID' => '이메일 ID',
    'LBL_PHONE' => '전화번호:',
    'LBL_PORTAL_FLAG' => '포탈에 게시하시겠습니까?',
    'LBL_EMBED_FLAG' => '이메일에 삽입하시겠습니까?',
    'LBL_PRODUCT_ID' => '제품 ID',
    'LBL_QUOTE_ID' => '견적 ID',
    'LBL_RELATED_TO' => '관련된 자료',
    'LBL_SEARCH_FORM_TITLE' => '메모 검색',
    'LBL_STATUS' => '상태',
    'LBL_SUBJECT' => '제목',
    'LNK_IMPORT_NOTES' => '메모내역 가져오기',
    'LNK_NEW_NOTE' => '메모 생성 및 파일 첨부',
    'LNK_NOTE_LIST' => '메모 보기',
    'LBL_MEMBER_OF' => '다음의 구성원',
    'LBL_LIST_ASSIGNED_TO_NAME' => '담당자',
    'LBL_OC_FILE_NOTICE' => '파일을 보려면 서버에 로그인하십시오',
    'LBL_REMOVING_ATTACHMENT' => '첨부파일 삭제중...',
    'ERR_REMOVING_ATTACHMENT' => '첨부파일을 삭제하는 동안 문제가 발생하였습니다.',
    'LBL_CREATED_BY' => '생성자',
    'LBL_MODIFIED_BY' => '수정자',
    'LBL_SEND_ANYWAYS' => '이메일의 제목이 없습니다. 그래도 계속 진행하시겠습니까?',
    'LBL_LIST_EDIT_BUTTON' => '편집하기',
    'LBL_ACTIVITIES_REPORTS' => '활동 보고서',
    'LBL_PANEL_DETAILS' => '세부사항',
    'LBL_NOTE_INFORMATION' => '기본정보',
    'LBL_MY_NOTES_DASHLETNAME' => '내 메모',
    'LBL_EDITLAYOUT' => '레이아웃 편집하기' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => '이름',
    'LBL_LAST_NAME' => '성',
    'LBL_EXPORT_PARENT_TYPE' => '관련된 모듈',
    'LBL_EXPORT_PARENT_ID' => '관련 ID',
    'LBL_DATE_ENTERED' => '생성일',
    'LBL_DATE_MODIFIED' => '수정일',
    'LBL_DELETED' => '삭제 완료',
    'LBL_REVENUELINEITEMS' => '매출 품목',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{plural_module_name}} 모듈은 텍스트 또는 관련 기록과 관련된 첨부 파일을 포함하는 개별 {{plural_module_name}} 으로 구성되어 있습니다. {{module_name}} 레코드 플렉스를 통해 대부분의 모듈에 하나의 레코드와 관련 될 수는 필드 관해서 또한 단일 {{contacts_singular_module}} 관련 될 수있다. {{plural_module_name}} 레코드 또는 레코드에 관련도 첨부 파일에 대한 일반적인 텍스트를 보유 할 수 있습니다.{{module_name}} 레코드가 만들어지면 등, 역사의 서브 패널을 통해 {{plural_module_name}} 오기 같은 {{plural_module_name}} 모듈을 통해 같은 설탕에 당신이 {} {{plural_module_name}} 만들 수있는 다양한 방법, 당신이 있습니다{{plural_module_name}} 기록보기를 통해 {{module_name}} 에 관한 정보를보고 편집 할 수 있습니다. 각 {{module_name}} 기록은 다음과 같은 {{accounts_module}} {{contacts_module}} {{opportunities_module}}, 그리고 많은 다른 사람과 같은 다른 설탕 레코드와 관련 될 수있다.',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{plural_module_name}} 모듈은 텍스트 또는 관련 기록과 관련된 첨부 파일을 포함하는 개별 {{plural_module_name}}으로 구성되어 있습니다. - 개인 필드 또는 편집 버튼을 클릭하여이 레코드의 필드를 편집합니다. -보기 또는 아래 왼쪽 창에 "데이터보기"를 전환하여 서브 패널에있는 다른 기록에 대한 링크를 수정합니다. - 확인 및보기 사용자의 의견과 왼쪽 하단 창에 "작업 스트림"을 전환하여 {{activitystream_singular_module}}의 기록 변화의 역사. - 레코드 이름의 오른쪽에 아이콘을 사용하여이 기록을 따르거나 좋아. - 추가 작업은 편집 버튼의 오른쪽에있는 드롭 다운 동작 메뉴에서 사용할 수 있습니다.',

    // Create View Help Text
    'LBL_HELP_CREATE' => '{{module_name}} 만들기:
1. 원하는필드의값을 지정합니다.
- "필수"로표시된 필드는 저장하기 전에완료해야 합니다.
- 필요한경우추가필드를노출하려면 "더보기"를클릭합니다. 
2. “저장하기”를 클릭하여 새 기록을 완성하고 이전 페이지로 복귀합니다.',
);
