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

$listViewDefs['ReportMaker'] = array(
    'NAME' => array(
        'width' => '20', 
        'label' => 'LBL_NAME', 
        'link' => true,
        'default' => true),
    'IS_SCHEDULED' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_SCHEDULED', 
        'default' => true,
        'customCode' => '<a  href="#" onclick=\'window.open("index.php?module=Reports&action=add_schedule&to_pdf=true&id={$ID}&schedule_type=ent","test","width=500,height=200,resizable=1,scrollbars=1");\' class="listViewTdToolsS1">{$IS_SCHEDULED_IMG} {$IS_SCHEDULED}</a>'),
);
