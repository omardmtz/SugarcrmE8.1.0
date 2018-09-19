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
 * $Id$
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
 $mod_strings = array (
  'LBL_MODULE_NAME' => '销售',
  'LBL_MODULE_TITLE' => '销售：首页',
  'LBL_SEARCH_FORM_TITLE' => '销售搜索',
  'LBL_VIEW_FORM_TITLE' => '销售视图',
  'LBL_LIST_FORM_TITLE' => '销售列表',
  'LBL_SALE_NAME' => '销售名称：',
  'LBL_SALE' => '销售：',
  'LBL_NAME' => '销售名称',
  'LBL_LIST_SALE_NAME' => '名称',
  'LBL_LIST_ACCOUNT_NAME' => '账户名称',
  'LBL_LIST_AMOUNT' => '数量',
  'LBL_LIST_DATE_CLOSED' => '结束',
  'LBL_LIST_SALE_STAGE' => '销售阶段',
  'LBL_ACCOUNT_ID'=>'帐户 ID',
  'LBL_TEAM_ID' =>'团队编号',
//DON'T CONVERT THESE THEY ARE MAPPINGS
  'db_sales_stage' => 'LBL_LIST_SALES_STAGE',
  'db_name' => 'LBL_NAME',
  'db_amount' => 'LBL_LIST_AMOUNT',
  'db_date_closed' => 'LBL_LIST_DATE_CLOSED',
//END DON'T CONVERT
  'UPDATE' => '销售-货币更新',
  'UPDATE_DOLLARAMOUNTS' => '更新美元总额',
  'UPDATE_VERIFY' => '验证金额',
  'UPDATE_VERIFY_TXT' => '验证销售金额为有效的十进制数，仅包含数字(0-9)和小数点(.)',
  'UPDATE_FIX' => '修复金额',
  'UPDATE_FIX_TXT' => '尝试从目前的金额新增有效的小数点来修正任何错误的金额。原有的资料会备份到 amount_backup 数据库字段。如果您在执行过程中发现任何错误，记得在重新执行前先使用备份数值进行还原，避免备份数值被新增的无效数据覆盖。',
  'UPDATE_DOLLARAMOUNTS_TXT' => '按当前设置的货币汇率更新美元销售金额。这个值被用于计算图形和列表视图中的货币金额。',
  'UPDATE_CREATE_CURRENCY' => '创建新货币：',
  'UPDATE_VERIFY_FAIL' => '记录验证失败：',
  'UPDATE_VERIFY_CURAMOUNT' => '当前金额：',
  'UPDATE_VERIFY_FIX' => '运行修复会给',
  'UPDATE_INCLUDE_CLOSE' => '包括关闭记录',
  'UPDATE_VERIFY_NEWAMOUNT' => '新金额：',
  'UPDATE_VERIFY_NEWCURRENCY' => '新货币：',
  'UPDATE_DONE' => '已完成',
  'UPDATE_BUG_COUNT' => '已发现并尝试修复的缺陷：',
  'UPDATE_BUGFOUND_COUNT' => '已发现的缺陷：',
  'UPDATE_COUNT' => '更新的记录：',
  'UPDATE_RESTORE_COUNT' => '还原的记录金额：',
  'UPDATE_RESTORE' => '恢复金额',
  'UPDATE_RESTORE_TXT' => '通过修正期间新增的备份来还原金额数值。',
  'UPDATE_FAIL' => '不能更新 -',
  'UPDATE_NULL_VALUE' => '没有输入金额的项目会设置为 0-',
  'UPDATE_MERGE' => '合并货币',
  'UPDATE_MERGE_TXT' => '将多种货币合并成单一货币。如果您发现同样的货币有多条记录，您可以将他们合并。这将会合并所有其他模块的货币。',
  'LBL_ACCOUNT_NAME' => '客户名：',
  'LBL_AMOUNT' => '金额：',
  'LBL_AMOUNT_USDOLLAR' => '美元金额：',
  'LBL_CURRENCY' => '货币：',
  'LBL_DATE_CLOSED' => '预期结束日期：',
  'LBL_TYPE' => '类型：',
  'LBL_CAMPAIGN' => '市场活动：',
  'LBL_LEADS_SUBPANEL_TITLE' => '潜在客户',
  'LBL_PROJECTS_SUBPANEL_TITLE' => '项目',  
  'LBL_NEXT_STEP' => '下一步：',
  'LBL_LEAD_SOURCE' => '潜在客户来源：',
  'LBL_SALES_STAGE' => '销售阶段：',
  'LBL_PROBABILITY' => '可能性(%)：',
  'LBL_DESCRIPTION' => '说明：',
  'LBL_DUPLICATE' => '可能重复的销售',
  'MSG_DUPLICATE' => '您要创建的销售可能会与已有的销售记录重复。含类似名称的销售记录如下。<br>单击“保存”继续创建这个新销售，或者单击“取消”返回模块取消本次销售创建。',
  'LBL_NEW_FORM_TITLE' => '创建销售',
  'LNK_NEW_SALE' => '创建销售',
  'LNK_SALE_LIST' => '销售',
  'ERR_DELETE_RECORD' => '必须指定记录编号才能删除此销售。',
  'LBL_TOP_SALES' => '我最佳的公开销售',
  'NTC_REMOVE_OPP_CONFIRMATION' => '您确定要从本销售中移除此联系人吗？',
	'SALE_REMOVE_PROJECT_CONFIRM' => '您确定要从本项目中移除此项销售吗？',
	'LBL_ACTIVITIES_SUBPANEL_TITLE'=>'活动',
	'LBL_HISTORY_SUBPANEL_TITLE'=>'历史记录',
    'LBL_RAW_AMOUNT'=>'原始金额',


    'LBL_CONTACTS_SUBPANEL_TITLE' => '联系人',
	'LBL_ASSIGNED_TO_NAME' => '用户：',
	'LBL_LIST_ASSIGNED_TO_NAME' => '获指派的用户',
  'LBL_MY_CLOSED_SALES' => '我已结束的销售',
  'LBL_TOTAL_SALES' => '销售总额',
  'LBL_CLOSED_WON_SALES' => '谈成结束的销售',
  'LBL_ASSIGNED_TO_ID' =>'负责人 ID',
  'LBL_CREATED_ID'=>'创建人 ID',
  'LBL_MODIFIED_ID'=>'修改人 ID',
  'LBL_MODIFIED_NAME'=>'修改人用户名',
  'LBL_SALE_INFORMATION'=>'销售信息',
  'LBL_CURRENCY_ID'=>'货币 ID',
  'LBL_CURRENCY_NAME'=>'货币名称',
  'LBL_CURRENCY_SYMBOL'=>'货币符号',
  'LBL_EDIT_BUTTON' => '编辑',
  'LBL_REMOVE' => '移除',
  'LBL_CURRENCY_RATE' => '汇率',

);

