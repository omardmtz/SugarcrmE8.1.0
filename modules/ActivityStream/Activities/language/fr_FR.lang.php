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
    'TPL_ACTIVITY_CREATE' => 'A créé {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}} {{str "LBL_MODULE_NAME_SINGULAR" object.module}}.',
    'TPL_ACTIVITY_POST' => '{{{value}}}{{{str "TPL_ACTIVITY_ON" "Activities" this}}}',
    'TPL_ACTIVITY_UPDATE' => 'Mis à jour {{#if updateStr}}{{{updateStr}}} sur {{/if}}{{#if object.module}}{{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}{{else}}{{object.name}}{{/if}}.',
    'TPL_ACTIVITY_UPDATE_FIELD' => '<a rel="tooltip" title="De : {{before}} à : {{after}}">{{field_label}}</a>',
    'TPL_ACTIVITY_LINK' => 'Associé {{{str "TPL_ACTIVITY_RECORD" "Activities" subject}}} à {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}.',
    'TPL_ACTIVITY_UNLINK' => 'A désassocié {{{str "TPL_ACTIVITY_RECORD" "Activities" subject}}} de {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}.',
    'TPL_ACTIVITY_ATTACH' => 'A ajouté le fichier <a class="dragoff" target="sugar_attach" href="{{url}}" data-note-id="{{noteId}}">{{filename}}</a>{{{str "TPL_ACTIVITY_ON" "Activities" this}}}',
    'TPL_ACTIVITY_DELETE' => 'A supprimé  {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}} {{str "LBL_MODULE_NAME_SINGULAR" object.module}}.',
    'TPL_ACTIVITY_UNDELETE' => 'A restauré {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}} {{str "LBL_MODULE_NAME_SINGULAR" object.module}}.',
    'TPL_ACTIVITY_RECORD' => '<a href="#{{buildRoute module=module id=id}}">{{name}}</a>',
    // We need the trailing space at the end of the next line so that the str
    // handlebars helper isn't confused by a template that returns no text.
    'TPL_ACTIVITY_ON' => '{{#if object}} sur {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}.{{/if}}{{#if module}} on {{str "LBL_MODULE_NAME_SINGULAR" module}}.{{else}} {{/if}}',
    'TPL_COMMENT' => '{{{value}}}',
    'TPL_MORE_COMMENT' => '{{this}} plus de commentaires&hellip;',
    'TPL_MORE_COMMENTS' => '{{this}} plus de commentaires&hellip;',
    'TPL_SHOW_MORE_MODULE' => 'Plus de posts...',
);
