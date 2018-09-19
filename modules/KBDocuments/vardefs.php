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

$dictionary['KBDocument'] = array(
    'reassignable' => false,
    'table' => 'kbdocuments',
    'favorites' => true,
    'unified_search' => true,
    'full_text_search' => false,
    'comment' => 'Knowledge Base management',
    'fields' => array(
    ),
    'uses' => array(
        'basic',
        'team_security',
        'assignable',
    ),
    'ignore_templates' => array(
        'taggable',
    ),
);
VardefManager::createVardef(
    'KBDocuments',
    'KBDocument'
);

//boost value for full text search
$dictionary['KBDocument']['fields']['name']['full_text_search']['boost'] = 1.49;
