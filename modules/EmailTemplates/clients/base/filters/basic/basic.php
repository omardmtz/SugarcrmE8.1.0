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
$viewdefs['EmailTemplates']['base']['filter']['basic'] = array(
    'create' => false,
    'quicksearch_field' => array('name'),
    'quicksearch_priority' => 1,
    'filters' => array(
        array(
            'id'                => 'all_email_type',
            'name'              => 'LBL_FILTER_EMAIL_TYPE_TEMPLATES',
            'filter_definition' => array(
                '$or' => array(
                    array(
                        'type' => array('$is_null' => ''),
                    ),
                    array(
                        'type' => array('$equals' => ''),
                    ),
                    array(
                        'type' => array('$equals' => 'email'),
                    ),
                ),
            ),
            'editable'          => false,
        ),
    ),
);
