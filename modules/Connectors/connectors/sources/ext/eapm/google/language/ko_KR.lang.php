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

$connector_strings = array(
    'LBL_LICENSING_INFO' => '<table border="0" cellspacing="1"><tr><td valign="top" width="35%" class="dataLabel">
슈가 인스턴스를 새로운 애플리케이션으로 등록하여 Google에서 API 키 및 시크릿을 획득하십시오.
<br/><br>인스턴스 등록 절차:
<br/><br/>
<ol>
<li>다음의 Google 개발자 사이트로 이동하십시오.
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>애플리케이션을 등록하고자 하는 Google 계정을 이용하여 로그인하십시오.</li>
<li>새 프로젝트를 생성하십시오.</li>
<li>프로젝트 이름을 입력하여 생성을 클릭하십시오.</li>
<li>프로젝트를 생성하고 나면 Google 드라이브 및 Google 연락처가 활성화됩니다.</li>
<li>APIs & Auth > 자격증명 섹션 하에서 새 클라이언트 id를 만드십시오.</li>
<li>웹 애플리케이션을 선택하고 동의 화면을 구성하십시오.</li>
<li>제품 이름을 입력하고 저장을 클릭하십시오.</li>
<li>권한이 부여된 리다이렉트 URIs 섹션 하에서 다음의 url을 입력하십시오. {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>클라이언트 id 생성을 클릭하십시오.</li>
<li>클라이언트 id 및 클라이언트 시크릿을 아래의 상자들 안으로 복사하십시오.</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => '클라이언트 ID',
    'oauth2_client_secret' => '클라이언트 시크릿',
);
