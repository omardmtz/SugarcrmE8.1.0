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

$dictionary['KBContentTemplate'] = array(
    'table' => 'kbcontent_templates',
    'audited' => true,
    'activity_enabled' => true,
    'comment' => 'A template is used as a body for KBContent.',
    'fields' => array(
        'body' => array(
            'name' => 'body',
            'vname' => 'LBL_TEXT_BODY',
            'type' => 'longtext',
            'comment' => 'Template body',
            'audited' => true,
        ),
    ),
    'relationships' => array(),
    'duplicate_check' => array(
        'enabled' => false,
    ),
    'uses' => array(
        'basic',
        'team_security',
    ),
    'ignore_templates' => array(
        'taggable',
    ),
    'acls' => array(
        'SugarACLKB' => true,
        'SugarACLDeveloperOrAdmin' => array(
            'aclModule' => 'KBContents',
            'allowUserRead' => true
        ),
    ),
);

VardefManager::createVardef(
    'KBContentTemplates',
    'KBContentTemplate'
);
$dictionary['KBContentTemplate']['fields']['name']['audited'] = true;
