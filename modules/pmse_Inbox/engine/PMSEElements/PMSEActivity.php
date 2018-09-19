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
 * Description of PMSEActivity
 *
 * @codeCoverageIgnore
 */
class PMSEActivity extends PMSEShape
{
    /**
     *
     * @var type
     */
    protected $definitionBean;

    /**
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->definitionBean = BeanFactory::newBean('pmse_BpmActivityDefinition');
        parent::__construct();
    }

    /**
     *
     * @param type $module
     * @return \PMSEHistoryData
     * @codeCoverageIgnore
     */
    protected function retrieveHistoryData($module)
    {
        $data = ProcessManager\Factory::getPMSEObject('PMSEHistoryData');
        $data->setModule($module);
        return $data;
    }

    /**
     * @param null $id
     * @return Lead|Opportunity|pmse_Inbox|Team|User
     * @codeCoverageIgnore
     */
    protected function retrieveUserData($id = null)
    {
        return BeanFactory::getBean('Users', $id);
    }

    /**
     * @param $id
     * @return Lead|Opportunity|pmse_Inbox|Team|User
     * @codeCoverageIgnoreØ
     */
    protected function retrieveTeamData($id)
    {
        return BeanFactory::getBean('Teams', $id);
    }

    /**
     *
     * @param type $id
     * @return type
     * @codeCoverageIgnore
     */
    protected function retrieveDefinitionData($id)
    {
        $this->definitionBean->retrieve($id);
        return $this->definitionBean->fetched_row;
    }
}
