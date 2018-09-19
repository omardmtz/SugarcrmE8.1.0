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

/*
 * vCard API implementation
 */
class vCardApi extends SugarApi
{

    /**
     * This function registers the vCard api
     */
    public function registerApiRest()
    {
        return array(
            'vCardSave' => array(
                'reqType' => 'GET',
                'path' => array('VCardDownload'),
                'pathVars' => array(''),
                'method' => 'vCardSave',
                'rawReply' => true,
                'allowDownloadCookie' => true,
                'shortHelp' => 'An API to download a contact as a vCard.',
                'longHelp' => 'include/api/help/vcarddownload_get_help.html',
            ),
            'vCardDownload' => array(
                'reqType' => 'GET',
                'path' => array('<module>', '?', 'vcard'),
                'pathVars' => array('module', 'record', ''),
                'method' => 'vCardDownload',
                'rawReply' => true,
                'allowDownloadCookie' => true,
                'shortHelp' => 'An API to download a contact as a vCard.',
                'longHelp' => 'include/api/help/module_vcarddownload_get_help.html',
            ),
            'vCardImportPost' => array(
                'reqType' => 'POST',
                'path' => array('<module>', 'file', 'vcard_import'),
                'pathVars' => array('module', '', ''),
                'method' => 'vCardImport',
                'rawPostContents' => true,
                'shortHelp' => 'Imports a person record from a vcard',
                'longHelp' => 'include/api/help/module_file_vcard_import_post_help.html',
            ),
        );
    }

    /**
     * vCardSave
     *
     * @param ServiceBase $api  ServiceBase The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param array $args The arguments array passed in from the API
     *
     * @return String
     */
    public function vCardSave(ServiceBase $api, array $args)
    {
        $this->requireArgs($args, array('id', 'module'));
        $args['record'] = $args['id'];

        return $this->getVcardForRecord($api, $args);
    }

    /**
     * vCardDownload
     *
     * @param ServiceBase $api  ServiceBase The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param array $args The arguments array passed in from the API
     *
     * @return String
     */
    public function vCardDownload(ServiceBase $api, array $args)
    {
        $this->requireArgs($args, array('record', 'module'));

        return $this->getVcardForRecord($api, $args);
    }

    /**
     * vCardImport
     *
     * @param ServiceBase $api  ServiceBase The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param array $args The arguments array passed in from the API
     *
     * @return String
     */
    public function vCardImport(ServiceBase $api, array $args)
    {
        $this->requireArgs($args, array('module'));

        $bean = BeanFactory::newBean($args['module']);
        if (!$bean->ACLAccess('save') || !$bean->ACLAccess('import')) {
            throw new SugarApiExceptionNotAuthorized('EXCEPTION_NOT_AUTHORIZED');
        }

        $this->checkPostRequestBody();

        if (isset($_FILES) && count($_FILES) === 1) {
            reset($_FILES);
            $first_key = key($_FILES);
            if (isset($_FILES[$first_key]['tmp_name'])
                && $this->isUploadedFile($_FILES[$first_key]['tmp_name'])
                && isset($_FILES[$first_key]['size'])
                && isset($_FILES[$first_key]['size']) > 0
            ) {
                $vcard = new vCard();
                try {
                    $recordId = $vcard->importVCard($_FILES[$first_key]['tmp_name'], $args['module']);
                } catch (Exception $e) {
                    throw new SugarApiExceptionRequestMethodFailure('ERR_VCARD_FILE_PARSE');
                }

                $results = array($first_key => $recordId);

                return $results;
            }
        }
    }

    /**
     * This function is a wrapper for checking if the file was uploaded so that the php built in function can be mocked
     *
     * @param string FileName
     *
     * @return boolean
     */
    protected function isUploadedFile($fileName)
    {
        return is_uploaded_file($fileName);
    }

    /**
     * Retrieves the generated vcard for a record
     *
     * @param ServiceBase $api  ServiceBase The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param array $args The arguments array passed in from the API
     *
     * @return String
     */
    protected function getVcardForRecord(ServiceBase $api, array $args)
    {
        $bean = $this->loadBean($api, $args);
        if (!$bean->ACLAccess('view')) {
            throw new SugarApiExceptionNotAuthorized('No access to view records for module: ' . $args['module']);
        }

        $vcard = new vCard();

        if (isset($args['module'])) {
            $module = clean_string($args['module']);
        } else {
            $module = 'Contacts';
        }

        $vcard->loadContact($args['record'], $module);

        return $vcard->saveVCardApi($api);
    }
}
