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
    // Dashboard Names
    'LBL_NOTES_LIST_DASHBOARD' => '笔记列表仪表板',

    'ERR_DELETE_RECORD' => '必须指定记录编号才能删除帐户。',
    'LBL_ACCOUNT_ID' => '帐户 ID：',
    'LBL_CASE_ID' => '客户反馈编号：',
    'LBL_CLOSE' => '关闭：',
    'LBL_COLON' => ':',
    'LBL_CONTACT_ID' => '联系人 ID：',
    'LBL_CONTACT_NAME' => '联系人：',
    'LBL_DEFAULT_SUBPANEL_TITLE' => '笔记',
    'LBL_DESCRIPTION' => '备忘录',
    'LBL_EMAIL_ADDRESS' => '电子邮件地址：',
    'LBL_EMAIL_ATTACHMENT' => '电子邮件附件',
    'LBL_EMAIL_ATTACHMENT_FOR' => '电子邮件附件用于',
    'LBL_FILE_MIME_TYPE' => 'Mime 类型',
    'LBL_FILE_EXTENSION' => '文件扩展名',
    'LBL_FILE_SOURCE' => '文件来源',
    'LBL_FILE_SIZE' => '文件大小',
    'LBL_FILE_URL' => '文件 URL',
    'LBL_FILENAME' => '附件：',
    'LBL_LEAD_ID' => '潜在客户编号：',
    'LBL_LIST_CONTACT_NAME' => '联系人',
    'LBL_LIST_DATE_MODIFIED' => '最终修改',
    'LBL_LIST_FILENAME' => '附件',
    'LBL_LIST_FORM_TITLE' => '笔记列表',
    'LBL_LIST_RELATED_TO' => '关联到',
    'LBL_LIST_SUBJECT' => '主题',
    'LBL_LIST_STATUS' => '状态',
    'LBL_LIST_CONTACT' => '联系人',
    'LBL_MODULE_NAME' => '笔记',
    'LBL_MODULE_NAME_SINGULAR' => '笔记',
    'LBL_MODULE_TITLE' => '笔记：首页',
    'LBL_NEW_FORM_TITLE' => '新增笔记或添加附件',
    'LBL_NEW_FORM_BTN' => '添加笔记',
    'LBL_NOTE_STATUS' => '笔记',
    'LBL_NOTE_SUBJECT' => '主题：',
    'LBL_NOTES_SUBPANEL_TITLE' => '笔记与附件',
    'LBL_NOTE' => '笔记：',
    'LBL_OPPORTUNITY_ID' => '商业机会编号：',
    'LBL_PARENT_ID' => '父级编号：',
    'LBL_PARENT_TYPE' => '原始类型',
    'LBL_EMAIL_TYPE' => '电子邮件类型',
    'LBL_EMAIL_ID' => '电子邮件编号',
    'LBL_PHONE' => '电话：',
    'LBL_PORTAL_FLAG' => '在门户中显示？',
    'LBL_EMBED_FLAG' => '嵌入到电子邮件中？',
    'LBL_PRODUCT_ID' => '已报价单项编号：',
    'LBL_QUOTE_ID' => '报价编号：',
    'LBL_RELATED_TO' => '关联到：',
    'LBL_SEARCH_FORM_TITLE' => '查找笔记',
    'LBL_STATUS' => '状态',
    'LBL_SUBJECT' => '主题',
    'LNK_IMPORT_NOTES' => '导入笔记',
    'LNK_NEW_NOTE' => '新增笔记或附件',
    'LNK_NOTE_LIST' => '查看笔记',
    'LBL_MEMBER_OF' => '成员：',
    'LBL_LIST_ASSIGNED_TO_NAME' => '负责人',
    'LBL_OC_FILE_NOTICE' => '如需查看文件，请先登录服务器',
    'LBL_REMOVING_ATTACHMENT' => '正在移除附件...',
    'ERR_REMOVING_ATTACHMENT' => '移除附件失败...',
    'LBL_CREATED_BY' => '创建人',
    'LBL_MODIFIED_BY' => '修改人',
    'LBL_SEND_ANYWAYS' => '没有电子邮件主题，确定发送或保存吗？',
    'LBL_LIST_EDIT_BUTTON' => '编辑',
    'LBL_ACTIVITIES_REPORTS' => '活动报表',
    'LBL_PANEL_DETAILS' => '详细说明',
    'LBL_NOTE_INFORMATION' => '概述',
    'LBL_MY_NOTES_DASHLETNAME' => '我的笔记',
    'LBL_EDITLAYOUT' => '编辑布局' /*for 508 compliance fix*/,
    //For export labels
    'LBL_FIRST_NAME' => '名字',
    'LBL_LAST_NAME' => '姓',
    'LBL_EXPORT_PARENT_TYPE' => '与模块关联',
    'LBL_EXPORT_PARENT_ID' => '关联到 ID',
    'LBL_DATE_ENTERED' => '创建日期',
    'LBL_DATE_MODIFIED' => '修改的日期',
    'LBL_DELETED' => '已删除',
    'LBL_REVENUELINEITEMS' => '营收单项',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{plural_module_name}} 模块由单独的 {{plural_module_name}} 组成，这些单独模块包含与相关记录有关的文本或附件。{{module_name}} 记录可通过灵活的相关字段与大部分模块中的一个记录相关联，也可以与单一的 {{contacts_singular_module}} 相关联。{{plural_module_name}} 可以保持关于记录的一般文本或甚至与记录有关的附件。可使用很多方法在 Sugar 中创建 {{plural_module_name}}，例如通过 {{plural_module_name}} 模块、导入 {{plural_module_name}}、通过历史子面板等。创建 {{module_name}} 记录之后，您可通过 {{plural_module_name}} 记录视图来查看和编辑与 {{module_name}} 有关的信息。每一个 {{module_name}} 记录便可与其他 Sugar 记录相关联，例如 {{accounts_module}}、{{contacts_module}}、{{opportunities_module}} 和其他许多记录。',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{plural_module_name}} 模块由包含相关记录有关文本或附件的单个 {{plural_module_name}} 组成。

- 通过点击单个字段或“编辑”按钮，编辑此记录的字段。
- 通过切换左下角窗格至“数据视图”，查看或修改子面板其它记录的链接。
- 通过切换左下角窗格至“活动流”，在 {{activitystream_singular_module}} 中执行和查看用户注释以及记录更改历史。
- 使用记录名称右侧的图标关注此记录或将此记录新增至收藏夹。
- “编辑”按钮右侧的下拉“操作”菜单提供其它操作选项。',

    // Create View Help Text
    'LBL_HELP_CREATE' => '创建{{module_name}}：
1. 按需提供字段值。
 - 标记为“必填”的字段在保存前必须先填写完整。
 - 如有需要，点击“显示更多”以显示其他字段。
2. 点击“保存”以完成新纪录，并返回至上一页。',
);
