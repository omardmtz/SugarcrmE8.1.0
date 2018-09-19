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

require_once('include/export_utils.php');

/*
 * Export API implementation
 */
class ExportApi extends SugarApi
{

    /**
     * This function registers the Rest api
     */
    public function registerApiRest()
    {
        return array(
            'exportGet' => array(
                'reqType' => 'GET',
                'path' => array('<module>', 'export', '?'),
                'pathVars' => array('module', '', 'record_list_id'),
                'method' => 'export',
                'rawReply' => true,
                'allowDownloadCookie' => true,
                'shortHelp' => 'Returns a record set in CSV format along with HTTP headers to indicate content type.',
                'longHelp' => 'include/api/help/module_export_get_help.html',
            ),
        );
    }

    /**
     * Export API
     *
     * @param ServiceBase $api The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param array $args The arguments array passed in from the API
     * @return String
     */
    public function export(ServiceBase $api, array $args = array())
    {
        $seed = BeanFactory::newBean($args['module']);

        if (!$seed->ACLAccess('export')) {
            throw new SugarApiExceptionNotAuthorized($GLOBALS['app_strings']['ERR_EXPORT_DISABLED']);
        }

        ob_start();
        global $sugar_config;
        global $current_user;
        global $app_list_strings;

        $theModule = clean_string($args['module']);

        if ($sugar_config['disable_export'] || (!empty($sugar_config['admin_export_only']) && !(is_admin(
                        $current_user
                    ) || (ACLController::moduleSupportsACL($theModule) && ACLAction::getUserAccessLevel(
                            $current_user->id,
                            $theModule,
                            'access'
                        ) == ACL_ALLOW_ENABLED &&
                        (ACLAction::getUserAccessLevel($current_user->id, $theModule, 'admin') == ACL_ALLOW_ADMIN ||
                            ACLAction::getUserAccessLevel(
                                $current_user->id,
                                $theModule,
                                'admin'
                            ) == ACL_ALLOW_ADMIN_DEV))))
        ) {
            throw new SugarApiExceptionNotAuthorized($GLOBALS['app_strings']['ERR_EXPORT_DISABLED']);
        }

        //check to see if this is a request for a sample or for a regular export
        if (!empty($args['sample'])) {
            //call special method that will create dummy data for bean as well as insert standard help message.
            $content = exportSampleFromApi($args);

        } else {
            $content = exportFromApi($args);
        }

        $filename = $args['module'];
        //use label if one is defined
        if (!empty($app_list_strings['moduleList'][$args['module']])) {
            $filename = $app_list_strings['moduleList'][$args['module']];
        }

        //strip away any blank spaces
        $filename = str_replace(' ', '', $filename);


        if (isset($args['members']) && $args['members'] == true) {
            $filename .= '_' . 'members';
        }
        ///////////////////////////////////////////////////////////////////////////////
        ////	BUILD THE EXPORT FILE
        ob_end_clean();

        return $this->doExport($api, $filename, $content);
    }

    /**
     * Utility method to allow for subclasses to do the same export calls
     *
     * @param ServiceBase $api
     * @param string $filename The File name for the export
     * @param string $content What should be in the exported file
     * @return mixed
     */
    protected function doExport(ServiceBase $api, $filename, $content)
    {
        $api->setHeader("Pragma", "cache");
        $api->setHeader("Content-Type", "application/octet-stream; charset=" . $GLOBALS['locale']->getExportCharset());
        $api->setHeader("Content-Disposition", "attachment; filename={$filename}.csv");
        $api->setHeader("Content-transfer-encoding", "binary");
        $api->setHeader("Expires", "Mon, 26 Jul 1997 05:00:00 GMT");
        $api->setHeader("Last-Modified", TimeDate::httpTime());
        $api->setHeader("Cache-Control", "post-check=0, pre-check=0");
        return $GLOBALS['locale']->translateCharset(
            $content,
            'UTF-8',
            $GLOBALS['locale']->getExportCharset(),
            false,
            true
        );
    }
}
