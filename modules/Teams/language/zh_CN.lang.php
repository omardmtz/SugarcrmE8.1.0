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
    'ERR_ADD_RECORD' => '必须指定记录编号才能增加用户到这个团队。',
    'ERR_DUP_NAME' => '团队名称已存在，请选择另一个。',
    'ERR_DELETE_RECORD' => '必须指定记录编号才能删除此团队。',
    'ERR_INVALID_TEAM_REASSIGNMENT' => '错误。被选团队 <b> ({0}) </b>是您选择要删除的团队。请选择另一个团队。',
    'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => '错误。您不能删除个人团队未被删除的用户。',
    'LBL_DESCRIPTION' => '说明:',
    'LBL_GLOBAL_TEAM_DESC' => '全局可见',
    'LBL_INVITEE' => '团队成员',
    'LBL_LIST_DEPARTMENT' => '部门',
    'LBL_LIST_DESCRIPTION' => '说明',
    'LBL_LIST_FORM_TITLE' => '团队列表',
    'LBL_LIST_NAME' => '名称',
    'LBL_FIRST_NAME' => '名：',
    'LBL_LAST_NAME' => '姓：',
    'LBL_LIST_REPORTS_TO' => '汇报对象',
    'LBL_LIST_TITLE' => '职位',
    'LBL_MODULE_NAME' => '团队',
    'LBL_MODULE_NAME_SINGULAR' => '团队',
    'LBL_MODULE_TITLE' => '团队：首页',
    'LBL_NAME' => '团队名称：',
    'LBL_NAME_2' => '团队名称 (2)：',
    'LBL_PRIMARY_TEAM_NAME' => '主要团队名称',
    'LBL_NEW_FORM_TITLE' => '新团队',
    'LBL_PRIVATE' => '私有',
    'LBL_PRIVATE_TEAM_FOR' => '私有团队： ',
    'LBL_SEARCH_FORM_TITLE' => '团队搜索',
    'LBL_TEAM_MEMBERS' => '团队成员',
    'LBL_TEAM' => '团队：',
    'LBL_USERS_SUBPANEL_TITLE' => '用户',
    'LBL_USERS' => '用户',
    'LBL_REASSIGN_TEAM_TITLE' => '纪录已被分配给下列团队：<b> {0}</b><br>删除纪录前，您必须把这些纪录分配给新的团队。选择一个团队作为替换。',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => '重新分配',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => '重新分配',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => '更新受影响的记录使之使用新的团队？',
    'LBL_REASSIGN_TABLE_INFO' => '更新表 {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => '操作已成功。',
    'LNK_LIST_TEAM' => '团队',
    'LNK_LIST_TEAMNOTICE' => '团队通知',
    'LNK_NEW_TEAM' => '创建团队',
    'LNK_NEW_TEAM_NOTICE' => '创建团队通知',
    'NTC_DELETE_CONFIRMATION' => '您确定要删除这条记录吗？',
    'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => '您是否确定要删除这个用户的成员关系？',
    'LBL_EDITLAYOUT' => '编辑布局' /*for 508 compliance fix*/,

    // Team-Based Permissions
    'LBL_TBA_CONFIGURATION' => '团队权限',
    'LBL_TBA_CONFIGURATION_DESC' => '启用团队访问，通过模块管理访问。',
    'LBL_TBA_CONFIGURATION_LABEL' => '启用团队权限',
    'LBL_TBA_CONFIGURATION_MOD_LABEL' => '选择要启用的模块',
    'LBL_TBA_CONFIGURATION_TITLE' => '通过角色管理启用团队权限将确保您可将特定访问权分配给单个模块的团队和用户。',
    'LBL_TBA_CONFIGURATION_WARNING' => <<<STR
利用这一特点禁用一个模块的团队权限将会恢复关于该模块团队权限的所有数据，包括流程定义或流程。这包括所有在该模块使用”负责人与选择的团队“选项的角色，以及该模块中所有用于记录的团队权限数据。
我们还建议您在禁用任何模块的团队权限之后，使用快速修复和重建工具来清理您的系统缓存。
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC' => <<<STR
<strong>警告：</strong>利用这一特点禁用一个模块的团队权限将会恢复关于该模块团队权限的所有数据，包括流程定义或流程。这包括所有在该模块使用”负责人与选择的团队“选项的角色，以及该模块中所有用于记录的团队权限数据。
我们还建议您在禁用任何模块的团队权限之后，使用快速修复和重建工具来清理您的系统缓存。
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN' => <<<STR
利用这一特点禁用一个模块的团队权限将会恢复关于该模块团队权限的所有数据，包括流程定义或流程。这包括所有在该模块使用”负责人与选择的团队“选项的角色，以及该模块中所有用于记录的团队权限数据。
我们还建议您在禁用任何模块的团队权限之后，使用快速修复和重建工具来清理您的系统缓存。如果您没有使用快速修复或重建工具，那么，请访问修复菜单，
STR
,
    'LBL_TBA_CONFIGURATION_WARNING_DESC_NO_ADMIN' => <<<STR
<strong>警告：</strong>利用这一特点禁用一个模块的团队权限将会恢复关于该模块团队权限的所有数据，包括流程定义或流程。这包括所有在该模块使用”负责人与选择的团队“选项的角色，以及该模块中所有用于记录的团队权限数据。
我们还建议您在禁用任何模块的团队权限之后，使用快速修复和重建工具来清理您的系统缓存。如果您没有使用快速修复或重建工具，那么，请访问修复菜单，
STR
,
);
