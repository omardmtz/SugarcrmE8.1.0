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


require_once('modules/Teams/TeamSetManager.php');

/**
 * This class extends the Link object to implement custom handling for team sets behavior
 * This is defined in include/SugarObjects/team_security/vardefs.php as:
 * 'link_type' => 'TeamSetLink',
 * 'link_file' => 'modules/Teams/TeamSetLink.php'
 *
 */
class TeamSetLink extends Link2 {
	/*
	 * a TeamSet Object
	 */
	protected  $_teamSet;
	/*
	 * maintain an internal array of team_ids we are going to save
	 */
    protected $_teamList;
    /*
     * maintain an internal array of selected team_ids we are going to save
     * (list of teams with team based acls enabled)
     */
    protected $_selectedTeamList;
	/*
	 * Whether this data has been committed to the database or not.
	 */
    protected $_saved = false;

	public function __construct($linkName, $bean, $linkDef = false){
		parent::__construct($linkName, $bean, $linkDef);
        $this->_teamSet = BeanFactory::newBean('TeamSets');
		$this->_teamList = array();
	}

	/**
	 * @Override add from Link.php
	 * If we have an array of team ids then append them, since that is in common with how we call add() on relationships
	 *
	 * @param unknown_type $rel_keys
	 * @param unknown_type $additional_values
	 * @param unknown_type $save
	 */
	public function add($rel_keys, $additional_values=array(), $save = true) {
		if (!isset($rel_keys) or empty($rel_keys)) {
			$GLOBALS['log']->debug("TeamSetLink.add, Null key passed, no-op, returning... ");
			return;
		}

        $rel_keys = is_array($rel_keys) ? $rel_keys : [$rel_keys];
        $teamIds = [];
        foreach ($rel_keys as $key) {
            if ($key instanceof SugarBean) { /**@var $key SugarBean*/
                $teamIds[] = $key->id;
            } else { /**@var $key string*/
                $teamIds[] = $key;
            }
        }

        $this->appendTeams($teamIds, $additional_values, $save);
	}

	/**
	 * @Override delete from Link.php
	 * if we do not have a related_id, we can assume we are operating on the related bean
	 * so we can just call remove, otherwise we will have to do something similar to what we do in the delete()
	 * method within Link.php and try to load the bean from the relationship table.
	 *
	 * @param string $id	- the team_id or array of team_ids to delete
	 * @param string $related_id	- if this is blank then we will assume we are working with an existing team_id, otherwise use what is passed in.
	 */
	public function delete($module_id, $team_id=''){

		if (!isset($module_id) or empty($module_id)) {
			$GLOBALS['log']->debug("TeamSetLink.delete, Null key passed, no-op, returning... ");
			return;
		}

		//if we do not have a related_id, we can assume we are operating on the related bean
		//so we can just call remove, otherwise we will have to do something similar to what we do in the delete()
		//method within Link.php and try to load the bean from the relationship table.
		if(!empty($team_id)){
			if(!is_array($team_id)){
				$team_id = array($team_id);
			}
			//pass true as the 3rd argument to ensure we save the record.
			$this->remove($team_id, array(), true);
		}
	}

	/**
	 * Commit any unsaved changes to the database
	 *
	 */
    public function save($checkForUpdate = true, $usedDefaultTeam = false)
    {
        if ($this->_saved == false) {
            $previousTeamSetId = $this->focus->team_set_id;
            //disable_team_sanity_check can be set to allow for us to just take the values provided on the bean blindly rather than
            //doing a check to confirm whether the data is correct.
            if (empty($GLOBALS['sugar_config']['disable_team_sanity_check'])) {
                if (!empty($this->focus->team_id)) {
                    if (empty($this->_teamList)) {
                        //we have added this logic here to account for side quick create.
                        // If you use side quick create then we do not set the team_id nor the team_set_id
                        //from the UI. In that case the team_id will be set in SugarBean.php by the current user's
                        // default team but the team_set_id will still not be set
                        //we have to hold off on setting the team_set_id until in here so we can be sure to check that
                        // it is not waiting to be saved to the db and is being held in
                        //_teamList.  So we use $usedDefaultTeam to signify that we did in fact not have a team_id until
                        // we set it in SugarBean.php and that this is not an update so we do not have a team_set_id on the bean.
                        // In that case we can use the current user's default team set.
                        if ($usedDefaultTeam && empty($this->focus->team_set_id) 
                            && isset($GLOBALS['current_user']) && isset($GLOBALS['current_user']->team_set_id)
                        ) {
                            $this->focus->team_set_id = $GLOBALS['current_user']->team_set_id;
                        }

                        //this is a safety check to ensure we actually do have a set team_set_id
                        if (!empty($this->focus->team_set_id)) {
                            $this->_teamList = $this->_teamSet->getTeamIds($this->focus->team_set_id);
                        }
                    }

                    //this stmt is intended to handle the situation where the code has set the team_id but not the team_set_id as may be the case
                    //from SOAP or related bean creation.
                    if (!in_array($this->focus->team_id, $this->_teamList)) {
                        $this->_teamList[] = $this->focus->team_id;
                        //use default acl_team_set_id
                        if (empty($this->focus->acl_team_set_id)
                            && isset($GLOBALS['current_user']) && isset($GLOBALS['current_user']->acl_team_set_id)
                        ) {
                            $this->_selectedTeamList[] = $GLOBALS['current_user']->acl_team_set_id;
                        }
                    }

                    // Apply the above-described functionality for acl_team_set_id also.
                    if (empty($this->_selectedTeamList)) {
                        if ($usedDefaultTeam && empty($this->focus->acl_team_set_id)
                            && isset($GLOBALS['current_user']) && isset($GLOBALS['current_user']->acl_team_set_id)
                        ) {
                            $this->focus->acl_team_set_id = $GLOBALS['current_user']->acl_team_set_id;
                        }

                        //this is a safety check to ensure we actually do have a set acl_team_set_id
                        if (!empty($this->focus->acl_team_set_id)) {
                            $this->_selectedTeamList = $this->_teamSet->getTeamIds($this->focus->acl_team_set_id);
                        }
                    }
                }

                //we need to check if the assigned_user has access to any of the teams on this record,
                //if they do not then we need to be sure to add their private team to the list.
                //If assigned_user_id is not set on the object as is the case with Documents, then use created_by

                //we added 'disable_team_access_check' config entry to allow for admins to revert back to the way things were
                //pre 5.5. So that they could disable this check and if the assigned_user was not a member of one of the
                //teams on this record we would just leave it alone.
                //Exclude user's module so additional teams are NOT added to a user record
                if (($this->focus->module_dir != 'Users') && empty($GLOBALS['sugar_config']['disable_team_access_check']) 
                    && empty($this->focus->in_workflow)) {
                    $assigned_user_id = null;
                    if (isset($this->focus->assigned_user_id)) {
                        $assigned_user_id = $this->focus->assigned_user_id;
                    } else {
                        if (isset($this->focus->created_by)) {
                            $assigned_user_id = $this->focus->created_by;
                        }
                    }
                    if (!empty($assigned_user_id) && !$this->_teamSet->isUserAMember($assigned_user_id, '', $this->_teamList)) {
                        $privateTeamId = User::staticGetPrivateTeamID($assigned_user_id);
                        if (!empty($privateTeamId)) {
                            $this->_teamList[] = $privateTeamId;
                        }
                    }
                }
                if (!empty($this->_teamList)) {
                    $this->focus->team_set_id = $this->_teamSet->addTeams($this->_teamList);
                }
                // If Team based ACLs have been enabled on any team then set the correct team set id on the bean
                if (!empty($this->_selectedTeamList)) {
                    $this->focus->acl_team_set_id = $this->_teamSet->addTeams($this->_selectedTeamList);
                }
            }//fi empty($GLOBALS['sugar_config']['disable_team_sanity_check']))

            //if this bean already exists in the database, and is not new with id
            //and if we are not saving this bean from Import or Mass Update, then perform the query
            //otherwise the bean should be saved later.
            $runUpdate = false;
            if ($checkForUpdate) {
                $runUpdate = (!empty($this->focus->id) && empty($this->focus->new_with_id) && !empty($this->focus->save_from_post));
            }

            if ($runUpdate) {
                $updatesArr[] = "team_set_id = " . $GLOBALS['db']->quoted($this->focus->team_set_id);
                // If Team based ACLs are enabled on any team then add that to the update as well
                if (!empty($this->_selectedTeamList)) {
                    $updatesArr[] .= "acl_team_set_id = " . $GLOBALS['db']->quoted($this->focus->acl_team_set_id);
                }
                $updateQuery = sprintf(
                    'UPDATE %s SET %s WHERE id = %s',
                    $this->focus->table_name,
                    implode(", ", $updatesArr),
                    $GLOBALS['db']->quoted($this->focus->id)
                );
                $GLOBALS['db']->query($updateQuery);
            }

            //keep track of what we put into the database so we can clean things up later
            TeamSetManager::saveTeamSetModule($this->focus->team_set_id, $this->focus->table_name);

            if ($previousTeamSetId != $this->focus->team_set_id) {
                TeamSetManager::removeTeamSetModule($this->focus, $previousTeamSetId);
            }
            
            $this->_saved = true;

        }
	}

    /**
     * Replace whatever teams are on the bean with the teams given in the $rel_keys
     *
     * @param array $rel_keys
     * @param array $additional_values
     * @param bool $save
     */
    public function replace($rel_keys, $additional_values = [], $save = true)
    {
        $teamIds = [];

        foreach ($rel_keys as $key) {
            if ($key instanceof SugarBean) {
                $teamIds[] = $key->id;
            } else {
                $teamIds[] = $key;
            }
        }

        $this->_teamList = $teamIds;

            if (!empty($additional_values['selected_teams'])) {
                $this->_selectedTeamList = $additional_values['selected_teams'];
            }
            $this->_saved = false; //bug 48733 - "New team added during merge duplicate is not saved"
            if ($save) {
                $this->save();
            }
	}

	/**
	 * Remove the given teams from the bean
	 *
	 * @param unknown_type $rel_keys
	 * @param unknown_type $additional_values
	 * @param unknown_type $save
	 */
	public function remove($rel_keys, $additional_values=array(), $save = true) {
		$team_ids = $this->_teamSet->getTeamIds($this->focus->team_set_id);
		//Check if an attempt was made to remove the primary team (team_id) of the bean
		$primary_key = array_search($this->focus->team_id, $rel_keys);
		if($primary_key !== false) {
		   //Remove the entry from $rel_keys	
		   unset($rel_keys[$primary_key]);	
		   $params = array($this->focus->team_id, $this->focus->object_name,  $this->focus->id);
		   $msg = string_format($GLOBALS['app_strings']['LBL_REMOVE_PRIMARY_TEAM_ERROR'], $params);
		   $GLOBALS['log']->error($msg);
		}

		$team_ids = array_diff($team_ids, $rel_keys);
		//Make sure that we are not adding an empty set of teams
		//this will occur if you attempt to remove all the existing teams attached to the bean
		if(!empty($team_ids)) {
			$this->_teamList = $team_ids;
			if($save){
				$this->save();
			}
		}
	}

	public function isSaved(){
		return $this->_saved;
	}
	
	/**
	 * We use this method in action_utils when setting the team_id, so we can ensure that the proper team logic is re-run.
	 * @param BOOL $saved
	 */
	public function setSaved($saved){ 
		$this->_saved = $saved;
	}

	/**
	 * Append the given teams to the list of teams already on the bean
	 *
	 * @param unknown_type $rel_keys
	 * @param unknown_type $additional_values
	 * @param unknown_type $save
	 */
	protected function appendTeams($rel_keys, $additional_values=array(), $save = true) {
            if (empty($this->_teamList)) {
                $team_ids = $this->_teamSet->getTeamIds($this->focus->team_set_id);
                $this->_teamList = array_merge($rel_keys, $team_ids);
            } else {
                $this->_teamList = array_merge($this->_teamList, $rel_keys);
            }

            // If Team based ACLs are enabled on any team
            if (!empty($additional_values['selected_teams'])) {
                if (empty($this->_selectedTeamList)) {
                    $selected_team_ids = array();
                if (!empty($this->focus->acl_team_set_id)) {
                        // get the teams associated with this selected team set id
                        $selected_team_ids = $this->_teamSet->getTeamIds($this->focus->acl_team_set_id);
                    }
                    // merge the old teams and new teams
                    $this->_selectedTeamList = array_merge($selected_team_ids, $additional_values['selected_teams']);

                } else {
                    // merge the old teams and new teams
                    $this->_selectedTeamList = array_merge($this->_selectedTeamList, $additional_values['selected_teams']);
                }
            }

            if ($save) {
                $this->save();
            }
	}

    /**
     * Removes TeamSet module if no records exist
     */
    public function removeTeamSetModule()
    {
        TeamSetManager::removeTeamSetModule($this->focus, $this->focus->team_set_id);
    }
}
