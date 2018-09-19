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

$dictionary['{{$class.name}}'] = array(
    'table' => '{{$class.table_name}}',
    'audited' => {{if $class.audited}}true{{else}}false{{/if}},
    'activity_enabled' => {{if $class.activity_enabled}}true{{else}}false{{/if}},
{{if !($class.templates|strstr:"file")}}
    'duplicate_merge' => true,
{{/if}}
    'fields' => {{$class.fields_string}},
    'relationships' => {{$class.relationships}},
    'optimistic_locking' => true,
{{if !empty($class.table_name) && !empty($class.templates)}}
    'unified_search' => true,
    'full_text_search' => true,
{{/if}}
);

if (!class_exists('VardefManager')){
}
VardefManager::createVardef('{{$class.name}}','{{$class.name}}', array({{$class.templates}}));
