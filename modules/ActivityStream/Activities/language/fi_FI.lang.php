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
    'TPL_ACTIVITY_CREATE' => 'Luotiin {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}} {{str "LBL_MODULE_NAME_SINGULAR" object.module}}.',
    'TPL_ACTIVITY_POST' => '{{{value}}}{{{str "TPL_ACTIVITY_ON" "Activities" this}}}',
    'TPL_ACTIVITY_UPDATE' => 'Päivitettiin {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}{{#if updateStr}}&rarr;{{{updateStr}}}{{/if}}',
    'TPL_ACTIVITY_UPDATE_FIELD' => '<a rel=\'tooltip\' title=\'Vaihdettiin: {{before}} &rarr; {{after}}\'>{{field_label}}</a>',
    'TPL_ACTIVITY_LINK' => 'Linkitettiin {{{str "TPL_ACTIVITY_RECORD" "Activities" subject}}} &rarr; {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}.',
    'TPL_ACTIVITY_UNLINK' => 'Epälinkitettiin {{{str "TPL_ACTIVITY_RECORD" "Activities" subject}}} &rarr; {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}.',
    'TPL_ACTIVITY_ATTACH' => 'Lisättiin tiedosto <a class=\'dragoff\' target=\'sugar_attach\' href=\'{{url}}\' data-note-id=\'{{noteId}}\'>{{filename}}</a>{{{str "TPL_ACTIVITY_ON" "Activities" this}}}',
    'TPL_ACTIVITY_DELETE' => 'Poistettiin {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}} {{str "LBL_MODULE_NAME_SINGULAR" object.module}}.',
    'TPL_ACTIVITY_UNDELETE' => 'Palautettiin {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}} {{str "LBL_MODULE_NAME_SINGULAR" object.module}}.',
    'TPL_ACTIVITY_RECORD' => '<a href=\'#{{buildRoute module=module id=id}}\'>{{name}}</a>',
    // We need the trailing space at the end of the next line so that the str
    // handlebars helper isn't confused by a template that returns no text.
    'TPL_ACTIVITY_ON' => '{{#if object}} on {{{str "TPL_ACTIVITY_RECORD" "Activities" object}}}.{{/if}}{{#if module}} on {{str "LBL_MODULE_NAME_SINGULAR" module}}.{{else}} {{/if}}',
    'TPL_COMMENT' => '{{{value}}}',
    'TPL_MORE_COMMENT' => '{{this}} enemmän kommenttia...',
    'TPL_MORE_COMMENTS' => '{{this}} enemmän kommentteja...',
    'TPL_SHOW_MORE_MODULE' => 'Enemmän viestejä...',
);
