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
/**
 * Upgrade private teams from CE to PRO
 */
class SugarUpgradeTeamsCE extends UpgradeScript
{
    public $order = 7000;
    public $type = self::UPGRADE_DB;

    public function run()
    {
        if(!($this->from_flavor == 'ce' && $this->toFlavor('pro'))) return;
        require_once('ModuleInstall/ModuleInstaller.php');

        $globalteam = new Team();
        $globalteam->retrieve('1');
        if(empty($globalteam->name)){
            Team::create_team("Global", $this->mod_strings['LBL_GLOBAL_TEAM_DESC'], $globalteam->global_team);
        }

        $this->log("Start Building private teams");
        $result = $this->db->query("SELECT id, user_name, first_name, last_name FROM users where deleted=0");
        while($row = $this->db->fetchByAssoc($result)) {
            $results2 = $this->db->query("SELECT id FROM teams WHERE name = '({$row['user_name']})'");
            $assoc = '';
            if(!$assoc = $this->db->fetchByAssoc($results2)) {
                //if team does not exist, then lets create the team for this user
          		$team = new Team();
          		$user = new User();
          		$user->retrieve($row['id']);
          		$team->new_user_created($user);
          		$team_id = $team->id;
          	} else {
          		$team_id =$assoc['id'];
          	}

          	//upgrade the team
          	$name = is_null($row['first_name'])?'':$row['first_name'];
          	$name_2 = is_null($row['last_name'])?'':$row['last_name'];
          	$associated_user_id = $row['id'];

          	//Bug 32914
          	//Ensure team->name is not empty by using team->name_2 if available
          	if(empty($name) && !empty($name_2)) {
          	    $name = $name_2;
          	    $name_2 = '';
          	}

          	$this->db->query("UPDATE teams SET name = '{$name}', name_2 = '{$name_2}', associated_user_id = '{$associated_user_id}' WHERE id = '{$team_id}'");
        } //while

        $this->db->query("update users set team_set_id = (select teams.id from teams where teams.associated_user_id = users.id)");
        $this->db->query("update users set default_team = (select teams.id from teams where teams.associated_user_id = users.id)");
        $this->log("Done Building private teams");


        $this->log("Start Building the team_set and team_sets_teams");
        require('include/modules.php');
        foreach($beanList as $moduleName=>$beanName) {
            if($moduleName == 'TeamMemberships' || $moduleName == 'ForecastOpportunities'){
                continue;
            }
            $bean = loadBean($moduleName);
            if(empty($bean) ||
                    empty($bean->table_name)) {
                continue;
            }

            $FieldArray = $this->db->get_columns($bean->table_name);
            if(!isset($FieldArray['team_id'])) {
                continue;
            }
            $this->upgradeTeamColumn($bean, 'team_id');
        } //foreach

        //Upgrade users table
        $bean = BeanFactory::newBean('Users');
        $this->upgradeTeamColumn($bean, 'default_team');
        $result = $this->db->query("SELECT id FROM teams where deleted=0");
        while($row = $this->db->fetchByAssoc($result)) {
            $teamset = new TeamSet();
            $teamset->addTeams($row['id']);
        }
        $this->log("Finish Building the team_set and team_sets_teams");

        $this->log("Start modules/Administration/upgradeTeams.php");
        ob_start();
        include('modules/Administration/upgradeTeams.php');
        ob_end_clean();
        $this->log("Finish modules/Administration/upgradeTeams.php");
    }

    /**
     * upgradeTeamColumn
     * Helper function to create a team_set_id column and also set team_set_id column
     * to have the value of the $column_name parameter
     *
     * @param $bean SugarBean which we are adding team_set_id column to
     * @param $column_name The name of the column containing the default team_set_id value
     */
    function upgradeTeamColumn($bean, $column_name)
    {
    	//first let's check to ensure that the team_set_id field is defined, if not it could be the case that this is an older
    	//module that does not use the SugarObjects
    	if(empty($bean->field_defs['team_set_id']) && $bean->module_dir != 'Trackers'){

    		//at this point we could assume that since we have a team_id defined and not a team_set_id that we need to
    		//add that field and the corresponding relationships
    		$object = $bean->object_name;
    		$module = $bean->module_dir;
    		$object_name = $object;
    		$_object_name = strtolower($object_name);

    		if(!empty($GLOBALS['dictionary'][$object]['table'])){
    			$table_name = $GLOBALS['dictionary'][$object]['table'];
    		}else{
    			$table_name = strtolower($module);
    		}

    		require 'include/SugarObjects/implements/team_security/vardefs.php';
    		//go through each entry in the vardefs from team_security and unset anything that is already set in the core module
    		//this will ensure we have the proper ordering.
    		$fieldDiff = array_diff_assoc($vardefs['fields'], $GLOBALS['dictionary'][$bean->object_name]['fields']);

    		$file = 'custom/Extension/modules/' . $bean->module_dir. '/Ext/Vardefs/teams.php';
    		$contents = "<?php\n";
    		if(!empty($fieldDiff)){
    			foreach($fieldDiff as $key => $val){
    				$contents .= "\n\$GLOBALS['dictionary']['". $object . "']['fields']['". $key . "']=" . var_export_helper($val) . ";";
    			}
    		}
    		$relationshipDiff = array_diff_assoc($vardefs['relationships'], $GLOBALS['dictionary'][$bean->object_name]['relationships']);
    		if(!empty($relationshipDiff)){
    			foreach($relationshipDiff as $key => $val){
    				$contents .= "\n\$GLOBALS['dictionary']['". $object . "']['relationships']['". $key . "']=" . var_export_helper($val) . ";";
    			}
    		}
    		$indexDiff = array_diff_assoc($vardefs['indices'], $GLOBALS['dictionary'][$bean->object_name]['indices']);
    		if(!empty($indexDiff)){
    			foreach($indexDiff as $key => $val){
    					$contents .= "\n\$GLOBALS['dictionary']['". $object . "']['indices']['". $key . "']=" . var_export_helper($val) . ";";
    			}
    		}
    		file_put_contents($file, $contents);

    		//we have written out the teams.php into custom/Extension/modules/{$module_dir}/Ext/Vardefs/teams.php'
    		//now let's merge back into vardefs.ext.php
    		$mi = new ModuleInstaller();
    		$mi->merge_files('Ext/Vardefs/', 'vardefs.ext.php');
            VardefManager::loadVardef($bean->getModuleName(), $bean->object_name, true);
    		$bean->field_defs = $GLOBALS['dictionary'][$bean->object_name]['fields'];
    	}

    	if(isset($bean->field_defs['team_set_id'])) {
    		//Create the team_set_id column
    		$FieldArray = $this->db->helper->get_columns($bean->table_name);
    		if(!isset($FieldArray['team_set_id'])) {
    			$this->db->addColumn($bean->table_name, $bean->field_defs['team_set_id']);
    		}
    		$indexArray = $this->db->helper->get_indices($bean->table_name);

            $indexName = $this->db->getValidDBName('idx_'.strtolower($bean->table_name).'_tmst_id', true, 34);
            $indexDef = array(
    					 array(
    						'name' => $indexName,
    						'type' => 'index',
    						'fields' => array('team_set_id')
    					 )
    				   );
    		if(!isset($indexArray[$indexName])) {
    			$this->db->addIndexes($bean->table_name, $indexDef);
    		}

    		//Update the table's team_set_id column to have the same values as team_id
    	    $this->db->query("UPDATE {$bean->table_name} SET team_set_id = {$column_name}");
    	}
    }
}
