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

// TODO: remove when SIDECAR-236 is ready.
$listViewDefs['KBContents'] = array(
    'NAME' => array(
        'label' => 'LBL_NAME',
        'default' => true,
        'link' => true,
        'width' => '20',
    ),
    'LANGUAGE' => array(
        'label' => 'LBL_LANG',
        'default' => true,
        'link' => true,
        'type' => 'enum-config',
        'module' => 'KBDocuments',
        'key' => 'languages',
        'width' => '5',
    ),
    'STATUS' => array(
        'label' => 'LBL_STATUS',
        'default' => true,
        'type' => 'status',
        'width' => '10',
    ),
    'ACTIVE_DATE' => array(
        'label' => 'LBL_PUBLISH_DATE',
        'type' => 'date',
        'default' => true,
        'width' => '10',
    ),
    'EXP_DATE' => array(
        'label' => 'LBL_EXP_DATE',
        'type' => 'date',
        'default' => true,
        'width' => '10',
    ),
    'DATE_ENTERED' => array(
        'width' => '5',
        'label' => 'LBL_DATE_ENTERED',
        'default' => true,
    ),
);
