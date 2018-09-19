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

class SugarFavoritesController extends SugarController {
	public function __construct(){
	}
	public function loadBean(){
		if(empty($this->record))$this->record = SugarFavorites::generateGUID($_REQUEST['fav_module'], $_REQUEST['fav_id']);
		$this->bean = BeanFactory::newBean('SugarFavorites');
	}
	public function pre_save(){
	    global $current_user;

		if(!$this->bean->retrieve($this->record, true, false)){
			$this->bean->new_with_id = true;
		}
		$this->bean->id = $this->record;
		$this->bean->module = $_REQUEST['fav_module'];
		$this->bean->record_id = $_REQUEST['fav_id'];
		$this->bean->created_by = $current_user->id;
		$this->bean->assigned_user_id = $current_user->id;
		$this->bean->deleted = 0;
	}

	public function action_save(){
	    if(!empty($this->bean->fetched_row['deleted']) && empty($this->bean->deleted)) {
	        $this->bean->mark_undeleted($this->bean->id);
	    }
		$this->bean->save();

	}
	public function post_save(){
		echo $this->record;
	}
	public function action_delete(){
		$this->bean->mark_deleted($this->record);

	}
	public function post_delete(){
		echo $this->record;
	}
	public function action_tag(){
		return 'Favorite Tagged';

	}
}
?>