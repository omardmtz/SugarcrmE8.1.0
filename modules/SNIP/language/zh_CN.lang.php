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
    'LBL_MODULE_NAME' => '电子邮件归档',
    'LBL_SNIP_SUMMARY' => "电子邮件归档是一种自动导入服务，它允许用户从任何邮件客户端或者服务将电子邮件发送至 Sugar 支持的邮件地址来导入 Sugar。每个 Sugar 实例都有它唯一的电子邮件地址。用户通过发送，抄送和秘密抄送将电子邮件发送至所提供的电子邮件地址并将其导入。电子邮件归档服务会把电子邮件导入到 Sugar 实例中。该服务导入电子邮件，包括任何附件，图片，日历事件，也可以基于匹配的电子邮件地址在与已有记录相关的在应用程序中创建记录。
<br><br>例如: 作为用户，当我查看一个账户时，我可以根据账户记录中的电子邮件地址查看与该账户关联的所有电子邮件。我同样可以看到与该账户相关联的联系人的电子邮件记录。
<br><br>接受以下条款，点击启用就可以开始使用该服务。您可以随时禁用该服务。一旦启用，所使用的电子邮件地址就会显示。
<br><br>",
	'LBL_REGISTER_SNIP_FAIL' => '联系电子邮件归档服务失败：%s!<br>',
	'LBL_CONFIGURE_SNIP' => '电子邮件归档',
    'LBL_DISABLE_SNIP' => '禁用',
    'LBL_SNIP_APPLICATION_UNIQUE_KEY' => '程序唯一密匙',
    'LBL_SNIP_USER' => '电子邮件归档用户',
    'LBL_SNIP_PWD' => '电子邮件归档密码',
    'LBL_SNIP_SUGAR_URL' => '该 Sugar 实例的 URL',
	'LBL_SNIP_CALLBACK_URL' => '电子邮件归档服务 URL',
    'LBL_SNIP_USER_DESC' => '电子邮件归档用户',
    'LBL_SNIP_KEY_DESC' => '电子邮件归档 OAuth 密匙。在访问该实例时用于导入电子邮件。',
    'LBL_SNIP_STATUS_OK' => '启用',
    'LBL_SNIP_STATUS_OK_SUMMARY' => '该 Sugar 实例已成功连接到电子邮件归档服务器。',
    'LBL_SNIP_STATUS_ERROR' => '错误',
    'LBL_SNIP_STATUS_ERROR_SUMMARY' => '该实例拥有有效的电子邮件归档服务器证书，但是服务返回了以下错误信息：',
    'LBL_SNIP_STATUS_FAIL' => '不能在电子邮件归档服务器注册',
    'LBL_SNIP_STATUS_FAIL_SUMMARY' => '电子邮件归档服务暂时不可用。 不是服务断线就是连接此 Sugar 实例失败。',
    'LBL_SNIP_GENERIC_ERROR' => '电子邮件归档服务暂时不可用。 不是服务断线就是连接此 Sugar 实例失败。',

	'LBL_SNIP_STATUS_RESET' => '还未运行',
	'LBL_SNIP_STATUS_PROBLEM' => '问题：%s',
    'LBL_SNIP_NEVER' => "从不",
    'LBL_SNIP_STATUS_SUMMARY' => "电子邮件归档服务状态：",
    'LBL_SNIP_ACCOUNT' => "帐户",
    'LBL_SNIP_STATUS' => "状态",
    'LBL_SNIP_LAST_SUCCESS' => "上一次成功运行",
    "LBL_SNIP_DESCRIPTION" => "电子邮件归档服务是一个自动电子邮件归档系统",
    "LBL_SNIP_DESCRIPTION_SUMMARY" => "它能让您看到 SugarCRM 中您发出或您的联系人发过来的邮件，不需要手工导入或链接邮件",
    "LBL_SNIP_PURCHASE_SUMMARY" => "您必须购买 SugarCRM 实例的许可证才能用电子邮件归档",
    "LBL_SNIP_PURCHASE" => "点击这里购买",
    'LBL_SNIP_EMAIL' => '电子邮件归档地址',
    'LBL_SNIP_AGREE' => "我同意上述条款和 <a href='http://www.sugarcrm.com/crm/TRUSTe/privacy.html' target='_blank'>隐私协议</a>。",
    'LBL_SNIP_PRIVACY' => '隐私协议',

    'LBL_SNIP_STATUS_PINGBACK_FAIL' => '回ping失败',
    'LBL_SNIP_STATUS_PINGBACK_FAIL_SUMMARY' => '电子邮件归档服务器不能与你的 Sugar
实例建立连接。请重新尝试或 <a href="http://www.sugarcrm.com/crm/case-tracker/submit.html?lsd=supportportal&tmpl=" target="_blank">联系客户支持</a>。',

    'LBL_SNIP_BUTTON_ENABLE' => '启用电子邮件归档',
    'LBL_SNIP_BUTTON_DISABLE' => '禁用电子邮件归档',
    'LBL_SNIP_BUTTON_RETRY' => '尝试重新连接',
    'LBL_SNIP_ERROR_DISABLING' => '在尝试与电子邮件归档服务器联系时发生错误，无法禁用服务',
    'LBL_SNIP_ERROR_ENABLING' => '在尝试与电子邮件归档服务器联系时发生错误，无法启用服务',
    'LBL_CONTACT_SUPPORT' => '请重新尝试或联系 SugarCRM 支持。',
    'LBL_SNIP_SUPPORT' => '请联系 SugarCRM 支持来获取帮助。',
    'ERROR_BAD_RESULT' => '服务返回坏结果',
	'ERROR_NO_CURL' => 'cURL 扩展已请求，但尚未启用',
	'ERROR_REQUEST_FAILED' => '无法联系服务器',

    'LBL_CANCEL_BUTTON_TITLE' => '取消',

    'LBL_SNIP_MOUSEOVER_STATUS' => '这是您的实例上的电子邮件归档服务器状态。状态反应了电子邮件归档服务器和您的 Sugar 实例是否连接成功。',
    'LBL_SNIP_MOUSEOVER_EMAIL' => '这是接收邮件的电子邮件归档邮件地址，用于将电子邮件导入到 Sugar 中。',
    'LBL_SNIP_MOUSEOVER_SERVICE_URL' => '这是电子邮件归档服务器的 URL。所有请求都将通过这个 URL 转播，例如启用或禁用电子邮件归档服务请求。',
    'LBL_SNIP_MOUSEOVER_INSTANCE_URL' => '这是您的 Sugar 实例 web 服务URL。电子邮件归档服务器将通过这个 URL 连接到您的服务器。',
);
