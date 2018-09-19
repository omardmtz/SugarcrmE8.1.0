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
    'TPL_ACTIVITY_CREATE' => 'Aangemaakt {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}} {{str "LBL_MODULE_NAME_SINGULAR" object.module}}.',
    'TPL_ACTIVITY_POST' => '{{{value}}}{{{str "TPL_ACTIVITY_ON" "Activities" this}}}',
    'TPL_ACTIVITY_UPDATE' => 'Bijgewerkt {{#if updateStr}}{{{updateStr}}} voor {{/if}}{{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}.',
    'TPL_ACTIVITY_UPDATE_FIELD' => '<a rel="tooltip" title="Gewijzigd: van {{before}} naar: {{after}}">{{field_label}}</a>',
    'TPL_ACTIVITY_LINK' => 'Gekoppeld {{{str "TPL_ACTIVITY_RECORD" "Activities" subject}}} aan {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}.',
    'TPL_ACTIVITY_UNLINK' => 'Ontkoppeld {{{str "TPL_ACTIVITY_RECORD" "Activities" subject}}} aan {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}.',
    'TPL_ACTIVITY_ATTACH' => 'Bestand toegevoegd <a class="dragoff" target="sugar_attach" href="{{url}}" data-note-id="{{noteId}}">{{filename}}</a>{{{str "TPL_ACTIVITY_ON" "Activities" this}}}',
    'TPL_ACTIVITY_DELETE' => 'Verwijderd {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}} {{str "LBL_MODULE_NAME_SINGULAR" object.module}}.',
    'TPL_ACTIVITY_UNDELETE' => 'Hersteld {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}} {{str "LBL_MODULE_NAME_SINGULAR" object.module}}.',
    'TPL_ACTIVITY_RECORD' => '<a href="#{{buildRoute module=module id=id}}">{{name}}</a>',
    // We need the trailing space at the end of the next line so that the str
    // handlebars helper isn't confused by a template that returns no text.
    'TPL_ACTIVITY_ON' => '{{#if object}} bij {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}.{{/if}}{{#if object_type}} bij {{str "LBL_MODULE_NAME_SINGULAR" object.module}}.{{else}} {{/if}}',
    'TPL_COMMENT' => '{{{value}}}',
    'TPL_MORE_COMMENT' => '{{this}} meer commentaar&hellip;',
    'TPL_MORE_COMMENTS' => '{{this}} meer commentaar&hellip;',
    'TPL_SHOW_MORE_MODULE' => 'Toon meer posts...',
);
