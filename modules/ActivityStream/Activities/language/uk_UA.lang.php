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
    'TPL_ACTIVITY_CREATE' => 'Створено {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}} {{getModuleName object.module}}.',
    'TPL_ACTIVITY_POST' => '{{{value}}}{{{str "TPL_ACTIVITY_ON" "Activities" this}}}',
    'TPL_ACTIVITY_UPDATE' => 'Оновлено {{#if updateStr}}{{{updateStr}}} on {{/if}}{{#if object.module}}{{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}{{else}}{{object.name}}{{/if}}.',
    'TPL_ACTIVITY_UPDATE_FIELD' => '<a rel="tooltip" title="Changed: {{before}} To: {{after}}">{{field_label}}</a>',
    'TPL_ACTIVITY_LINK' => 'З&#39;єднано {{{str "TPL_ACTIVITY_RECORD" "Activities" subject}}} to {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}.',
    'TPL_ACTIVITY_UNLINK' => 'Роз&#39;єднано {{{str "TPL_ACTIVITY_RECORD" "Activities" subject}}} to {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}.',
    'TPL_ACTIVITY_ATTACH' => 'Доданий файл <a class="dragoff" target="sugar_attach" href="{{url}}" data-note-id="{{noteId}}">{{filename}}</a>{{{str "TPL_ACTIVITY_ON" "Activities" this}}}',
    'TPL_ACTIVITY_DELETE' => 'Видалено {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}} {{getModuleName object.module}}.',
    'TPL_ACTIVITY_UNDELETE' => 'Відновлено {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}} {{getModuleName object.module}}.',
    'TPL_ACTIVITY_RECORD' => '<a href="#{{buildRoute module=module id=id}}">{{name}}</a>',
    // We need the trailing space at the end of the next line so that the str
    // handlebars helper isn't confused by a template that returns no text.
    'TPL_ACTIVITY_ON' => '{{#if object}} у {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}.{{/if}}{{#if module}} у {{getModuleName module}}.{{else}} {{/if}}',
    'TPL_COMMENT' => '{{{value}}}',
    'TPL_MORE_COMMENT' => '{{this}} весь коментар&hellip;',
    'TPL_MORE_COMMENTS' => '{{this}} всі коментарі&hellip;',
    'TPL_SHOW_MORE_MODULE' => 'Більше постів...',
);
