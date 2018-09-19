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
  'LBL_TASKS_LIST_DASHBOARD' => '任务列表仪表板',

  'LBL_MODULE_NAME' => '任务',
  'LBL_MODULE_NAME_SINGULAR' => '任务',
  'LBL_TASK' => '任务：',
  'LBL_MODULE_TITLE' => '任务：首页',
  'LBL_SEARCH_FORM_TITLE' => '任务搜索',
  'LBL_LIST_FORM_TITLE' => '任务列表',
  'LBL_NEW_FORM_TITLE' => '创建任务',
  'LBL_NEW_FORM_SUBJECT' => '主题:',
  'LBL_NEW_FORM_DUE_DATE' => '截止日期：',
  'LBL_NEW_FORM_DUE_TIME' => '截止时间：',
  'LBL_NEW_TIME_FORMAT' => '(24:00)',
  'LBL_LIST_CLOSE' => '关闭',
  'LBL_LIST_SUBJECT' => '主题',
  'LBL_LIST_CONTACT' => '联系人',
  'LBL_LIST_PRIORITY' => '优先级',
  'LBL_LIST_RELATED_TO' => '关联到',
  'LBL_LIST_DUE_DATE' => '截止日期',
  'LBL_LIST_DUE_TIME' => '截止时间',
  'LBL_SUBJECT' => '主题:',
  'LBL_STATUS' => '状态:',
  'LBL_DUE_DATE' => '截止日期：',
  'LBL_DUE_TIME' => '截止时间：',
  'LBL_PRIORITY' => '优先级：',
  'LBL_COLON' => ':',
  'LBL_DUE_DATE_AND_TIME' => '截止日期与时间：',
  'LBL_START_DATE_AND_TIME' => '开始日期与时间：',
  'LBL_START_DATE' => '开始日期：',
  'LBL_LIST_START_DATE' => '开始日期',
  'LBL_START_TIME' => '开始时间：',
  'LBL_LIST_START_TIME' => '开始时间',
  'DATE_FORMAT' => '(年-月-日)',
  'LBL_NONE' => '无',
  'LBL_CONTACT' => '联系人：',
  'LBL_EMAIL_ADDRESS' => '电子邮件地址：',
  'LBL_PHONE' => '电话：',
  'LBL_EMAIL' => '电子邮件地址：',
  'LBL_DESCRIPTION_INFORMATION' => '说明信息',
  'LBL_DESCRIPTION' => '说明:',
  'LBL_NAME' => '名称:',
  'LBL_CONTACT_NAME' => '联系人姓名',
  'LBL_LIST_COMPLETE' => '完成：',
  'LBL_LIST_STATUS' => '状态',
  'LBL_DATE_DUE_FLAG' => '无截止日期',
  'LBL_DATE_START_FLAG' => '无开始日期',
  'ERR_DELETE_RECORD' => '必须指定记录编号才能删除联系人。',
  'ERR_INVALID_HOUR' => '请输入 0 到 24 之间的小时数',
  'LBL_DEFAULT_PRIORITY' => '中',
  'LBL_LIST_MY_TASKS' => '我的公开任务',
  'LNK_NEW_TASK' => '创建任务',
  'LNK_TASK_LIST' => '查看任务',
  'LNK_IMPORT_TASKS' => '导入任务',
  'LBL_CONTACT_FIRST_NAME'=>'联系人的名',
  'LBL_CONTACT_LAST_NAME'=>'联系人的姓',
  'LBL_LIST_ASSIGNED_TO_NAME' => '指派的用户',
  'LBL_ASSIGNED_TO_NAME'=>'负责人：',
  'LBL_LIST_DATE_MODIFIED' => '修改的日期',
  'LBL_CONTACT_ID' => '联系人 ID：',
  'LBL_PARENT_ID' => '父级编号：',
  'LBL_CONTACT_PHONE' => '联系人电话：',
  'LBL_PARENT_NAME' => '父类型：',
  'LBL_ACTIVITIES_REPORTS' => '活动报告',
  'LBL_EDITLAYOUT' => '编辑布局' /*for 508 compliance fix*/,
  'LBL_TASK_INFORMATION' => '概述',
  'LBL_HISTORY_SUBPANEL_TITLE' => '笔记',
  'LBL_REVENUELINEITEMS' => '营收单项',
  //For export labels
  'LBL_DATE_DUE' => '截止日期',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => '分配的用户名',
  'LBL_EXPORT_ASSIGNED_USER_ID' => '分配的用户 ID',
  'LBL_EXPORT_MODIFIED_USER_ID' => '修改人 ID',
  'LBL_EXPORT_CREATED_BY' => '创建人 ID',
  'LBL_EXPORT_PARENT_TYPE' => '与模块关联',
  'LBL_EXPORT_PARENT_ID' => '关联到 ID',
  'LBL_TASK_CLOSE_SUCCESS' => '任务成功结束。',
  'LBL_ASSIGNED_USER' => '负责人',

    'LBL_NOTES_SUBPANEL_TITLE' => '笔记',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{plural_module_name}} 模块包含可变操作、待办事项或其它需完成的活动类型。{{module_name}} 记录可通过可变关联字段关联至大多数模块的一个记录，亦可关联至单个 {{contacts_singular_module}}。在 Sugar 中创建 {{plural_module_name}} 的方式多种多样，比如通过 {{plural_module_name}} 模块、复制、导入 {{plural_module_name}} 等。创建 {{module_name}} 记录后，即可通过 {{plural_module_name}} 记录视图查看和编辑 {{module_name}} 有关信息。视乎 {{module_name}} 的详细情况而定，您亦可通过“日历”模块查看和编辑 {{module_name}} 信息。之后，各 {{module_name}} 记录可关联至其它 Sugar 记录，比如 {{accounts_module}}、{{contacts_module}}、{{opportunities_module}}。',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{plural_module_name}} 模块包含可变操作、待办事项或其它需完成的活动类型。

- 通过点击单个字段或“编辑”按钮，编辑此记录的字段。
- 通过切换左下角窗格至“数据视图”，查看或修改子面板其它记录的链接。
- 通过切换左下角窗格至“活动流”，在 {{activitystream_singular_module}} 中执行和查看用户注释以及记录更改历史。
- 使用记录名称右侧的图标关注此记录或将此记录新增至收藏夹。
- “编辑”按钮右侧的下拉“操作”菜单提供其它操作选项。',

    // Create View Help Text
    'LBL_HELP_CREATE' => '{{plural_module_name}} 模块包含可变操作、待办事项或其它需完成的活动类型。

创建 {{module_name}}：
1. 按需提供字段值。
 - 标记为“必填”的字段在保存前必须先填写完整。
 - 如有需要，点击“显示更多”以显示其它字段。
2. 点击“保存”以完成新纪录，并返回至上一页。',

);
