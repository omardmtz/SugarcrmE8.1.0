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

// $Id: Menu.php 36149 2008-06-03 00:01:27Z Ajay Gupta $

$module_menu = array();
global $mod_strings;

// Each index of module_menu must be an array of:
// the link url, display text for the link, and the icon name.

if(ACLController::checkAccess('Project', 'edit', true))$module_menu[] = array("index.php?module=Project&action=EditView&return_module=Project&return_action=DetailView",
	$mod_strings['LNK_NEW_PROJECT'], 'CreateProject');
if(ACLController::checkAccess('Project', 'list', true))$module_menu[] = array('index.php?module=Project&action=index',
	$mod_strings['LNK_PROJECT_LIST'], 'Project');
    /*
if(ACLController::checkAccess('ProjectTask', 'edit', true))$module_menu[] = array("index.php?module=ProjectTask&action=EditView&return_module=ProjectTask&return_action=DetailView",
	$mod_strings['LNK_NEW_PROJECT_TASK'], 'CreateProjectTask');
    */
if(ACLController::checkAccess('ProjectTask', 'list', true))$module_menu[] = array('index.php?module=ProjectTask&action=index',
	$mod_strings['LNK_PROJECT_TASK_LIST'], 'ProjectTask');


?>
