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
    'LBL_LICENSING_INFO' => '<table border="0" cellspacing="1"><table border="0" cellspacing="1"><tr><td valign="top" width="35%" class="dataLabel">
احصل على مفتاح API والمفتاح "السري" من Google بواسطة تسجيل مثيل Sugar كتطبيق جديد.
<br/><br>الخطوات لتسجيل المثيل الخاص بك:
<br/><br/>
<ol>
<li>اذهب إلى موقع مطوري Google:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>قم بتسجيل الدخول إلى حساب Google الذي ترغب في تسجيل التطبيق ضمنه.</li>
<li>إنشاء مشروع جديد</li>
<li>أدخل اسم المشروع وانقر فوق "حفظ".</li>
<li>بمجرد إنشاء المشروع قم بتمكين Google Drive وجهات اتصال API في Google </li>
<li>ضمن APIs & Auth > قسم بيانات الاعتماد قم بإنشاء معرف عميل جديد </li>
<li>حدد تطبيق ويب وانقر فوق تهيئة شاشة الموافقة</li>
<li>أدخل اسم المنتج وانقر فوق "حفظ"</li>
<li>ضمن قسم "عناوين URL المعاد توجيهها المعتمدة" أدخل عنوان URL التالي: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>انقر فوق إنشاء معرف العميل</li>
<li>انسخ معرف العميل وسر العميل في المربعات التالية</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'معرف العميل',
    'oauth2_client_secret' => 'سر العميل',
);
