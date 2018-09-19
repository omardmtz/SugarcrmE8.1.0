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


class SugarFavorites extends Basic
{
	public $new_schema = true;
	public $module_dir = 'SugarFavorites';
	public $object_name = 'SugarFavorites';
	public $table_name = 'sugarfavorites';
	public $importable = false;

    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $module;
    public $record_id;
    public $tag;
    public $record_name;
    public $disable_row_level_security = true;

	public static function generateStar(
	    $on,
	    $module,
	    $record
	    )
	{
        return '<div class="star"><div class="'. ($on ? 'on': 'off') .'" onclick="var self=this; parent.SUGAR.App.api.favorite(\''.$module. '\',  \''.$record. '\', $(self).hasClass(\'off\'), { success: function() {$(self).toggleClass(\'on off\');} });">&nbsp;</div></div>';
	}

	public static function generateGUID(
	    $module,
	    $record,
	    $user_id = ''
	    )
	{
	    if(empty($user_id))
	        $user_id = $GLOBALS['current_user']->id;

		return md5($module . $record . $user_id);
	}

	public static function isUserFavorite(
	    $module,
	    $record,
	    $user_id = ''
	    )
	{
		$id = SugarFavorites::generateGUID($module, $record, $user_id);

		$focus = BeanFactory::getBean('SugarFavorites', $id);

		return !empty($focus->id);
	}

	public static function getUserFavoritesByModule($module = '', User $user = null, $orderBy = "", $limit = -1)
	{
	    if ( empty($user) )
	        $where = " sugarfavorites.assigned_user_id = '{$GLOBALS['current_user']->id}' ";
	    else
	        $where = " sugarfavorites.assigned_user_id = '{$user->id}' ";

        if ( !empty($module) ) {
            if ( is_array($module) ) {
                $where .= " AND sugarfavorites.module IN ('" . implode("','",$module) . "')";
            }
            else {
            	$where .= " AND sugarfavorites.module = '$module' ";
            }
        }
        $focus = BeanFactory::newBean('SugarFavorites');
		$response = $focus->get_list($orderBy,$where,0,$limit);

	    return $response['list'];
	}

	public static function getFavoritesByModuleByRecord($module, $id)
	{
		$where = '';
		$orderBy = '';
		$limit = -1;
        if ( !empty($module) ) {
            if ( is_array($module) ) {
                $where .= " sugarfavorites.module IN ('" . implode("','",$module) . "')";
            }
            else {
                $where .= " sugarfavorites.module = '$module' ";
            }
        }

        $where .= " AND sugarfavorites.record_id = '{$id}'";

        $focus = BeanFactory::newBean('SugarFavorites');
		$response = $focus->get_list($orderBy,$where,0,$limit);

	    return $response['list'];
	}

	/**
	 * Use a direct DB Query to retreive only the assigned user id's for a module/record.
	 * @param string $module - module name
	 * @param string $id - guid
	 * @return array $assigned_user_ids - array of assigned user ids
	 */
	public static function getUserIdsForFavoriteRecordByModuleRecord($module, $id) {
		global $db;
		$query = "SELECT assigned_user_id FROM sugarfavorites WHERE module = '$module' AND record_id = '$id' AND deleted = 0";
		$queryResult = $db->query($query);
		$assigned_user_ids = array();
		while($row = $db->fetchByAssoc($queryResult)) {
            $id = $db->fromConvert($row['assigned_user_id'], 'id');
            $assigned_user_ids[] = $id;
		}
		return $assigned_user_ids;
	}

	public function markRecordDeletedInFavoritesByUser($record_id, $module, $assigned_user_id)
	{
		$query = "UPDATE {$this->table_name} set deleted=1 , module = '{$module}', date_modified = '$date_modified', modified_user_id = NOW() where record_id='{$record_id}' AND assigned_user_id = '{$assigned_user_id}'";
        $this->db->query($query, true, "Error marking favorites deleted: ");
	}

	/**
	 * An easy way to toggle a favorite on and off.
	 * @param string $id
	 * @param int $deleted
	 * @return bool
	 */
	public function toggleExistingFavorite($id, $deleted)
	{
		$deleted = (int) $deleted;
		if ($deleted != 0 && $deleted != 1) {
			return false;
		}

        if ($deleted == 0) {
            $this->mark_undeleted($id);
        } else {
            $this->mark_deleted($id);
        }

		return true;
	}

    public static function markRecordDeletedInFavorites($record_id, $date_modified, $modified_user_id = "")
    {
        $focus = BeanFactory::newBean('SugarFavorites');
        $focus->mark_records_deleted_in_favorites($record_id, $date_modified, $modified_user_id);
    }

    public function mark_records_deleted_in_favorites($record_id, $date_modified, $modified_user_id = "")
    {
        $record_id = $this->db->quoted($record_id);
        $date_modified = $this->db->quoted($date_modified);
        $modified_user_id = $this->db->quoted($modified_user_id);

        if (isset($modified_user))
            $query = "UPDATE $this->table_name set deleted=1 , date_modified = $date_modified, modified_user_id = $modified_user_id where record_id=$record_id";
        else
            $query = "UPDATE $this->table_name set deleted=1 , date_modified = $date_modified where record_id=$record_id";

        $this->db->query($query, true, "Error marking favorites deleted: ");
    }

	public function fill_in_additional_list_fields()
	{
	    parent::fill_in_additional_list_fields();

	    $focus = BeanFactory::newBean($this->module);
	    if ( $focus instanceOf SugarBean ) {
	        $focus->retrieve($this->record_id);
	        if ( !empty($focus->id) )
	            $this->record_name = $focus->name;
	    }
	}

    /**
     * Add a Favorites block to the SugarQuery Object to fetch favorites for a specific [default to current] user
     * @param SugarQuery $sugar_query
     * @param bool $joinTo
     * @param string $join_type
     * @param bool|guid $user_id
     * @return string
     */
    public function addToSugarQuery(SugarQuery $sugar_query, $options = array()) {
        $alias = '';
        $user_id = (!isset($options['current_user_id'])) ? $GLOBALS['current_user']->id : $options['current_user_id'];
        $joinTo = (!isset($options['joinTo'])) ? false : $options['joinTo'];
        $joinType = (!isset($options['joinType'])) ? 'INNER' : $options['joinType'];

        if(!$joinTo) {
            if(is_array($sugar_query->from)) {
                list($bean, $alias) = $sugar_query->from;
            }
            else {
                $bean = $sugar_query->from;
                $alias = $bean->getTableName();
            }
        } else {
            $alias = $joinTo->joinName();
            $bean = $sugar_query->getTableBean($alias);
        }

        if (empty($bean)) {
            return false;
        }

        $sfAlias = $this->db->getValidDBName("sf_" . $bean->getTableName(), false, 'alias');

        $sugar_query->joinTable(self::getTableName(), array('alias'=>$sfAlias, 'joinType'=>$joinType, 'linkingTable' => true))
                    ->on()->equals("{$sfAlias}.module", $bean->module_name, $this)
                        ->equalsField("{$sfAlias}.record_id","{$alias}.id", $this)
                    ->equals("{$sfAlias}.assigned_user_id", $user_id, $this)
                    ->equals("{$sfAlias}.deleted", 0, $this);

        return $sfAlias;
    }

    /**
     * {@inheritdoc}
     *
     * Attempt to subscribe the user to the favorited bean after saving.
     *
     * @param bool $check_notify
     * @return String
     */
    public function save($check_notify = false)
    {
        parent::save($check_notify);
        $this->subscribeAfterFavorite();

        return $this->id;
    }

    /**
     * {@inheritdoc}
     *
     * Attempt to subscribe the user to the favorited bean after restoring the SugarFavorite bean.
     *
     * @param $id
     */
    public function mark_undeleted($id)
    {
        parent::mark_undeleted($id);
        $this->subscribeAfterFavorite();
    }

    /**
     * When the current user marks a bean as a favorite, the user is also subscribed to the bean if not already
     * following the record. Unfavorite-ing a bean results in no change to the subscription (i.e., if the user is
     * subscribed to the record then the subscription remains; if the user is not subscribed to the record then no new
     * subscription is added).
     */
    protected function subscribeAfterFavorite()
    {
        if (!empty($this->id)) {
            // reload the bean so that we're working with the newest state of the record before taking any other action
            $this->retrieve($this->id);

            // only subscribe to the associated bean if favorite-ing the record (deleted is 0)
            if ($this->deleted == 0) {
                $beanToFollow = BeanFactory::getBean($this->module, $this->record_id, array("strict_retrieve" => true));

                if (!is_null($beanToFollow)) {
                    // the return value is inconsequential because it doesn't bubble up or factor into any other logic
                    $this->subscribeUserToRecord($GLOBALS["current_user"], $beanToFollow);
                }
            }
        }
    }

    /**
     * Wraps the call to Subscription::subscribeUserToRecord so that we can mock it out in unit tests.
     *
     * @param User      $user
     * @param SugarBean $bean
     * @return bool|string
     */
    protected function subscribeUserToRecord(User $user, SugarBean $bean)
    {
        return Subscription::subscribeUserToRecord($user, $bean);
    }
}
