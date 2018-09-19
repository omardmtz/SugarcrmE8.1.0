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
 * Prepare data for KBDocument module conversion.
 */
class SugarUpgradeKBPrepare extends UpgradeScript
{
    public $order = 2000;
    public $type = self::UPGRADE_DB;

    /**
     * {@inheritDoc}
     */
    public function run()
    {
        if (version_compare($this->from_version, '7.7.0', '<')) {
            $this->preCheckMenu();
            $newpath = !empty($this->manifest['copy_files']['from_dir']) ?
                $this->context['extract_dir'] . '/' . $this->manifest['copy_files']['from_dir'] :
                $this->context['source_dir'];
            $script = new PrepareKBDocument(
                $this->context['source_dir'],
                $newpath,
                array($this, 'log')
            );
            if ($script->run()) {
                $this->upgrader->fileToDelete($script->getFilesToDelete(), $this);
                return true;
            }
            return false;
        }
    }

    /**
     * Check whether we need to add KB to menu after upgrade.
     */
    protected function preCheckMenu()
    {
        require_once('modules/MySettings/TabController.php');
        $tc = new TabController();
        $tabs = $tc->get_system_tabs();
        if (isset($tabs['KBDocuments'])) {
            $this->upgrader->state['addKBToMenu'] = true;
        }
    }
}

/**
 * Convert KBDocument modules to KBOLDDocument.
 * Class convertKBDocument
 */
class PrepareKBDocument
{
    /**
     * Files to delete after upgrade.
     * @var array
     */
    protected $fileToDelete;

    /**
     * Callback for log.
     * @var callable
     */
    protected $logCallback;

    /**
     * Path to work with,
     * @var String
     */
    protected $path;

    /**
     * Path contains new files.
     * @var String
     */
    protected $newpath;

    public function __construct($path, $newpath, $logCallback)
    {
        $this->path = $path;
        $this->newpath = $newpath;
        $this->logCallback = $logCallback;
        $this->db = DBManagerFactory::getInstance();
        $this->upgrader->fileToDelete = array();
    }

    /**
     * Return files for deletion.
     * @return array
     */
    public function getFilesToDelete()
    {
        return $this->upgrader->fileToDelete;
    }

    /**
     * Call external logger.
     * @param String $msg
     */
    public function log($msg)
    {
        call_user_func_array($this->logCallback, array($msg));
    }

    /**
     * Path to find files for delete.
     * @var array
     */
    protected $pathsToFindFiles = array(
        '/modules/KBArticles',
        '/modules/KBContents',
        '/modules/KBContentTemplates',
        '/modules/KBDocuments',
    );

    /**
     * Temporary table to store data in.
     * @var array
     */
    protected $tempTables = array(
        'prepKBDoc' => array(
            'users' => array(
                'assigned_user_id' => 'id',
                'assigned_user_name' => 'user_name',
                'kbdoc_approver_id' => 'id',
                'kbdoc_approver_name' => 'user_name',
            ),
            'kbcontents' => array(
                'body' => 'kbdocument_body',
            ),
            'kbdocuments_views_ratings' => array(
                'views_number' => 'views_number'
            ),
            'kbdocuments' => array(
                'id' => 'id',
                'name' => 'kbdocument_name',
                'active_date' => 'active_date',
                'exp_date' => 'exp_date',
                'status_id' => 'status_id',
                'date_entered' => 'date_entered',
                'date_modified' => 'date_modified',
                'deleted' => 'deleted',
                'is_external_article' => 'is_external_article',
                'modified_user_id' => 'modified_user_id',
                'created_by' => 'created_by',
                'team_id' => 'team_id',
                'team_set_id' => 'team_set_id',
                'kbscase_id' => 'case_id',
                'parent_type' => 'parent_type',
                'parent_id' => 'parent_id',
            ),
        ),
        'prepKBAtt' => array(
            'document_revisions' => array(
                'id' => 'id',
                'filename' => 'filename',
                'file_mime_type' => 'file_mime_type',
            ),
            'kbdocument_revisions' => array(
                'kbdocument_id' => 'kbdocument_id'
            )
        ),
        'prepKBTag' => array(
            'kbtags' => array(
                'id' => 'id',
                'tag_name' => 'tag_name',
                'parent_tag_id' => 'parent_tag_id'
            )
        ),
        'prepKBDocTag' => array(
            'kbdocuments_kbtags' => array(
                'kbdocument_id' => 'kbdocument_id',
                'kbtag_id' => 'kbtag_id',
            )
        ),

    );

    /**
     * Run conversion.
     */
    public function run()
    {

        if ($this->path != $this->newpath) {
            $newFiles = array();
            $oldFiles = array();
            $tmpFiles = array();
            foreach ($this->pathsToFindFiles as $path) {
                $oldFiles = $this->getFiles($this->path . $path, $oldFiles);
                $newFiles = $this->getFiles($this->newpath . $path, $newFiles);
            }

            $currentRealPath = realpath($this->path);
            $newRealPath = realpath($this->newpath);

            foreach ($newFiles as $key => $value) {
                $tmpFiles[$key] = str_replace($newRealPath, $currentRealPath, $value);
            }

            $files = array_diff($oldFiles, $tmpFiles);
            $files[$this->path . '/KBDocumentKBTags'] = $this->path . '/KBDocumentKBTags';
            $files[$this->path . '/KBDocumentRevisions'] = $this->path . '/KBDocumentRevisions';
            $this->upgrader->fileToDelete = $files;
        }
        if (!$this->prepareTables()) {
            return $this->error("Can't create temporary tables for KB conversion");
        }
        return true;
    }

    /**
     * Get files to work with,
     * @param string $path
     * @param array $files
     * @return array
     */
    public function getFiles($path, $files)
    {
        if (!file_exists($path) && !is_dir($path)) {
            return $files;
        }

        $iterator = new \DirectoryIterator($path);

        foreach ($iterator as $info) {
            if ($info->isFile()) {
                $files[$info->getPathname()] = $info->getRealPath();
            } elseif (!$info->isDot() || strpos($info->getFilename(), '.') !== 0) {
                $list = $this->getFiles($info->getPathname(), $files);
                if (!empty($list)) {
                    $files = array_merge($files, $list);
                }
            }
        }
        return $files;
    }

    /**
     * Prepare tables and copy data in it.
     * @return bool
     */
    public function prepareTables()
    {
        $result = true;
        //Prepare custom table.
        $customTable = 'kbdocuments_cstm';
        if ($this->db->tableExists($customTable)) {
            $this->tempTables['prepKBCustom'] = array(
                $customTable => array(),
            );
            $cols = $this->db->get_columns($customTable);
            foreach (array_keys($cols) as $key) {
                $this->tempTables['prepKBCustom'][$customTable][$key] = $key;
            }
        }

        foreach ($this->tempTables as $table => $map) {
            if ($this->db->tableExists($table)) {
                $this->db->dropTableName($table);
            }
            $columns = array();
            foreach ($map as $oldtable => $oldcolumns) {
                $cols = $this->db->get_columns($oldtable);
                foreach ($oldcolumns as $name => $key) {
                    $columns[$name] = $cols[$key];
                    $columns[$name]['name'] = $name;
                }
            }
            if (!$this->db->createTableParams($table, $columns, array())) {
                $this->log("Can't create table {$table}");
                return false;
            }
            $result = $result && $this->copyData($table);
        }
        return $result;
    }

    /**
     * Copy data into temporary table.
     * @param String $table Table to copy data into,
     * @return bool result of the operation, true if all is fine.
     */
    public function copyData($table)
    {
        $query = '';
        switch ($table) {
            case 'prepKBDoc':
                $select = "SELECT
                    jt0.id assigned_user_id ,
                    jt0.user_name assigned_user_name ,
                    jt1.id kbdoc_approver_id ,
                    jt1.user_name kbdoc_approver_name ,
                    cont.kbdocument_body body ,
                    kvr.views_number views_number ,
                    kbdocuments.id ,
                    kbdocuments.kbdocument_name name,
                    kbdocuments.active_date ,
                    kbdocuments.exp_date ,
                    kbdocuments.status_id ,
                    kbdocuments.date_entered date_entered ,
                    kbdocuments.date_modified ,
                    kbdocuments.deleted ,
                    kbdocuments.is_external_article ,
                    kbdocuments.modified_user_id ,
                    kbdocuments.created_by,
                    kbdocuments.team_id ,
                    kbdocuments.team_set_id ,
                    kbdocuments.case_id kbscase_id
                FROM
                    kbdocuments kbdocuments
                    LEFT JOIN kbdocuments_views_ratings kvr
                        ON kbdocuments.id = kvr.kbdocument_id
                    LEFT JOIN users jt0
                        ON jt0.id = kbdocuments.assigned_user_id
                        AND jt0.deleted = 0
                    LEFT JOIN users jt1
                        ON jt1.id = kbdocuments.kbdoc_approver_id
                        AND jt1.deleted = 0
                    LEFT JOIN kbdocument_revisions rev
                        ON rev.kbdocument_id = kbdocuments.id
                        AND rev.latest = 1
                        AND rev.deleted = 0
                    LEFT JOIN kbcontents cont on rev.kbcontent_id = cont.id
                ORDER BY
                    kbdocuments.date_entered";
                $query =
                    "INSERT INTO {$table} (
                        assigned_user_id,
                        assigned_user_name,
                        kbdoc_approver_id,
                        kbdoc_approver_name,
                        body,
                        views_number,
                        id,
                        name,
                        active_date,
                        exp_date,
                        status_id,
                        date_entered,
                        date_modified,
                        deleted,
                        is_external_article,
                        modified_user_id,
                        created_by,
                        team_id,
                        team_set_id,
                        kbscase_id
                    )
                    {$select}";
                break;
            case 'prepKBAtt':
                $select = "SELECT
                    dr.id,
                    dr.filename,
                    dr.file_mime_type,
                    kdr.kbdocument_id kbdocument_id
                FROM
                    document_revisions dr
                    JOIN kbdocument_revisions kdr on kdr.document_revision_id = dr.id
                        AND kdr.deleted = 0
                WHERE
                    dr.file_mime_type IS NOT NULL
                    AND dr.deleted = 0";
                $query =
                    "INSERT INTO {$table} (
                        id,
                        filename,
                        file_mime_type,
                        kbdocument_id
                    )
                    {$select}";
                break;
            case 'prepKBTag':
                $select = "SELECT
                    id,
                    tag_name,
                    parent_tag_id
                FROM kbtags
                WHERE kbtags.deleted = 0";
                $query =
                    "INSERT INTO {$table} (
                        id,
                        tag_name,
                        parent_tag_id
                    )
                    {$select}";
                break;
            case 'prepKBDocTag':
                $select = "SELECT
                    kbdocument_id,
                    kbtag_id
                FROM kbdocuments_kbtags
                WHERE kbdocuments_kbtags.deleted = 0";
                $query =
                    "INSERT INTO {$table} (
                        kbdocument_id,
                        kbtag_id
                    )
                    {$select}";
                break;
            case 'prepKBCustom':
                $columns = implode(',', array_keys($this->tempTables['prepKBCustom']['kbdocuments_cstm']));
                $select = "SELECT $columns FROM kbdocuments_cstm";
                $query = "INSERT INTO {$table} ({$columns}) {$select}";
                break;
        }
        if (empty($query)) {
            $this->log("Can't generate query for {$table}");
            return false;
        }
        $this->db->query($query);
        return true;
    }
}
