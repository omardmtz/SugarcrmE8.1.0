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

$mod_strings = array (
  // Dashboard Names
  'LBL_BUGS_LIST_DASHBOARD' => '错误列表仪表板',
  'LBL_BUGS_RECORD_DASHBOARD' => '错误记录仪表板',

  'LBL_MODULE_NAME' => '缺陷追踪',
  'LBL_MODULE_NAME_SINGULAR'	=> '错误',
  'LBL_MODULE_TITLE' => '缺陷跟踪：首页',
  'LBL_MODULE_ID' => '缺陷追踪',
  'LBL_SEARCH_FORM_TITLE' => '错误搜索',
  'LBL_LIST_FORM_TITLE' => '错误列表',
  'LBL_NEW_FORM_TITLE' => '新增错误',
  'LBL_CONTACT_BUG_TITLE' => '联系人- 错误：',
  'LBL_SUBJECT' => '主题:',
  'LBL_BUG' => '错误：',
  'LBL_BUG_NUMBER' => '错误编号：',
  'LBL_NUMBER' => '数量：',
  'LBL_STATUS' => '状态:',
  'LBL_PRIORITY' => '优先级：',
  'LBL_DESCRIPTION' => '说明:',
  'LBL_CONTACT_NAME' => '联系人姓名：',
  'LBL_BUG_SUBJECT' => '错误主题：',
  'LBL_CONTACT_ROLE' => '角色：',
  'LBL_LIST_NUMBER' => '编号.',
  'LBL_LIST_SUBJECT' => '主题',
  'LBL_LIST_STATUS' => '状态',
  'LBL_LIST_PRIORITY' => '优先级',
  'LBL_LIST_RELEASE' => '版本',
  'LBL_LIST_RESOLUTION' => '分析',
  'LBL_LIST_LAST_MODIFIED' => '最新修改',
  'LBL_INVITEE' => '联系人',
  'LBL_TYPE' => '类型:',
  'LBL_LIST_TYPE' => '类型',
  'LBL_RESOLUTION' => '分析:',
  'LBL_RELEASE' => '版本:',
  'LNK_NEW_BUG' => '报表缺陷',
  'LNK_CREATE'  => '汇报缺陷',
  'LNK_CREATE_WHEN_EMPTY'    => '现在报告错误。',
  'LNK_BUG_LIST' => '查看错误',
  'LBL_SHOW_MORE' => '显示更多错误',
  'NTC_REMOVE_INVITEE' => '您确定要从错误里移除这个联系人吗?',
  'NTC_REMOVE_ACCOUNT_CONFIRMATION' => '您确定要从此帐户中移除这个错误吗?',
  'ERR_DELETE_RECORD' => '您必须指定一个记录编号才能删除这个错误。',
  'LBL_LIST_MY_BUGS' => '分配给我的错误',
  'LNK_IMPORT_BUGS' => '输入错误',
  'LBL_FOUND_IN_RELEASE' => '在现有版本上查找：',
  'LBL_FIXED_IN_RELEASE' => '在现有版本上修复：',
  'LBL_LIST_FIXED_IN_RELEASE' => '在现有版本上修复',
  'LBL_WORK_LOG' => '工作日志：',
  'LBL_SOURCE' => '来源:',
  'LBL_PRODUCT_CATEGORY' => '类别：',

  'LBL_CREATED_BY' => '创建人：',
  'LBL_DATE_CREATED' => '创建日期：',
  'LBL_MODIFIED_BY' => '最新修改人：',
  'LBL_DATE_LAST_MODIFIED' => '修改日期：',

  'LBL_LIST_EMAIL_ADDRESS' => '邮箱地址',
  'LBL_LIST_CONTACT_NAME' => '联系人姓名',
  'LBL_LIST_ACCOUNT_NAME' => '账户名称',
  'LBL_LIST_PHONE' => '联系电话',
  'NTC_DELETE_CONFIRMATION' => '您确定要从错误中移除此联系人吗?',

  'LBL_DEFAULT_SUBPANEL_TITLE' => '缺陷跟踪',
  'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'活动',
  'LBL_HISTORY_SUBPANEL_TITLE'=>'历史记录',
  'LBL_CONTACTS_SUBPANEL_TITLE' => '联系人',
  'LBL_ACCOUNTS_SUBPANEL_TITLE' => '帐户',
  'LBL_CASES_SUBPANEL_TITLE' => '客户反馈',
  'LBL_PROJECTS_SUBPANEL_TITLE' => '项目',
  'LBL_DOCUMENTS_SUBPANEL_TITLE' => '文件',
  'LBL_LIST_ASSIGNED_TO_NAME' => '指定使用者',
	'LBL_ASSIGNED_TO_NAME' => '指派',

	'LNK_BUG_REPORTS' => '查看错误报表',
	'LBL_SHOW_IN_PORTAL' => '显示在门户',
	'LBL_BUG_INFORMATION' => '概述',

    //For export labels
	'LBL_FOUND_IN_RELEASE_NAME' => '在版本名称中查找',
    'LBL_PORTAL_VIEWABLE' => '可视门户站点',
    'LBL_EXPORT_ASSIGNED_USER_NAME' => '分配的用户名',
    'LBL_EXPORT_ASSIGNED_USER_ID' => '分配的用户 ID',
    'LBL_EXPORT_FIXED_IN_RELEASE_NAMR' => '在版本名称中修复',
    'LBL_EXPORT_MODIFIED_USER_ID' => '修改人 ID',
    'LBL_EXPORT_CREATED_BY' => '创建人 ID',

    //Tour content
    'LBL_PORTAL_TOUR_RECORDS_INTRO' => '该错误模块是用于查看和报告错误。使用下面的箭头开始快速的游览。',
    'LBL_PORTAL_TOUR_RECORDS_PAGE' => '此页显示已发表的错误列表。',
    'LBL_PORTAL_TOUR_RECORDS_FILTER' => '您可以提供搜索字词来过滤错误列表',
    'LBL_PORTAL_TOUR_RECORDS_FILTER_EXAMPLE' => '例如，您可以使用此功能找到之前已报告过的错误。',
    'LBL_PORTAL_TOUR_RECORDS_CREATE' => '如果您想报告新的错误，您可以点击这里举报新的错误。',
    'LBL_PORTAL_TOUR_RECORDS_RETURN' => '点击此处将随时返回到此视图。',

    'LBL_NOTES_SUBPANEL_TITLE' => '笔记',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{plural_module_name}} 模块用于追踪和管理产品相关问题，通常称为{{plural_module_name}} 或缺陷，无论是内部发现或客户报告的。 {{plural_module_name}} 可以进一步透过追踪已发表报告的发现及修复状况分流。 {{plural_module_name}} 模块让用户能快速审查所有{{module_name}} 的细节和使用程序来修正。一旦{{module_name}} 创建或提交后，您可以通过{{module_name}} 记录表查看和编辑{{module_name}} 相关资料。每个{{module_name}} 记录可能与其他Sugar记录相关，例如{{calls_module}}，{{contacts_module}}，{{cases_module}}，或其他。',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{plural_module_name}} 模块用于追踪和管理产品相关问题，通常称为{{plural_module_name}} 或缺陷，无论是内部发现或客户报告的。

- 点击单个字段或编辑按钮來编辑此记录字段。
- 通过切换底部左侧窗格中的“数据视图”来查看或修改连结到子面板的其他记录。
- 通过切换底部的左侧窗格中的“活动流”來制作和查看用户意见，并在{{activitystream_singular_module}} 记录更改历史。
- 使用记录名称右侧的图示来关注或标记最爱记录。
- 在编辑按钮右侧的下拉操作选单还有其他操作功能。',

    // Create View Help Text
    'LBL_HELP_CREATE' => '{{plural_module_name}} 模块用于追踪和管理由内部发现或客户反馈的产品相关问题，通常被称作{{plural_module_name}} 或缺陷。

创建{{module_name}}：
1. 按需提供字段值。
- 标记为“必填”的字段在保存前必须先填写完整。
- 如有需要，点击“显示更多”以显示其他字段。
2. 点击“保存”以完成新记录，并返回至上一页。',
);
