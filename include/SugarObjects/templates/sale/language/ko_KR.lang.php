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
 * $Id$
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
 $mod_strings = array (
  'LBL_MODULE_NAME' => '영업',
  'LBL_MODULE_TITLE' => '영업:홈',
  'LBL_SEARCH_FORM_TITLE' => '영업 검색',
  'LBL_VIEW_FORM_TITLE' => '영업 보기',
  'LBL_LIST_FORM_TITLE' => '영업 목록',
  'LBL_SALE_NAME' => '영업명',
  'LBL_SALE' => '영업:',
  'LBL_NAME' => '영업명',
  'LBL_LIST_SALE_NAME' => '이름',
  'LBL_LIST_ACCOUNT_NAME' => '고객명',
  'LBL_LIST_AMOUNT' => '금액',
  'LBL_LIST_DATE_CLOSED' => '완료',
  'LBL_LIST_SALE_STAGE' => '영업 상태',
  'LBL_ACCOUNT_ID'=>'고객 ID',
  'LBL_TEAM_ID' =>'팀 ID',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => '영업 - 화폐 업데이트',
  'UPDATE_DOLLARAMOUNTS' => '미화량 업데이트',
  'UPDATE_VERIFY' => '금액 인증',
  'UPDATE_VERIFY_TXT' => '영업 금액항목의 입력값이 유효한 숫자로만 기입되었는지 확인합니다.',
  'UPDATE_FIX' => '고정 금액',
  'UPDATE_FIX_TXT' => '현 금액으로부터의 유효한 숫자로 무효금액을 고치는 시도입니다. 모든 변경된 금액은 금액 보조 데이타 베이스 필드에 저장됩니다. 만약 실행중 오류가 발생하면 무효 데이타가 보조 데이타에 겹쳐질수 있으니 보조 데이타의 저장 없이 재실행 하지 마십시요.',
  'UPDATE_DOLLARAMOUNTS_TXT' => '현 환율설정에 근거한 미화 영업금액을 수정합니다. 이 금액은 그래프 계산과 화폐금액 목록보기에 사용됩니다.',
  'UPDATE_CREATE_CURRENCY' => '새 화폐 입력:',
  'UPDATE_VERIFY_FAIL' => '인증 실패 기록:',
  'UPDATE_VERIFY_CURAMOUNT' => '현재 금액:',
  'UPDATE_VERIFY_FIX' => '수정 프로그램 실행',
  'UPDATE_INCLUDE_CLOSE' => '완료된 기록 포함',
  'UPDATE_VERIFY_NEWAMOUNT' => '새 금액:',
  'UPDATE_VERIFY_NEWCURRENCY' => '새 화폐:',
  'UPDATE_DONE' => '완료',
  'UPDATE_BUG_COUNT' => '문제 해결 시도중 오류 발견:',
  'UPDATE_BUGFOUND_COUNT' => '오류 발견:',
  'UPDATE_COUNT' => '기록 업데이트:',
  'UPDATE_RESTORE_COUNT' => '복구된 금액 기록:',
  'UPDATE_RESTORE' => '복구된 금액',
  'UPDATE_RESTORE_TXT' => '수정중 발생한 금액 복구',
  'UPDATE_FAIL' => '업데이트 불가 -',
  'UPDATE_NULL_VALUE' => '금액에 빈값을 입력하였습니다. 0 혹은 -로 대체해주세요.',
  'UPDATE_MERGE' => '화폐 병합하기',
  'UPDATE_MERGE_TXT' => '두개 이상의 화폐들을 하나의 화폐로 통합하십시요. 한 화폐에 복수의 화폐기록이 있다면 하나로 통합하고 이는 다른 모듈에서의 화폐도 통합합니다.',
  'LBL_ACCOUNT_NAME' => '고객명:',
  'LBL_AMOUNT' => '금액:',
  'LBL_AMOUNT_USDOLLAR' => '미화 금액:',
  'LBL_CURRENCY' => '화폐:',
  'LBL_DATE_CLOSED' => '예상 수주일:',
  'LBL_TYPE' => '종류:',
  'LBL_CAMPAIGN' => '캠페인:',
  'LBL_LEADS_SUBPANEL_TITLE' => '관심고객',
  'LBL_PROJECTS_SUBPANEL_TITLE' => '프로젝트',  
  'LBL_NEXT_STEP' => '다음 단계:',
  'LBL_LEAD_SOURCE' => '관심고객 출처:',
  'LBL_SALES_STAGE' => '영업 상태:',
  'LBL_PROBABILITY' => '수주확률 (%):',
  'LBL_DESCRIPTION' => '설명:',
  'LBL_DUPLICATE' => '중복 추측 가능한 영업 목록',
  'MSG_DUPLICATE' => '현재 저장하시려는 정보가 기존의 영업기회와 유사합니다. 아래의 비슷한 영업명을 가지고 있는 기록을 확인하시고 중복된 자료이면 취소를 선택하여 기존화면으로 돌아갑니다.<br>만약 새로운 거래처가 확실하다면 저장하기를 선택해주세요.',
  'LBL_NEW_FORM_TITLE' => '영업 추가하기',
  'LNK_NEW_SALE' => '영업 추가하기',
  'LNK_SALE_LIST' => '영업',
  'ERR_DELETE_RECORD' => '자료를 삭제하기 위해선 정확한 자료 고유번호를 입력하셔야합니다.',
  'LBL_TOP_SALES' => '진행중 상위 영업',
  'NTC_REMOVE_OPP_CONFIRMATION' => '이 연락처를 제거하시겠습니까?',
	'SALE_REMOVE_PROJECT_CONFIRM' => '프로젝트로부터 이 영업를 제거하시겠습니까?',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'활동내역',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'보관함',
    'LBL_RAW_AMOUNT'=>'원 금액',


    'LBL_CONTACTS_SUBPANEL_TITLE' => '연락처',
	'LBL_ASSIGNED_TO_NAME' => '사용자:',
	'LBL_LIST_ASSIGNED_TO_NAME' => '지정 사용자',
  'LBL_MY_CLOSED_SALES' => '내 완료된 영업',
  'LBL_TOTAL_SALES' => '최종 영업 합계',
  'LBL_CLOSED_WON_SALES' => '수주 성공한 영업',
  'LBL_ASSIGNED_TO_ID' =>'ID에 지정',
  'LBL_CREATED_ID'=>'생성자 ID',
  'LBL_MODIFIED_ID'=>'수정자 ID',
  'LBL_MODIFIED_NAME'=>'사용자명에 의해 수정',
  'LBL_SALE_INFORMATION'=>'영업 정보',
  'LBL_CURRENCY_ID'=>'화폐 ID',
  'LBL_CURRENCY_NAME'=>'화폐명',
  'LBL_CURRENCY_SYMBOL'=>'화폐 기호',
  'LBL_EDIT_BUTTON' => '수정하기',
  'LBL_REMOVE' => '제거하기',
  'LBL_CURRENCY_RATE' => '환율',

);

