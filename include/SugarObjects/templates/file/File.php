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

require_once('include/formbase.php');

class File extends Basic
{
	public $file_url;
	public $file_url_noimage;

	/**
	 * @see SugarBean::save()
	 */
	public function save($check_notify=false)
	{
		if (!empty($this->uploadfile)) {
			$this->filename = $this->uploadfile;
		}

		return parent::save($check_notify);
 	}

 	/**
	 * @see SugarBean::fill_in_additional_detail_fields()
	 */
	public function fill_in_additional_detail_fields()
 	{
		global $app_list_strings;
		global $img_name;
		global $img_name_bare;

		$this->uploadfile = $this->filename;

		// Bug 41453 - Make sure we call the parent method as well
		parent::fill_in_additional_detail_fields();

		if (!$this->file_ext) {
			$img_name = SugarThemeRegistry::current()->getImageURL(strtolower($this->file_ext)."_image_inline.gif");
			$img_name_bare = strtolower($this->file_ext)."_image_inline";
		}

		//set default file name.
		if (!empty ($img_name) && file_exists($img_name)) {
			$img_name = $img_name_bare;
		}
		else {
			$img_name = "def_image_inline"; //todo change the default image.
		}
		$this->file_url_noimage = $this->id;

        // Handle getting the status if the source of the status id field has
        // changed
        if (!empty($this->status_id)) {
            // Default value for the DLL source for this field
            $ddl_source = 'document_status_dom';
            if (isset($this->field_defs['status_id']['options'])) {
                $ddl_source = $this->field_defs['status_id']['options'];
            }

            $this->status = $app_list_strings[$ddl_source][$this->status_id];
        }
    }

    /**
     * @see SugarBean::fill_in_additional_list_fields()
     */
    public function fill_in_additional_list_fields()
    {
        $this->name = $this->document_name;
    }

	/**
	 * @see SugarBean::retrieve()
	 */
	public function retrieve($id = -1, $encode=true, $deleted=true)
	{
		$ret_val = parent::retrieve($id, $encode, $deleted);

		$this->name = $this->document_name;

		return $ret_val;
	}

    /**
     * Method to delete an attachment
     *
     * @param string $isduplicate
     * @return bool
     */
    public function deleteAttachment($isduplicate = "false")
    {
        if ($this->ACLAccess('edit')) {
            if ($isduplicate == "true") {
                return true;
            }
            $removeFile = "upload://{$this->id}";
        }
        if (file_exists($removeFile)) {
            if (!unlink($removeFile)) {
                $GLOBALS['log']->error("*** Could not unlink() file: [ {$removeFile} ]");
            } else {
                $this->uploadfile = '';$this->uploadfile = '';
                $this->filename = '';
                $this->file_mime_type = '';
                $this->file_ext = '';
                $this->save();
                return true;
            }
        } else {
            $this->uploadfile = '';
            $this->filename = '';
            $this->file_mime_type = '';
            $this->file_ext = '';
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function populateFromRow(array $row, $convert = false)
    {
        $row = parent::populateFromRow($row, $convert);

        if (!empty($this->document_name) && empty($this->name)) {
            $this->name = $this->document_name;
        }

        return $row;
    }

    /**
     * @inheritDoc
     */
    public function getRecordName()
    {
        return isset($this->document_name) ? trim($this->document_name) : '';
    }
}
