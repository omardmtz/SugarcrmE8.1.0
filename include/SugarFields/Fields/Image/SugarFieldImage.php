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

class SugarFieldImage extends SugarFieldBase
{

    function getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex, $searchView = false)
    {
        $displayParams['bean_id'] = 'id';
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        return $this->fetch($this->findTemplate('EditView'));
    }

    function getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex, $searchView = false)
    {
        $displayParams['bean_id'] = 'id';
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        return $this->fetch($this->findTemplate('DetailView'));
    }

    function getUserEditView($parentFieldArray, $vardef, $displayParams, $tabindex, $searchView = false)
    {
        $displayParams['bean_id'] = 'id';
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex, false);
        return $this->fetch($this->findTemplate('UserEditView'));
    }

    function getUserDetailView($parentFieldArray, $vardef, $displayParams, $tabindex, $searchView = false)
    {
        $displayParams['bean_id'] = 'id';
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex, false);
        return $this->fetch($this->findTemplate('UserDetailView'));
    }

    public function save($bean, $params, $field, $properties, $prefix = '')
    {
        $upload_file = new UploadFile($field);

        //remove file
        if (isset($_REQUEST['remove_imagefile_' . $field]) && $_REQUEST['remove_imagefile_' . $field] == 1) {
            $upload_file->unlink_file($bean->$field);
            $bean->$field = "";
        }

        //uploadfile
        if (isset($_FILES[$field])) {
            //confirm only image file type can be uploaded
            if (verify_image_file($_FILES[$field]['tmp_name'])) {
                if ($upload_file->confirm_upload()) {
                    // for saveTempImage API
                    if (isset($params['temp']) && $params['temp'] === true) {

                        // Create the new field value
                        $bean->$field = create_guid();

                        // Move to temporary folder
                        if (!$upload_file->final_move($bean->$field, true)) {
                            // If this was a fail, reset the bean field to original
                            $this->error = $upload_file->getErrorMessage();
                        }
                    } else {

                        // Capture the old value in case of error
                        $oldvalue = $bean->$field;

                        // Create the new field value
                        $bean->$field = create_guid();

                        // Add checking for actual file move for reporting to consumers
                        if (!$upload_file->final_move($bean->$field)) {
                            // If this was a fail, reset the bean field to original
                            $bean->$field = $oldvalue;
                            $this->error  = $upload_file->getErrorMessage();
                        }
                    }
                } else {
                    // Added error reporting
                    $this->error = $upload_file->getErrorMessage();
                }
            } else {
                $imgInfo = !empty($_FILES[$field]['tmp_name']) ? getimagesize($_FILES[$field]['tmp_name']) : false;
                // if file is image then this image is no longer supported.
                if (false !== $imgInfo) {
                    $this->error = string_format(
                        $GLOBALS['app_strings']['LBL_UPLOAD_IMAGE_FILE_NOT_SUPPORTED'],
                        array($imgInfo['mime'])
                    );
                } else {
                    $this->error = $GLOBALS['app_strings']["LBL_UPLOAD_IMAGE_FILE_INVALID"];
                }
            }
        }

        //Check if we have the duplicate value set and use it if $bean->$field is empty
        if (empty($bean->$field) && !empty($_REQUEST[$field . '_duplicate'])) {
            $bean->$field = $_REQUEST[$field . '_duplicate'];
        }

        // case when we should copy one file to another using merge-duplicate view
        // $params[$field . '_duplicateBeanId'] contains id of bean from
        // which we should copy file.
        if (!empty($params[$field]) && !empty($params[$field . '_duplicateBeanId'])) {
            $bean->$field = create_guid();
            $upload_file->duplicate_file($params[$field], $bean->$field);
        }
    }

    /**
     * Override apiSave to call save method instead of using parent apiSave
     *
     * @override
     * @param SugarBean $bean       - the bean performing the save
     * @param array     $params     - an array of parameters array from passed up to the API
     * @param string    $field      - The name of the field to save (the vardef name, not the form element name)
     * @param array     $properties - Any properties for this field
     */
    public function apiSave(SugarBean $bean, array $params, $field, $properties)
    {
        $this->save($bean, $params, $field, $properties);
    }
}
