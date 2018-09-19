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

$dictionary['prospect_list_campaigns'] = array(
    'table' => 'prospect_list_campaigns',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
        ),
        'prospect_list_id' => array(
            'name' => 'prospect_list_id',
            'type' => 'id',
        ),
        'campaign_id' => array(
            'name' => 'campaign_id',
            'type' => 'id',
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'type' => 'datetime',
        ),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'len' => '1',
            'default' => '0',
        ),
    ),
    'indices' => array(
        array(
            'name' => 'prospect_list_campaignspk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_pro_id',
            'type' => 'index',
            'fields' => array(
                'prospect_list_id',
            ),
        ),
        array(
            'name' => 'idx_cam_id',
            'type' => 'index',
            'fields' => array(
                'campaign_id',
            ),
        ),
        array(
            'name' => 'idx_prospect_list_campaigns',
            'type' => 'alternate_key',
            'fields' => array(
                'prospect_list_id',
                'campaign_id',
            ),
        ),
    ),
    'relationships' => array(
        'prospect_list_campaigns' => array(
            'lhs_module' => 'ProspectLists',
            'lhs_table' => 'prospect_lists',
            'lhs_key' => 'id',
            'rhs_module' => 'Campaigns',
            'rhs_table' => 'campaigns',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'prospect_list_campaigns',
            'join_key_lhs' => 'prospect_list_id',
            'join_key_rhs' => 'campaign_id',
        ),
    ),
);
