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


class KBContentsUsefulnessApi extends ModuleApi
{
    public function registerApiRest()
    {
        return array(
            'useful' => array(
                'reqType' => 'PUT',
                'path' => array('KBContents', '?', 'useful'),
                'pathVars' => array('module', 'record', 'useful'),
                'method' => 'voteUseful',
                'shortHelp' => 'This method votes a record of the specified type as useful',
                'longHelp' => 'include/api/help/kb_vote_put_help.html',
            ),
            'notuseful' => array(
                'reqType' => 'PUT',
                'path' => array('KBContents', '?', 'notuseful'),
                'pathVars' => array('module', 'record', 'notuseful'),
                'method' => 'voteNotUseful',
                'shortHelp' => 'This method votes a record of the specified type as not useful',
                'longHelp' => 'include/api/help/kb_vote_put_help.html',
            ),
        );
    }

    /**
     * This method votes a record of the specified type as a useful or not useful.
     *
     * @param ServiceBase $api
     * @param array $args
     * @param bool $isUseful
     *
     * @throws SugarApiExceptionNotAuthorized
     *
     * @return array An array version of the SugarBean with only the requested fields (also filtered by ACL)
     */
    protected function vote(ServiceBase $api, array $args, $isUseful)
    {
        $this->requireArgs($args, array('module', 'record'));
        $bean = $this->loadBean($api, $args, 'view');

        if (!$bean->ACLAccess('view')) {
            // No create access so we construct an error message and throw the exception
            $failed_module_strings = return_module_language($GLOBALS['current_language'], $args['module']);
            $moduleName = $failed_module_strings['LBL_MODULE_NAME'];
            $exceptionArgs = null;
            if (!empty($moduleName)) {
                $exceptionArgs = array('moduleName' => $moduleName);
            }
            throw new SugarApiExceptionNotAuthorized(
                'EXCEPTION_VOTE_USEFULNESS_NOT_AUTHORIZED',
                $exceptionArgs,
                $args['module']
            );
        }

        $bean->load_relationship('usefulness');
        $bean->usefulness->vote($isUseful);

        //user set `useful`
        if ($isUseful) {
            //we need to correct `notuseful` if user voted `not useful` before
            if ($bean->usefulness_user_vote == -1) {
                $bean->notuseful--;
            }
            if ($bean->usefulness_user_vote != 1) {
                $bean->useful++;
            }
        } else {
            //we need to correct `useful` if user voted `useful` before
            if ($bean->usefulness_user_vote == 1) {
                $bean->useful--;
            }
            if ($bean->usefulness_user_vote != -1) {
                $bean->notuseful++;
            }
        }
        $bean->saveUsefulness();

        $bean = BeanFactory::getBean($bean->module_dir, $bean->id, array('use_cache' => false));
        $api->action = 'view';
        $data = $this->formatBean($api, $args, $bean);

        return $data;
    }

    /**
     * This method votes a record of the specified type as a useful.
     *
     * @param ServiceBase $api
     * @param array $args
     *
     * @return array An array version of the SugarBean with only the requested fields (also filtered by ACL)
     */
    public function voteUseful(ServiceBase $api, array $args)
    {
        return $this->vote($api, $args, true);
    }

    /**
     * This method votes a record of the specified type as a not useful.
     *
     * @param ServiceBase $api
     * @param array $args
     *
     * @return array An array version of the SugarBean with only the requested fields (also filtered by ACL)
     */
    public function voteNotUseful(ServiceBase $api, array $args)
    {
        return $this->vote($api, $args, false);
    }
}
