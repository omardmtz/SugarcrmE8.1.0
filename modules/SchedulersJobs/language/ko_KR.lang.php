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
    'LBL_MODULE_NAME' => '작업 대기열',
    'LBL_MODULE_NAME_SINGULAR' => '작업 대기열',
    'LBL_MODULE_TITLE' => '작업 대기열: 홈',
    'LBL_MODULE_ID' => '작업 대기열',
    'LBL_TARGET_ACTION' => '액션',
    'LBL_FALLIBLE' => '오류가 있기 쉬움',
    'LBL_RERUN' => '재실행',
    'LBL_INTERFACE' => '인터페이스',
    'LINK_SCHEDULERSJOBS_LIST' => '작업 대기열 보기',
    'LBL_SCHEDULERS_JOBS_ADMIN_MENU' => '환경구성',
    'LBL_CONFIG_PAGE' => '작업 대기열 설정',
    'LBL_JOB_CANCEL_BUTTON' => '취소',
    'LBL_JOB_PAUSE_BUTTON' => '잠시 멈춤',
    'LBL_JOB_RESUME_BUTTON' => '재개',
    'LBL_JOB_RERUN_BUTTON' => '대기열 재배열',
    'LBL_LIST_NAME' => '이름',
    'LBL_LIST_ASSIGNED_USER' => '요청자',
    'LBL_LIST_STATUS' => '상태',
    'LBL_LIST_RESOLUTION' => '해결',
    'LBL_NAME' => '작업명',
    'LBL_EXECUTE_TIME' => '실행 시간',
    'LBL_SCHEDULER_ID' => '일정 관리',
    'LBL_STATUS' => '작업 상태',
    'LBL_RESOLUTION' => '결과',
    'LBL_MESSAGE' => '메세지',
    'LBL_DATA' => '작업 데이타',
    'LBL_REQUEUE' => '실패시 재시도',
    'LBL_RETRY_COUNT' => '최대 시도수',
    'LBL_FAIL_COUNT' => '실패',
    'LBL_INTERVAL' => '시도간 최소 간격',
    'LBL_CLIENT' => '소유중인 고객',
    'LBL_PERCENT' => '완료 백분율',
    'LBL_JOB_GROUP' => '직업 그룹',
    'LBL_RESOLUTION_FILTER_QUEUED' => '해결 대기열',
    'LBL_RESOLUTION_FILTER_PARTIAL' => '부분적 해결',
    'LBL_RESOLUTION_FILTER_SUCCESS' => '완료된 해결',
    'LBL_RESOLUTION_FILTER_FAILURE' => '해결 실패',
    'LBL_RESOLUTION_FILTER_CANCELLED' => '취소된 해결',
    'LBL_RESOLUTION_FILTER_RUNNING' => '실행 중인 해결책',
    // Errors
    'ERR_CALL' => "전화상담 기능 불가:백분율",
    'ERR_CURL' => "CURL  없음- URL 작업 실행 불가",
    'ERR_FAILED' => "예기치못한 실패입니다. PHP일지와 Sugarcrm일지를 확인하십시오",
    'ERR_PHP' => "%s [%d]: 라인 %d에 있는 %s에서 %s",
    'ERR_NOUSER' => "작업을 위한 명시된 내 사용자 ID가 없습니다.",
    'ERR_NOSUCHUSER' => "사용자 ID %가 발견되지 않았습니다.",
    'ERR_JOBTYPE' => "알수없는 작업 유형:%",
    'ERR_TIMEOUT' => "시간경과로 실패했습니다.",
    'ERR_JOB_FAILED_VERBOSE' => 'CRON실행에서 실패한 작업 %',
    'ERR_WORKER_CANNOT_LOAD_BEAN' => 'id: %s 포함 빈을 로드할 수 없음',
    'ERR_WORKER_NO_REGISTERED_FUNCTIONS' => '경로 %s에 대한 핸들러를 찾을 수 없음',
    'ERR_CONFIG_MISSING_EXTENSION' => '이 대기열에 대한 확장자가 설치되어 있지 않음',
    'ERR_CONFIG_EMPTY_FIELDS' => '일부 필드가 비어 있음',
    //    Configuration
    'LBL_CONFIG_TITLE_MODULE_SETTINGS' => '작업 대기열 설정',
    'LBL_CONFIG_MAIN_SECTION' => '메인 환경구성',
    'LBL_CONFIG_GEARMAN_SECTION' => 'Gearman 환경구성',
    'LBL_CONFIG_AMQP_SECTION' => 'AMQP 환경구성',
    'LBL_CONFIG_AMAZON_SQS_SECTION' => 'Amazon-sqs Configuration',
    'LBL_CONFIG_SERVERS_TITLE' => '작업 대기열 환경구성 도움말',
    'LBL_CONFIG_SERVERS_TEXT' => "<p><b>주요 환경구성 섹션</b></p>
<ul>
    <li>Runner:
    <ul>
    <li><i>표준</i> - 작업자에 대해 프로세스 하나만 사용합니다.</li>
    <li><i>평행</i> - 작업자에 대해 몇 개의 프로세스를 사용합니다.</li>
    </ul>
    </li>
    <li>Adapter:
    <ul>
    <li><i>기본 대기열</i> - 메시지 대기열 없이 Sugar의 데이터베이스만 사용합니다.</li>
    <li><i>Amazon SQS</i> - Amazon Simple Queue Service는 배분된 대기열 메시징 서비스로, Amazon.com에 의해 유도됩니다.
    이것은 인터넷을 통해 커뮤니케이션하기 위한 방식으로 웹 서비스 애플리케이션을 통해 메시지의 프로그램 전송을 지원합니다.</li>
    <li><i>RabbitMQ</i> -은 고급 메시지 대기열 프로토콜(AMQP)을 이행하는 오픈 소스 메시지 브로커 소프트웨어입니다(메시지 중심의 미들웨어라고도 함).</li>
    <li><i>Gearman</i>은 대형 태스크를 더 빨리 수행할 수 있도록, 여러 컴퓨터에 적절한 컴퓨터 태스크를 배분하도록 배분된 오픈 소스 애플리케이션 프레임워크입니다.</li>
    <li><i>Immediate</i> - 기본 대기열과 마찬가지지만, 추가 후 즉시 태스크를 집행합니다.</li>
    </ul>
    </li>
</ul>",
    'LBL_CONFIG_AMAZON_SQS_TITLE' => 'Amazon SQS 환경구성 도움말',
    'LBL_CONFIG_AMAZON_SQS_TEXT' => "<p><b>Amazon SQS 환경구성 섹션</b></p>
<ul>
    <li>액세스 키 ID: <i>Amazon SQS에 대한 액세스 키 id 번호를 입력</i></li>
    <li>비밀 액세스 키: <i>Amazon SQS에 대한 비밀 액세스 키 입력</i></li>
    <li>지역: <i>Amazon SQS 서버의 지역 입력</i></li>
    <li>대기열 이름: <i>Amazon SQS 서버의 대기열 이름 입력</i></li>
</ul>",
    'LBL_CONFIG_AMQP_TITLE' => 'AMQP 환경구성 도움말',
    'LBL_CONFIG_AMQP_TEXT' => "<p><b>AMQP 환경구성 섹션</b></p>
<ul>
    <li>서버 URL: <i>메시지 대기열 서버의 URL 입력</i></li>
    <li>로그인: <i>RabbitMQ에 대한 로그인 입력</i></li>
    <li>패스워드: <i>RabbitMQ에 대한 패스워드 입력</i></li>
</ul>",
    'LBL_CONFIG_GEARMAN_TITLE' => 'Gearman 환경구성 도움말',
    'LBL_CONFIG_GEARMAN_TEXT' => "<p><b>Gearman 환경구성 도움말</b></p>
<ul>
    <li>서버 URL: <i>메시지 대기열 서버의 URL 입력</i></li>
</ul>",
    'LBL_CONFIG_QUEUE_TYPE' => '어댑터',
    'LBL_CONFIG_QUEUE_MANAGER' => '러너',
    'LBL_SERVER_URL' => '서버 URL',
    'LBL_LOGIN' => '로그인',
    'LBL_ACCESS_KEY' => '액세스 키 ID',
    'LBL_REGION' => '지역',
    'LBL_ACCESS_KEY_SECRET' => '비밀 액세스 키',
    'LBL_QUEUE_NAME' => '어댑터 이름',
);
