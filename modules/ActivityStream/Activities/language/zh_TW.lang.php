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
    'TPL_ACTIVITY_CREATE' => '已建立 {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}{{#if object.module}} {{getModuleName object.module}}{{/if}}。',
    'TPL_ACTIVITY_POST' => '{{{value}}}{{{str "TPL_ACTIVITY_ON" "Activities" this}}}',
    'TPL_ACTIVITY_UPDATE' => '更新 {{#if updateStr}}{{{updateStr}}} 於 {{/if}}{{#if object.module}}{{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}{{else}}{{object.name}}{{/if}}。',
    'TPL_ACTIVITY_UPDATE_FIELD' => '<a rel="tooltip" title="Changed: {{before}} To: {{after}}">{{field_label}}</a>',
    'TPL_ACTIVITY_LINK' => '連結 {{{str "TPL_ACTIVITY_RECORD" "Activities" subject}}} 至 {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}.',
    'TPL_ACTIVITY_UNLINK' => '取消 {{{str "TPL_ACTIVITY_RECORD" "Activities" subject}}} 和 {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}} 的連結。',
    'TPL_ACTIVITY_ATTACH' => '新增檔案 <a class="dragoff" target="sugar_attach" href="{{url}}" data-note-id="{{noteId}}">{{filename}}</a>{{{str "TPL_ACTIVITY_ON" "Activities" this}}}',
    'TPL_ACTIVITY_DELETE' => '已刪除 {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}} {{getModuleName object.module}}。',
    'TPL_ACTIVITY_UNDELETE' => '已還原 {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}} {{getModuleName object.module}}。',
    'TPL_ACTIVITY_RECORD' => '{{#if module}}<a href="#{{buildRoute module=module id=id}}">{{name}}</a>{{else}}{{name}}{{/if}}',
    // We need the trailing space at the end of the next line so that the str
    // handlebars helper isn't confused by a template that returns no text.
    'TPL_ACTIVITY_ON' => '{{#if object}} 於 {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}。{{/if}}{{#if module}} 於 {{getModuleName module}}。{{else}} {{/if}}',
    'TPL_COMMENT' => '{{{value}}}',
    'TPL_MORE_COMMENT' => '{{this}} 更多註解&hellip;',
    'TPL_MORE_COMMENTS' => '{{this}} 更多註解&hellip;',
    'TPL_SHOW_MORE_MODULE' => '更多貼文...',
);
