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
 * API Class to handle file and image (attachment) interactions with a field in
 * a record.
 */
class FileApi extends SugarApi {
    /**
     * Dictionary registration method, called when the API definition is built
     *
     * @return array
     */
    public function registerApiRest() {
        return array(
            'saveFilePost' => array(
                'reqType' => 'POST',
                'path' => array('<module>', '?', 'file', '?'),
                'pathVars' => array('module', 'record', '', 'field'),
                'method' => 'saveFilePost',
                'rawPostContents' => true,
                'shortHelp' => 'Saves a file. The file can be a new file or a file override.',
                'longHelp' => 'include/api/help/module_record_file_field_post_help.html',
            ),
            'saveFilePut' => array(
                'reqType' => 'PUT',
                'path' => array('<module>', '?', 'file', '?'),
                'pathVars' => array('module', 'record', '', 'field'),
                'method' => 'saveFilePut',
                'rawPostContents' => true,
                'shortHelp' => 'Saves a file. The file can be a new file or a file override. (This is an alias of the POST method save.)',
                'longHelp' => 'include/api/help/module_record_file_field_put_help.html',
            ),
            'getFileList' => array(
                'reqType' => 'GET',
                'path' => array('<module>', '?', 'file'),
                'pathVars' => array('module', 'record', ''),
                'method' => 'getFileList',
                'shortHelp' => 'Gets a listing of files related to a field for a module record.',
                'longHelp' => 'include/api/help/module_record_file_get_help.html',
            ),
            'getFileContents' => array(
                'reqType' => 'GET',
                'path' => array('<module>', '?', 'file', '?'),
                'pathVars' => array('module', 'record', '', 'field'),
                'method' => 'getFile',
                'rawReply' => true,
                'allowDownloadCookie' => true,
                'shortHelp' => 'Gets the contents of a single file related to a field for a module record.',
                'longHelp' => 'include/api/help/module_record_file_field_get_help.html',
            ),
            'removeFile' => array(
                'reqType' => 'DELETE',
                'path' => array('<module>', '?', 'file', '?'),
                'pathVars' => array('module', 'record', '', 'field'),
                'method' => 'removeFile',
                'rawPostContents' => true,
                'shortHelp' => 'Removes a file from a field.',
                'longHelp' => 'include/api/help/module_record_file_field_delete_help.html',
            ),
        );
    }

    /**
     * Saves a file to a module field using the PUT method
     *
     * @param ServiceBase $api  The service base
     * @param array       $args Arguments array built by the service base
     * @param string      $stream
     *
     * @throws SugarApiExceptionMissingParameter
     * @return array
     */
    public function saveFilePut(ServiceBase $api, array $args, $stream = 'php://input')
    {
        // Mime type, set to null for grabbing it later if not sent
        $filetype = isset($_SERVER['HTTP_CONTENT_TYPE']) ? $_SERVER['HTTP_CONTENT_TYPE'] : null;

        // Set the filename, first from the passed args then from the request itself
        if (isset($args['filename'])) {
            $filename = $args['filename'];
        } else {
            $filename = isset($_SERVER['HTTP_FILENAME']) ? $_SERVER['HTTP_FILENAME'] : create_guid();
        }

        // Legacy support for base64 encoded file data
        $encoded = $this->isFileEncoded($api, $args);

        // Create a temp name for our file to begin mocking the $_FILES array
        $tempfile = $this->getTempFileName();
        $this->createTempFileFromInput($tempfile, $stream, $encoded);

        // Now validate our file
        $filesize = filesize($tempfile);
        $this->checkPutRequestBody($filesize);

        // Now get our actual mime type from our internal methodology if it wasn't passed
        if (empty($filetype)) {
            $dl = $this->getDownloadFileApi($api);
            $filetype = $dl->getMimeType($tempfile);
        }

        // Mock a $_FILES array member, adding in _SUGAR_API_UPLOAD to allow file uploads
        $_FILES[$args['field']] = array(
            'name' => $filename,
            'type' => $filetype,
            'tmp_name' => $tempfile,
            'error' => 0,
            'size' => $filesize,
            '_SUGAR_API_UPLOAD' => true, // This is in place to allow bypassing is_uploaded_file() checks
        );

        // Now that we are set up, hand this off to the POST save handler
        $return = $this->saveFilePost($api, $args);

        // Handle temp file cleanup
        if (file_exists($tempfile)) {
            unlink($tempfile);
        }

        // Send back our result
        return $return;
    }

    /**
     * Check if we have access to upload this file
     * @param SugarBean $bean
     * @param string $field
     * @param array $args
     * @throws SugarApiExceptionNotAuthorized
     */
    protected function checkFileAccess(SugarBean $bean, $field, array $args)
    {
        if(!$bean->ACLAccess('view')) {
            throw new SugarApiExceptionNotAuthorized('No access to view records for module: '.$args['module']);
        }

        // Handle ACL - if there is no current field data, it is a CREATE
        // This addresses an issue where the portal user has create but not edit
        // rights for particular modules. The perspective here is that even if
        // a record exists, if there is no attachment, you are CREATING the
        // attachment instead of EDITING the parent record. -rgonzalez
        $accessType = empty($bean->$field) ? 'create' : 'edit';
        $this->verifyFieldAccess($bean, $field, $accessType);
    }

    /**
     * Saves a file to a module field using the POST method
     *
     * @param ServiceBase $api The service base
     * @param array $args Arguments array built by the service base
     * @param bool $temporary true if we are saving a temporary image
     * @return array
     * @throws SugarApiExceptionError
     */
    public function saveFilePost(ServiceBase $api, array $args, $temporary = false)
    {
        //Needed by SugarFieldImage.php to know if we are saving a temporary image
        $args['temp'] = $temporary;

        // Get the field
        $field = $args['field'];

        // To support field prefixes like Sugar proper
        $prefix = empty($args['prefix']) ? '' : $args['prefix'];

        // Set the files array index (for type == file)
        $filesIndex = $prefix . $field;

        // Get the bean before we potentially delete if fails (e.g. see below if attachment too large, etc.)
        $bean = $this->loadBean($api, $args);

        $this->checkFileAccess($bean, $field, $args);

        // Simple validation
        // In the case of very large files that are too big for the request too handle AND
        // if the auth token was sent as part of the request body, you will get a no auth error
        // message on uploads. This check is in place specifically for file uploads that are too
        // big to be handled by checking for the presence of the $_FILES array and also if it is empty.
        if (empty($_FILES[$filesIndex])) {

            // If we get here, the attachment was > php.ini upload_max_filesize value so we need to
            // check if delete_if_fails optional parameter was set true, etc.
            $this->deleteIfFails($bean, $args);

            $this->checkPostRequestBody();

            // @TODO Localize this exception message
            throw new SugarApiExceptionMissingParameter("Incorrect field name for attachement: $filesIndex");
        }

        // Get the defs for this field
        $def = $bean->field_defs[$field];

        // Only work on file or image fields
        if (isset($def['type']) && ($def['type'] == 'image' || $def['type'] == 'file')) {
            // Get our tools to actually save the file|image
            $sfh = new SugarFieldHandler();
            $sf = $sfh->getSugarField($def['type']);
            if ($sf) {
                // SugarFieldFile expects something different than SugarFieldImage
                if ($def['type'] == 'file') {
                    // docType setting is throwing errors if missing
                    if (!isset($def['docType'])) {
                        $def['docType'] = 'Sugar';
                    }

                    // Session error handler is throwing errors if not set
                    $_SESSION['user_error_message'] = array();

                    // Handle setting the files array to what SugarFieldFile is expecting
                    if (!empty($_FILES[$filesIndex]) && empty($_FILES[$filesIndex . '_file'])) {
                        $_FILES[$filesIndex . '_file'] = $_FILES[$filesIndex];
                        unset($_FILES[$filesIndex]);
                        $filesIndex .= '_file';
                    }
                }

                // Noticed for some reason that API FILE[type] was set to application/octet-stream
                // That breaks the uploader which is looking for very specific mime types
                // So rather than rely on what $_FILES thinks, set it with our own methodology
                $dl = $this->getDownloadFileApi($api);
                $mime = $dl->getMimeType($_FILES[$filesIndex]['tmp_name']);
                $_FILES[$filesIndex]['type'] = $mime;

                // Set the docType into args if its in the def
                // This addresses a need in the UploadFile object
                if (isset($def['docType']) && !isset($args[$prefix . $def['docType']])) {
                    $args[$prefix . $def['docType']] = $mime;
                }

                // This saves the attachment
                $sf->save($bean, $args, $field, $def, $prefix);

                // Handle errors
                if (!empty($sf->error)) {

                    // Note, that although the code earlier in this method (where attachment too large) handles
                    // if file > php.ini upload_maxsize, we still may have a file > sugarcrm maxsize
                    $this->deleteIfFails($bean, $args);
                    throw new SugarApiExceptionRequestTooLarge($sf->error);
                }

                // Prep our return
                $fileinfo = array();

                //In case we are returning a temporary file
                if ($temporary) {
                    $fileinfo['guid'] = $bean->$field;
                }
                else {
                    // Save the bean
                    $this->saveBean($bean, $api, $args);

                    $fileinfo = $this->getFileInfo($bean, $field, $api);

                    // This isn't needed in this return
                    unset($fileinfo['path']);
                }

                // This is a good return
                return array(
                    $field => $fileinfo,
                    'record' => $this->formatBean($api, $args, $bean)
                );
            }
        }

        // @TODO Localize this exception message
        throw new SugarApiExceptionError("Unexpected field type: ".$def['type']);
    }

    /**
     * Gets a list of all fields that have files attached to them for a module
     *
     * @param ServiceBase $api The service base
     * @param array $args Arguments array built by the service base
     * @return array
     */
    public function getFileList(ServiceBase $api, array $args)
    {
        // Validate and load up the bean because we need it
        $bean = $this->loadBean($api, $args, 'view');

        if(!$bean->ACLAccess('view')) {
            throw new SugarApiExceptionNotAuthorized('No access to view records for module: '.$args['module']);
        }

        // Special cases for document revision sets
        if (property_exists($bean, 'document_revision_id') && !empty($bean->document_revision_id)) {
            $newbean = BeanFactory::getBean('DocumentRevisions', $bean->document_revision_id);
            // Some Doc Revisions have a filename but no mime type, which means no file
            if ($newbean && !empty($newbean->file_mime_type)) {
                $bean = $newbean;
            }
        }

        // Set up our return array
        $list = array();
        foreach ($bean->field_defs as $field => $def) {
            // We are looking specifically for file and image types
            if (isset($def['type']) && ($def['type'] == 'image' || $def['type'] == 'file')) {
                // Add this field to the response, even if it is empty
                $fileinfo = $this->getFileInfo($bean, $field, $api);

                // This isn't needed in this return
                unset($fileinfo['path']);

                // Add it to the return, as an object so that if it is empty it
                // is still an object in json responses
                $list[$field] = (object) $fileinfo;
            }
        }

        return $list;
    }

    /**
     * Gets a single file for rendering
     *
     * @param ServiceBase $api The service base
     * @param array $args Arguments array built by the service base
     * @throws SugarApiExceptionMissingParameter When field name is missing.
     * @throws SugarApiExceptionNotFound When file cannot be found.
     * @throws SugarApiExceptionNotAuthorized When there is no access to record in module.
     */
    public function getFile(ServiceBase $api, array $args)
    {
        // if exists link_name param then get archive
        if (!empty($args['link_name'])) {
            // @TODO Remove this code and use getArchive method via rest
            $this->getArchive($api, $args);
            return;
        }

        // Get the field
        if (empty($args['field'])) {
            // @TODO Localize this exception message
            throw new SugarApiExceptionMissingParameter('Field name is missing');
        }
        $field = $args['field'];

        // Get the bean
        $bean = $this->loadBean($api, $args);

        if(!$bean->ACLAccess('view')) {
            throw new SugarApiExceptionNotAuthorized('No access to view records for module: '.$args['module']);
        }

        if (empty($bean->{$field})) {
            // @TODO Localize this exception message
            throw new SugarApiExceptionNotFound("The requested file $args[module] :: $field could not be found.");
        }

        // Handle ACL
        $this->verifyFieldAccess($bean, $field);

        $def = $bean->field_defs[$field];
        //for the image type field, forceDownload set default as false in order to display on the image element.
        $forceDownload = ($def['type'] == 'image') ? false : true;
        if (isset($args['force_download'])) {
            $forceDownload = (bool) $args['force_download'];
        }

        $download = $this->getDownloadFileApi($api);
        try {
            $download->getFile($bean, $field, $forceDownload);
        } catch (Exception $e) {
            throw new SugarApiExceptionNotFound($e->getMessage(), null, null, 0, $e);
        }
    }

    /**
     * Removes an attachment from a record field
     *
     * @param ServiceBase $api The service base
     * @param array $args The request args
     * @return array Listing of fields for a record
     * @throws SugarApiExceptionError|SugarApiExceptionNoMethod|SugarApiExceptionRequestMethodFailure
     */
    public function removeFile(ServiceBase $api, array $args)
    {
        // Get the field
        $field = $args['field'];

        // Get the bean
        $bean = $this->loadBean($api, $args);

        // Handle ACL
        $this->verifyFieldAccess($bean, $field, 'delete');

        // Only remove if there is something to remove
        if (!empty($bean->{$field})) {
            // Get the defs for this field
            $def = $bean->field_defs[$field];

            // Only work on file or image fields
            if (isset($def['type']) && ($def['type'] == 'image' || $def['type'] == 'file')) {
                if ($def['type'] == 'file') {
                    if (method_exists($bean, 'deleteAttachment')) {
                        if (!$bean->deleteAttachment()) {
                            // @TODO Localize this exception message
                            throw new SugarApiExceptionRequestMethodFailure('Removal of attachment failed.');
                        }
                    } else {
                        // @TODO Localize this exception message
                        throw new SugarApiExceptionNoMethod('No method found to remove attachment.');
                    }
                } else {
                    $upload = new UploadFile($field);
                    $upload->unlink_file($bean->$field);
                    $bean->$field = '';
                    $bean->save();
                }
            } else {
                // @TODO Localize this exception message
                throw new SugarApiExceptionError("Unexpected field type: ".$def['type']);
            }
        }

        $list = $this->getFileList($api, $args);
        $list['record'] = $this->formatBean($api, $args, $bean);
        return $list;
    }

    /**
     * Gets a zip archive of files for rendering.
     *
     * @param ServiceBase $api The service base
     * @param array $args Arguments array built by the service base
     *
     * @throws SugarApiExceptionNotFound When record, name of relationship or file cannot be found.
     * @throws SugarApiExceptionNotAuthorized When there is no access to record.
     * @throws SugarApiExceptionMissingParameter When field name is missing.
     * @throws SugarApiExceptionInvalidParameter When relationship name is incorrect.
     */
    public function getArchive(ServiceBase $api, array $args)
    {
        // Get the field
        if (empty($args['field'])) {
            throw new SugarApiExceptionMissingParameter('Field name is missing');
        }
        // Load the parent bean.
        $record = BeanFactory::retrieveBean($args['module'], $args['record']);

        if (empty($record)) {
            throw new SugarApiExceptionNotFound(
                sprintf(
                    'Could not find parent record %s in module: %s',
                    $args['record'],
                    $args['module']
                )
            );
        }
        if (!$record->ACLAccess('view')) {
            throw new SugarApiExceptionNotAuthorized('No access to view records for module: ' . $args['module']);
        }

        // Load the relationship.
        $linkName = $args['link_name'];
        if (!$record->load_relationship($linkName)) {
            // The relationship did not load.
            throw new SugarApiExceptionNotFound('Could not find a relationship named: ' . $args['link_name']);
        }
        $linkModuleName = $record->$linkName->getRelatedModuleName();
        $linkSeed = BeanFactory::newBean($linkModuleName);

        if (empty($linkSeed)) {
            throw new SugarApiExceptionInvalidParameter("Cannot use condition against $linkName - unknown module");
        }
        if (!$linkSeed->ACLAccess('list')) {
            throw new SugarApiExceptionNotAuthorized('No access to list records for module: ' . $linkModuleName);
        }
        $field = $args['field'];

        // Handle ACL
        $this->verifyFieldAccess($linkSeed, $field);

        $beans = $record->$linkName->getBeans();
        $download = $this->getDownloadFileApi($api);
        try {
            $download->getArchive($beans, $field, empty($record->name) ? $record->id : $record->name);
        } catch (Exception $e) {
            throw new SugarApiExceptionNotFound($e->getMessage(), null, null, 0, $e);
        }
    }

    /**
     * Provides ability to mark a SugarBean deleted if related file upload failed (and user passed
     * the delete_if_fails optional parameter). Note, private to respect "Principle of least privilege"
     * If you need in derived classes then you may change to protected.
     *
     * @param SugarBean $bean Bean
     * @param array $args The request args
     * @return false if no deletion occured because delete_if_fails was not set otherwise true.
     */
    protected function deleteIfFails(SugarBean $bean, array $args)
    {
        // Bug 57210: Need to be able to mark a related record 'deleted=1' when a file uploads fails.
        // delete_if_fails flag is an optional query string which can trigger this behavior. An example
        // use case might be: user's in a modal and client: 1. POST's related record 2. uploads file...
        // If the file was too big, the user may still want to go back and select a smaller file < max;
        // but now, upon saving, the client will attempt to PUT related record first and if their ACL's
        // may prevent edit/deletes it would fail. This rectifies such a scenario.
        if(!empty($args['delete_if_fails'])) {

            // First ensure user owns record
            if($bean->created_by == $GLOBALS['current_user']->id) {
                $bean->mark_deleted($bean->id);
                return true;
            }
        }
        return false;
    }

    /**
     * Gets the file information array for an uploaded file that is associated
     * with a bean's $field
     *
     * The $args array should have already been called prior to this method in
     * order to get full path URIs for the file
     *
     * @param SugarBean $bean The bean to get the info from
     * @param string $field The field name to get the file data from
     * @param ServiceBase $api The calling API service object
     * @return array|bool
     */
    protected function getFileInfo(SugarBean $bean, $field, ServiceBase $api)
    {
        $info = array();
        if (isset($bean->field_defs[$field])) {
            $def = $bean->field_defs[$field];
            if (isset($def['type']) && !empty($bean->{$field})) {
                if ($def['type'] == 'image') {
                    $filename = $bean->{$field};
                    $filepath = 'upload://' . $filename;
                    $filedata = getimagesize($filepath);

                    // Add in height and width for image types
                    $info = array(
                        'content-type' => $filedata['mime'],
                        'content-length' => filesize($filepath),
                        'name' => $filename,
                        'path' => $filepath,
                        'width' => empty($filedata[0]) ? 0 : $filedata[0],
                        'height' => empty($filedata[1]) ? 0 : $filedata[1],
                        'uri' => $api->getResourceURI(array($bean->module_dir, $bean->id, 'file', $field)),
                    );
                } elseif ($def['type'] == 'file') {
                    $download = $this->getDownloadFileApi($api);
                    $info = $download->getFileInfo($bean, $field);
                    if (!empty($info) && empty($info['uri'])) {
                        $info['uri'] = $api->getResourceURI(array($bean->module_dir, $bean->id, 'file', $field));
                    }
                }
            }
        }

        return $info;
    }

    /**
     * Inspects the request to determine if there is a need to decode the file
     * data on PUT requests. This supports legacy SOAP API style file transfers.
     *
     * @param ServiceBase $api A Service object
     * @param array $args The request arguments
     * @return boolean
     */
    protected function isFileEncoded(ServiceBase $api, array $args)
    {
        if ($api->getRequest()->hasHeader('X_CONTENT_TRANSFER_ENCODING')) {
            return $api->getRequest()->getHeader('X_CONTENT_TRANSFER_ENCODING') === 'base64';
        }

        if (isset($args['content_transfer_encoding'])) {
            return $args['content_transfer_encoding'] === 'base64';
        }

        return false;
    }

    /**
     * Gets a file handle resource
     *
     * @param string $path The path to read/write file data from/to
     * @param string $mode The mode to open the handle in, defaults to 'r'
     * @return Resource A file handle resource
     */
    protected function getFileHandle($path, $mode = 'r')
    {
        return fopen($path, $mode);
    }

    /**
     * Closes a file handle that was fetched with {@see getFileHandle}.
     *
     * @param Resource $handle A file handle resource
     * @return Boolean
     */
    protected function closeFileHandle($handle)
    {
        return fclose($handle);
    }

    /**
     * Writes data from an input file handle to an output file handle. If the
     * encoded argument is true, will also base64_decode the data as it is
     * reading.
     *
     * @param Resource $inputHandle The input file data resource
     * @param Resource $outputHandle The output file data resource
     * @param boolean $encoded Tells this method whether to base64 decode the input
     */
    protected function writeFileData($inputHandle, $outputHandle, $encoded = false)
    {
        // Write it out
        while ($data = fread($inputHandle, 1024)) {
            // Decode if we are handling encoded file contents
            if ($encoded) {
                $data = base64_decode($data);
            }

            fwrite($outputHandle, $data);
        }
    }

    /**
     * Creates a temporary file from an input source
     *
     * @param string $tempfile Path to the temporary file that will be created
     * @param string $input Path to the file that is being copied to the temp file
     * @param boolean $encoded Base64 encoding indicator
     */
    protected function createTempFileFromInput($tempfile, $input, $encoded = false)
    {
        // Now read the raw body to capture what is being sent by PUT requests
        // Using a file handle to save on memory consumption with file_get_contents
        $inputHandle  = $this->getFileHandle($input);
        $outputHandle = $this->getFileHandle($tempfile, 'w');

        $this->writeFileData($inputHandle, $outputHandle, $encoded);

        // Close the handles
        $this->closeFileHandle($inputHandle);
        $this->closeFileHandle($outputHandle);
    }

    /**
     * Gets a temporary file name. Used in PUT requests to create a temporary file.
     *
     * @return string A temp file name with full path
     */
    public function getTempFileName()
    {
        return tempnam(sys_get_temp_dir(), 'API');
    }

    /**
     * Gets the DownloadFile object for api.
     *
     * @param ServiceBase $api Api.
     * @return DownloadFileApi
     */
    protected function getDownloadFileApi(ServiceBase $api)
    {
        return new DownloadFileApi($api);
    }
}
