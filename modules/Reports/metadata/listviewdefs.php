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

// $Id: listviewdefs.php 16278 2006-08-22 19:09:18 +0000 (Tue, 22 Aug 2006) awu $

$listViewDefs['Reports'] = array(
    'NAME' => array(
        'width' => '40', 
        'label' => 'LBL_REPORT_NAME', 
        'customCode' => '<span id="obj_{$ID}"><a  href="index.php?action=ReportCriteriaResults&module=Reports&page=report&id={$ID}">{$NAME}</a></span>',
        'default' => true), 
    'MODULE' => array(
        'width' => '15',
        'label' => 'LBL_MODULE',
        'default' => true),
    'REPORT_TYPE_TRANS' => array(
        'width' => '15', 
        'label' => 'LBL_REPORT_TYPE',
        'default' => true,
        'orderBy' => 'report_type',
        'related_fields' => array('report_type'),
    ),
    'TEAM_NAME' => array(
        'width' => '2', 
        'label' => 'LBL_LIST_TEAM',
        'default' => false,
        'related_fields' => array('team_id'),
        'orderBy' => 'team_id'
        ),
    'ASSIGNED_USER_NAME' => array(
        'width' => '2', 
        'label' => 'LBL_LIST_ASSIGNED_USER',
        'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true,
        ),
        
     'IS_SCHEDULED' => array(
        'width' => '10',
        'label' => 'LBL_SCHEDULE_REPORT',
        'default' => true,
        'related_fields' => array('active', 'schedule_id'),
        'sortable' => false
      ),
    'LAST_RUN_DATE' => array(
        'width' => '15', 
        'label' => 'LBL_REPORT_LAST_RUN_DATE',
        'default' => true,
        'sortable' => true,
        'related_fields' => array('active', 'report_cache.date_modified'),
    ),
    'DATE_ENTERED' => array(
        'width' => '14',
        'orderBy' => 'date_entered',
        'sortOrder' => 'desc',
        'label' => 'LBL_DATE_ENTERED',
        'default' => true),
);
