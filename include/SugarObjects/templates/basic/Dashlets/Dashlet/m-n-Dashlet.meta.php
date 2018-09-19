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
/*********************************************************************************
 * $Id$
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
global $app_strings;

$dashletMeta['<module_name>Dashlet'] = array('module'		=> '<module_name>',
										  'title'       => translate('LBL_HOMEPAGE_TITLE', '<module_name>'), 
                                          'description' => 'A customizable view into <module_name>',
                                          'icon'        => 'icon_<module_name>_32.gif',
                                          'category'    => 'Module Views');