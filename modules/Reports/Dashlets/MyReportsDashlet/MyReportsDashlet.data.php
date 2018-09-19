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

 // $Id: MyReportsDashlet.data.php 17070 2006-10-13 22:09:18 +0000 (Fri, 13 Oct 2006) awu $

global $current_user;

$dashletData['MyReportsDashlet']['searchFields'] = array();
                                                                                           
$dashletData['MyReportsDashlet']['columns'] = array('name' => array('width'   => '40', 
                                                                          'label'   => 'LBL_REPORT_NAME',
                                                                          'customCode'    => '<span id="obj_{$ID}"><a  href="index.php?action=ReportCriteriaResults&module=Reports&page=report&id={$ID}">{$NAME}</a></span>',
                                                                          'default' => true 
                                                                          ), 
                                                          'module' => array('width'  => '30', 
                                                                            'label'   => 'LBL_MODULE',
                                                                             'default' => true),
                                                          'report_type_trans' => array(
                                                                'width' => '30',
                                                                'label' => 'LBL_REPORT_TYPE',
                                                                'default' => true,
                                                                'orderBy' => 'report_type',
                                                                'related_fields' => array('report_type'),
                                                                ),
                                                           );
?>
