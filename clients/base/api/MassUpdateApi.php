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
 * Mass Update API implementation
 */
class MassUpdateApi extends SugarApi {

    /**
     * This function registers the mass update Rest api
     */
    public function registerApiRest() {
        return array(
            'massUpdatePut' => array(
                'reqType' => 'PUT',
                'path' => array('<module>','MassUpdate'),
                'pathVars' => array('module',''),
                'jsonParams' => array('filter'),
                'method' => 'massUpdate',
                'shortHelp' => 'An API to handle mass update.',
                'longHelp' => 'include/api/help/module_massupdate_put_help.html',
            ),
            'massUpdateDelete' => array(
                'reqType' => 'DELETE',
                'path' => array('<module>','MassUpdate'),
                'pathVars' => array('module',''),
                'jsonParams' => array('filter'),
                'method' => 'massDelete',
                'shortHelp' => 'An API to handle mass delete.',
                'longHelp' => 'include/api/help/module_massupdate_delete_help.html',
            ),
        );
    }

    /**
     * @var bool to indicate whether this is a request to delete records
     */
    protected $delete = false;

    /**
     * @var string job id
     */
    protected $jobId = null;

    /**
     * @var if we are dealing with these mass update vars then need to check for _type vars to determine add/replace
     */
    protected $massUpdateVars = array('team_name','tag');

    /**
     * To perform mass delete
     * @param ServiceBase $api The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param array $args The arguments array passed in from the API
     * @return String
     */
    public function massDelete(ServiceBase $api, array $args)
    {
        $this->requireArgs($args, array('massupdate_params', 'module'));
        $this->delete = true;
        $args['massupdate_params']['Delete'] = true;
        
        if (empty($args['massupdate_params']['uid'])) {
            throw new SugarApiExceptionMissingParameter('Missing required parameter uid');
        }

        return $this->massUpdate($api, $args);
    }

    /**
     * To perform massupdate, either update or delete, based on the args parameter
     * @param ServiceBase $api The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param array $args The arguments array passed in from the API
     * @return String
     */
    public function massUpdate(ServiceBase $api, array $args)
    {
        $this->requireArgs($args, array('massupdate_params', 'module'));

        $mu_params = $args['massupdate_params'];
        $mu_params['module'] = $args['module'];

        if (empty($mu_params['uid'])) {
            throw new SugarApiExceptionMissingParameter('Missing required parameter uid');
        }

        // In some cases, a field will be sent with a *_type arg that maps to it
        // This is used in cases like team_name and tag, where the user might want
        // to override OR append values on the request
        $mu_params = $this->handleTypeAdjustments($mu_params);

        // check ACL
        $bean = BeanFactory::newBean($mu_params['module']);
        if (!$bean instanceof SugarBean) {
            throw new SugarApiExceptionInvalidParameter("Invalid bean, is module valid?");
        }
        $action = $this->delete? 'delete': 'save';
        if (!$bean->ACLAccess($action))
        {
            throw new SugarApiExceptionNotAuthorized('No access to mass update records for module: '.$mu_params['module']);
        }
        $mu_params['action'] = $action;

        $massUpdateJob = new SugarJobMassUpdate();
        $result = $massUpdateJob->runUpdate($mu_params);
        $result['status'] = 'done';

        return $result;
    }

    /**
     * This function returns job id.
     * @return String job id
     */
    public function getJobId()
    {
        return $this->jobId;
    }

    /**
     * Handles modifying values of *_type fields to strings from boolean int
     * values. This is used by team_name and tag primarily
     *
     * @param array $params The params sent in the request
     * @return array
     */
    protected function handleTypeAdjustments($params)
    {
        foreach ($this->massUpdateVars as $massUpdateVar) {
            if (isset($params[$massUpdateVar])) {
                // check if there is an _type variable
                $typeVar = $massUpdateVar . '_type';
                if (isset($params[$typeVar]) && ($params[$typeVar] == '1')) {
                    // its an add operation
                    $params[$typeVar] = 'add';
                } else {
                    // its a replace operation
                    $params[$typeVar] = 'replace';
                }
            }
        }

        return $params;
    }
}
