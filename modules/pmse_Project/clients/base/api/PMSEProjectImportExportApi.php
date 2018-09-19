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

class PMSEProjectImportExportApi extends vCardApi
{
    /**
     *
     * @return type
     */
    public function registerApiRest()
    {
        return array(
            'projectImportPost' => array(
                'reqType' => 'POST',
                'path' => array('pmse_Project', 'file', 'project_import'),
                'pathVars' => array('module', '', ''),
                'method' => 'projectImport',
                'rawPostContents' => true,
                'acl' => 'create',
                'shortHelp' => 'Imports a Process Definition from a .bpm file',
                'longHelp'  => 'modules/pmse_Project/clients/base/api/help/project_import_help.html',
            ),
            'projectDownload' => array(
                'reqType' => 'GET',
                'path' => array('pmse_Project', '?', 'dproject'),
                'pathVars' => array('module', 'record', ''),
                'method' => 'projectDownload',
                'rawReply' => true,
                'allowDownloadCookie' => true,
                'acl' => 'view',
                'shortHelp' => 'Exports a .bpm file with a Process Definition',
                'longHelp'  => 'modules/pmse_Project/clients/base/api/help/project_export_help.html',
            ),
        );
    }

    public function projectDownload(ServiceBase $api, array $args)
    {
        ProcessManager\AccessManager::getInstance()->verifyAccess($api, $args);
        $projectBean = ProcessManager\Factory::getPMSEObject('PMSEProjectExporter');
        $requiredFields = array('record', 'module');
        foreach ($requiredFields as $fieldName) {
            if (!array_key_exists($fieldName, $args)) {
                $sugarApiExceptionMissingParameter = new SugarApiExceptionMissingParameter(
                    'Missing parameter: ' . $fieldName
                );
                PMSELogger::getInstance()->alert($sugarApiExceptionMissingParameter->getMessage());
                throw $sugarApiExceptionMissingParameter;
            }
        }
        
        if (PMSEEngineUtils::isExportDisabled($args['module'])) {
            $sugarApiExceptionNotAuthorized = new SugarApiExceptionNotAuthorized(
                $GLOBALS['app_strings']['ERR_EXPORT_DISABLED']
            );
            PMSELogger::getInstance()->alert($sugarApiExceptionNotAuthorized->getMessage());
            throw $sugarApiExceptionNotAuthorized;
        }

        return $projectBean->exportProject($args['record'], $api);
    }

    public function projectImport(ServiceBase $api, array $args)
    {
        ProcessManager\AccessManager::getInstance()->verifyAccess($api, $args);
        $this->requireArgs($args, array('module'));

        $bean = BeanFactory::newBean($args['module']);
        if (!$bean->ACLAccess('save') || !$bean->ACLAccess('import')) {
            $sugarApiExceptionNotAuthorized = new SugarApiExceptionNotAuthorized('EXCEPTION_NOT_AUTHORIZED');
            PMSELogger::getInstance()->alert($sugarApiExceptionNotAuthorized->getMessage());
            throw $sugarApiExceptionNotAuthorized;
        }

        if (isset($_FILES) && count($_FILES) === 1) {
            reset($_FILES);
            $first_key = key($_FILES);
            if (isset($_FILES[$first_key]['tmp_name'])
                && $this->isUploadedFile($_FILES[$first_key]['tmp_name'])
                && isset($_FILES[$first_key]['size'])
                && isset($_FILES[$first_key]['size']) > 0
            ) {
                $importerObject = ProcessManager\Factory::getPMSEObject('PMSEProjectImporter');
                $name = $_FILES[$first_key]['name'];
                $extension = pathinfo($name,  PATHINFO_EXTENSION);
                if ($extension == $importerObject->getExtension()) {
                    try {
                        $data = $importerObject->importProject($_FILES[$first_key]['tmp_name']);
                    } catch (SugarApiExceptionNotAuthorized $e) {
                        $e->setMessage('ERROR_UPLOAD_ACCESS_PD');
                        PMSELogger::getInstance()->alert($e->getMessage());
                        throw $e;
                    }
                    $results = array('project_import' => $data);
                } else  {
                    $sugarApiExceptionRequestMethodFailure = new SugarApiExceptionRequestMethodFailure(
                        'ERROR_UPLOAD_FAILED'
                    );
                    PMSELogger::getInstance()->alert($sugarApiExceptionRequestMethodFailure->getMessage());
                    throw $sugarApiExceptionRequestMethodFailure;
                }
                return $results;
            }
        } else {
            $sugarApiExceptionMissingParameter = new SugarApiExceptionMissingParameter('ERROR_UPLOAD_FAILED');
            PMSELogger::getInstance()->alert($sugarApiExceptionMissingParameter->getMessage());
            throw $sugarApiExceptionMissingParameter;
        }
    }
}

