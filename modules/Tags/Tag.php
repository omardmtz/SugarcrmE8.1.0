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
 * The Tag class handles operations related to the Tags functionality
 **/
class Tag extends Basic
{
    public $module_dir = 'Tags';
    public $object_name = 'Tag';
    public $table_name = 'tags';
    public $new_schema = true;
    public $importable = true;

    /**
     * Flag that indicates of a secondary uniqueness check needs to be done
     * during save
     *
     * @var boolean
     */
    public $verifiedUnique = false;

    public function __construct()

    {
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function save($check_notify = false)
    {
        global $current_user;

        // We need a tag name or really what's the point?
        $this->name = trim($this->name);
        if (empty($this->name)) {
            return false;
        }

        // Get the lowercase tag name, as we will need this no matter what
        $nameLower = strtolower($this->name);

        // Verify uniqueness of the tag if needed
        $this->verifyUniqueness($nameLower);

        // Handle setting the assigned user if not already set
        if ($this->assigned_user_id === null  && !$this->isUpdate()) {
            $this->assigned_user_id = $current_user->id;
        }

        // For searching making sure we lowercase the name to name_lower
        $this->name_lower = $nameLower;
        return parent::save($check_notify);
    }

    /**
     * Verifies the uniqueness of the tag
     *
     * @param string $nameLower The lowercased tag name
     * @return void
     * @throws SugarApiExceptionNotAuthorized
     */
    public function verifyUniqueness($nameLower)
    {
        // Handle uniqueness checking early
        if (!$this->verifiedUnique) {
            // Handle check return value defaults
            $result = array();

            // Uniqueness needs to be checked for two cases...
            // 1. New tags must be unique
            // 2. Existing tags cannot be edited to an existing tag
            if (!$this->isUpdate() || $nameLower != $this->name_lower) {
                // Grab any tag records that might have this same name that are
                // not deleted
                $q = new SugarQuery();

                // We really only need to check to see if there is a single row
                $q->select(array('id'));
                $q->from($this)
                  ->where()
                  ->equals('name_lower', $nameLower);
                $result = $q->execute();
            }

            // If there is an id property of the result then we have existing records
            // and need to bomb out now
            if (!empty($result[0]['id'])) {
                throw new SugarApiExceptionNotAuthorized(
                    'EXCEPTION_DUPLICATE_TAG_FOUND',
                    null,
                    $this->module_dir,
                    null,
                    'duplicate_tag'
                );
            }
        }
    }

    /**
     * Gets all the tags for every record id given
     *
     * @param $focus
     * @param $ids array of record ids
     * @return array
     */
    public function getRelatedModuleRecords($focus, $ids)
    {
        // No ids means nothing to do
        // Not use this in Tags module, use only for other modules
        if (empty($ids) || ($focus == null) || ($focus->table_name === 'tags')) {
            return array();
        }

        // We need to make a reasonable assumption here that ids will either be
        // an imploded string of ids or an array of ids. If an array, make it a
        // string of ids.
        if (is_array($ids)) {
            $ids = "'" . implode("','", $ids) . "'";
        }

        $tableAlias = $this->db->getValidDBName($focus->table_name . '_id', false, 'alias');

        $sql = "SELECT tags.id, tags.name, tbr.bean_id as {$tableAlias}";
        $sql .= " FROM tags INNER JOIN tag_bean_rel tbr ON tags.id=tbr.tag_id";
        $sql .= " WHERE tbr.bean_module = '{$focus->module_name}' AND tbr.bean_id in ($ids) AND tbr.deleted=0";
        $sql .= " ORDER BY tags.name_lower ASC";

        $result = $this->db->query($sql);
        $returnArray = array();
        while ($data = $this->db->fetchByAssoc($result)) {
            $returnArray[$data[$tableAlias]][] = array("id" => $data["id"], "name"=>$data["name"]);
        }
        return $returnArray;
    }

    /**
     * Retrieve the list of related tags from the database, given the SugarBean id.
     * @param string $beanId The id of the bean
     * @param string $beanModule The module name
     * @return array
     */
    public function getTagIdsByBeanId($beanId, $beanModule)
    {
        $query = 'SELECT tag_id
            FROM tag_bean_rel
            WHERE bean_id = ?
                AND bean_module = ?
                AND deleted = 0';
        $stmt = $this->db->getConnection()->executeQuery($query, [$beanId, $beanModule]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Retrieve the list of related tags from the database, given a SugarBean.
     * @param Sugarbean $bean
     * @return array
     */
    public function getTagIdsByBean(SugarBean $bean)
    {
        return $this->getTagIdsByBeanId($bean->id, $bean->module_name);
    }

    /**
     * @inheritDoc
     */
    public function mark_deleted($id) {
        //When deleting a tag, also delete the tag relation rows associated with that tag
        $date_modified = $GLOBALS['timedate']->nowDb();

        $sql = "UPDATE tag_bean_rel";
        $sql .= " SET deleted = 1, date_modified = ? ";
        $sql .= " WHERE tag_id= ? ";
        $db = DBManagerFactory::getInstance();
        $conn = $db->getConnection();
        $conn->executeQuery($sql, array($date_modified, $id));
        parent::mark_deleted($id);
    }


    /**
     * @inheritDoc
     */
    public function ACLAccess($view, $context = null)
    {
        if ($view === 'list' || $view === 'view') {
            // for Filters we have 2 values for view - `list` for new tags and
            // `view` for existing tags and we want Tags to be searchable and
            // creatable when creating a new record for any module
            return true;
        }

        return parent::ACLAccess($view, $context);
    }
}
