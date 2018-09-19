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

$dictionary['quotes_opportunities'] = array(
    'table' => 'quotes_opportunities',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
        ),
        'opportunity_id' => array(
            'name' => 'opportunity_id',
            'type' => 'id',
        ),
        'quote_id' => array(
            'name' => 'quote_id',
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
            'required' => false,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'quotes_opportunitiespk',
            'type' => 'primary',
            'fields' => array(
                'id',
            ),
        ),
        array(
            'name' => 'idx_opp_qte_opp',
            'type' => 'index',
            'fields' => array(
                'opportunity_id',
            ),
        ),
        array(
            'name' => 'idx_quote_oportunities',
            'type' => 'alternate_key',
            'fields' => array(
                'quote_id',
            ),
        ),
    ),
    'relationships' => array(
        'quotes_opportunities' => array(
            'lhs_module' => 'Opportunities',
            'lhs_table' => 'opportunities',
            'lhs_key' => 'id',
            'rhs_module' => 'Quotes',
            'rhs_table' => 'quotes',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'true_relationship_type' => 'one-to-many',
            'join_table' => 'quotes_opportunities',
            'join_key_lhs' => 'opportunity_id',
            'join_key_rhs' => 'quote_id',
        ),
    ),
);
