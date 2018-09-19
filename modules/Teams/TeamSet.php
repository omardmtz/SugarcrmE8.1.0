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
 * TeamSet represents a unique combination of Teams in the system. The goal here is to reduce the amount of duplicated
 * data.
 *
 * Example: West, East, Global could represent  one team set
 * East, Global could represent another.
 *
 * So records that have these combinbations of teams will have the same team_set_id.
 *
 */

use Sugarcrm\Sugarcrm\Denormalization\TeamSecurity\Listener;
use Sugarcrm\Sugarcrm\DependencyInjection\Container;

require_once('vendor/ytree/Tree.php');
require_once('vendor/ytree/Node.php');
require_once('modules/Teams/TeamSetManager.php');

class TeamSet extends SugarBean{
    /*
    * char(36) GUID
    */
    var $id;
    /*
    * This is not being used right now for any purpose within the application. However, in the future we will have
    * named team sets called aliases so that you instead of selecting the same teams,  you can have 'macros' which
    * will allows the user to select an alias and get all of the teams associated with that team set.
    */
    var $name;
    /*
    * When saving a team set we need to ensure that the combination does not exist, so rather than looking it up
    * by joining on the teams within the set, we save the md5 of the sorted ids of the teams for easy look up.
    */
    var $team_md5;
    /*
    * This is NOT A DB FIELD, but simply used to return the primary team id
    */
    var $primary_team_id;
    /*
    * The number of teams associated with a team set.
    */
    var $team_count = 0;

    var $date_modified;
    /*
    * Whether this record has been soft deleted or not.
    */
    var $deleted;

    var $team_ids;

    var $table_name = "team_sets";
    var $object_name = "TeamSet";
    var $module_name = "TeamSets";
    var $module_dir = 'Teams';
    var $disable_custom_fields = true;
    /**
    * Default constructor
    *
    */
    public function __construct(){
        parent::__construct();
        $this->disable_row_level_security =true;
    }

    /**
     * Returns an array of Team objects for the given team_set_id ordered by name
     *
     * @param string $team_set_id
     * @return Team[]
     */
    public function getTeams($team_set_id){
        ///TODO CONCAT
        $sql = 'SELECT teams.id, teams.name, teams.name_2 FROM teams
            INNER JOIN team_sets_teams ON team_sets_teams.team_id = teams.id
            WHERE team_sets_teams.team_set_id = ?
            ORDER BY teams.name';
        $stmt = $this->db->getConnection()->executeQuery($sql, array($team_set_id));
        $teams = array();

        while ($row = $stmt->fetch()) {
            $team = BeanFactory::newBean('Teams');
            $team->populateFromRow($row, true);
            $teams[$team->id] = $team;
        }
        return $teams;
    }

    /**
    * Returns an array of team ids associated with a given team set id
    *
    * @param id $team_set_id
    * @return array of team ids
    */
    public function getTeamIds($team_set_id){
        $teams_arr = TeamSetManager::getUnformattedTeamsFromSet($team_set_id);
        $team_ids = array();
        if ( is_array($teams_arr) )
            foreach($teams_arr as $team)
                $team_ids[] = $team['id'];

        return $team_ids;
    }

    /**
    * Given an array of team_ids, determine if the set already exists, if it does return the set_id, if not,
    * create the set and return the id.
    *
    * @param array $team_ids
    * @return id	the id of the newly created team set
    */
    public function addTeams($team_ids){
        if(!is_array($team_ids)){
        $team_ids = array($team_ids);
        }
        $stats = $this->_getStatistics($team_ids);
        $team_md5 = $stats['team_md5'];
        $team_count = $stats['team_count'];
        $this->primary_team_id = $stats['primary_team_id'];
        $team_ids = $stats['team_ids'];

        $sql = "SELECT id FROM $this->table_name WHERE team_md5 = ?";
        $stmt = $this->db->getConnection()->executeQuery($sql, [$team_md5]);
        $row = $stmt->fetch();
        if (!$row){
            //we did not find a set with this combination of teams
            //so we should create the set and associate the teams with the set and return the set id.
            if(count($team_ids) == 1) {
                $this->new_with_id = true;
                $this->id = $this->db->fromConvert($team_ids[0], 'id');
                if ($this->db->getConnection()->fetchColumn(
                    "SELECT id FROM $this->table_name WHERE id = ?",
                    [$this->id]
                )) {
                    $GLOBALS['log']->error("Detected duplicate team set for $this->id");
                    // Reset new_with_id so we overwrite this wrong set
                    $this->new_with_id = false;
                }
            } else {
                // this is a new team set. so need to get a new id.
                $this->new_with_id = true;
                $this->id = create_guid();
            }
            $this->team_md5 = $team_md5;
            $this->primary_team_id = $this->getPrimaryTeamId();
            $this->name = $team_md5;
            $this->team_count = $team_count;
            $this->save();
            foreach($team_ids as $team_id){
                $this->_addTeamToSet($team_id);
            }

            Container::getInstance()->get(Listener::class)->teamSetCreated($this->id, $team_ids);

            return $this->id;
        }else{
            $id = $this->db->fromConvert($row['id'], 'id');
            return $id;
        }
    }

    /**
    * Compute generic statistics we need when saving a team set.
    *
    * @param array $team_ids
    * @return array
    */
    protected function _getStatistics($team_ids){
        $team_md5 = '';
        sort($team_ids, SORT_STRING);
        $primary_team_id = '';
        //remove any dups
        $teams = array();

        $team_count = count($team_ids);
        if($team_count == 1) {
            $team_md5 = md5($team_ids[0]);
            $teams[] = $team_ids[0];
            if(empty($this->primary_team_id)) {
            $primary_team_id = $team_ids[0];
            }
        } else {
            for($i=0; $i<$team_count; $i++) {

                $team_id = $team_ids[$i];

                if(!array_key_exists("$team_id", $teams)){
                    $team_md5 .= $team_id;
                    if(empty($this->primary_team_id)){
                        $primary_team_id = $team_id;
                    }
                    $teams["$team_id"] = $team_id;
                }
            }
            $team_md5 = md5($team_md5);
        }
        return array('team_ids' => $team_ids, 'team_md5' => $team_md5, 'primary_team_id' => $primary_team_id, 'team_count' => $team_count);
    }

    /**
    * Given a team_id remove that team from this particular set
    *
    * @param string $team_id
    */
    public function removeTeamFromSet($team_id){
        $GLOBALS['log']->info("Removing team_id: {$team_id} from team set: {$this->id}");
        $this->db->getConnection()->delete(
            'team_sets_teams',
            ['team_id' => $team_id, 'team_set_id' => $this->id]
        );

        //have to recalc the md5 hash and the count
        $stats = $this->_getStatistics($this->getTeamIds($this->id));
        $this->team_md5 = $stats['team_md5'];
        $this->team_count = $stats['team_count'];
        $this->save();
    }


    public function getPrimaryTeamId(){
        return $this->primary_team_id;
    }

    /**
    * Associate the teams with the team set
    *
    * @param id $team_id	the team id to associate with the team set
    */
    private function _addTeamToSet($team_id){
        if($this->load_relationship('teams')){
            $this->teams->add($team_id);
        }
    }

    /**
    * Used in export to generate the proper sql to join on team sets
    *
    * @param String $table_name	the table name of the module we are using
    * @return String
    */
    public static function getTeamNameJoinSql($table_name){
        return " LEFT JOIN  team_sets ts ON $table_name.team_set_id=ts.id  AND ts.deleted=0
                LEFT JOIN  teams teams ON teams.id=ts.id AND teams.deleted=0 AND teams.deleted=0 ";
    }

    /**
     * Retrieve all team set ids for a given user.
     * @static
     * @param $user_id
     * @return array
     */
    public static function getTeamSetIdsForUser($user_id)
    {
        global $db;
        $cacheKey = "teamSetIdByUser{$user_id}";
        $cachedResults = sugar_cache_retrieve($cacheKey);
        if($cachedResults)
            return $cachedResults;

        $sql = 'SELECT tst.team_set_id from team_sets_teams tst
            INNER JOIN team_memberships team_memberships ON tst.team_id = team_memberships.team_id
            AND team_memberships.user_id = ? AND team_memberships.deleted=0 group by tst.team_set_id';
        $stmt = $GLOBALS['db']->getConnection()->executeQuery($sql, [$user_id]);
        $results = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        $newResults = array();
        foreach ($results as $result) {
            $newResults[] = $db->fromConvert($result, 'id');
        }
        sugar_cache_put($cacheKey, $newResults);
        return $newResults;
    }
    /**
    * Determine whether a user has access to any of the teams on a team set.
    *
    * @param id $user_id
    * @param id $team_set_id
    * @return boolean	true if the user has acces, false otherwise
    */
    function isUserAMember($user_id, $team_set_id = '', $team_ids = array()){
        // determine whether the user is already on the team
        if(!empty($team_set_id)){
            $sql = 'SELECT team_memberships.id FROM team_memberships
                INNER JOIN team_sets_teams ON team_sets_teams.team_id = team_memberships.team_id
                WHERE user_id = ? AND team_sets_teams.team_set_id = ? AND team_memberships.deleted = 0';
            $result = $this->db->getConnection()->fetchColumn($sql, [$user_id, $team_set_id]);
        }elseif(!empty($team_ids)){
            $sql = 'SELECT team_memberships.id FROM team_memberships
                WHERE user_id = ? AND team_id IN (?) AND team_memberships.deleted = 0';
            $stmt = $this->db->getConnection()->executeQuery(
                $sql,
                [$user_id, $team_ids],
                [null, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY]
            );
            $result = $stmt->fetchColumn();
        }else{
            return false;
        }

        if ($result != null) {
            return true;
        }
        return false;
    }

    /**
    * Returns all the users of teams of teamset
    *
    * @param id $team_set_id
    * @param boolean $only_active_users
    * @return array - users
    */
    function getTeamSetUsers($team_set_id, $only_active_users = false) {
        $usersArray = array();
        $teamIds = $this->getTeamIds($team_set_id);
        if(!empty($teamIds)) {
            foreach($teamIds as $team_id) {
                $team = BeanFactory::newBean('Teams');
                $team->id = $team_id;
                $usersList = $team->get_team_members($only_active_users);
                if (!empty($usersList)) {
                    foreach($usersList as $user) {
                        $usersArray[$user->id] = $user;
                    } // foreach
                } // if
            } // foreach
        } // if
        return $usersArray;
    } // fn
}
