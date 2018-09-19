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
    'TPL_ACTIVITY_CREATE' => 'تم إنشاء{{{str "TPL_ACTIVITY_RECORD" "Activities" object}}} {{getModuleName object.module}}.',
    'TPL_ACTIVITY_POST' => '{{{value}}}{{{str "TPL_ACTIVITY_ON" "Activities" this}}}',
    'TPL_ACTIVITY_UPDATE' => 'تم تحديث{{#if updateStr}}{{{updateStr}}} في {{/if}}{{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}.',
    'TPL_ACTIVITY_UPDATE_FIELD' => '<a rel="tooltip" title="Changed: {{before}} To: {{after}}">{{field_label}}</a>',
    'TPL_ACTIVITY_LINK' => 'تم ربط{{{str "TPL_ACTIVITY_RECORD" "Activities" subject}}} بـ {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}.',
    'TPL_ACTIVITY_UNLINK' => 'تم إلغاء ارتباط{{{str "TPL_ACTIVITY_RECORD" "Activities" subject}}} بـ {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}.',
    'TPL_ACTIVITY_ATTACH' => 'ملف مضاف <a class="dragoff" target="sugar_attach" href="{{url}}" data-note-id="{{noteId}}">{{filename}}</a>{{{str "TPL_ACTIVITY_ON" "Activities" this}}}',
    'TPL_ACTIVITY_DELETE' => 'تم حذف{{{str "TPL_ACTIVITY_RECORD" "Activities" object}}} {{getModuleName object.module}}.',
    'TPL_ACTIVITY_UNDELETE' => 'تمت استعادة{{{str "TPL_ACTIVITY_RECORD" "Activities" object}}} {{getModuleName object.module}}.',
    'TPL_ACTIVITY_RECORD' => '<a href="#{{buildRoute module=module id=id}}">{{name}}</a>',
    // We need the trailing space at the end of the next line so that the str
    // handlebars helper isn't confused by a template that returns no text.
    'TPL_ACTIVITY_ON' => '{{#if object}} على {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}.{{/if}}{{#if module}} على {{getModuleName module}}.{{else}} {{/if}}',
    'TPL_COMMENT' => '{{{value}}}',
    'TPL_MORE_COMMENT' => '{{this}} مزيد من التعليقات&hellip;',
    'TPL_MORE_COMMENTS' => '{{this}} مزيد من التعليقات&hellip;',
    'TPL_SHOW_MORE_MODULE' => 'المزيد من المنشورات...',
);
