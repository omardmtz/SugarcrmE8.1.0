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

 // $Id: MyReportsDashlet.meta.php 16280 2006-08-22 19:47:48 +0000 (Tue, 22 Aug 2006) awu $

global $app_strings;

$dashletMeta['MyReportsDashlet'] = array('module'		=> 'Reports',
										 'title'     => translate('LBL_FAVORITE_REPORTS_TITLE', 'Reports'), 
                                          'icon'        => 'icon_FavoriteReports_32.gif', 
                                          'description' => 'My Favorite Reports',
                                          'category'    => 'Module Views');
?>