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


class SugarFieldFile extends SugarFieldBase
{
    static $imageFileMimeTypes = array(
        'image/jpeg',
        'image/gif',
        'image/png',
    );

    private function fillInOptions(&$vardef, &$displayParams)
    {
        if ( isset($vardef['allowEapm']) && $vardef['allowEapm'] == true ) {
            if ( empty($vardef['docType']) ) {
                $vardef['docType'] = 'doc_type';
            }
            if ( empty($vardef['docId']) ) {
                $vardef['docId'] = 'doc_id';
            }
            if ( empty($vardef['docUrl']) ) {
                $vardef['docUrl'] = 'doc_url';
            }
        } else {
            $vardef['allowEapm'] = false;
        }

        // Override the default module
        if ( isset($vardef['linkModuleOverride']) ) {
            $vardef['linkModule'] = $vardef['linkModuleOverride'];
        } else {
            $vardef['linkModule'] = '{$module}';
        }

        // This is needed because these aren't always filled out in the edit/detailview defs
        if ( !isset($vardef['fileId']) ) {
            if ( isset($displayParams['id']) ) {
                $vardef['fileId'] = $displayParams['id'];
            } else {
                $vardef['fileId'] = 'id';
            }
        }
    }


    public function getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex)
    {
        $this->fillInOptions($vardef, $displayParams);

        return parent::getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
    }

    public function getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex)
    {
        $this->fillInOptions($vardef, $displayParams);

        $keys = $this->getAccessKey($vardef, 'FILE', $vardef['module']);
        $displayParams['accessKeySelect'] = $keys['accessKeySelect'];
        $displayParams['accessKeySelectLabel'] = $keys['accessKeySelectLabel'];
        $displayParams['accessKeySelectTitle'] = $keys['accessKeySelectTitle'];
        $displayParams['accessKeyClear'] = $keys['accessKeyClear'];
        $displayParams['accessKeyClearLabel'] = $keys['accessKeyClearLabel'];
        $displayParams['accessKeyClearTitle'] = $keys['accessKeyClearTitle'];

        return parent::getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
    }

    public function save($bean, $params, $field, $vardef, $prefix = '')
    {
        $fakeDisplayParams = array();
        $this->fillInOptions($vardef, $fakeDisplayParams);

        $upload_file = new UploadFile($prefix . $field . '_file');

        //remove file
        if (isset($_REQUEST['remove_file_' . $field]) && $params['remove_file_' . $field] == 1) {
            $upload_file->unlink_file($bean->$field);
            $bean->$field="";
        }

        $move=false;
        // In case of failure midway, we need to reset the values of the bean
        $originalvals = array('value' => $bean->$field);

        // Bug 57400 - Some beans with a filename field type do NOT have file_mime_type
        // or file_ext. In the case of Documents, for example, this happens to be
        // the case, since the DocumentRevisions bean is where these fields are found.
        if (isset($bean->file_mime_type)) {
            $originalvals['mime'] = $bean->file_mime_type;
        }

        if (isset($bean->file_ext)) {
            $originalvals['ext'] = $bean->file_ext;
        }

        if (isset($_FILES[$prefix . $field . '_file']) && $upload_file->confirm_upload()) {

            // in order to avoid any discrepancies of MIME type with the download code,
            // call the same MIME function instead of using the uploaded file's mime type property.
            $mimeType = get_file_mime_type($upload_file->get_temp_file_location(), 'application/octet-stream');
            //verify the image
            if (in_array($mimeType, self::$imageFileMimeTypes) &&
                !verify_image_file($upload_file->get_temp_file_location())) {
                $this->error = string_format(
                    $GLOBALS['app_strings']['LBL_UPLOAD_IMAGE_FILE_NOT_SUPPORTED'],
                    array($upload_file->file_ext)
                );
                return;
            }
            $bean->$field = $upload_file->get_stored_file_name();
            $bean->file_mime_type = $upload_file->mime_type;
            $bean->file_ext = $upload_file->file_ext;
            $bean->file_size = $upload_file->file_size;
            $move=true;
        } else {
            $this->error = $upload_file->getErrorMessage();
        }

        if (!empty($params['isDuplicate']) && $params['isDuplicate'] == 'true' ) {
            // This way of detecting duplicates is used in Notes
            $old_id = $params['relate_id'];
        }
        if (!empty($params['duplicateSave']) && !empty($params['duplicateId']) ) {
            // It's a duplicate
            $old_id = $params['duplicateId'];
        }

        // case when we should copy one file to another using merge-duplicate view
        // $params[$field . '_duplicateBeanId'] contains id of bean from
        // which we should copy file.
        if (!empty($params[$field . '_duplicateBeanId'])) {
            $duplicateModuleId = $params[$field . '_duplicateBeanId'];
        }

        // Backwards compatibility for fields that still use customCode to handle the file uploads
        if ( !$move && empty($old_id) && isset($_FILES['uploadfile']) ) {
            $upload_file = new UploadFile('uploadfile');
            if ( $upload_file->confirm_upload() ) {
                $bean->$field = $upload_file->get_stored_file_name();
                $bean->file_mime_type = $upload_file->mime_type;
                $bean->file_ext = $upload_file->file_ext;
                $move=true;
            } else {
                $this->error = $upload_file->getErrorMessage();
            }
        } elseif ( !$move && !empty($old_id) && isset($_REQUEST['uploadfile']) && !isset($_REQUEST[$prefix . $field . '_file']) ) {
            // I think we are duplicating a backwards compatibility module.
            $upload_file = new UploadFile('uploadfile');
        }

        if (empty($bean->id)) {
            $bean->id = create_guid();
            $bean->new_with_id = true;
        }

        if ($move) {
            $temp = !empty($params['temp']);
            // Added checking of final move to capture errors that might occur
            if ($upload_file->final_move($bean->id, $temp)) {
                if (!$temp) {
                    // This fixes an undefined index warning being thrown
                    $docType = isset($vardef['docType']) && isset($params[$prefix . $vardef['docType']]) ? $params[$prefix . $vardef['docType']] : null;
                    $upload_file->upload_doc($bean, $bean->id, $docType, $bean->$field, $upload_file->mime_type);
                }
            } else {
                // Reset the bean back to original, but only if we had set them.
                $bean->$field = $originalvals['value'];

                // See comments for these properties above in regards to Bug 57400
                if (isset($originalvals['mime'])) {
                    $bean->file_mime_type = $originalvals['mime'];
                }

                if (isset($originalvals['ext'])) {
                    $bean->file_ext = $originalvals['ext'];
                }

                // Report the error
                $this->error = $upload_file->getErrorMessage();
            }

        } elseif ( ! empty($old_id) ) {
            // It's a duplicate, I think

            if (empty($vardef['docUrl'] ) || empty($params[$prefix . $vardef['docUrl'] ]) ) {
                $upload_file->duplicate_file($old_id, $bean->id, $bean->$field);
            } else {
                $docType = $vardef['docType'];
                $bean->$docType = $params[$prefix . $field . '_old_doctype'];
            }
        } elseif ( !empty($params[$prefix . $field . '_remoteName']) ) {
            // We aren't moving, we might need to do some remote linking
            $displayParams = array();
            $this->fillInOptions($vardef, $displayParams);

            if ( isset($params[$prefix . $vardef['docId']])
                 && ! empty($params[$prefix . $vardef['docId']])
                 && isset($params[$prefix . $vardef['docType']])
                 && ! empty($params[$prefix . $vardef['docType']])
                ) {
                $bean->$field = $params[$prefix . $field . '_remoteName'];

                $extension = get_file_extension($bean->$field);
                if (!empty($extension)) {
                    $bean->file_ext = $extension;
                    $bean->file_mime_type = get_mime_content_type_from_filename($bean->$field);
                }
            }
        } elseif (!empty($duplicateModuleId)) {
            if ($bean->object_name == 'Note') {
                $duplicateBean = BeanFactory::getBean('Notes', $duplicateModuleId);
                $duplicateModuleId = $duplicateBean->getUploadId();
            }
            // don't copy the actual file.
            // for now, we only handle email notes
            if (!empty($params['email_type']) &&
                in_array($params['email_type'], array('Emails', 'EmailTemplates'))
            ) {
                $bean->upload_id = $duplicateModuleId;
            }
            else {
                $upload_file->duplicate_file($duplicateModuleId, $bean->id, $bean->$field);
            }
            $bean->$field = $params[$field];

            $extension = get_file_extension($bean->$field);
            if (!empty($extension)) {
                $bean->file_ext = $extension;
                $bean->file_mime_type = get_mime_content_type_from_filename($bean->$field);
            }
        }

        if ( $vardef['allowEapm'] == true && empty($bean->$field) ) {
            $GLOBALS['log']->info("The $field is empty, clearing out the lot");
            // Looks like we are emptying this out
            $clearFields = array('docId', 'docType', 'docUrl', 'docDirectUrl');
            foreach ($clearFields as $clearMe) {
                if (!isset($vardef[$clearMe])) {
                    continue;
                }
                $clearField = $vardef[$clearMe];
                $bean->$clearField = '';
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function apiFormatField(
        array &$data,
        SugarBean $bean,
        array $args,
        $fieldName,
        $properties,
        array $fieldList = null,
        ServiceBase $service = null
    ) {
        // Handle the parent so our current data element is set
        parent::apiFormatField($data, $bean, $args, $fieldName, $properties, $fieldList, $service);

        // Get fields related to this field but only if the current field is empty
        // If the current field is empty, set it to the related field(s) until it 
        // isn't empty. This happens in the case of upload file fields in File 
        // type SugarObject based custom modules. In most cases the fields array
        // will either be empty or one entry long.
        if (empty($data[$fieldName]) && isset($properties['fields'])) {
            foreach ($properties['fields'] as $field) {
                if (empty($data[$field])) {
                    $data[$field] = empty($bean->$field) ? '' : $bean->$field;
                }

                $data[$fieldName] = $data[$field];
                break;
            }
        }
        
        // Add in file_mime_type
        if (empty($data['file_mime_type'])) {
            $data['file_mime_type'] = empty($bean->file_mime_type) ? '' : $bean->file_mime_type;
        }
    }

    /** {@inheritDoc} */
    public function apiSave(SugarBean $bean, array $params, $field, $properties)
    {
        parent::apiSave($bean, $params, $field, $properties);
        // handle copy
        // $params[$field . '_duplicateBeanId'] contains id of bean from which we should copy file
        if (!empty($params[$field . '_duplicateBeanId'])) {
            $upload_file = new UploadFile($field . '_file');
            $duplicateModuleId = $params[$field . '_duplicateBeanId'];
            if ($bean->object_name == 'Note') {
                $duplicateBean = BeanFactory::getBean('Notes', $duplicateModuleId);
                $uploadId = $duplicateBean->getUploadId();
                // don't copy the actual file for email notes
                if (!empty($params['email_type']) &&
                    in_array($params['email_type'], array('Emails', 'EmailTemplates'))
                ) {
                    $bean->upload_id = $uploadId;
                }
                else {
                    $upload_file->duplicate_file($uploadId, $bean->id, $bean->$field);
                }
            }
            else {
                $upload_file->duplicate_file($duplicateModuleId, $bean->id, $bean->$field);
            }
            require_once 'include/utils/file_utils.php';
            $extension = get_file_extension($bean->$field);
            if (!empty($extension)) {
                $bean->file_ext = $extension;
                $bean->file_mime_type = get_mime_content_type_from_filename($bean->$field);
            }
        }
    }
}
