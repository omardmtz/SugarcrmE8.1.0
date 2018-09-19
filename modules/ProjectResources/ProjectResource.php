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

// $Id: Project.php 17092 2006-10-16 20:48:09 +0000 (Mon, 16 Oct 2006) awu $










/**
 *
 */
class ProjectResource extends SugarBean {
	// database table columns
	var $id;
	var $date_modified;
	var $assigned_user_id;
	var $modified_user_id;
	var $created_by;

	var $team_id;
	var $deleted;

	// related information
	var $modified_by_name;
	var $created_by_name;

	var $team_name;

	var $project_id;
	var $resource_id;
	var $resource_type;
	
	var $object_name = 'ProjectResource';
	var $module_dir = 'ProjectResources';
	var $new_schema = true;
	var $table_name = 'project_resources';
}
