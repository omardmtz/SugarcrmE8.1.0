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

$dictionary['KBArticle'] = array(
    'table' => 'kbarticles',
    'reassignable' => false,
    'favorites' => true,
    'unified_search' => true,
    'full_text_search' => false,
    'comment' => 'Knowledge Base Article',
    'fields' => array(
        'kbdocuments_kbarticles' => array(
            'name' => 'kbdocuments_kbarticles',
            'type' => 'link',
            'vname' => 'LBL_KBDOCUMENTS',
            'relationship' => 'kbdocuments_kbarticles',
            'source' => 'non-db',
        ),
        'kbdocument_id' => array(
            'name' => 'kbdocument_id',
            'id_name' => 'kbdocument_id',
            'vname' => 'LBL_KBDOCUMENT_ID',
            'rname' => 'id',
            'type' => 'id',
            'table' => 'kbdocuments',
            'isnull' => 'true',
            'module' => 'KBDocuments',
            'reportable' => false,
            'massupdate' => false,
            'duplicate_merge' => 'disabled',
        ),
        'kbdocument_name' => array(
            'name' => 'kbdocument_name',
            'rname' => 'name',
            'vname' => 'LBL_KBDOCUMENT',
            'type' => 'relate',
            'reportable' => false,
            'source' => 'non-db',
            'table' => 'kbdocuments',
            'id_name' => 'kbdocument_id',
            'link' => 'kbdocuments_kbarticles',
            'module' => 'KBDocuments',
            'duplicate_merge' => 'disabled',
        ),
        'kbarticles_kbcontents' => array(
            'name' => 'kbarticles_kbcontents',
            'type' => 'link',
            'vname' => 'LBL_KBARTICLES',
            'relationship' => 'kbarticles_kbcontents',
            'source' => 'non-db',
            'side' => 'right',
        ),
    ),
    'relationships' => array(
        'kbdocuments_kbarticles' => array (
            'lhs_module' => 'KBDocuments',
            'lhs_table' => 'kbdocuments',
            'lhs_key' => 'id',
            'rhs_module' => 'KBArticles',
            'rhs_table' => 'kbarticles',
            'rhs_key' => 'kbdocument_id',
            'relationship_type' => 'one-to-many',
        ),
        'kbarticles_kbcontents' => array (
            'lhs_module' => 'KBArticles',
            'lhs_table' => 'kbarticles',
            'lhs_key' => 'id',
            'rhs_module' => 'KBContents',
            'rhs_table' => 'kbcontents',
            'rhs_key' => 'kbarticle_id',
            'relationship_type' => 'one-to-many',
        ),
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
    'KBArticles',
    'KBArticle'
);
