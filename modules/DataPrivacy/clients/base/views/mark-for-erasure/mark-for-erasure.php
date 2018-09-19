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
$viewdefs['DataPrivacy']['base']['view']['mark-for-erasure'] = array(
    'template' => 'mark-for-erasure',
    'panels' => array(
        array(
            'fields' => array(
                array(
                    'type' => 'piiname',
                    'name' => 'field_name',
                    'label' => 'LBL_DATAPRIVACY_FIELDNAME',
                    'sortable' => true,
                    'filter' => 'contains',
                ),
                array(
                    'type' => 'base',
                    'name' => 'value',
                    'label' => 'LBL_DATAPRIVACY_VALUE',
                    'sortable' => true,
                    'filter' => 'contains',
                ),
                array(
                    'type' => 'source',
                    'name' => 'source',
                    'label' => 'LBL_DATAPRIVACY_SOURCE',
                    'sortable' => false,
                ),
                array(
                    'type' => 'datetimecombo',
                    'name' => 'date_modified',
                    'label' => 'LBL_DATAPRIVACY_CHANGE_DATE',
                    'sortable' => false,
                ),
            ),
        ),
    ),
);
