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
 * API Class to handle temporary image (attachment) interactions with a field in
 * a bean that can be new, so no record id is associated yet.
 */
class FileTempApi extends FileApi {
    /**
     * Dictionary registration method, called when the API definition is built
     *
     * @return array
     */
    public function registerApiRest() {
        return array(
            'saveTempImagePost' => array(
                'reqType' => 'POST',
                'path' => array('<module>', 'temp', 'file', '?'),
                'pathVars' => array('module', 'temp', '', 'field'),
                'method' => 'saveTempImagePost',
                'rawPostContents' => true,
                'shortHelp' => 'Saves an image to a temporary folder.',
                'longHelp' => 'include/api/help/module_temp_file_field_post_help.html',
            ),
            'getTempImage' => array(
                'reqType' => 'GET',
                'path' => array('<module>', 'temp', 'file', '?', '?'),
                'pathVars' => array('module', 'record', '', 'field', 'temp_id'),
                'method' => 'getTempImage',
                'rawReply' => true,
                'allowDownloadCookie' => true,
                'shortHelp' => 'Reads a temporary image and deletes it.',
                'longHelp' => 'include/api/help/module_temp_file_field_temp_id_get_help.html',
            ),
        );
    }

    /**
     * Saves a temporary image to a module field using the POST method (but not attached to any model)
     *
     * @param ServiceBase $api The service base
     * @param array $args Arguments array built by the service base
     * @return array
     * @throws SugarApiExceptionError
     */
    public function saveTempImagePost(ServiceBase $api, array $args)
    {
        if (!isset($args['record'])) {
            $args['record'] = null;
        }
        $temp = true;
        return $this->saveFilePost($api, $args, $temp);
    }

    /**
     * Gets a single temporary file for rendering and removes it from filesystem.
     *
     * @param ServiceBase $api The service base
     * @param array $args Arguments array built by the service base
     */
    public function getTempImage(ServiceBase $api, array $args)
    {
        // Get the field
        if (empty($args['field'])) {
            // @TODO Localize this exception message
            throw new SugarApiExceptionMissingParameter('Field name is missing');
        }
        $field = $args['field'];

        // Get the bean
        $bean = BeanFactory::newBean($args['module']);

        // Handle ACL
        $this->verifyFieldAccess($bean, $field);

        $filepath = UploadStream::path("upload://tmp/") . $args['temp_id'];
        if (is_file($filepath)) {
            $filedata = getimagesize($filepath);

            $info = array(
                'content-type' => $filedata['mime'],
                'path' => $filepath,
            );
            $dl = new DownloadFileApi($api);
            $dl->outputFile(false, $info);

            if (!empty($args['keep'])) {
                return;
            }

            register_shutdown_function(
                function () use($filepath) {
                    if (is_file($filepath)) {
                        unlink($filepath);
                    }
                }
            );
        } else {
            throw new SugarApiExceptionInvalidParameter('File not found');
        }
    }
}
