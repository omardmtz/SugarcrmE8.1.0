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



use Sugarcrm\Sugarcrm\ProcessManager;

/**
 * API Class to handle temporary image (attachment) interactions with a field in
 * a bean that can be new, so no record id is associated yet.
 */
class PMSEImageGeneratorApi extends FileTempApi
{
    /**
     * Dictionary registration method, called when the API definition is built
     *
     * @return array
     */
    public function registerApiRest()
    {
        return array(
            'getFileContents' => array(
                'reqType' => 'GET',
                'path' => array('pmse_Inbox', '?', 'file', '?'),
                'pathVars' => array('module', 'record', '', 'field'),
                'method' => 'getFile',
                'rawReply' => true,
                'allowDownloadCookie' => true,
                'shortHelp' => 'Returns the process status image file',
                'longHelp' => 'modules/pmse_Inbox/clients/base/api/help/process_get_file_help.html',
            ),
            'getTempImage' => array(
                'reqType' => 'GET',
                'path' => array('pmse_Inbox', 'temp', 'file', '?', '?'),
                'pathVars' => array('module', 'record', '', 'field', 'temp_id'),
                'method' => 'getTempImage',
                'rawReply' => true,
                'allowDownloadCookie' => true,
//                'shortHelp' => 'Returns the process status image file from tmp folder',
            ),
            'getPaProjectFileContents' => array(
                'reqType' => 'GET',
                'path' => array('pmse_Project', '?', 'file', '?'),
                'pathVars' => array('module', 'record', '', 'field'),
                'method' => 'getProjectFile',
                'rawReply' => true,
                'allowDownloadCookie' => true,
//                'shortHelp' => 'Returns Project image file',
            ),
            'getPaProjectTempImage' => array(
                'reqType' => 'GET',
                'path' => array('pmse_Project', 'temp', 'file', '?', '?'),
                'pathVars' => array('module', 'record', '', 'field', 'temp_id'),
                'method' => 'getTempImage',
                'rawReply' => true,
                'allowDownloadCookie' => true,
//                'shortHelp' => 'Returns project image file from tmp folder',
            ),
        );
    }

    /**
     * Gets a single file for rendering
     *
     * @param ServiceBase $api The service base
     * @param array $args Arguments array built by the service base
     * @return string
     * @throws SugarApiExceptionMissingParameter|SugarApiExceptionNotFound
     */
    public function getFile(ServiceBase $api, array $args)
    {
        $this->getProcessImage($api, $args);
        $args['temp_id'] = $args['record'];
        parent::getTempImage($api, $args);
    }

    /**
     * Gets a single temporary file for rendering and removes it from filesystem.
     *
     * @param ServiceBase $api The service base
     * @param array $args Arguments array built by the service base
     * @return array
     */
    public function getTempImage(ServiceBase $api, array $args)
    {
        PMSEEngineUtils::logDeprecated(__METHOD__);
        parent::getTempImage($api, $args);
    }

    /**
     * Get Project preview file for render
     *
     * @param ServiceBase $api The service base
     * @param array $args Arguments array built by the service base
     * @return string
     * @throws SugarApiExceptionMissingParameter|SugarApiExceptionNotFound
     */
    public function getProjectFile(ServiceBase $api, array $args)
    {
        PMSEEngineUtils::logDeprecated(__METHOD__);
        $args['_project'] = true;
        $this->getProcessImage($api, $args);
        $args['temp_id'] = $args['record'];
        parent::getTempImage($api, $args);
    }

    public function getProcessImage(ServiceBase $api, array $args)
    {
        $this->doGetProcessImage($args);
    }

    /**
     * Create the project preview image file
     *
     * @param array $args Arguments array built by the service base
     */
    public function doGetProcessImage(array $args)
    {
        $path = 'upload://tmp/';
        $image = ProcessManager\Factory::getPMSEObject('PMSEImageGenerator');
        $img = empty($args['_project']) ?
            $image->get_image($args['record']) : $image->getProjectImage($args['record']);
        $file = new UploadStream();
        if (!$file->checkDir($path)) {
            $file->createDir($path);
        }
        $file_path = UploadFile::realpath($path) . '/' . $args['record'];
        imagepng($img, $file_path);
        imagedestroy($img);
    }
}

