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

// $Id: listviewdefs.php 56123 2010-04-26 21:48:19Z asandberg $
global $theme, $mod_strings;

$listViewDefs['Campaigns'] = array(
	'NAME' => array(
		'width' => '20', 
		'label' => 'LBL_LIST_CAMPAIGN_NAME',
        'link' => true,
        'default' => true), 
	'STATUS' => array(
		'width' => '10', 
		'label' => 'LBL_LIST_STATUS',
        'default' => true),
    'CAMPAIGN_TYPE' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_TYPE',
        'default' => true),
    'END_DATE' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_END_DATE',
        'default' => true),        
        
	'TEAM_NAME' => array(
		'width' => '15', 
		'label' => 'LBL_LIST_TEAM',
        'default' => false),
	'ASSIGNED_USER_NAME' => array(
		'width' => '8', 
		'label' => 'LBL_LIST_ASSIGNED_USER',
		'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true),
    'TRACK_CAMPAIGN' => array(
        'width' => '1', 
        'label' => '&nbsp;',
        'link' => true,
        'customCode' => ' <a title="{$TRACK_CAMPAIGN_TITLE}" href="index.php?action=TrackDetailView&module=Campaigns&record={$ID}"><!--not_in_theme!--><img border="0" src="{$TRACK_CAMPAIGN_IMAGE}" alt="{$TRACK_VIEW_ALT_TEXT}"></a> ',
        'default' => true,
        'studio' => false,
        'nowrap' => true,
        'sortable' => false),      
    'LAUNCH_WIZARD' => array(
        'width' => '1', 
        'label' => '&nbsp;',
        'link' => true,
        'customCode' => ' <a title="{$LAUNCH_WIZARD_TITLE}" href="index.php?action=WizardHome&module=Campaigns&record={$ID}"><!--not_in_theme!--><img border="0" src="{$LAUNCH_WIZARD_IMAGE}"  alt="{$LAUNCH_WIZ_ALT_TEXT}"></a>  ',
        'default' => true,
        'studio' => false,
        'nowrap' => true,
        'sortable' => false),
	'DATE_ENTERED' => array (
	    'width' => '10',
	    'label' => 'LBL_DATE_ENTERED',
	    'default' => true),              
);
?>
