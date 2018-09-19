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
class DocumentsFileApi extends FileApi {
    /**
     * Dictionary registration method, called when the API definition is built
     *
     * @return array
     */
    public function registerApiRest() {
        return array(
            'saveFilePost' => array(
                'reqType' => 'POST',
                'path' => array('Documents', '?', 'file', '?'),
                'pathVars' => array('module', 'record', '', 'field'),
                'method' => 'saveFilePost',
                'rawPostContents' => true,
                'shortHelp' => 'Saves a file. The file can be a new file or a file override.',
                'longHelp' => 'include/api/help/module_record_file_field_post_help.html',
            ),
            'saveFilePut' => array(
                'reqType' => 'PUT',
                'path' => array('Documents', '?', 'file', '?'),
                'pathVars' => array('module', 'record', '', 'field'),
                'method' => 'saveFilePut',
                'rawPostContents' => true,
                'shortHelp' => 'Saves a file. The file can be a new file or a file override. (This is an alias of the POST method save.)',
                'longHelp' => 'include/api/help/module_record_file_field_put_help.html',
            ),
        );
    }

    /**
     * (non-PHPdoc)
     * @see FileApi::checkFileAccess()
     */
    protected function checkFileAccess(SugarBean $bean, $field, array $args)
    {
        parent::checkFileAccess($bean, $field, $args);
        // Check that we can create revision
        $revision = $bean->createRevisionBean();
        if(!$revision->ACLAccess('create')) {
            throw new SugarApiExceptionNotAuthorized('No access to create revisions');
        }
    }

    /** {@inheritDoc} */
    protected function saveBean(SugarBean $bean, ServiceBase $api, array $args)
    {
        // Recreate revision bean with correct data
        if($bean->document_revision_id) {
            ++$bean->revision;
        } else {
            $bean->revision = 1;
        }
        $revision = $bean->createRevisionBean();
        $bean->document_revision_id = $revision->id;

        // Save the revision object
        $revision->save();

        // Save the bean
        parent::saveBean($bean, $api, $args);
        // move the file to the revision's ID
        if(empty($bean->doc_type) || $bean->doc_type == 'Sugar') {
            rename("upload://{$bean->id}", "upload://{$revision->id}");
        }
        // update the fields
        $bean->fill_in_additional_detail_fields();
    }

    protected function deleteIfFails(SugarBean $bean, array $args)
    {
        // if we already have the revision, we won't delete the document on failure to add another one
        if($bean->document_revision_id) {
            return;
        }
        parent::deleteIfFails($bean, $args);
    }
}
