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
/*********************************************************************************

 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/





// User is used to store Forecast information.
class DocumentRevision extends SugarBean {

	var $id;
	var $document_id;
    var $doc_id;
    var $doc_type;
    var $doc_url;
	var $date_entered;
	var $created_by;
	var $filename;
	var $file_mime_type;
    public $file_size;
	var $revision;
	var $change_log;
	var $document_name;
	var $latest_revision;
	var $file_url;
	var $file_ext;
	var $created_by_name;

	var $img_name;
	var $img_name_bare;
	
	var $table_name = "document_revisions";	
	var $object_name = "DocumentRevision";
	var $module_dir = 'DocumentRevisions';
	var $new_schema = true;
	var $latest_revision_id;

    public $name;
	
	/*var $column_fields = Array("id"
		,"document_id"
		,"date_entered"
		,"created_by"
		,"filename"	
		,"file_mime_type"
		,"revision"
		,"change_log"
		,"file_ext"
		);
*/
	var $encodeFields = Array();

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('');

	// This is the list of fields that are in the lists.
	var $list_fields = Array("id"
		,"document_id"
		,"date_entered"
		,"created_by"
		,"filename"	
		,"file_mime_type"
		,"revision"
		,"file_url"
		,"change_log"
		,"file_ext"
		,"created_by_name"
		);
		
	var $required_fields = Array("revision");
	


	public function __construct() {
		parent::__construct();
		$this->setupCustomFields('DocumentRevisions');  //parameter is module name
		$this->disable_row_level_security =true; //no direct access to this module. 
	}

    public function save($check_notify = false)
    {
        $filePath = "upload://{$this->id}";
        $this->file_size = file_exists($filePath) ? filesize($filePath) : 0;

		$saveRet = parent::save($check_notify);

		//update documents table. (not through save, because it causes a loop)
        // If we don't have a document_id, find it.
        if ( empty($this->document_id) ) {
            $query = "SELECT document_id FROM document_revisions WHERE id = '".$this->db->quote($this->id)."'";
            $ret = $this->db->query($query,true);
            $row = $this->db->fetchByAssoc($ret);
            $this->document_id = $row['document_id'];
        }
		$query = "UPDATE documents set document_revision_id='".$this->db->quote($this->id)."', doc_type='".$this->db->quote($this->doc_type)."', doc_url='".$this->db->quote($this->doc_url)."', doc_id='".$this->db->quote($this->doc_id)."' where id = '".$this->db->quote($this->document_id)."'";
		$this->db->query($query,true);

        return $saveRet;
	}
	function get_summary_text()
	{
		return "$this->filename";
	}

    public function retrieve($id = '-1', $encode = false, $deleted = true)
    {
		$ret = parent::retrieve($id, $encode,$deleted);	
		
		return $ret;
	}

	function is_authenticated()
	{
		return $this->authenticated;
	}

	function fill_in_additional_list_fields() {
		$this->fill_in_additional_detail_fields();
	}

	function fill_in_additional_detail_fields()
	{
		global $theme;
		global $current_language;
		
		parent::fill_in_additional_detail_fields();

        if ( empty($this->id) && empty($this->document_id) && isset($_REQUEST['return_id']) && !empty($_REQUEST['return_id']) ) {
            $this->document_id = $_REQUEST['return_id'];
        }

		//find the document name and current version.
        $query = 'SELECT document_name, revision, document_revision_id 
                  FROM documents, document_revisions WHERE documents.id = ? 
                  AND document_revisions.id = documents.document_revision_id';
        $conn = $this->db->getConnection();
        $stmt = $conn->executeQuery($query, array($this->document_id));
        $row = $stmt->fetch();
		if ($row != null) {
			$this->document_name = $row['document_name'];
            $this->name = $this->document_name;
            $this->document_name = '<a href="index.php?module=Documents&action=DetailView&record='.$this->document_id.'">'.$row['document_name'].'</a>';
			$this->latest_revision = $row['revision'];	
			$this->latest_revision_id = $row['document_revision_id'];

            if ( empty($this->revision) ) {
                $this->revision = $this->latest_revision + 1;
            }
       }
	}
	
	/**
	 * Returns a filename based off of the logical (Sugar-side) Document name and combined with the revision. Tailor
	 * this to needs created by email RFCs, filesystem name conventions, charset conventions etc.
	 * @param string revId Revision ID if not latest
	 * @return string formatted name
	 */
	function getDocumentRevisionNameForDisplay($revId='') {
		global $sugar_config;
		global $current_language;
		
		$localLabels = return_module_language($current_language, 'DocumentRevisions');
		
		// prep - get source Document
		$document = BeanFactory::newBean('Documents');
		
		// use passed revision ID
		if(!empty($revId)) {
			$tempDoc = BeanFactory::getBean('DocumentRevisions', $revId);
		} else {
			$tempDoc = $this;
		}
		
		// get logical name
		$document->retrieve($tempDoc->document_id);
		$logicalName = $document->document_name;
		
		// get revision string
		$revString = '';
		if(!empty($tempDoc->revision)) {
			$revString = "-{$localLabels['LBL_REVISION']}_{$tempDoc->revision}";
		}
		
		// get extension
		$realFilename = $tempDoc->filename;
		$fileExtension_beg = strrpos($realFilename, ".");
		$fileExtension = "";
		
		if($fileExtension_beg > 0) {
			$fileExtension = substr($realFilename, $fileExtension_beg + 1);
		}
		//check to see if this is a file with extension located in "badext"
		foreach($sugar_config['upload_badext'] as $badExt) {
	       	if(strtolower($fileExtension) == strtolower($badExt)) {
		       	//if found, then append with .txt to filename and break out of lookup
		       	//this will make sure that the file goes out with right extension, but is stored
		       	//as a text in db.
		        $fileExtension .= ".txt";
		        break; // no need to look for more
	       	}
        }
		$fileExtension = ".".$fileExtension;
		
		$return = $logicalName.$revString.$fileExtension;
		
		// apply RFC limitations here
		if(mb_strlen($return) > 1024) {
			// do something if we find a real RFC issue
		}
		
		return $return;
	}

	function fill_document_name_revision($doc_id) {

		//find the document name and current version.
		$query = "SELECT documents.document_name, revision FROM documents, document_revisions where documents.id = '$doc_id'";
		$query .= " AND document_revisions.id = documents.document_revision_id";
		$result = $this->db->query($query,true,"Error fetching document details...:");
		$row = $this->db->fetchByAssoc($result);
		if ($row != null) {
			$this->name = $row['document_name'];
			$this->latest_revision = $row['revision'];	
		}	
	}
	
    public function list_view_parse_additional_sections(&$list_form)
    {
		return $list_form;
	}
	
	function get_list_view_data(){
		$revision_fields = $this->get_list_view_array();

		$forecast_fields['FILE_URL'] = $this->file_url;						
		return $revision_fields;
	}

    public static function get_document_revision_name($doc_revision_id)
    {
		if (empty($doc_revision_id)) return null;
		
		$db = DBManagerFactory::getInstance();				
		$query="select revision from document_revisions where id='$doc_revision_id'";
		$result=$db->query($query);
		if (!empty($result)) {
			$row=$db->fetchByAssoc($result);
			if (!empty($row)) {
				return $row['revision'];
			}
		}
		return null;
	}

    public static function get_document_revisions($doc_id)
    {
		$return_array= Array();
		if (empty($doc_id)) return $return_array;
		
		$db = DBManagerFactory::getInstance();				
		$query="select id, revision from document_revisions where document_id='$doc_id' and deleted=0";
		$result=$db->query($query);
		if (!empty($result)) {
			while (($row=$db->fetchByAssoc($result)) != null) {
				$return_array[$row['id']]=$row['revision'];
			}
		}
		return $return_array;
    }

    /**
     * {@inheritdoc}
     *
     * @uses UploadFile::unlink_file()
     */
    public function mark_deleted($id)
    {
        UploadFile::unlink_file($id);
        parent::mark_deleted($id);
    }
}

require_once('modules/Documents/DocumentExternalApiDropDown.php');
