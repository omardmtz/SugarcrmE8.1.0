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

$listViewDefs['TimePeriods'] = array(
    'NAME' => array(
        'width' => '30', 
        'label' => 'LBL_TP_NAME', 
        'link' => true,
        'default' => true),
    'START_DATE' => array(
        'width' => '30', 
        'label' => 'LBL_TP_START_DATE', 
        'default' => true),
    'END_DATE' => array(
        'width' => '20', 
        'label' => 'LBL_TP_END_DATE', 
        'default' => true),
    'IS_FISCAL_YEAR' => array(
        'width' => '20', 
        'label' => 'LBL_TP_IS_FISCAL_YEAR', 
        'default' => true),
);
