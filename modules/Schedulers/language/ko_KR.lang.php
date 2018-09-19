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
global $sugar_config;

$mod_strings = array (
// OOTB Scheduler Job Names:
'LBL_OOTB_WORKFLOW'		=> '작업흐름 과제 진행',
'LBL_OOTB_REPORTS'		=> '과제일정 보고서 만들기 실행',
'LBL_OOTB_IE'			=> '수신 편지함 확인',
'LBL_OOTB_BOUNCE'		=> '매일 저녁 반송 캠페인 이메일 처리 실행',
'LBL_OOTB_CAMPAIGN'		=> '매일 저녁 대용량 이메일 캠페인 실행',
'LBL_OOTB_PRUNE'		=> '매달 1일 Prune 데이타베이스',
'LBL_OOTB_TRACKER'		=> 'Prune 추적 테이블',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> '예전 기록 잘라내기',
'LBL_OOTB_REMOVE_TMP_FILES' => '임시 파일을 제거합니다',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => '진단 도구 파일을 제거',
'LBL_OOTB_REMOVE_PDF_FILES' => '임시 PDF 파일을 제거',
'LBL_UPDATE_TRACKER_SESSIONS' => '추적장치 업데이트-세션 테이블',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => '이메일 공지 알림 실행',
'LBL_OOTB_CLEANUP_QUEUE' => '대기중 작업 비우기',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => '추후 기간 생성',
'LBL_OOTB_HEARTBEAT' => '설탕 하트 비트',
'LBL_OOTB_KBCONTENT_UPDATE' => 'KBContent 기사를 업데이트하십시오.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => '승인 된 조항 & 만료 KB 조항을 게시 합니다.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Advanced Workflow Scheduled Job',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => '정규화되지 않은 팀 보안 데이터 다시 빌드',

// List Labels
'LBL_LIST_JOB_INTERVAL' => '간격',
'LBL_LIST_LIST_ORDER' => '일정관리',
'LBL_LIST_NAME' => '일정 관리',
'LBL_LIST_RANGE' => '범위',
'LBL_LIST_REMOVE' => '제거',
'LBL_LIST_STATUS' => '상태',
'LBL_LIST_TITLE' => '일정 목록',
'LBL_LIST_EXECUTE_TIME' => '다음을 실행합니다.',
// human readable:
'LBL_SUN'		=> '일요일',
'LBL_MON'		=> '월요일',
'LBL_TUE'		=> '화요일',
'LBL_WED'		=> '수요일',
'LBL_THU'		=> '목요일',
'LBL_FRI'		=> '금요일',
'LBL_SAT'		=> '토요일',
'LBL_ALL'		=> '매일',
'LBL_EVERY_DAY'	=> '매일',
'LBL_AT_THE'	=> '다음 ',
'LBL_EVERY'		=> '매',
'LBL_FROM'		=> '발신인',
'LBL_ON_THE'	=> '다음 ',
'LBL_RANGE'		=> '기한',
'LBL_AT' 		=> 'at',
'LBL_IN'		=> 'in',
'LBL_AND'		=> '그리고',
'LBL_MINUTES'	=> '분',
'LBL_HOUR'		=> '시간',
'LBL_HOUR_SING'	=> '시간',
'LBL_MONTH'		=> '월',
'LBL_OFTEN'		=> '가능한 자주',
'LBL_MIN_MARK'	=> '분 표시',


// crontabs
'LBL_MINS' => '분',
'LBL_HOURS' => '시간',
'LBL_DAY_OF_MONTH' => '일',
'LBL_MONTHS' => '월',
'LBL_DAY_OF_WEEK' => '일',
'LBL_CRONTAB_EXAMPLES' => '위는 기본 crontab표시를 사용합니다.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'cron 내역 실행은 서버의 시간대에 근거합니다.',
'LBL_CRONTAB_SERVER_TIME_POST' => '알맞은 일정관리 실행 시간을 명시하십시오',
// Labels
'LBL_ALWAYS' => '항상',
'LBL_CATCH_UP' => '놓친 부분 실행',
'LBL_CATCH_UP_WARNING' => '실행시간이 일정시간 이상 소요되면 확인 취소하십시오',
'LBL_DATE_TIME_END' => '날짜와 완료 시간',
'LBL_DATE_TIME_START' => '날짜와 시작 시간',
'LBL_INTERVAL' => '간격',
'LBL_JOB' => '작업',
'LBL_JOB_URL' => '작업 URL',
'LBL_LAST_RUN' => '지난 성공적인 실행',
'LBL_MODULE_NAME' => 'Sugar 일정관리',
'LBL_MODULE_NAME_SINGULAR' => 'Sugar 일정관리',
'LBL_MODULE_TITLE' => '일정 관리',
'LBL_NAME' => '작업명',
'LBL_NEVER' => '사용하지않음',
'LBL_NEW_FORM_TITLE' => '신규 일정',
'LBL_PERENNIAL' => '반복',
'LBL_SEARCH_FORM_TITLE' => '일정 검색',
'LBL_SCHEDULER' => '일정관리',
'LBL_STATUS' => '상태',
'LBL_TIME_FROM' => '시작 시간',
'LBL_TIME_TO' => '완료 시간',
'LBL_WARN_CURL_TITLE' => 'cURL 경고',
'LBL_WARN_CURL' => '경고',
'LBL_WARN_NO_CURL' => '이 시스템에는 PHP모듈로 작동가능한/ 편집가능한 cURL libraries 이 없습니다. 이 문제 해결을 위해서는 관리자에 문의하십시오. cURL기능이 없이는 일정관리의 작업을 관통할수 없습니다.',
'LBL_BASIC_OPTIONS' => '기본 구성',
'LBL_ADV_OPTIONS'		=> '고급 선택사항',
'LBL_TOGGLE_ADV' => '고급 선택사항 보이기',
'LBL_TOGGLE_BASIC' => '기본 선택사항 보이기',
// Links
'LNK_LIST_SCHEDULER' => '일정 관리',
'LNK_NEW_SCHEDULER' => '신규 일정관리 만들기',
'LNK_LIST_SCHEDULED' => '예정된 작업',
// Messages
'SOCK_GREETING' => "이것은 SugarCRM 일정관리 서비스의 인터페이스 입니다. [사용가능한 디먼 명령어:시작|재실행|종료|상태] 중지하려면 중지를, 서비스를 종료하시려면 종료를 입력하시십오.",
'ERR_DELETE_RECORD' => '일정을 삭제하시려면 정확한 자료 고유번호를 입력하셔야합니다.',
'ERR_CRON_SYNTAX' => '유효하지 않은 Cron 구문',
'NTC_DELETE_CONFIRMATION' => '이 기록을 삭제하시겠습니까?',
'NTC_STATUS' => '이 일정을 일정 내려보기 목록에서 제거하려면 상태를 정지로 설정합니다.',
'NTC_LIST_ORDER' => '일정 내려보기 목록에 나타나도록 이 일정의 순서를 설정하니다.',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => '윈도우 일정 설정하기',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Crontab 설정하기',
'LBL_CRON_LINUX_DESC' => '노트:Sugar 일정관리를 실행하려면 crontab 파일에 다음 줄을 추가하십시오.',
'LBL_CRON_WINDOWS_DESC' => '노트:Sugar일정관리를 실행하려면 Windows Scheduled Tasks를 사용한 파일묶음을 만들어야 합니다. 이것은 다음의 명령어를 포함해야합니다.',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> '작업 일지',
'LBL_EXECUTE_TIME'			=> '실행 시간',

//jobstrings
'LBL_REFRESHJOBS' => '작업 새로고침',
'LBL_POLLMONITOREDINBOXES' => '수신메일 계정 확인',
'LBL_PERFORMFULLFTSINDEX' => '전체문장 검색 색인 시스템',
'LBL_SUGARJOBREMOVEPDFFILES' => '임시 PDF 파일을 제거',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => '승인된 조항 & 만료 KB 조항을 게시합니다.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Elasticsearch 큐 스케줄러',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => '진단 도구 파일을 제거',
'LBL_SUGARJOBREMOVETMPFILES' => '임시 파일 제거',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => '정규화되지 않은 팀 보안 데이터 다시 빌드',

'LBL_RUNMASSEMAILCAMPAIGN' => '매일 저녁 대용량 이메일 캠페인 실행',
'LBL_ASYNCMASSUPDATE' => '비동시적 대규모 업데이트를 실행합니다.',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => '매일 저녁 반송 캠페인 이메일 실행',
'LBL_PRUNEDATABASE' => '매달 1일 Prune 데이타베이스',
'LBL_TRIMTRACKER' => 'Prune 추적 테이블',
'LBL_PROCESSWORKFLOW' => '작업흐름 과제 진행',
'LBL_PROCESSQUEUE' => '예정과제 보고서 생성 실행',
'LBL_UPDATETRACKERSESSIONS' => '추적 세션테일블 업데이트',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => '추후 기간 생성',
'LBL_SUGARJOBHEARTBEAT' => '설탕 하트 비트',
'LBL_SENDEMAILREMINDERS'=> '이메일 알림 전송 실행',
'LBL_CLEANJOBQUEUE' => '대기중 작업 마무리하기',
'LBL_CLEANOLDRECORDLISTS' => '예전 기록목록을 삭제합니다.',
'LBL_PMSEENGINECRON' => 'Advanced Workflow Scheduler',
);

