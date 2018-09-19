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
  'LBL_TARGETS_LIST_DASHBOARD' => '目标列表仪表板',
  'LBL_TARGETS_RECORD_DASHBOARD' => '目标记录仪表板',

  'LBL_MODULE_NAME' => '目标',
  'LBL_MODULE_NAME_SINGULAR' => '目标',
  'LBL_MODULE_ID'   => '目标',
  'LBL_INVITEE' => '直接报告',
  'LBL_MODULE_TITLE' => '目标：首页',
  'LBL_SEARCH_FORM_TITLE' => '目标搜索',
  'LBL_LIST_FORM_TITLE' => '目标列表',
  'LBL_NEW_FORM_TITLE' => '新增目标',
  'LBL_PROSPECT' => '目标：',
  'LBL_BUSINESSCARD' => '商务名片',
  'LBL_LIST_NAME' => '名称',
  'LBL_LIST_LAST_NAME' => '姓',
  'LBL_LIST_PROSPECT_NAME' => '目标姓名',
  'LBL_LIST_TITLE' => '职位',
  'LBL_LIST_EMAIL_ADDRESS' => '电子邮件',
  'LBL_LIST_OTHER_EMAIL_ADDRESS' => '其他电子邮件',
  'LBL_LIST_PHONE' => '电话',
  'LBL_LIST_PROSPECT_ROLE' => '角色',
  'LBL_LIST_FIRST_NAME' => '名',
  'LBL_ASSIGNED_TO_NAME' => '负责人',
  'LBL_ASSIGNED_TO_ID'=>'负责人：',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_last_name' => 'LBL_LIST_LAST_NAME',
  'db_first_name' => 'LBL_LIST_FIRST_NAME',
  'db_title' => 'LBL_LIST_TITLE',
  'db_email1' => 'LBL_LIST_EMAIL_ADDRESS',
  'db_email2' => 'LBL_LIST_OTHER_EMAIL_ADDRESS',
//END DON'T CONVERT
  'LBL_EXISTING_PROSPECT' => '使用现有联系人',
  'LBL_CREATED_PROSPECT' => '创建新联系人',
  'LBL_EXISTING_ACCOUNT' => '使用现有账户',
  'LBL_CREATED_ACCOUNT' => '创建新账户',
  'LBL_CREATED_CALL' => '创建新电话',
  'LBL_CREATED_MEETING' => '创建新会议',
  'LBL_ADDMORE_BUSINESSCARD' => '新增商务名片',
  'LBL_ADD_BUSINESSCARD' => '输入商务名片',
  'LBL_NAME' => '名称:',
  'LBL_FULL_NAME' => '名称',
  'LBL_PROSPECT_NAME' => '目标名称：',
  'LBL_PROSPECT_INFORMATION' => '概述',
  'LBL_MORE_INFORMATION' => '更多信息',
  'LBL_FIRST_NAME' => '名：',
  'LBL_OFFICE_PHONE' => '办公室电话：',
  'LBL_ANY_PHONE' => '任何电话：',
  'LBL_PHONE' => '电话：',
  'LBL_LAST_NAME' => '姓：',
  'LBL_MOBILE_PHONE' => '移动电话：',
  'LBL_HOME_PHONE' => '家庭电话：',
  'LBL_OTHER_PHONE' => '其他电话：',
  'LBL_FAX_PHONE' => '传真：',
  'LBL_STREET' => '街道',
  'LBL_PRIMARY_ADDRESS_STREET' => '主要住址街道：',
  'LBL_PRIMARY_ADDRESS_CITY' => '主要住址城市：',
  'LBL_PRIMARY_ADDRESS_COUNTRY' => '主要住址国家/地区：',
  'LBL_PRIMARY_ADDRESS_STATE' => '主要住址省份：',
  'LBL_PRIMARY_ADDRESS_POSTALCODE' => '主要住址邮政编码：',
  'LBL_ALT_ADDRESS_STREET' => '备用地址街道：',
  'LBL_ALT_ADDRESS_CITY' => '备用地址城市：',
  'LBL_ALT_ADDRESS_COUNTRY' => '备用地址国家/地区：',
  'LBL_ALT_ADDRESS_STATE' => '备用地址省份：',
  'LBL_ALT_ADDRESS_POSTALCODE' => '备用地址邮政编码：',
  'LBL_TITLE' => '职称：',
  'LBL_DEPARTMENT' => '部门：',
  'LBL_BIRTHDATE' => '生日：',
  'LBL_EMAIL_ADDRESS' => '电子邮件地址：',
  'LBL_OTHER_EMAIL_ADDRESS' => '其他电子邮件：',
  'LBL_ANY_EMAIL' => '电子邮件：',
  'LBL_ASSISTANT' => '助理：',
  'LBL_ASSISTANT_PHONE' => '助理电话：',
  'LBL_DO_NOT_CALL' => '谢绝来电：',
  'LBL_EMAIL_OPT_OUT' => '退出电子邮件：',
  'LBL_PRIMARY_ADDRESS' => '主要地址：',
  'LBL_ALTERNATE_ADDRESS' => '其他地址：',
  'LBL_ANY_ADDRESS' => '任何地址：',
  'LBL_CITY' => '城市：',
  'LBL_STATE' => '省份：',
  'LBL_POSTAL_CODE' => '邮编：',
  'LBL_COUNTRY' => '国家/地区：',
  'LBL_DESCRIPTION_INFORMATION' => '说明信息',
  'LBL_ADDRESS_INFORMATION' => '地址信息',
  'LBL_DESCRIPTION' => '说明:',
  'LBL_PROSPECT_ROLE' => '角色：',
  'LBL_OPP_NAME' => '商业机会名称:',
  'LBL_IMPORT_VCARD' => '导入 vCard',
  'LBL_IMPORT_VCARD_SUCCESS' => '从 vCard 成功的创建目标',
  'LBL_IMPORT_VCARDTEXT' => '从您的文件系统里通过导入 vCard 自动生成一个新目标。',
  'LBL_DUPLICATE' => '可能重复的目标',
  'MSG_SHOW_DUPLICATES' => '您想要创建的目标记录可能造成现有目标记录的重复。下面列出的是包括相同名称和/或电子邮件地址的目标记录。<br>点击“创建目标”继续创建新目标，或在下面已有目标中选择一个。',
  'MSG_DUPLICATE' => '您想要创建的目标记录可能造成现有目标记录的重复。下面列出的是包括相同名称和/或电子邮件地址的目标记录。<br>点击“保存”继续创建此新目标，或者点击“取消”返回模块不创建目标。',
  'LNK_IMPORT_VCARD' => '从 vCard 新建目标',
  'LNK_NEW_ACCOUNT' => '新增账户',
  'LNK_NEW_OPPORTUNITY' => '创建商业机会',
  'LNK_NEW_CASE' => '创建客户反馈',
  'LNK_NEW_NOTE' => '新增笔记或附件',
  'LNK_NEW_CALL' => '记录电话',
  'LNK_NEW_EMAIL' => '存档电子邮件',
  'LNK_NEW_MEETING' => '安排会议',
  'LNK_NEW_TASK' => '创建任务',
  'LNK_NEW_APPOINTMENT' => '创建约会',
  'LNK_IMPORT_PROSPECTS' => '导入目标',
  'NTC_DELETE_CONFIRMATION' => '您确定要删除这条记录吗？',
  'NTC_REMOVE_CONFIRMATION' => '您确定要将联系人从这个事件中删除吗?',
  'NTC_REMOVE_DIRECT_REPORT_CONFIRMATION' => '您确定要将作为直接报告的此记录移除吗?',
  'ERR_DELETE_RECORD' => '您必须指定一个记录编号才可删除此联系人。',
  'NTC_COPY_PRIMARY_ADDRESS' => '复制主要地址到备用地址',
  'NTC_COPY_ALTERNATE_ADDRESS' => '复制备用地址到主要地址',
  'LBL_SALUTATION' => '称谓',
  'LBL_SAVE_PROSPECT' => '保存目标',
  'LBL_CREATED_OPPORTUNITY' =>'创建新的商业机会',
  'NTC_OPPORTUNITY_REQUIRES_ACCOUNT' => '新建商业机会需要账户。\\n 您可以新建账户或选择现有账户。',
  'LNK_SELECT_ACCOUNT' => "选择账户",
  'LNK_NEW_PROSPECT' => '创建目标',
  'LNK_PROSPECT_LIST' => '查看目标',
  'LNK_NEW_CAMPAIGN' => '创建市场活动',
  'LNK_CAMPAIGN_LIST' => '市场活动',
  'LNK_NEW_PROSPECT_LIST' => '创建目标列表',
  'LNK_PROSPECT_LIST_LIST' => '目标列表',
  'LNK_IMPORT_PROSPECT' => '导入目标',
  'LBL_SELECT_CHECKED_BUTTON_LABEL' => '选择选中的目标',
  'LBL_SELECT_CHECKED_BUTTON_TITLE' => '选择选中的目标',
  'LBL_INVALID_EMAIL'=>'无效的电子邮件：',
  'LBL_DEFAULT_SUBPANEL_TITLE'=>'目标',
  'LBL_PROSPECT_LIST' => '潜在客户列表',
  'LBL_CONVERT_BUTTON_KEY' => 'V',
  'LBL_CONVERT_BUTTON_TITLE' => '转换目标',
  'LBL_CONVERT_BUTTON_LABEL' => '转换目标',
  'LBL_CONVERTPROSPECT'=>'转换目标',
  'LNK_NEW_CONTACT'=>'新联系人',
  'LBL_CREATED_CONTACT'=>"创建一个新联系人",
  'LBL_BACKTO_PROSPECTS'=>'返回至目标',
  'LBL_CAMPAIGNS'=>'市场活动',
  'LBL_CAMPAIGN_LIST_SUBPANEL_TITLE'=>'市场活动日志',
  'LBL_TRACKER_KEY'=>'跟踪器密匙',
  'LBL_LEAD_ID'=>'潜在客户编号',
  'LBL_LEAD' => '潜在客户',
  'LBL_CONVERTED_LEAD'=>'转换潜在客户',
  'LBL_ACCOUNT_NAME'=>'账户名称',
  'LBL_EDIT_ACCOUNT_NAME'=>'客户名称:',
  'LBL_CREATED_USER' => '已创建的用户',
  'LBL_MODIFIED_USER' => '已修改的用户',
  'LBL_CAMPAIGNS_SUBPANEL_TITLE' => '市场活动',
  'LBL_HISTORY_SUBPANEL_TITLE'=>'历史活动',
  //For export labels
  'LBL_PHONE_HOME' => '电话首页',
  'LBL_PHONE_MOBILE' => '移动电话',
  'LBL_PHONE_WORK' => '工作电话',
  'LBL_PHONE_OTHER' => '其他电话',
  'LBL_PHONE_FAX' => '电话传真',
  'LBL_CAMPAIGN_ID' => '市场活动 ID',
  'LBL_EXPORT_ASSIGNED_USER_NAME' => '分配的用户名',
  'LBL_EXPORT_ASSIGNED_USER_ID' => '分配的用户 ID',
  'LBL_EXPORT_MODIFIED_USER_ID' => '修改人 ID',
  'LBL_EXPORT_CREATED_BY' => '创建人 ID',
  'LBL_EXPORT_EMAIL2'=>'其他电子邮件地址',
  'LBL_RECORD_SAVED_SUCCESS' => '您已成功创建 {{moduleSingularLower}} <a href="#{{buildRoute model=this}}">{{full_name}}</a>。',
    //D&B Principal Identification
    'LBL_DNB_PRINCIPAL_ID' => 'D&B 主要编号',
    //Document title
    'TPL_BROWSER_SUGAR7_RECORDS_TITLE' => '{{module}} &raquo; {{appId}}',
    'TPL_BROWSER_SUGAR7_RECORD_TITLE' => '{{#if last_name}}{{#if first_name}}{{first_name}} {{/if}}{{last_name}} &raquo; {{/if}}{{module}} &raquo; {{appId}}',

    // Help Text
    // List View Help Text
    'LBL_HELP_RECORDS' => '{{module_name}} 模块中包含未经确定的潜在目标的个人，您拥有其部分信息，但尚未能列为合格的{{leads_singular_module}}。关于这些 {{plural_module_name}} 的信息（例如名字和电子邮件地址）一般来自于各种展览会、会议期间收集的名
片，Sugar 内的 {{plural_module_name}} 是独立的记录，与 {{contacts_module}}、{{leads_module}}、{{accounts_module}} 或 {{opportunities_module}} 等无关。有多种方法可在 Sugar 内创建{{plural_module_name}}，例如通过 {{plural_module_name}} 模块，导入 {{plural_module_name}} 等。创建 {{module_name}} 记录之后，您可以通过 {{plural_module_name}} 的记录视图查看并编辑关于 {{module_name}} 的信息。',

    // Record View Help Text
    'LBL_HELP_RECORD' => '{{module_name}} 模块中包含未经确定的潜在目标的个人，您拥有其部分信息，但尚未能列为合格的{{leads_singular_module}}。

－点击单一字段或“编辑”按钮，对记录的字段进行编辑。
－将底部左侧的窗格切换至“数据视图”，查看或修改子面板中其他记录的链接。
－将底部左侧的窗格切换至“活动流”，以在 {{activitystream_singular_module}} 中创建和查看用户评论以及记录更改历史。
－使用记录名称右侧的图标，关注或收藏此记录。
－使用“编辑”按钮右方的下拉操作菜单，可执行其他操作。',

    // Create View Help Text
    'LBL_HELP_CREATE' => '{{module_name}} 模块中包含未经确定的潜在个目标，您拥有其部分信息，但尚未能列为合格的{{leads_singular_module}}。

创建{{module_name}}：
1. 按需提供字段值。 
 - 标记为“必填”的字段在保存前必须先填写完整。
 - 如有需要，点击“显示更多”以显示其它字段。
2. 点击“保存”以完成新纪录，并返回至上一页。',

    'LBL_FILTER_PROSPECTS_REPORTS' => '目标报告',
    'LBL_DATAPRIVACY_BUSINESS_PURPOSE' => '许可的商业目的',
    'LBL_DATAPRIVACY_CONSENT_LAST_UPDATED' => '上次更新的许可',
);
