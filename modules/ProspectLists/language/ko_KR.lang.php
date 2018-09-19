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
  'LBL_TARGET_LISTS_LIST_DASHBOARD' => '목표고객 목록 대시보드',

  'LBL_MODULE_NAME' => '목표고객 목록',
  'LBL_MODULE_NAME_SINGULAR' => '목표고객 목록',
  'LBL_MODULE_ID'   => '목표고객 목록',
  'LBL_MODULE_TITLE' => '목표고객 목록:홈',
  'LBL_SEARCH_FORM_TITLE' => '목표고객 목록 검색',
  'LBL_LIST_FORM_TITLE' => '목표고객 목록',
  'LBL_PROSPECT_LIST_NAME' => '목표고객 목록',
  'LBL_NAME' => '이름',
  'LBL_ENTRIES' => '최종 항목',
  'LBL_LIST_PROSPECT_LIST_NAME' => '목표고객 목록',
  'LBL_LIST_ENTRIES' => '목록의 목표고객',
  'LBL_LIST_DESCRIPTION' => '설명',
  'LBL_LIST_TYPE_NO' => '유형',
  'LBL_LIST_END_DATE' => '완료일',
  'LBL_DATE_ENTERED' => '생성일',
  'LBL_MARKETING_ID' => '마케팅 ID',
  'LBL_DATE_MODIFIED' => '수정일',
  'LBL_MODIFIED' => '수정자:',
  'LBL_CREATED' => '생성자',
  'LBL_TEAM' => '팀:',
  'LBL_ASSIGNED_TO' => '담당자:',
  'LBL_DESCRIPTION' => '설명:',
  'LNK_NEW_CAMPAIGN' => '캠페인 새로 만들기',
  'LNK_CAMPAIGN_LIST' => '캠페인 목록',
  'LNK_NEW_PROSPECT_LIST' => '신규 목표고객 목록 만들기',
  'LNK_PROSPECT_LIST_LIST' => '목표목록 보기',
  'LBL_MODIFIED_BY' => '수정자:',
  'LBL_CREATED_BY' => '생성자',
  'LBL_DATE_CREATED' => '생성일:',
  'LBL_DATE_LAST_MODIFIED' => '수정일',
  'LNK_NEW_PROSPECT' => '신규 목표 만들기',
  'LNK_PROSPECT_LIST' => '목표 고객',

  'LBL_PROSPECT_LISTS_SUBPANEL_TITLE' => '목표고객 목록',
  'LBL_CONTACTS_SUBPANEL_TITLE' => '연락처',
  'LBL_LEADS_SUBPANEL_TITLE' => '관심고객목록',
  'LBL_PROSPECTS_SUBPANEL_TITLE'=>'목표 고객 목록',
  'LBL_ACCOUNTS_SUBPANEL_TITLE' => '거래처 목록',
  'LBL_CAMPAIGNS_SUBPANEL_TITLE' => '캠페인 목록',
  'LBL_COPY_PREFIX' =>'복사하기',
  'LBL_USERS_SUBPANEL_TITLE' =>'사용자 목록',
  'LBL_TYPE' => '유형',
  'LBL_LIST_TYPE' => '유형',
  'LBL_LIST_TYPE_LIST_NAME'=>'유형',
  'LBL_NEW_FORM_TITLE'=>'신규 목표고객 목록',
  'LBL_MARKETING_NAME'=>'마케팅명',
  'LBL_MARKETING_MESSAGE'=>'이메일 마케팅 메세지',
  'LBL_DOMAIN_NAME'=>'도메인명',
  'LBL_DOMAIN'=>'도메인으로 이메일이 없습니다.',
  'LBL_LIST_PROSPECTLIST_NAME'=>'이름',
	'LBL_MORE_DETAIL' => '세부정보 더보기' /*for 508 compliance fix*/,

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{module_name}} 당신이 대량 마케팅 {{campaigns_singular_module}} 에 포함 또는 제외 할 개인이나 조직의 모음으로 구성되어 있습니다. {{accounts_module}} {{plural_module_name}} {{contacts_module}}, 대상의 수와 어떤 조합을 포함 할 수 있습니다 {{leads_module}}, 사용자 및. 대상은 연령대, 지리적 위치, 또는 지출 습관으로 미리 정해진 일련의 기준에 따라 {{module_name}} 로 그룹화 할 수 있습니다. {{plural_module_name}} 대량 이메일 마케팅에서 사용되는 {{campaigns_module}} 이 {{campaigns_module}} 모듈을 구성 할 수 있습니다.',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{module_name}} 당신이 대량 마케팅 {{campaigns_singular_module}} 에 포함 또는 제외 할 개인이나 조직의 모음으로 구성되어 있습니다. - 개인 필드 또는 편집 버튼을 클릭하여이 레코드의 필드를 편집합니다. -보기 또는 아래 왼쪽 창에 "데이터보기"를 전환하여, {{campaigns_singular_module}} recipeints을 포함하여 서브 패널에있는 다른 기록에 대한 링크를 수정합니다. - 확인 및보기 사용자의 의견과 왼쪽 하단 창에 "작업 스트림"을 전환하여 {{activitystream_singular_module}} 의 기록 변화의 역사. - 레코드 이름의 오른쪽에 아이콘을 사용하여이 기록을 따르거나 좋아. - 추가 작업은 편집 버튼의 오른쪽에있는 드롭 다운 동작 메뉴에서 사용할 수 있습니다.',

    // Create View Help Text
    'LBL_HELP_CREATE' => '한 {{module_name}} 은대량마케팅 {{campaigns_singular_module}} 에포함시키거나또는제외할개인이나조직의모음으로 구성됩니다.

{{module_name}} 만들기:
1. 원하는필드의값을 지정합니다.
- "필수"로표시된 필드는 저장하기 전에완료해야 합니다. 
- 필요한경우추가필드를노출하려면 "더보기"를클릭합니다.
2. “저장하기”를 클릭하여 새 기록을 완성하고 이전 페이지로 돌아갑니다.
3. 저장후, 타겟의 기록보기에 있는 서브패널을 사용하여 {{campaigns_singular_module}} 수신자들을 추가합니다.',
);
