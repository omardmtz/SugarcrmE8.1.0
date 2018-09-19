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
$listViewDefs['ProjectTemplates'] = array(
    'NAME' => array(
        'width' => '40',
        'label' => 'LBL_LIST_NAME',
        'link' => true,
        'default' => true,
        'customCode'=>'<a href="index.php?offset={$OFFSET}&record={$ID}&action=ProjectTemplatesDetailView&module=Project" >{$NAME}</a>'),
    'ESTIMATED_START_DATE' => array(
        'width' => '20',
        'label' => 'LBL_DATE_START',
        'link' => false,
        'default' => true),
    'ESTIMATED_END_DATE' => array(
        'width' => '20',
        'label' => 'LBL_DATE_END',
        'link' => false,
        'default' => true),
    'TEAM_NAME' => array(
        'width' => '2',
        'label' => 'LBL_LIST_TEAM',
        'related_fields' => array('team_id'),
        'default' => false),
);