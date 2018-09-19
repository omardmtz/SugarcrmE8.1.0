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
    'TPL_ACTIVITY_CREATE' => 'Creat {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}{{#if object.module}} {{getModuleName object.module}}{{/if}}.',
    'TPL_ACTIVITY_POST' => '{{{value}}} {{{str "TPL_ACTIVITY_ON", "Activitats" això}}}',
    'TPL_ACTIVITY_UPDATE' => 'Actualitzat a{{{updateStr}}} {{#if updateStr}} a {{/if}} {{#if object.module}} {{{str "TPL_ACTIVITY_RECORD", "Activitats" objecte}}} {{/if}}{{else}}{{object.name}}.',
    'TPL_ACTIVITY_UPDATE_FIELD' => '<a rel="tooltip" title="Canvia: {{before}} A: {{after}}">{{field_label}}</a>',
    'TPL_ACTIVITY_LINK' => 'Enllaçat {{{str "TPL_ACTIVITY_RECORD", "Activitats" tema}}} a {{{str "TPL_ACTIVITY_RECORD", "Activitats" objecte}}}.',
    'TPL_ACTIVITY_UNLINK' => 'Desvinculat {{{str "TPL_ACTIVITY_RECORD" "Activitats" tema}}} a {{{str "TPL_ACTIVITY_RECORD" "Activitats" objecte}}}.',
    'TPL_ACTIVITY_ATTACH' => 'Afegit arxiu <a class="dragoff" target="sugar_attach" href="{{url}}" data-note-id="{{noteId}}">{{filename}}</a> {{{str "TPL_ACTIVITY_ON", "Activitats" això}}}',
    'TPL_ACTIVITY_DELETE' => 'Suprimit {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}} {{getModuleName object.module}}.',
    'TPL_ACTIVITY_UNDELETE' => 'Restaurat {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}} {{getModuleName object.module}}.',
    'TPL_ACTIVITY_RECORD' => '{{#if module}} <a href="#{{buildRoute module=module id=id}}">{{name}}</a>{{else}}{{name}} {{/if}}',
    // We need the trailing space at the end of the next line so that the str
    // handlebars helper isn't confused by a template that returns no text.
    'TPL_ACTIVITY_ON' => '{{#if object}} a {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}.{{/if}}{{#if module}} a {{getModuleName module}}.{{else}} {{/if}}',
    'TPL_COMMENT' => '{{{value}}}',
    'TPL_MORE_COMMENT' => '{{this}} comentari més &hellip;',
    'TPL_MORE_COMMENTS' => '{{this}} més comentaris &hellip;',
    'TPL_SHOW_MORE_MODULE' => 'Més llocs...',
);
