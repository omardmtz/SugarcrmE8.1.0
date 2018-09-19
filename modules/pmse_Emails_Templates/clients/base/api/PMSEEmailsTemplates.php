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

class PMSEEmailsTemplates extends vCardApi
{
    public function __construct()
    {
        $this->crmDataWrapper = ProcessManager\Factory::getPMSEObject('PMSECrmDataWrapper');
    }

    public function registerApiRest()
    {
        return array(
            'emailTemplateDownload' => array(
                'reqType' => 'GET',
                'path' => array('pmse_Emails_Templates', '?', 'etemplate'),
                'pathVars' => array('module', 'record', ''),
                'method' => 'emailTemplateDownload',
                'rawReply' => true,
                'allowDownloadCookie' => true,
                'acl' => 'view',
                'shortHelp' => 'Exports a .pet file with a Process Email Templates definition',
                'longHelp'  => 'modules/pmse_Emails_Templates/clients/base/api/help/email_templates_export_help.html',
            ),
            'emailTemplatesImportPost' => array(
                'reqType' => 'POST',
                'path' => array('pmse_Emails_Templates', 'file', 'emailtemplates_import'),
                'pathVars' => array('module', '', ''),
                'method' => 'emailTemplatesImport',
                'rawPostContents' => true,
                'acl' => 'create',
                'shortHelp' => 'Imports a Process Email Templates from a .pet file',
                'longHelp'  => 'modules/pmse_Emails_Templates/clients/base/api/help/email_templates_import_help.html',
            ),
            'listVariables' => array(
                'reqType' => 'GET',
                'path' => array('pmse_Emails_Templates', 'variables', 'find'),
                'pathVars' => array('module', '', ''),
                'method' => 'findVariables',
                'acl' => 'view',
                'shortHelp' => 'Get the variable list for a module',
                'longHelp'  => 'modules/pmse_Emails_Templates/clients/base/api/help/email_templates_variable_list_get_help.html',
            ),
            'listModulesRelated' => array(
                'reqType' => 'GET',
                'path' => array('pmse_Emails_Templates', '?', 'find_modules'),
                'pathVars' => array('module', '', ''),
                'method' => 'retrieveRelatedBeans',
                'acl' => 'view',
                'shortHelp' => 'Retrieve a list of related modules',
                'longHelp'  => 'modules/pmse_Emails_Templates/clients/base/api/help/email_templates_module_list_get_help.html',
            ),
        );
    }

    /**
     * Finds related modules variables that match the search term.
     *
     * Arguments:
     *    q           - search string
     *    module_list -  one of the keys from $modules
     *    order_by    -  columns to sort by (one or more of $sortableColumns) with direction
     *                   ex.: name:asc,id:desc (will sort by last_name ASC and then id DESC)
     *    offset      -  offset of first record to return
     *    max_num     -  maximum records to return
     *
     * @param $api
     * @param $args
     * @return array
     */
    public function findVariables($api, $args)
    {
        // Initialize this var since not all requests send 'q'
        $q = isset($args["q"]) ? $args["q"] : null;
        ProcessManager\AccessManager::getInstance()->verifyAccess($api, $args);
        $offset = 0;
        $limit = (!empty($args["max_num"])) ? (int)$args["max_num"] : 20;
        $orderBy = array();

        if (!empty($args["offset"])) {
            if ($args["offset"] === "end") {
                $offset = "end";
            } else {
                $offset = (int)$args["offset"];
            }
        }

        if (!empty($args["order_by"])) {
            $orderBys = explode(",", $args["order_by"]);

            foreach ($orderBys as $sortBy) {
                $column = $sortBy;
                $direction = "ASC";

                if (strpos($sortBy, ":")) {
                    // it has a :, it's specifying ASC / DESC
                    list($column, $direction) = explode(":", $sortBy);

                    if (strtolower($direction) == "desc") {
                        $direction = "DESC";
                    } else {
                        $direction = "ASC";
                    }
                }

                // only add column once to the order-by clause
                if (empty($orderBy[$column])) {
                    $orderBy[$column] = $direction;
                }
            }
        }

        $records = array();
        $nextOffset = -1;

        if ($offset !== "end") {
            $records = $this->retrieveFields(
                $args["module_list"],
                $direction,
                $limit,
                $offset,
                $args["base_module"],
                $q);
            $totalRecords = $records['totalRecords'];
            $trueOffset = $offset + $limit;

            if ($trueOffset < $totalRecords) {
                $nextOffset = $trueOffset;
            }
        }

        return array(
            "next_offset" => $nextOffset,
            "records" => $records['records'],
        );
}
    public function retrieveFields($filter, $orderBy, $limit, $offset, $baseModule, $q = null)
    {
        global $beanList;
        $pmseRelatedModule = ProcessManager\Factory::getPMSEObject('PMSERelatedModule');
        if (isset($beanList[$filter])) {
            $newModuleFilter = $filter;
        } else {
            $newModuleFilter = $pmseRelatedModule->getRelatedModuleName($baseModule, $filter);
        }

        $output = array();
        $moduleBean = BeanFactory::newBean($newModuleFilter);
        $fieldsData = isset($moduleBean->field_defs) ? $moduleBean->field_defs : array();
        foreach ($fieldsData as $field) {
            //$retrieveId = isset($additionalArgs['retrieveId']) && !empty($additionalArgs['retrieveId']) && $field['name'] == 'id' ? $additionalArgs['retrieveId'] : false;
            if (isset($field['vname']) && PMSEEngineUtils::isValidField($field, 'ET')) {
                $tmpField = array();
                $tmpField['id'] = $field['name'];
                $tmpField['_module'] = $newModuleFilter;
                $tmpField['name'] = str_replace(':', '', translate($field['vname'], $newModuleFilter));
                $tmpField['rhs_module'] = $filter;
                if (empty($q) || stripos($tmpField['name'], $q) !== false) {
                    $output[] = $tmpField;
                }
            }
        }

        $text = array();
        foreach ($output as $key => $row) {
            $text[$key] = strtolower($row['name']);
        }
        if($orderBy == "ASC"){
            array_multisort($text, SORT_ASC, $output);
        } else {
            array_multisort($text, SORT_DESC, $output);
        }
        $start = $offset;
        $end = $offset + $limit;
        $count = 0;
        $outputTmp = array();
        foreach($output as $field){
            if($count >= $start && $count < $end){
                $outputTmp[] = $field;
            }
            $count++;
        }

        return array('totalRecords' => count($output),'records' => $outputTmp);
    }


    public function retrieveRelatedBeans($api, $args)
    {
        ProcessManager\AccessManager::getInstance()->verifyAccess($api, $args);
        $related_modules = $this->crmDataWrapper->retrieveRelatedBeans($args['module_list'],'one-to-one');
        return $related_modules;
    }

    public function emailTemplatesImport($api, $args)
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
                $importerObject = ProcessManager\Factory::getPMSEObject('PMSEEmailTemplateImporter');
                $name = $_FILES[$first_key]['name'];
                $extension = pathinfo($name,  PATHINFO_EXTENSION);
                if ($extension == $importerObject->getExtension()) {
                    try {
                        $data = $importerObject->importProject($_FILES[$first_key]['tmp_name']);
                    } catch (SugarApiExceptionNotAuthorized $e) {
                        $e->setMessage('ERROR_UPLOAD_ACCESS_ET');
                        PMSELogger::getInstance()->alert($e->getMessage());
                        throw $e;
                    }
                    $result = array('emailtemplates_import' => $data);
                } else  {
                    $sugarApiExceptionRequestMethodFailure = new SugarApiExceptionRequestMethodFailure(
                        'ERROR_UPLOAD_FAILED'
                    );
                    PMSELogger::getInstance()->alert($sugarApiExceptionRequestMethodFailure->getMessage());
                    throw $sugarApiExceptionRequestMethodFailure;
                }
                return $result;
            }
        } else {
            $sugarApiExceptionMissingParameter = new SugarApiExceptionMissingParameter('ERROR_UPLOAD_FAILED');
            PMSELogger::getInstance()->alert($sugarApiExceptionMissingParameter->getMessage());
            throw $sugarApiExceptionMissingParameter;
        }
    }

    public function emailTemplateDownload ($api, $args)
    {
        ProcessManager\AccessManager::getInstance()->verifyAccess($api, $args);
        $emailTemplate = ProcessManager\Factory::getPMSEObject('PMSEEmailTemplateExporter');
        $requiredFields  = array('record', 'module');
        foreach ( $requiredFields as $fieldName ) {
            if ( !array_key_exists($fieldName, $args) ) {
                $sugarApiExceptionMissingParameter = new SugarApiExceptionMissingParameter(
                    'Missing parameter: '.$fieldName
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
}
