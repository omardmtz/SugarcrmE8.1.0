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
השג מפתח וסוד API מ-Google על ידי רישום מופע ה-Sugar שלך כאפליקציה חדשה.
<br/><br>שלבי רישום המופע שלך:
<br/><br/>
<ol>
<li>עבור לאתר של Google Developers:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>היכנס באמצעות חשבון Google שבו אתה רוצה לרשום את האפליקציה.</li>
<li>צור פרויקט חדש </li>
<li>הזן שם פרוייקט ולחץ \'צור\'.</li>
<li>ברגע שהפרויקט נוצר, אפשר ה-Google Drive וה- Google Contacts API</li>
<li>תחת חלק APIs ואימות > בחלק אישורים, צור מזהה לקוח חדש </li>
<li>בחר Web Application ולחץ על \'קבע תצורת מסך הסכמה\'</li>
<li>הזן שם מוצר ולחץ על \'שמור\'</li>
<li>תחת החלק רכיבי URI ניתוב מחדש מורשים, הזן את כתובת ה-url הבאה:
{$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>לחץ \'צור מזהה לקוח\'</li>
<li>העתק את מזהה הלקוח וסוד הלקוח בתיבות להלן</ 5>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'מזהה לקוח',
    'oauth2_client_secret' => 'סוד הלקוח',
);
