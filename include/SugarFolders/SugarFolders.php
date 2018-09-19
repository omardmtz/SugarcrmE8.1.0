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
use Sugarcrm\Sugarcrm\Util\Uuid;

require_once("vendor/ytree/Tree.php");
require_once("vendor/ytree/ExtNode.php");

/**
 * Polymorphic buckets - place any item in a folder
 */
class SugarFolder {

	// public attributes
	var $id;
	var $name;
	var $parent_folder;
	var $has_child = 0; // flag node has child
	var $is_group = 0;
	var $is_dynamic = 0;
	var $dynamic_query = '';
	var $assign_to_id;
	var $created_by;
	var $modified_by;
	var $date_created;
	var $date_modified;
	var $deleted;
	var $folder_type;
	var $team_id;
	var $team_set_id;

	var $db;
	var $new_with_id = false;

	// core queries
	var $core = "SELECT f.id, f.name, f.has_child, f.is_group, f.is_dynamic, f.dynamic_query, f.folder_type, f.created_by, i.deleted FROM folders f left join inbound_email i on f.id = i.groupfolder_id ";
	var $coreSubscribed = "SELECT f.id, f.name, f.has_child, f.is_group, f.is_dynamic, f.dynamic_query, f.folder_type, f.created_by, i.deleted FROM folders f LEFT JOIN folders_subscriptions fs ON f.id = fs.folder_id LEFT JOIN inbound_email i on  i.groupfolder_id = f.id ";
	var $coreWhere = "WHERE f.deleted = 0 ";
	var $coreWhereSubscribed = "WHERE f.deleted = 0 AND fs.assigned_user_id = ";
	var $coreOrderBy = " ORDER BY f.is_dynamic, f.is_group, f.name ASC ";

	var $hrSortLocal = array(
            'flagged' => 'type',
            'status'  => 'reply_to_status',
            'from'    => 'emails_text.from_addr',
            'subject' => 'name',
            'date'    => 'date_sent',
            'AssignedTo' => 'assigned_user_id',
            'flagged' => 'flagged'
        );
    var $defaultSort = 'date';
    var $defaultDirection = "DESC";

    protected $emailBean;

	// private attributes
	var $_depth;

    /**
     * folder field definition
     * @var array
     */
    private $fields = [];

    /**
     * folder subscription field definition
     * @var array
     */
    protected $folderSubscriptionFields = [];

    /**
     * folder table name
     * @var string
     */
    protected $table;

    /**
     * folder subscription table name
     * @var string
     */
    protected $folderSubscriptionTable;

	/**
	 * Sole constructor
	 */
    public function __construct()
    {
		$this->db = DBManagerFactory::getInstance();
        $this->emailBean = BeanFactory::newBean('Emails');
        $dictionary = [];
        require 'metadata/foldersMetaData.php';
        $this->table = $dictionary['folders']['table'];
        $this->fields = $dictionary['folders']['fields'];
        $this->folderSubscriptionTable = $dictionary['folders_subscriptions']['table'];
        $this->folderSubscriptionFields = $dictionary['folders_subscriptions']['fields'];
	}

	function deleteEmailFromAllFolder($id) {
        $this->db->getConnection()->delete(
            'folders_rel',
            ['polymorphic_module' => 'Emails', 'polymorphic_id' => $id]
        );
	}

	function deleteEmailFromFolder($id) {
        $this->db->getConnection()->delete(
            'folders_rel',
            ['polymorphic_module' => 'Emails', 'polymorphic_id' => $id, 'folder_id' => $this->id]
        );
	}

	function checkEmailExistForFolder($id) {
        $count = $this->db->getConnection()->executeQuery(
            "SELECT COUNT(*) FROM folders_rel"
            . " WHERE polymorphic_module = 'Emails' AND polymorphic_id = ? AND folder_id = ?",
            [$id, $this->id]
        )->fetchColumn();
        return $count > 0;
	}
	/**
	 * Moves beans from one folder to another folder
	 * @param string fromFolder GUID of source folder
	 * @param string toFolder GUID of destination folder
	 * @param string beanId GUID of SugarBean being moved
	 */
	function move($fromFolder, $toFolder, $beanId) {
        $this->db->getConnection()->update('folders_rel', ['folder_id' => $toFolder], [
            'folder_id' => $fromFolder,
            'polymorphic_id' => $beanId,
            'deleted' => 0,
        ]);
	}

	/**
	 * Copies one bean from one folder to another
	 */
	function copyBean($fromFolder, $toFolder, $beanId, $module) {
        $this->db->getConnection()->insert('folders_rel', [
            'id' => Uuid::uuid1(),
            'folder_id' => $toFolder,
            'polymorphic_module' => $module,
            'polymorphic_id' => $beanId,
            'deleted' => 0,
        ]);
	}

	/**
	 * Creates a new group Folder from the passed fields
	 * @param array fields
	 */
	function setFolder($fields) {

		global $current_user;
		if(empty($fields['groupFoldersUser'])) {
			$fields['groupFoldersUser'] = $current_user->id;
		}

		$this->name = $fields['name'];
		$this->parent_folder = $fields['parent_folder'];
		$this->has_child = 0;
		$this->is_group = 1;
		$this->assign_to_id = $fields['groupFoldersUser'];
		$this->team_id = $fields['team_id'];
		$this->team_set_id = $fields['team_set_id'];

		$this->save();
	}

	/**
	 * Returns GUIDs of folders that the user in focus is subscribed to
	 * @param object user User object in focus
	 * @return array
	 */
	function getSubscriptions($user) {
		if(empty($user)) {
			global $current_user;
			$user = $current_user;
		}
        return $this->db->getConnection()->executeQuery(
            'SELECT folder_id FROM folders_subscriptions WHERE assigned_user_id = ?',
            [$user->id]
        )->fetchAll(\PDO::FETCH_COLUMN);
	}

	/**
	 * Sets a user's preferences for subscribe folders (Sugar only)
	 * @param array subs Array of IDs for subscribed folders
	 */
	function setSubscriptions($subs) {
		global $current_user;

		if(empty($current_user->id)) {
			$GLOBALS['log']->fatal("*** FOLDERS: tried to update folder subscriptions for a user with no ID");
			return false;
		}

		$cleanSubscriptions = array();

		// ensure parent folders are selected, regardless.
		foreach($subs as $id) {
			$id = trim($id);
			if(!empty($id)) {
				$cleanSubscriptions[] = $id;
                $parentFolder = $this->db->getConnection()
                    ->executeQuery('SELECT parent_folder FROM folders WHERE id = ?', [$id])
                    ->fetchColumn();
                if (!empty($parentFolder)) {
                    $cleanSubscriptions = $this->getParentIDRecursive($parentFolder, $cleanSubscriptions);
				}
			}
		}

		$this->clearSubscriptions();

		foreach($cleanSubscriptions as $id) {
		    $this->insertFolderSubscription($id, $current_user->id);
		}
	}

	/**
	 * Given a folder id and user id, create a folder subscription entry.
	 *
	 * @param String $folderId
	 * @param String $userID
	 * @return String The id of the newly created folder subscription.
	 */
	function insertFolderSubscription($folderId, $userID)
	{
        $id = Uuid::uuid1();
        $this->db->getConnection()->insert('folders_subscriptions', [
            'id' => $id,
            'folder_id' => $folderId,
            'assigned_user_id' => $userID,
        ]);
        return $id;
	}
	/**
	 * Recursively finds parent node until it hits root
	 * @param string id Starting id to follow up
	 * @param array ret collected ids
	 * @return array of IDs
	 */
	function getParentIDRecursive($id, $ret=array()) {
		if(!in_array($id, $ret)) {
			$ret[] = $id;
		}

        $parentFolder = $this->db->getConnection()
            ->executeQuery('SELECT parent_folder FROM folders WHERE id = ? AND deleted = 0', [$id])->fetchColumn();
        if (!empty($parentFolder)) {
            $ret = $this->getParentIDRecursive($parentFolder, $ret);
        }

		return $ret;
	}

	/**
	 * Deletes subscriptions to folders in preparation for reset
	 */
	function clearSubscriptions() {
		global $current_user;

		if(!empty($current_user->id)) {
            $this->db->getConnection()
                ->delete('folders_subscriptions', ['assigned_user_id' => $current_user->id]);
		}
	}


	/**
	 * Deletes all subscriptions for a particular folder id
	 *
	 * @return unknown
	 */
	function clearSubscriptionsForFolder($folderID)
	{
        $this->db->getConnection()->delete('folders_subscriptions', ['folder_id' => $folderID]);
	}

	protected function generateArchiveFolderQuery()
	{
		global $current_user;
	    $q = <<<ENDQ
SELECT emails.id , emails.name, emails.date_sent, emails.status, emails.type, emails.flagged, emails.reply_to_status, emails_text.from_addr, emails_text.to_addrs, 'Emails' polymorphic_module FROM emails
JOIN emails_text on emails.id = emails_text.email_id
ENDQ;
        $this->emailBean->addVisibilityFrom($q, array('where_condition' => true));

        $q .= <<<ENDW
 WHERE emails.deleted=0 AND emails.type NOT IN ('out', 'draft') AND emails.status NOT IN ('sent', 'draft') 
AND EXISTS (
SELECT 1 FROM emails_email_addr_rel eear
 JOIN email_addr_bean_rel eabr ON eabr.email_address_id=eear.email_address_id AND eabr.bean_id = '{$current_user->id}'
 AND eabr.bean_module = 'Users'
 WHERE eear.deleted=0 AND emails.id = eear.email_id
)
ENDW;
        $this->emailBean->addVisibilityWhere($q, array('where_condition' => true));
        return $q;
	}

	function generateSugarsDynamicFolderQuery()
	{
		global $current_user;
		$type = $this->folder_type;
		if($type == 'archived') {
		    return $this->generateArchiveFolderQuery();
		}
		$status = $type;
		if($type == "sent") {
			$type = "out";
		}
		if($type == 'inbound') {
			$ret = " AND emails.status NOT IN ('sent', 'archived', 'draft') AND emails.type NOT IN ('out', 'archived', 'draft')";
		} else {
			$ret = " AND emails.status NOT IN ('archived') AND emails.type NOT IN ('archived')";
		}
        $q = "SELECT
                emails.id,
                emails.name,
                emails.date_sent,
                emails.status,
                emails.type,
                emails.flagged,
                emails.reply_to_status,
                emails_text.from_addr,
                emails_text.to_addrs,
                'Emails' polymorphic_module
            FROM
                emails
            JOIN
                emails_text
            on
                emails.id = emails_text.email_id
                ";
        $options = array(
            'where_condition' => true,
            'action' => 'list',
        );
        $this->emailBean->addVisibilityFrom($q, $options);
        $q .= "
            WHERE
                (type = '{$type}' OR status = '{$status}')
                AND assigned_user_id = '{$current_user->id}'
                AND emails.deleted = 0 ";
        $this->emailBean->addVisibilityWhere($q, $options);
		return $q . $ret;
	} // fn

	/**
	 * returns array of items for listView display in yui-ext Grid
	 */
	function getListItemsForEmailXML($folderId, $page = 1, $pageSize = 10, $sort = '', $direction='') {
		global $timedate;
		global $current_user;
		global $beanList;
		global $sugar_config;
		global $app_strings;

		$this->retrieve($folderId);
		$start = ($page - 1) * $pageSize;

		$sort = (empty($sort)) ? $this->defaultSort : $sort;
		if (!in_array(strtolower($direction), array('asc', 'desc'))) $direction = $this->defaultDirection;

		if (!empty($this->hrSortLocal[$sort]))
		{
			$order = " ORDER BY {$this->hrSortLocal[$sort]} {$direction}";
		}
		else
		{
			$order = "";
		}

		if($this->is_dynamic) {
			$r = $this->db->limitQuery(from_html($this->generateSugarsDynamicFolderQuery() . $order), $start, $pageSize);
		} else {
			// get items and iterate through them
            $q = "SELECT
                    emails.id,
                    emails.name,
                    emails.date_sent,
                    emails.status,
                    emails.type,
                    emails.flagged,
                    emails.reply_to_status,
                    emails_text.from_addr,
                    emails_text.to_addrs,
                    'Emails' polymorphic_module
                FROM
                    emails
                JOIN
                    folders_rel
                ON
                    emails.id = folders_rel.polymorphic_id
                JOIN
                    emails_text
                on
                    emails.id = emails_text.email_id
                    ";
            $this->emailBean->addVisibilityFrom($q, array('where_condition' => true));
            $q .= "
                WHERE
                    folders_rel.folder_id = '{$folderId}'
                    AND folders_rel.deleted = 0
                    AND emails.deleted = 0 "
            ;
            $this->emailBean->addVisibilityWhere($q, array('where_condition' => true));
			if ($this->is_group) {
				$q = $q . " AND ((emails.assigned_user_id is null or emails.assigned_user_id = '') OR (emails.intent = 'createcase'))";
			}
			$r = $this->db->limitQuery($q . $order, $start, $pageSize);
		}

		$return = array();

		$email = BeanFactory::newBean('Emails'); //Needed for email specific functions.

		while($a = $this->db->fetchByAssoc($r)) {

			$temp = array();
			$temp['flagged'] = (is_null($a['flagged']) || $a['flagged'] == '0') ? '' : 1;
			$temp['status'] = (is_null($a['reply_to_status']) || $a['reply_to_status'] == '0') ? '' : 1;
			$temp['from']	= preg_replace('/[\x00-\x08\x0B-\x1F]/', '', $a['from_addr']);
			$temp['subject'] = $a['name'];
			$temp['date']	= $timedate->to_display_date_time($this->db->fromConvert($a['date_sent'], 'datetime'));
			$temp['uid'] = $a['id'];
			$temp['mbox'] = 'sugar::'.$a['polymorphic_module'];
			$temp['ieId'] = $folderId;
			$temp['site_url'] = $sugar_config['site_url'];
			$temp['seen'] = ($a['status'] == 'unread') ? 0 : 1;
			$temp['type'] = $a['type'];
			$temp['hasAttach'] = $email->doesImportedEmailHaveAttachment($a['id']);
			$temp['to_addrs'] = preg_replace('/[\x00-\x08\x0B-\x1F]/', '', $a['to_addrs']);
			$return[] = $temp;
		}


		$metadata = array();
		$metadata['mbox'] = $app_strings['LBL_EMAIL_SUGAR_FOLDER'].': '.$this->name;
		$metadata['ieId'] = $folderId;
		$metadata['name'] = $this->name;
		$metadata['unreadChecked'] = ($current_user->getPreference('showUnreadOnly', 'Emails') == 1) ? 'CHECKED' : '';
		$metadata['out'] = $return;

		return $metadata;
	}

	function getCountItems ( $folderId ) {
		global $current_user ;
		global $beanList ;
		global $sugar_config ;
		global $app_strings ;

		$this->retrieve ( $folderId ) ;
		if ($this->is_dynamic) {
	    	$pattern = '/SELECT(.*?)(\s){1}FROM(\s){1}/is';  // ignores the case
	    	$replacement = 'SELECT count(*) c FROM ';
	    	$modified_select_query = preg_replace($pattern, $replacement, $this->generateSugarsDynamicFolderQuery(), 1);
	    	$r = $this->db->query ( from_html ( $modified_select_query )) ;
		} else {
			// get items and iterate through them
            $q = "SELECT
                    count(*) c
                FROM
                    folders_rel
                JOIN
                    emails
                ON
                    emails.id = folders_rel.polymorphic_id
                    ";
            $this->emailBean->addVisibilityFrom($q, array('where_condition' => true));
            $q .= "
                WHERE
                    folder_id = '{$folderId}'
                    AND folders_rel.deleted = 0
                    AND emails.deleted = 0 "
            ;
            $this->emailBean->addVisibilityWhere($q, array('where_condition' => true));
			if ($this->is_group) {
				$q .= " AND ((emails.assigned_user_id is null or emails.assigned_user_id = '') OR (emails.intent = 'createcase'))";
			}
			$r = $this->db->query ( $q ) ;
		}

		$a = $this->db->fetchByAssoc($r);
		return $a['c'];
	}

    function getCountUnread ( $folderId ) {
        global $current_user ;
        global $beanList ;
        global $sugar_config ;
        global $app_strings ;

        $this->retrieve ( $folderId ) ;
        if ($this->is_dynamic) {
	    	$pattern = '/SELECT(.*?)(\s){1}FROM(\s){1}/is';  // ignores the case
	    	$replacement = 'SELECT count(*) c FROM ';
	    	$modified_select_query = preg_replace($pattern, $replacement, $this->generateSugarsDynamicFolderQuery(), 1);
	    	$r = $this->db->query (from_html($modified_select_query) . " AND emails.status = 'unread'") ;
        } else {
            // get items and iterate through them
            $q = "SELECT
                    count(*) c
                FROM
                    folders_rel fr
                JOIN
                    emails
                on
                    fr.folder_id = '{$folderId}'
				";
            $this->emailBean->addVisibilityFrom($q, array('where_condition' => true));
            $q .= "
                    AND fr.deleted = 0
                    AND fr.polymorphic_id = emails.id
                    AND emails.status = 'unread'
                    AND emails.deleted = 0 "
            ;
            $this->emailBean->addVisibilityWhere($q, array('where_condition' => true));
            if ($this->is_group) {
                $q .= " AND ((emails.assigned_user_id is null or emails.assigned_user_id = '') OR (emails.intent = 'createcase'))";
            }
            $r = $this->db->query ( $q ) ;
        }

		$a = $this->db->fetchByAssoc($r);
        return $a['c'];
    }


	/**
	 * Convenience method, pass a SugarBean and User to this to add anything to a given folder
	 */
	function addBean($bean, $user=null) {
		if(empty($bean->id) || empty($bean->module_dir)) {
			$GLOBALS['log']->fatal("*** FOLDERS: addBean() got empty bean - not saving");
			return false;
		} elseif(empty($this->id)) {
			$GLOBALS['log']->fatal("*** FOLDERS: addBean() is trying to save to a non-saved or non-existent folder");
			return false;
		}

        $this->db->getConnection()->insert('folders_rel', [
            'id' => Uuid::uuid1(),
            'folder_id' => $this->id,
            'polymorphic_module' => $bean->module_dir,
            'polymorphic_id' => $bean->id,
            'deleted' => 0,
        ]);

		return true;
	}

	/**
	 * Builds up a metacollection of user/group folders to be passed to processor methods
	 * @param object User object, defaults to $current_user
	 * @return array Array of abstract folder objects
	 */
	function retrieveFoldersForProcessing($user, $subscribed=true) {
		global $sugar_config;
		global $current_language, $current_user;

		$emails_mod_strings = return_module_language($current_language, "Emails");
		$myEmailTypeString = 'inbound';
		$myDraftsTypeString = 'draft';
		$mySentEmailTypeString = 'sent';

		if(empty($user)) {
			global $current_user;
			$user = $current_user;
		}
        $teamSecurityClause = '';

		$bean = new SugarBean();
		$bean->disable_row_level_security = false;
		$bean->add_team_security_where_clause($teamSecurityClause,'f');
		$bean->disable_row_level_security = true;

        // need space in coalesce for oracle to avoid to null conversion
        $rootWhere = "AND (f.parent_folder IS NULL OR f.parent_folder = '')";
        $parameters = [];
		if($subscribed) {
            $q = $this->coreSubscribed . $teamSecurityClause . $this->coreWhereSubscribed . '? '
                . $rootWhere . $this->coreOrderBy;
            $parameters[] = $user->id;
        } else {
			$q = $this->core.$teamSecurityClause.$this->coreWhere.$rootWhere.$this->coreOrderBy;
		}
        $stmt = $this->db->getConnection()->executeQuery($q, $parameters);
		$return = array();

		$found = array();
        while ($a = $stmt->fetch()) {
            $a['created_by'] = $this->db->fromConvert($a['created_by'], 'id');

			if ((($a['folder_type'] == $myEmailTypeString) ||
				($a['folder_type'] == $myDraftsTypeString) ||
				($a['folder_type'] == $mySentEmailTypeString)) &&
				($a['created_by'] != $current_user->id)) {

				continue;
			} // if
			if (!isset($found[$a['id']])) {
                $found[$a['id']] = true;
			    $return[] = $a;
			}
		}
		return $return;
	}
    /**
	 * Preps object array for async call from user's Settings->Folders
	 */
	function getGroupFoldersForSettings($focusUser=null) {
		global $app_strings;

		$grp = array();

		$folders = $this->retrieveFoldersForProcessing($focusUser, false);
		$subscriptions = $this->getSubscriptions($focusUser);

		foreach($folders as $a) {
			$a['selected'] = (in_array($a['id'], $subscriptions)) ? true : false;
            $a['origName'] = $a['name'];

			if($a['is_group'] == 1)
				if ($a['deleted'] != 1)
					$grp[] = $a;
		}

		return $grp;
	}
	/**
	 * Preps object array for async call from user's Settings->Folders
	 */
	function getFoldersForSettings($focusUser=null) {
		global $app_strings;

		$user = array();
		$grp = array();
		$user[] = array('id' => '', 'name' => $app_strings['LBL_NONE'], 'has_child' => 0, 'is_group' => 0, 'selected' => false);
		$grp[] = array('id' => '', 'name' => $app_strings['LBL_NONE'], 'has_child' => 0, 'is_group' => 1, 'selected' => false, 'origName' => "");

		$folders = $this->retrieveFoldersForProcessing($focusUser, false);
		$subscriptions = $this->getSubscriptions($focusUser);

		foreach($folders as $a) {
			$a['selected'] = (in_array($a['id'], $subscriptions)) ? true : false;
            $a['origName'] = $a['name'];
            if( isset($a['dynamic_query']) )
                unset($a['dynamic_query']);
			if($a['is_group'] == 1) {
				$grp[] = $a;
			} else {
				$user[] = $a;
			}

			if($a['has_child'] == 1) {
                $getChildrenStmt = $this->db->getConnection()->executeQuery(
                    $this->core . $this->coreWhere . 'AND parent_folder = ?',
                    [$a['id']]
                );
                while ($aGetChildren = $getChildrenStmt->fetch()) {
					if($a['is_group']) {
						$this->_depth = 1;
						$grp = $this->getFoldersChildForSettings($aGetChildren, $grp, $subscriptions);
					} else {
						$this->_depth = 1;
						$user = $this->getFoldersChildForSettings($aGetChildren, $user, $subscriptions);
					}
				}
			}
		}

		$ret = array(
			'userFolders'	=> $user,
			'groupFolders'	=> $grp,
		);
		return $ret;
	}

	function getFoldersChildForSettings($a, $collection, $subscriptions) {
		$a['selected'] = (in_array($a['id'], $subscriptions)) ? true : false;
		$a['origName'] = $a['name'];

		if(isset($a['dynamic_query']))
		{
		   unset($a['dynamic_query']);
		}

		for($i=0; $i<$this->_depth; $i++)
		{
			$a['name'] = ".".$a['name'];
		}

		$collection[] = $a;

		if($a['has_child'] == 1) {
			$this->_depth++;
            $getChildrenStmt = $this->db->getConnection()->executeQuery(
                $this->core . $this->coreWhere . 'AND parent_folder = ?',
                [$a['id']]
            );
            while ($aGetChildren = $getChildrenStmt->fetch()) {
				$collection = $this->getFoldersChildForSettings($aGetChildren, $collection, $subscriptions);
			}
		}

		return $collection;
	}

	/**
	 * Returns the number of "new" items (based on passed criteria)
	 * @param string id ID of folder
	 * @param array criteria
	 * 		expected:
	 * 		array('field' => 'status',
	 * 				'value' => 'unread');
	 * @param array
	 * @return int
	 */
	function getCountNewItems($id, $criteria, $folder) {
		global $current_user;

		$sugarFolder = new SugarFolder();
		return $sugarFolder->getCountUnread($id);
	}

	/**
	 * Collects, sorts, and builds tree of user's folders
	 * @param objec $rootNode Reference to tree root node
	 * @param array $folderStates User pref folder open/closed states
	 * @param object $user Optional User in focus, default current_user
	 * @return array
	 */
	function getUserFolders(&$rootNode, $folderStates, $user=null, $forRefresh=false) {
		if(empty($user)) {
			global $current_user;
			$user = $current_user;
		}
		global $mod_strings;
		$folders = $this->retrieveFoldersForProcessing($user, true);
		$subscriptions = $this->getSubscriptions($user);

		$refresh = ($forRefresh) ? array() : null;

		if(!is_array($folderStates)) {
			$folderStates = array();
		}

		foreach($folders as $a) {
			if ($a['deleted'] == 1)
				continue;
			$label = ($a['name'] == 'My Email' ? $mod_strings['LNK_MY_INBOX'] : $a['name']);

			$unseen = $this->getCountNewItems($a['id'], array('field' => 'status', 'value' => 'unread'), $a);

			$folderNode = new ExtNode($a['id'], $label);
			$folderNode->dynamicloadfunction = '';
			$folderNode->expanded = false;

			if(array_key_exists('Home::'.$a['id'], $folderStates)) {
				if($folderStates['Home::'.$a['id']] == 'open') {
					$folderNode->expanded = true;
				}
			}
			$nodePath = "Home::".$folderNode->_properties['id'];

			$folderNode->dynamic_load = true;
	        $folderNode->set_property('ieId', 'folder');
	        $folderNode->set_property('is_group', ($a['is_group'] == 1) ? 'true' : 'false');
	        $folderNode->set_property('is_dynamic', ($a['is_dynamic'] == 1) ? 'true' : 'false');
	        $folderNode->set_property('mbox', $folderNode->_properties['id']);
	        $folderNode->set_property('unseen', $unseen);
	        $folderNode->set_property('id', $a['id']);
	        $folderNode->set_property('folder_type', $a['folder_type']);
	        $folderNode->set_property('children', array());

			if(in_array($a['id'], $subscriptions) && $a['has_child'] == 1) {
                $getChildrenStmt = $this->db->getConnection()->executeQuery(
                    $this->core . $this->coreWhere . 'AND parent_folder = ?',
                    [$a['id']]
                );
                while ($aGetChildren = $getChildrenStmt->fetch()) {
                    if (in_array($aGetChildren['id'], $subscriptions)) {
						$folderNode->add_node($this->buildTreeNodeFolders($aGetChildren, $nodePath, $folderStates, $subscriptions));
					}
				}
			}
			$rootNode->add_node($folderNode);
		}

		/* the code below is called only by Settings->Folders when selecting folders to subscribe to */
		if($forRefresh) {
			$metaNode = array();

			if(!empty($rootNode->nodes)) {
				foreach($rootNode->nodes as $node) {
					$metaNode[] = $this->buildTreeNodeRefresh($node, $subscriptions);
				}
			}
			return $metaNode;
		}
	}

	/**
	 * Builds up a metanode for folder refresh (Sugar folders only)
	 */
	function buildTreeNodeRefresh($folderNode, $subscriptions) {
		$metaNode = $folderNode->_properties;
		$metaNode['expanded'] = $folderNode->expanded;
		$metaNode['text'] = $folderNode->_label;
		if($metaNode['is_group'] == 'true') {
			$metaNode['cls'] = 'groupFolder';
		} else {
		    $metaNode['cls'] = 'sugarFolder';
		}
		$metaNode['id'] = $folderNode->_properties['id'];
		$metaNode['children'] = array();
		$metaNode['type'] = 1;
		$metaNode['leaf'] = false;
		$metaNode['isTarget'] = true;
		$metaNode['allowChildren'] = true;

		if(!empty($folderNode->nodes)) {
			foreach($folderNode->nodes as $node) {
				if(in_array($node->_properties['id'], $subscriptions))
					$metaNode['children'][] = $this->buildTreeNodeRefresh($node, $subscriptions);
			}
		}
		return $metaNode;
	}

	/**
	 * Builds children nodes for folders for TreeView
	 * @return $folderNode TreeView node
	 */
	function buildTreeNodeFolders($a, $nodePath, $folderStates, $subscriptions) {
		$label = $a['name'];
		global $mod_strings;
		if($a['name'] == 'My Drafts') {
			$label = $mod_strings['LBL_LIST_TITLE_MY_DRAFTS'];
		}
		if($a['name'] == 'Sent Emails') {
			$label = $mod_strings['LBL_LIST_TITLE_MY_SENT'];
		}
		$unseen = $this->getCountNewItems($a['id'], array('field' => 'status', 'value' => 'unread'), $a);

		$folderNode = new ExtNode($a['id'], $label);
		$folderNode->dynamicloadfunction = '';
		$folderNode->expanded = false;

		$nodePath .= "::{$a['id']}";

		if(array_key_exists($nodePath, $folderStates)) {
			if($folderStates[$nodePath] == 'open') {
				$folderNode->expanded = true;
			}
		}

		$folderNode->dynamic_load = true;
        $folderNode->set_property('click', "SUGAR.email2.listView.populateListFrameSugarFolder(YAHOO.namespace('frameFolders').selectednode, '{$a['id']}', 'false');");
        $folderNode->set_property('ieId', 'folder');
        $folderNode->set_property('mbox', $a['id']);
		$folderNode->set_property('is_group', ($a['is_group'] == 1) ? 'true' : 'false');
        $folderNode->set_property('is_dynamic', ($a['is_dynamic'] == 1) ? 'true' : 'false');
        $folderNode->set_property('unseen', $unseen);
	    $folderNode->set_property('folder_type', $a['folder_type']);

		if(in_array($a['id'], $subscriptions) && $a['has_child'] == 1) {
            $getChildrenStmt = $this->db->getConnection()->executeQuery(
                $this->core . $this->coreWhere . 'AND parent_folder = ? ' . $this->coreOrderBy,
                [$a['id']]
            );

            while ($aGetChildren = $getChildrenStmt->fetch()) {
				$folderNode->add_node($this->buildTreeNodeFolders($aGetChildren, $nodePath, $folderStates, $subscriptions));
			}
		}
		return $folderNode;
	}

	/**
	 * Flags a folder as deleted
	 * @return bool True on success
	 */
	function delete() {
		global $current_user;

		if(!empty($this->id)) {
			if($this->has_child) {
				$this->deleteChildrenCascade($this->id);
			}
            $qb = $this->db->getConnection()->createQueryBuilder();
            $qb->update('folders')
                ->set('deleted', 1)
                ->where($qb->expr()->eq('id', $qb->createPositionalParameter($this->id)));
            if (!$current_user->isAdmin()) {
                $qb->andWhere($qb->expr()->eq('created_by', $current_user->id));
            }
            $qb->execute();
			return true;
		}
		return false;
	}

	/**
	 * Deletes all children in a cascade
	 * @param string $id ID of parent
	 * @return bool True on success
	 */
	function deleteChildrenCascade($id) {
		global $current_user;

        $conn = $this->db->getConnection();
		$canContinue = true;
        $count = $conn->executeQuery(
            'SELECT count(*) FROM inbound_email WHERE groupfolder_id = ? and deleted = 0',
            [$id]
        )->fetchColumn();
        if ($count > 0) {
			return false;
		} // if

        $count = $conn->executeQuery(
            "SELECT count(*) c FROM folders_rel where polymorphic_module = 'Emails' and folder_id = ? and deleted = 0",
            [$id]
        )->fetchColumn();
        if ($count > 0) {
			return false;
		} // if

        $doesFolderHaveChild = $conn->executeQuery('SELECT has_child FROM folders WHERE id = ?', [$id])->fetchColumn();
        if ($doesFolderHaveChild == 1) {
            $subFolderStmt = $conn->executeQuery(
                'SELECT id FROM folders WHERE parent_folder = ?',
                [$id]
            );
            while ($subFolder = $subFolderStmt->fetch()) {
                $canContinue = $this->deleteChildrenCascade($subFolder['id']);
            }
        }

		if ($canContinue) {
            // flag deleted
            $update = $conn->createQueryBuilder()
                ->update('folders')
                ->set('deleted', 1);
            $update->where($update->expr()->eq('id', $update->createPositionalParameter($id)));
            if (!$current_user->isAdmin()) {
                $update->andWhere(
                    $update->expr()->eq('created_by', $update->createPositionalParameter($current_user->id))
                );
            }
            $update->execute();
            // flag rels
            $conn->update('folders_rel', ['deleted' => 1], ['folder_id' => $id]);
            // delete subscriptions
            $conn->delete('folders_subscriptions', ['folder_id' => $id]);
		}
		return $canContinue;
	}

    /**
     * Return the default mapping values.
     *
     * @return array Mapping key-value pairs.
     */
    protected function getFieldMapping()
    {
        global $current_user;
        return array(
            'created_by' => $current_user->id,
            'modified_by' => $current_user->id,
            'dynamic_query' => $this->dynamic_query,
            'deleted' => 0,
        );
    }

    /**
     * return DB field values according to mapping and object properties
     * @param array $fields
     * @return array
     */
    protected function getFieldValues(array $fields)
    {
        $result = [];
        $mapping = $this->getFieldMapping();
        foreach ($fields as $name) {
            if (array_key_exists($name, $this->fields)) {
                switch (true) {
                    case isset($this->$name):
                        $result[$name] = $this->$name;
                        break;
                    case array_key_exists($name, $mapping):
                        $result[$name] = $mapping[$name];
                        break;
                    default:
                        $result[$name] = null;
                }
            }
        }
        return $result;
    }

	/**
	 * Saves folder
	 * @return bool
	 */
	function save($addSubscriptions = TRUE) {

		if((empty($this->id) && $this->new_with_id == false) || (!empty($this->id) && $this->new_with_id == true))
		{
            if (empty($this->id)) {
                $this->id = Uuid::uuid1();
            }
            $this->db->insertParams($this->table, $this->fields, $this->getFieldValues(array_keys($this->fields)));

			if($addSubscriptions)
			{
			    // create default subscription
			    $this->addSubscriptionsToGroupFolder();
			}
            $this->db->getConnection()->update($this->table, ['has_child' => 1], ['id' => $this->parent_folder]);
		}
		else {
            $values = $this->getFieldValues(array_keys($this->fields));
            unset($values['id'], $values['created_by']);
            $this->db->updateParams($this->table, $this->fields, $values, ['id' => $this->id]);
		}
		return true;
	}

	/**
	 * Add subscriptions to this group folder.
	 *
	 */
	function addSubscriptionsToGroupFolder()
	{
	    global $current_user;

	    $this->createSubscriptionForUser($current_user->id);

	    if ($this->is_group)
	    {
	        $team = BeanFactory::getBean('Teams', $this->team_id);
	        $usersList = $team->get_team_members(true);
	        foreach($usersList as $userObject)
	           $this->createSubscriptionForUser($userObject->id);
	    }
	}



    /**
	 * Add subscriptions to this group folder.
	 *
	 */
	function createSubscriptionForUser($user_id)
	{
        $this->db->insertParams(
            $this->folderSubscriptionTable,
            $this->folderSubscriptionFields,
            [
                'id' => Uuid::uuid1(),
                'folder_id' => $this->id,
                'assigned_user_id' => $user_id,
            ]
        );
	}


	function updateFolder($fields) {
		global $current_user;

		$this->dynamic_query = $this->db->quote($this->dynamic_query);
		$id = $fields['record'];
		$name = $fields['name'];
		$parent_folder = $fields['parent_folder'];
		$team_id = $fields['team_id'];
		$team_set_id = $fields['team_set_id'];
		// first do the retrieve
		$this->retrieve($id);
		if ($this->has_child) {
			$childrenArray = array();
			$this->findAllChildren($id, $childrenArray);
			if (in_array($parent_folder, $childrenArray)) {
				return array('status' => "failed", 'message' => "Can not add this folder to its children");
			}
		}
		// update has_child to 0 for this parent folder if this is the only child it has
        $count = $this->db->getConnection()->executeQuery(
            sprintf('SELECT COUNT(*) FROM %s WHERE parent_folder = ? AND deleted = 0', $this->table),
            [$this->parent_folder]
        )->fetchColumn();
        if ($count == 1) {
            $this->db->getConnection()->update($this->table, ['has_child' => 0], ['id' => $this->parent_folder]);
		} // if


		$this->name = $name;
		$this->parent_folder = $parent_folder;
		$this->team_id = $team_id;
		$this->team_set_id = $team_set_id;

        $values = $this->getFieldValues([
            'dynamic_query',
            'parent_folder',
            'team_set_id',
            'team_id',
            'modified_by',
        ]);
        $this->db->updateParams($this->table, $this->fields, $values, ['id' => $this->id]);
		if (!empty($this->parent_folder)) {
            $this->db->getConnection()->update($this->table, ['has_child' => 1], ['id' => $this->parent_folder]);
		} // if
		return array('status' => "done");

	} // fn

	function findAllChildren($folderId, &$childrenArray) {
        $conn = $this->db->getConnection();
        $doesFolderHaveChild = $conn
            ->executeQuery('SELECT has_child FROM folders WHERE id = ?', [$folderId])
            ->fetchColumn();
        if ($doesFolderHaveChild == 1) {
            $subFolderStmt = $conn->executeQuery(
                'SELECT id FROM folders WHERE parent_folder = ? AND deleted = 0',
                [$folderId]
            );
            while ($a2 = $subFolderStmt->fetch()) {
				$childrenArray[] = $a2['id'];
				$this->findAllChildren($a2['id'], $childrenArray);
			} // while
		} // if

	} // fn

	/**
	 * Retrieves and populates object
	 * @param string $id ID of folder
	 * @return bool True on success
	 */
	function retrieve($id) {
        $data = $this->db->getConnection()->executeQuery(
            'SELECT * FROM folders WHERE id = ? AND deleted = 0',
            [$id]
        )->fetch();
        if ($data) {
            foreach ($data as $name => $value) {
                $this->$name = $this->db->fromConvert($value, $this->fields[$name]['type']);
            }
            return true;
        } else {
            return false;
        }
    }
}
