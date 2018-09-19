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
    'TPL_ACTIVITY_CREATE' => '创建 {{{str "TPL_ACTIVITY_RECORD" "活动" 对象}}}{{#if object.module}} {{getModuleName object.module}}{{/假如}}。',
    'TPL_ACTIVITY_POST' => '{{{value}}}{{{str "TPL_ACTIVITY_ON" "活动"此}}}',
    'TPL_ACTIVITY_UPDATE' => '更新 {{#if updateStr}}{{{updateStr}}}到 {{/假如}}{{#if object.module}}{{{str "TPL_ACTIVITY_RECORD" "活动"对象}}}{{else}}{{object.name}}{{/假如}}。',
    'TPL_ACTIVITY_UPDATE_FIELD' => '<a rel="tooltip" title="Changed: {{before}} To: {{after}}">{{field_label}}</a>',
    'TPL_ACTIVITY_LINK' => '链接 {{{str "TPL_ACTIVITY_RECORD" "活动" 主题}}} 到 {{{str "TPL_ACTIVITY_RECORD" "活动" 对象}}}。',
    'TPL_ACTIVITY_UNLINK' => '取消 {{{str "TPL_ACTIVITY_RECORD" "活动" 主题}}} 与 {{{str "TPL_ACTIVITY_RECORD" "活动" 对象}}} 的链接。',
    'TPL_ACTIVITY_ATTACH' => '添加文件 <a class="dragoff" target="sugar_attach" href="{{url}}" data-note-id="{{noteId}}">{{filename}}</a>{{{str "TPL_ACTIVITY_ON" "活动" 此}}}',
    'TPL_ACTIVITY_DELETE' => '已删除 {{{str "TPL_ACTIVITY_RECORD" "活动" 对象}}} {{getModuleName object.module}}。',
    'TPL_ACTIVITY_UNDELETE' => '已恢复 {{{str "TPL_ACTIVITY_RECORD" "活动" 对象}}} {{getModuleName object.module}}。',
    'TPL_ACTIVITY_RECORD' => '{{#if module}}<a href="#{{buildRoute module=module id=id}}">{{name}}</a>{{else}}{{name}}{{/假如}}',
    // We need the trailing space at the end of the next line so that the str
    // handlebars helper isn't confused by a template that returns no text.
    'TPL_ACTIVITY_ON' => '{{#if object}} 于 {{{str "TPL_ACTIVITY_RECORD" "活动" 对象}}}.{{/假如}}{{#if module}} 于 {{getModuleName module}}.{{else}} {{/假如}}',
    'TPL_COMMENT' => '{{{value}}}',
    'TPL_MORE_COMMENT' => '{{this}} 更多评论&hellip;',
    'TPL_MORE_COMMENTS' => '{{this}} 更多评论&hellip;',
    'TPL_SHOW_MORE_MODULE' => '更多内容...',
);
