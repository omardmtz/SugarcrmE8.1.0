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
    'LBL_MODULE_NAME' => 'Архивиране на електронна поща',
    'LBL_SNIP_SUMMARY' => "Sugar Ease is an automatic email importing service that allows users to import emails into Sugar by sending them from any mail client or service to a Sugar-provided email address. Each Sugar instance has its own unique Sugar Ease mailbox. To import emails, a user sends to the Sugar Ease email address using the TO, CC, BCC fields. The Sugar Ease service will import the email into the Sugar instance. The service imports the email, along with any attachments, images and Calendar events, and creates records within the application that are associated with existing records based on matching email addresses.    <br><br>Example: As a user, when I view an Account, I will be able to see all the emails that are  associated with the Account based on the email address in the Account record.  I will also be able to see emails that are associated with Contacts related to the Account.    <br><br>Accept the terms below and click Enable to start using the service. You will be able to disable the service at any time. Once the service is enabled, the email address to use for the service will be displayed.    <br><br>",
	'LBL_REGISTER_SNIP_FAIL' => 'Failed to contact Sugar Ease service: %s!<br>',
	'LBL_CONFIGURE_SNIP' => 'Sugar Ease',
    'LBL_DISABLE_SNIP' => 'Забрани',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => 'Уникален ключ на приложението',
    'LBL_SNIP_USER' => 'Потребител, архивиращ имейла',
    'LBL_SNIP_PWD' => 'Sugar Ease Password',
    'LBL_SNIP_SUGAR_URL' => 'URL на тази инсталация на Sugar',
	'LBL_SNIP_CALLBACK_URL' => 'Sugar Ease service URL',
    'LBL_SNIP_USER_DESC' => 'Потребител, архивиращ имейла',
    'LBL_SNIP_KEY_DESC' => 'Имейл архивиране с ключ с отворена проверка. Използва се за достъп до тази инсталация за целите на импортирането на имейли.',
    'LBL_SNIP_STATUS_OK' => 'Разрешен',
    'LBL_SNIP_STATUS_OK_SUMMARY' => 'This Sugar instance is successfully connected to the Sugar Ease server.',
    'LBL_SNIP_STATUS_ERROR' => 'Грешка',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => 'This instance has a valid Sugar Ease license, but the server returned the following error message:',
    'LBL_SNIP_STATUS_FAIL' => 'Cannot register with Sugar Ease server',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => 'The Sugar Ease service is currently unavailable.  Either the service is down or the connection to this Sugar instance failed.',
    'LBL_SNIP_GENERIC_ERROR' => 'The Sugar Ease service is currently unavailable.  Either the service is down or the connection to this Sugar instance failed.',

	'LBL_SNIP_STATUS_RESET' => 'Все още не е стартиран',
	'LBL_SNIP_STATUS_PROBLEM' => 'Проблем: %s',
    'LBL_SNIP_NEVER' => "Никога",
    'LBL_SNIP_STATUS_SUMMARY' => "Sugar Ease archiving status:",
    'LBL_SNIP_ACCOUNT' => "Организация",
    'LBL_SNIP_STATUS' => "Статус",
    'LBL_SNIP_LAST_SUCCESS' => "Последно успешно изпълнение",
    "LBL_SNIP_DESCRIPTION" => "Sugar Ease is an automatic email archiving system",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "Позволява ви да видите имейлите, които са били изпратени до или от вашите контакти в рамките на SugarCRM, без да трябва ръчно да импортирате и свързвате имейлите",
    "LBL_SNIP_PURCHASE_SUMMARY" => "In order to use Sugar Ease, you must purchase a license for your SugarCRM instance",
    "LBL_SNIP_PURCHASE" => "Щракнете тук, за да закупите",
    'LBL_SNIP_EMAIL' => 'Sugar Ease Email',
    'LBL_SNIP_AGREE' => "Съгласен съм с горните условия и <a href='http://www.sugarcrm.com/crm/TRUSTe/privacy.html' target='_blank'>декларацията за поверителност</a>.",
    'LBL_SNIP_PRIVACY' => 'споразумение за поверителност',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => 'Обратното иззвъняване неуспешно',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => 'The Sugar Ease server is unable to establish a connection with your Sugar instance. Please try again or <a href="http://www.sugarcrm.com/crm/case-tracker/submit.html?lsd=supportportal&tmpl=" target="_blank">contact customer support</a>.',

    'LBL_SNIP_BUTTON_ENABLE' => 'Enable Sugar Ease',
    'LBL_SNIP_BUTTON_DISABLE' => 'Disable Sugar Ease',
    'LBL_SNIP_BUTTON_RETRY' => 'Опитайте да се свържете отново',
    'LBL_SNIP_ERROR_DISABLING' => 'An error occured while attempting to communicate with the Sugar Ease Server, and the service could not be disabled',
    'LBL_SNIP_ERROR_ENABLING' => 'An error occured while attempting to communicate with the Sugar Ease Server, and the service could not be enabled',
    'LBL_CONTACT_SUPPORT' => 'Моля, опитайте отново или се обърнете към поддръжката на SugarCRM.',
    'LBL_SNIP_SUPPORT' => 'Моля, свържете се с Поддръжката на SugarCRM за помощ.',
    'ERROR_BAD_RESULT' => 'Върнат неуспешен резултат от услугата',
	'ERROR_NO_CURL' => 'Изисква се cURL разширение, но не е активирано',
	'ERROR_REQUEST_FAILED' => 'Не може да се свърже със сървъра',

    'LBL_CANCEL_BUTTON_TITLE' => 'Отмени',

    'LBL_SNIP_MOUSEOVER_STATUS' => 'This is the status of the Sugar Ease service on your instance. The status reflects whether the connection between the Sugar Ease Server and your Sugar instance is successful.',
    'LBL_SNIP_MOUSEOVER_EMAIL' => 'This is the Sugar Ease email address to send to in order to import emails into Sugar.',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => 'This is the URL of the Sugar Ease Server. All requests, such as enabling and disabling the Sugar Ease service, will be relayed through this URL.',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => 'This is webservices URL of your Sugar instance. The Sugar Ease Server will connect to your server through this URL.',
);
