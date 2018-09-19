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

 // $Id: listviewdefs.php 54005 2010-01-25 20:32:59Z jmertic $

$listViewDefs['EmailMan'] = array(
    'CAMPAIGN_NAME' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_CAMPAIGN', 
        'link' => true,
		'customCode' => '<a href="index.php?module=Campaigns&action=DetailView&record={$CAMPAIGN_ID}">{$CAMPAIGN_NAME}</a>',
        'default' => true),
    'RECIPIENT_NAME' => array(
		'sortable' => false,
        'width' => '10', 
        'label' => 'LBL_LIST_RECIPIENT_NAME',
		'customCode' => '<a href="index.php?module={$RELATED_TYPE}&action=DetailView&record={$RELATED_ID}">{$RECIPIENT_NAME}</a>', 
        'default' => true),
    'RECIPIENT_EMAIL' => array(
		'sortable' => false,
        'width' => '10', 
        'label' => 'LBL_LIST_RECIPIENT_EMAIL',
		'customCode' => '{$EMAIL_LINK}{$RECIPIENT_EMAIL}</a>',
        'default' => true),
    'MESSAGE_NAME' => array(
		'sortable' => false,
        'width' => '10', 
        'label' => 'LBL_LIST_MESSAGE_NAME',
		'customCode' => '<a href="index.php?module=EmailMarketing&action=DetailView&record={$MARKETING_ID}">{$MESSAGE_NAME}</a>',
        'default' => true),
    'SEND_DATE_TIME' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_SEND_DATE_TIME', 
        'default' => true),
    'SEND_ATTEMPTS' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_SEND_ATTEMPTS', 
        'default' => true),
    'IN_QUEUE' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_IN_QUEUE', 
        'default' => true),
);
