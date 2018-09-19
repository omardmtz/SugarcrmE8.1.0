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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array(
    'LBL_MODULE_NAME' => 'ארכיון דואר אלקטרוני',
    'LBL_SNIP_SUMMARY' => "Email Archiving is an automatic importing service that allows users to import emails into Sugar by sending them from any mail client or service to a Sugar-provided email address. Each Sugar instance has its own unique email address. To import emails, a user sends to the provided email address using the TO, CC, BCC fields. The Email Archiving service will import the email into the Sugar instance. The service imports the email, along with any attachments, images and Calendar events, and creates records within the application that are associated with existing records based on matching email addresses.<br />    <br><br>Example: As a user, when I view an Account, I will be able to see all the emails that are  associated with the Account based on the email address in the Account record.  I will also be able to see emails that are associated with Contacts related to the Account.<br />    <br><br>Accept the terms below and click Enable to start using the service. You will be able to disable the service at any time. Once the service is enabled, the email address to use for the service will be displayed.<br />    <br><br>",
	'LBL_REGISTER_SNIP_FAIL' => 'כישלון בעת יצירת קשר עם שירות ארכיון דוא"ל: %s<br>',
	'LBL_CONFIGURE_SNIP' => 'מערכב דואר אלקטרוני',
    'LBL_DISABLE_SNIP' => 'לא מאופשר',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'מפתח אפליקציה יחודי',
    'LBL_SNIP_USER' => 'משתמש ארכיון דואר אלקטרוני',
    'LBL_SNIP_PWD' => 'סוסמא של משתמש ארכיב דואר אלקטרוני',
    'LBL_SNIP_SUGAR_URL' => 'כתובת האינטרנט של ההתקנה של שוגר שלך',
	'LBL_SNIP_CALLBACK_URL' => 'כתובת אינטרנט של שירות ארכיב דואר אלקטרוני',
    'LBL_SNIP_USER_DESC' => 'משתמש ארכיב דואר אלקטרוני',
    'LBL_SNIP_KEY_DESC' => 'מפתח הכניסה לארכיון הדואר.משתמש כדי להיכנס לארכיון כדי ליבא דברי דואר',
    'LBL_SNIP_STATUS_OK' => 'מאופשר',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'ההתקנה הזו של שוגר התחברה בהצלחה לשרת ארכיון הדואר',
    'LBL_SNIP_STATUS_ERROR' => 'שגיאה',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'להתקנה זו יש רישיון תקף לשרת ארכיון הדואר,אבל שרת הארכיון החזיר את הודעת השגיאה הזו:',
    'LBL_SNIP_STATUS_FAIL' => 'לא מצליח להירשם אצל שרת ארכיון הדואר',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'שירות ארכיון הדואר כרגע איננו זמין.או שהשירות למטה או שאירעה שגיאה להתקנה של שוגר',
    'LBL_SNIP_GENERIC_ERROR' => 'שירות ארכיון הדואר כרגע איננו זמין.או שהשירות למטה או שאירעה שגיאה להתקנה של שוגר',

	'LBL_SNIP_STATUS_RESET' => 'לא רץ עדיין',
	'LBL_SNIP_STATUS_PROBLEM' => 'בעיות: %s',
    'LBL_SNIP_NEVER' => "מעולם",
    'LBL_SNIP_STATUS_SUMMARY' => "מצב ארכיון דואר אלקטרוני",
    'LBL_SNIP_ACCOUNT' => "חשבון",
    'LBL_SNIP_STATUS' => "מצב",
    'LBL_SNIP_LAST_SUCCESS' => "ריצה מוצלחת אחרונה",
    "LBL_SNIP_DESCRIPTION" => "שירות ארכיון הדואר הוא שירות ארכיון אוטמטי",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "מאפשר לך לראות דואר שנשלח אל ומאת אנשי קשר הרשומים במערכת,מבלי שתצטרך ליבא ידנית או ללנקק להודעות",
    "LBL_SNIP_PURCHASE_SUMMARY" => "כדי להשתמש בארכיון הדואר,עליך לרכוש רישיון להתקנת שוגר שלך",
    "LBL_SNIP_PURCHASE" => "הקלק כאן לרכישה",
    'LBL_SNIP_EMAIL' => 'כתובת ארכיון הדואר',
    'LBL_SNIP_AGREE' => "I agree to the above terms and the <a href=&#39;http://www.sugarcrm.com/crm/TRUSTe/privacy.html&#39; target=&#39;_blank&#39;>privacy agreement</a>.",
    'LBL_SNIP_PRIVACY' => 'הצהרת פרטיות',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'שליחת פינג חוזר נכשלה',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'שרת ארכיון הדוא"ל לא יכול להתחבר למופע Sugar שלך. אנא נסה שוב או <a href="http://www.sugarcrm.com/crm/case-tracker/submit.html?lsd=supportportal&tmpl=" target="_blank">צור קשר עם שירות לקוחות</a>.',

    'LBL_SNIP_BUTTON_ENABLE' => 'מאפשר ארכיון דואר',
    'LBL_SNIP_BUTTON_DISABLE' => 'לא מאפשר ארכיון דואר',
    'LBL_SNIP_BUTTON_RETRY' => 'נזה להתחבר שנית',
    'LBL_SNIP_ERROR_DISABLING' => 'אירעה שגיאה בעת ההתקשרות לשרת ארכיון הדוארתהשירות לא יכול להיות לא מאופשר.',
    'LBL_SNIP_ERROR_ENABLING' => 'אירעה שגיאה בהתחברות לשרת ארכיון הדואר,לא ניתן לאפשר את השירות',
    'LBL_CONTACT_SUPPORT' => 'אנא נסה שנית או צור קשר עם התמיכה',
    'LBL_SNIP_SUPPORT' => 'אנא צור קשר עם עזרה ותמיכה של שוגר',
    'ERROR_BAD_RESULT' => 'תוצאה לא טובה הוחזרה מהשירות',
	'ERROR_NO_CURL' => 'הרחבות cURL נדרשות, אך לא הופעלו',
	'ERROR_REQUEST_FAILED' => 'לא מצליחח להתקשר עם השרת',

    'LBL_CANCEL_BUTTON_TITLE' => 'בטל',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'זהו בסטאטוס של ארכון הדואר הקשור בהתקנה שלך.הסטאטוס משקף האם הקשר בין ההתקנה שלך לשרת ארכיון הדואר התקיימה בהצלחה.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'זוהי כתובת דואר אלקטרוני כדי לשלוח על מנת להתחבר לשרת ארכיון הדואר',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'זוהי כתובת האינטרנט של שרת ארכיון הדואר,מאפשר לאפשר או לבטל את השירות,יופעל במצעות ה URL המצורף',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'זו כתובת URL של שירותי אינטרנט למופע Sugar שלך. שרת ארכיון הדוא"ל יתחבר לשרת שלך דרך כתובת URL זאת.',
);
