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


class PMSEBusinessRules extends vCardApi
{
    public function registerApiRest()
    {
        return array(
            'businessRuleDownload' => array(
                'reqType' => 'GET',
                'path' => array('pmse_Business_Rules', '?', 'brules'),
                'pathVars' => array('module', 'record', ''),
                'method' => 'businessRuleDownload',
                'rawReply' => true,
                'allowDownloadCookie' => true,
                'acl' => 'view',
                'shortHelp' => 'Exports a .pbr file with a Process Business Rules definition',
                'longHelp'  => 'modules/pmse_Business_Rules/clients/base/api/help/business_rules_export_help.html',
            ),
            'businessRulesImportPost' => array(
                'reqType' => 'POST',
                'path' => array('pmse_Business_Rules', 'file', 'businessrules_import'),
                'pathVars' => array('module', '', ''),
                'method' => 'businessRulesImport',
                'rawPostContents' => true,
                'acl' => 'create',
                'shortHelp' => 'Imports a Process Business Rules definition from a .pbr file',
                'longHelp'  => 'modules/pmse_Business_Rules/clients/base/api/help/business_rules_import_help.html',
            ),
        );
    }

    /**
     * @param $api
     * @param $args
     * @return array
     * @throws SugarApiExceptionMissingParameter
     * @throws SugarApiExceptionRequestMethodFailure
     * @throws SugarApiExceptionNotAuthorized
     */
    public function businessRulesImport($api, $args)
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
                && !empty($_FILES[$first_key]['size'])
            ) {
                $importerObject = ProcessManager\Factory::getPMSEObject('PMSEBusinessRuleImporter');
                $name = $_FILES[$first_key]['name'];
                $extension = pathinfo($name,  PATHINFO_EXTENSION);
                if ($extension == $importerObject->getExtension()) {
                    try {
                        $data = $importerObject->importProject($_FILES[$first_key]['tmp_name']);
                    } catch (SugarApiExceptionNotAuthorized $e) {
                        $e->setMessage('ERROR_UPLOAD_ACCESS_BR');
                        PMSELogger::getInstance()->alert($e->getMessage());
                        throw $e;
                    }
                    $results = array('businessrules_import' => $data);
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

    /**
     * @param $api
     * @param $args
     * @return string
     * @throws SugarApiExceptionMissingParameter
     */
    public function businessRuleDownload($api, $args)
    {
        ProcessManager\AccessManager::getInstance()->verifyAccess($api, $args);
        $emailTemplate = $this->getPMSEBusinessRuleExporter();
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

        return $emailTemplate->exportProject($args['record'], $api);
    }

    /*
     * @return PMSEBusinessRuleExporter
     */
    public function getPMSEBusinessRuleExporter()
    {
        return ProcessManager\Factory::getPMSEObject('PMSEBusinessRuleExporter');
    }
}
