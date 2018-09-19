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

require_once 'modules/ACLRoles/ACLRole.php';

use Sugarcrm\Sugarcrm\ProcessManager;

/**
 * Class PMSEWrapperCrmData
 *
 * The ADAMWrapperCrmData is a class part of the family of wrapper classes
 * that encapsulates a certain amount of logic in order to read records in
 * BpmActivityDefinition, BpmProcessDefinition, BpmProcess, BpmProject
 *
 * @package PMSE
 */
class PMSECrmDataWrapper implements PMSEObservable
{
    /**
     *
     * @var type
     */
    protected $defaultDynaform;

    /**
     *
     * @var type
     */
    protected $beanFactory;

    /**
     *
     * @var type
     */
    protected $studioBrowser;

    /**
     *
     * @var type
     */
    protected $processDefinition;

    /**
     *
     * @var type
     */
    protected $activityDefinitionBean;

    /**
     *
     * @var type
     */
    protected $dynaformBean;

    /**
     *
     * @var type
     */
    protected $teamsBean;

    /**
     *
     * @var type
     */
    protected $projectBean;

    /**
     *
     * @var type
     */
    protected $processBean;

    /**
     *
     * @var type
     */
    protected $activityBean;

    /**
     *
     * @var type
     */
    protected $ruleSetBean;

    /**
     *
     * @var type
     */
    protected $emailTemplateBean;

    /**
     *
     * @var type
     */
    protected $inboxBean;

    /**
     *
     * @var type
     */
    protected $usersBean;

    /**
     *
     * @var type
     */
    protected $emailBean;

    /**
     *
     * @var type
     */
    protected $inboundEmailBean;

    /**
     *
     * @var type
     */
    protected $sugarQueryObject;

    /**
     *
     * @var type
     *
     */
    protected $beanList;

    /**
     *
     * @var type
     */
    protected $db;

    /**
     *
     * @var array
     */
    protected $observers;

    protected $pmseRelatedModule;

    /**
     *
     * @var PMSELogger
     */
    protected $logger;

    /**
     *
     * @global type $beanList
     * @global type $db
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        /**
         * List of Beans in SugarCRM
         * @global array $GLOBALS ['beanList']
         * @name $beanList
         */
        global $beanList;
        /**
         * Database variable
         * @global array $GLOBALS ['db']
         * @name $db
         */
        global $db;

        $this->defaultDynaform = ProcessManager\Factory::getPMSEObject('PMSEDynaForm');
        $this->teamsBean = BeanFactory::newBean('Teams');
        $this->usersBean = BeanFactory::newBean('Users');
        $this->sugarQueryObject = new SugarQuery();
        $this->pmseRelatedModule = ProcessManager\Factory::getPMSEObject('PMSERelatedModule');


        $this->beanList = $beanList;
        $this->observers = array();
        $this->db = $db;

        $this->logger = PMSELogger::getInstance();
    }

    /**
     * Set global variable $db.
     * @codeCoverageIgnore
     * @param object $db
     * @return void
     */
    public function setDB($db)
    {
        $this->db = $db;
    }

    /**
     * Set global variable $beanList.
     * @codeCoverageIgnore
     * @param object $beanList
     * @return void
     */
    public function setBeanList($beanList)
    {
        $this->beanList = $beanList;
    }

    /**
     * Get class BeanFactory.
     * @codeCoverageIgnore
     * @return object
     */
    public function getBeanFactory()
    {
        if (!isset($this->beanFactory) || empty($this->beanFactory)) {
            $this->beanFactory = new BeanFactory();
        }
        return $this->beanFactory;
    }

    /**
     * Set class BeanFactory.
     * @codeCoverageIgnore
     * @param object $beanFactory
     * @return void
     * @deprecated since version pmse2
     */
    public function setBeanFactory($beanFactory)
    {
        $this->beanFactory = $beanFactory;
    }


    /**
     * Get custom bean class.
     * @return object
     * @codeCoverageIgnore
     */
    public function getModuleFilter($newModuleFilter)
    {
        return BeanFactory::newBean($newModuleFilter);
    }

    /**
     * Get class StudioBrowser.
     * @codeCoverageIgnore
     * @return object
     */
    public function getStudioBrowser()
    {
        if (!isset($this->studioBrowser) || empty($this->studioBrowser)) {
            $this->studioBrowser = new StudioBrowser();
        }
        return $this->studioBrowser;
    }

    /**
     * Get class SugarQuery.
     * @codeCoverageIgnore
     * @return object
     */
    public function getSugarQueryObject()
    {
        if (!isset($this->sugarQueryObject) || empty($this->sugarQueryObject)) {
            $this->sugarQueryObject = new SugarQuery();
        }
        return $this->sugarQueryObject;
    }

    /**
     * Set class SugarQuery.
     * @codeCoverageIgnore
     */
    public function setSugarQueryObject($sugarQueryObject)
    {
        $this->sugarQueryObject = $sugarQueryObject;
    }

    /**
     * Set class StudioBrowser.
     * @codeCoverageIgnore
     * @param object $studioBrowser
     * @return void
     */
    public function setStudioBrowser($studioBrowser)
    {
        $this->studioBrowser = $studioBrowser;
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getTeamsBean()
    {
        return $this->teamsBean;
    }

    /**
     *
     * @param type $teamsBean
     * @codeCoverageIgnore
     */
    public function setTeamsBean($teamsBean)
    {
        $this->teamsBean = $teamsBean;
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getUsersBean()
    {
        return $this->usersBean;
    }

    /**
     *
     * @param type $usersBean
     * @codeCoverageIgnore
     */
    public function setUsersBean($usersBean)
    {
        $this->usersBean = $usersBean;
    }


    /**
     * Get class DynaFormBean.
     * @codeCoverageIgnore
     * @return object
     */
    public function getDynaformBean()
    {
        if (!isset($this->dynaformBean) || empty($this->dynaformBean)) {
            $this->dynaformBean = BeanFactory::newBean('pmse_BpmDynaForm'); //new BpmDynaForm();
        }
        return $this->dynaformBean;
    }

    /**
     * Set class DynaFormBean.
     * @codeCoverageIgnore
     * @param object $dynaformBean
     * @return void
     */
    public function setDynaformBean($dynaformBean)
    {
        $this->dynaformBean = $dynaformBean;
    }

    /**
     * Get class BPMNProject.
     * @codeCoverageIgnore
     * @return object
     */
    public function getProjectBean()
    {
        if (!isset($this->projectBean) || empty($this->projectBean)) {
            $this->projectBean = BeanFactory::newBean('pmse_Project'); //new BpmnProject();
        }
        return $this->projectBean;
    }

    /**
     * Set class BPMNProject.
     * @codeCoverageIgnore
     * @param object $projectBean
     * @return void
     */
    public function setProjectBean($projectBean)
    {
        $this->projectBean = $projectBean;
    }

    /**
     * Get class BPMNProcess.
     * @codeCoverageIgnore
     * @return object
     */
    public function getProcessBean()
    {
        if (!isset($this->processBean) || empty($this->processBean)) {
            $this->processBean = BeanFactory::newBean('pmse_BpmnProcess'); //new BpmnProcess();
        }
        return $this->processBean;
    }

    /**
     * Set class BPMNProcess.
     * @codeCoverageIgnore
     * @param object $processBean
     * @return void
     */
    public function setProcessBean($processBean)
    {
        $this->processBean = $processBean;
    }

    /**
     * Get class BPMNActivity.
     * @codeCoverageIgnore
     * @return object
     */
    public function getActivityBean()
    {
        if (!isset($this->activityBean) || empty($this->activityBean)) {
            $this->activityBean = BeanFactory::newBean('pmse_BpmnActivity'); //new BpmnActivity();
        }
        return $this->activityBean;
    }

    /**
     * Set class BPMNActivity.
     * @codeCoverageIgnore
     * @param object $activityBean
     * @return void
     */
    public function setActivityBean($activityBean)
    {
        $this->activityBean = $activityBean;
    }

    /**
     * Get class BPMNProcessDefinition.
     * @codeCoverageIgnore
     * @return object
     */
    public function getProcessDefinition()
    {
        if (!isset($this->processDefinition) || empty($this->processDefinition)) {
            $this->processDefinition = BeanFactory::newBean(
                'pmse_BpmProcessDefinition'
            ); //new BpmProcessDefinition();
        }
        return $this->processDefinition;
    }

    /**
     * Set class BPMNProcessDefinition.
     * @codeCoverageIgnore
     * @param object $processDefinitionBean
     * @return void
     */
    public function setProcessDefinition($processDefinitionBean)
    {
        $this->processDefinition = $processDefinitionBean;
    }

    /**
     * Get class BPMNActivityDefinition.
     * @codeCoverageIgnore
     * @return object
     */
    public function getActivityDefinitionBean()
    {
        if (!isset($this->activityDefinitionBean) || empty($this->activityDefinitionBean)) {
            $this->activityDefinitionBean = BeanFactory::newBean(
                'pmse_BpmActivityDefinition'
            ); //new BpmActivityDefinition();
        }
        return $this->activityDefinitionBean;
    }

    /**
     * Set class BPMNActivityDefinition.
     * @codeCoverageIgnore
     * @param object $activityDefinitionBean
     * @return void
     */
    public function setActivityDefinitionBean($activityDefinitionBean)
    {
        $this->activityDefinitionBean = $activityDefinitionBean;
    }

    /**
     * Get class BPMRuleSet.
     * @codeCoverageIgnore
     * @return object
     */
    public function getRuleSetBean()
    {
        if (!isset($this->ruleSetBean) || empty($this->ruleSetBean)) {
            $this->ruleSetBean = BeanFactory::newBean('pmse_Business_Rules'); //new BpmRuleSet();
        }
        return $this->ruleSetBean;
    }

    /**
     * Set class BPMRuleSet.
     * @codeCoverageIgnore
     * @param object $ruleSetBean
     * @return void
     */
    public function setRuleSetBean($ruleSetBean)
    {
        $this->ruleSetBean = $ruleSetBean;
    }

    /**
     * Get class BPMEmailTemplate.
     * @codeCoverageIgnore
     * @return object
     */
    public function getEmailTemplateBean()
    {
        if (!isset($this->emailTemplateBean) || empty($this->emailTemplateBean)) {
            $this->emailTemplateBean = BeanFactory::newBean('pmse_Emails_Templates'); //new BpmEmailTemplate();
        }
        return $this->emailTemplateBean;
    }

    /**
     * Set class BPMEmailTemplate.
     * @codeCoverageIgnore
     * @param object $emailTemplateBean
     * @return void
     */
    public function setEmailTemplateBean($emailTemplateBean)
    {
        $this->emailTemplateBean = $emailTemplateBean;
    }

    /**
     * Get class BPMInbox.
     * @codeCoverageIgnore
     * @return object
     */
    public function getInboxBean()
    {
        if (!isset($this->inboxBean) || empty($this->inboxBean)) {
            $this->inboxBean = BeanFactory::newBean('pmse_Inbox'); //new BpmInbox();
        }
        return $this->inboxBean;
    }

    /**
     * Set class BPMInbox.
     * @codeCoverageIgnore
     * @param object $inboxBean
     * @return void
     */
    public function setInboxBean($inboxBean)
    {
        $this->inboxBean = $inboxBean;
    }

    /**
     * Get class Email.
     * @codeCoverageIgnore
     * @return object
     */
    public function getEmailBean()
    {
        if (!isset($this->emailBean) || empty($this->emailBean)) {
            $this->emailBean = new Email();
        }
        return $this->emailBean;
    }

    /**
     * Set class Email.
     * @codeCoverageIgnore
     * @return object
     */
    public function setEmailBean($emailBean)
    {
        $this->emailBean = $emailBean;
    }

    /**
     * Get class InboundEmail.
     * @codeCoverageIgnore
     * @return object
     */
    public function getInboundEmailBean()
    {
        if (!isset($this->inboundEmailBean) || empty($this->inboundEmailBean)) {
            $this->inboundEmailBean = new InboundEmail();
        }
        return $this->inboundEmailBean;
    }

    /**
     * Set class InboundEmail.
     * @codeCoverageIgnore
     * @return object
     */
    public function setInboundEmailBean($inboundEmailBean)
    {
        $this->inboundEmailBean = $inboundEmailBean;
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    public function getDefaultDynaform()
    {
        return $this->defaultDynaform;
    }

    /**
     *
     * @param type $defaultDynaform
     * @codeCoverageIgnore
     */
    public function setDefaultDynaform($defaultDynaform)
    {
        $this->defaultDynaform = $defaultDynaform;
    }

    /**
     *
     * @return type
     * @codeCoverageIgnore
     */
    function getObservers()
    {
        return $this->observers;
    }

    /**
     *
     * @param type $observers
     * @codeCoverageIgnore
     */
    function setObservers($observers)
    {
        $this->observers = $observers;
    }

    /**
     * @codeCoverageIgnore
     */
    public function _get(array $args, ModuleApi $moduleApi)
    {
        $output = null;
        $data = $args['data'];
        $filter = isset($args['filter']) ? $args['filter'] : '';
        $type = isset($args['call_type']) ? $args['call_type'] : '';
        $baseModule = isset($args['base_module']) ? $args['base_module'] : '';
        $orderBy = isset($args['order_by']) ? $args['order_by'] : '';

        $outputType = 0;
        switch ($data) {
            case 'emails':
                $output = $this->retrieveEmails($filter);
                break;
            case 'teams':
                $output = $this->retrieveTeams($filter);
                break;
            case 'fields':
                $output = $this->retrieveFields($filter, $moduleApi, $type, $baseModule);
                $outputType = 1;
                break;
            case 'relatedfields':
                $output = $this->retrieveFields($filter, $moduleApi, $type, $baseModule);
                $outputType = 1;
                break;
            case 'allFields':
                $output = $this->getTargetAndRelatedFields($filter);
                $outputType = 1;
                break;
            case 'oneToOneFields':
                $output = $this->getTargetAndRelatedFields($filter, 'one-to-one');
                $outputType = 1;
                break;
            case 'oneToManyFields':
                $output = $this->getTargetAndRelatedFields($filter, 'one-to-many');
                $outputType = 1;
                break;
            case 'fields_ruleset':
                $output = $this->retrieveFields($filter);
                break;
            case 'users':
                $output = $this->retrieveUsers($filter);
                break;
            case 'modules':
                $output = $this->retrieveModules($filter);
                break;
            case 'related_modules':
                $output = $this->retrieveRelatedModules($filter);
                break;
            case 'dynaforms':
                $output = $this->retrieveDynaforms($filter);
                break;
            case 'activities':
                $output = $this->retrieveActivities($filter);
                break;
            case 'process_definition':
                $output = $this->retrieveProcessDefinition($filter);
                break;
            case 'related':
                $cardinality = isset($args['cardinality']) ? $args['cardinality'] : 'all';
                $output = $this->retrieveRelatedBeans($filter, $cardinality);
                $outputType = 1;
                break;
            case 'oneToOneRelatedModules':
                $output = $this->retrieveRelatedBeans($filter, 'one-to-one');
                $outputType = 1;
                break;
            case 'rulesets':
                $output = $this->retrieveRuleSets($filter, $orderBy);
                break;
            case 'businessrules':
                $output = $this->retrieveBusinessRules($filter);
                break;
            case 'emailtemplates':
                $output = $this->retrieveEmailTemplates($filter);
                break;
            case 'validateProjectName':
                $output = $this->validateProjectName($filter);
                $outputType = 1;
                break;
            case 'validateEmailTemplateName':
                $output = $this->validateEmailTemplateName($filter, $args['id']);
                break;
            case 'validateBusinessRuleName':
                $output = $this->validateBusinessRuleName($filter);
                break;
            case 'defaultUsersList':
                $output = $this->defaultUsersList();
                break;
            case 'rolesList':
                $output = $this->rolesList();
                break;
            case 'dateFields':
                $output = $this->retrieveDateFields($args['module']);
                $outputType = 1;
                break;
            case 'validateReclaimCase':
                $output = $this->validateReclaimCase($args['cas_id'], $args['cas_index']);
                $outputType = 1;
                break;
            case 'addRelatedRecord':
                $output = $this->retrieveFields($filter, $moduleApi, 'AC', $baseModule);
                //$output = $this->addRelatedRecord($filter);
                $outputType = 1;
                break;
            case 'allRelated':
                $output = $this->getAllRelated($filter, $moduleApi, 'all', $baseModule, $type);
                $outputType = 1;
                break;
            case 'oneToOneRelated':
                $output = $this->getAllRelated($filter, $moduleApi, 'one-to-one', $baseModule, $type);
                $outputType = 1;
                break;
            case 'oneToManyRelated':
                $output = $this->getAllRelated($filter, $moduleApi, 'one-to-many', $baseModule, $type);
                $outputType = 1;
                break;
            //case 'Log':
            //    $output = $this->retrieveHistoryLog($filter);
            //    $outputType = 1;
            //    break;
            default:
                $outputType = 2;
        }
        switch ($outputType) {
            case 0:
                $out = $this->retrieveCrmData($output, $filter);
                break;
            case 1:
                $out = $output;
                break;
            case 2:
                $out = $this->invalidRequest();
                break;
        }
        return $out;
    }

    /**
     * Return the structure of an invalid request.
     * @codeCoverageIgnore
     */
    public function invalidRequest()
    {
        $response = array('success' => false, 'message' => 'Invalid Request');
        return $response;
    }

//    public function retrieveHistoryLog($filter)
//    {
////        if (!isset($this->wrapperHistoryLog) || empty($this->wrapperHistoryLog)) {
//            $wrapperHistoryLog = new PMSEHistoryLogWrapper();
////        }
//
//        //return $this->wrapperHistoryLog;
//        $result = $wrapperHistoryLog->pppppp($filter);
//        return $result;
//    }

    /**
     * @codeCoverageIgnore
     */
    public function retrieveEmails($filter)
    {
        if ($filter === '') {
            return $this->invalidRequest();
        }
        $out = array();

        $email = $this->getEmailBean();
        $email->email2init();
        $ie = $this->getInboundEmailBean();
        $ie->email = $email;

        $query = "SELECT users.id, users.first_name, users.last_name, eabr.primary_address, ea.email_address,
        		  	'Users' module FROM users JOIN email_addr_bean_rel eabr ON
        		  	(users.id = eabr.bean_id and eabr.deleted=0) JOIN email_addresses ea ON
        		  	(eabr.email_address_id = ea.id)  WHERE (users.deleted = 0 AND eabr.primary_address = 1)
        		  	AND (first_name LIKE '$filter%' OR last_name LIKE '$filter%' OR email_address LIKE '$filter%')
				  	UNION ALL
					SELECT contacts.id, contacts.first_name, contacts.last_name, eabr.primary_address,
					ea.email_address, 'Contacts' module FROM contacts JOIN email_addr_bean_rel eabr ON
					(contacts.id = eabr.bean_id and eabr.deleted=0) JOIN email_addresses ea ON
					(eabr.email_address_id = ea.id)  WHERE (contacts.deleted = 0 AND eabr.primary_address = 1)
					AND (first_name LIKE '$filter%' OR last_name LIKE '$filter%' OR email_address LIKE '$filter%')
					UNION ALL
					SELECT leads.id, leads.first_name, leads.last_name, eabr.primary_address, ea.email_address,
					'Leads' module FROM leads JOIN email_addr_bean_rel eabr ON
					(leads.id = eabr.bean_id and eabr.deleted=0) JOIN email_addresses ea ON
					(eabr.email_address_id = ea.id)  WHERE (leads.deleted = 0 AND eabr.primary_address = 1)
					AND (first_name LIKE '$filter%' OR last_name LIKE '$filter%' OR email_address LIKE '$filter%')
					UNION ALL
					SELECT prospects.id, prospects.first_name, prospects.last_name, eabr.primary_address,
					ea.email_address, 'Prospects' module FROM prospects JOIN email_addr_bean_rel eabr ON
					(prospects.id = eabr.bean_id and eabr.deleted=0) JOIN email_addresses ea ON
					(eabr.email_address_id = ea.id)  WHERE (prospects.deleted = 0 AND eabr.primary_address = 1)
					AND (first_name LIKE '$filter%' OR last_name LIKE '$filter%' OR email_address LIKE '$filter%')
					UNION ALL
					SELECT accounts.id, '' first_name, accounts.name last_name, eabr.primary_address,
					ea.email_address, 'Accounts' module FROM accounts JOIN email_addr_bean_rel eabr ON
					(accounts.id = eabr.bean_id and eabr.deleted=0) JOIN email_addresses ea ON
					(eabr.email_address_id = ea.id)  WHERE (accounts.deleted = 0 AND eabr.primary_address = 1)
					AND (email_address LIKE '$filter%' OR name LIKE '$filter%')";
        $r = $ie->db->limitQuery($query, 0, 25, true);

        while ($a = $ie->db->fetchByAssoc($r)) {
            $person = array();
            $person['fullName'] = $a['first_name'] . ' ' . $a['last_name'];
            $person['emailAddress'] = $a['email_address'];
            $person['id'] = $a['id'];
            $out[] = $person;
        }

        return $out;
    }

    /**
     * Returns the name of the module related to the target module based on the relationship
     * @param $linkField Relationship Key
     * @param $targetModule Target Module
     */
    public function getRelatedModule($linkField, $targetModule) {
        $baseModule = BeanFactory::newBean($targetModule);
        $baseModule->load_relationship($linkField);
        if (isset($baseModule->$linkField)) {
            $moduleName = $baseModule->$linkField->getRelatedModuleName();
        } else {
            $moduleName = $targetModule;
        }
        return $moduleName;
    }

    /**
     * Retrieve list of Teams
     * @param string $filter
     * @return object
     */
    public function retrieveTeams($filter = '')
    {
        $beansTeams = $this->getTeamsBean();
        $output = array();

        $q = $this->sugarQueryObject;
        $q->from($beansTeams, array('add_deleted' => true));
        $q->distinct(false);
        $fields = array(
            'id',
            'name',
            'name2',
        );

        if ($filter == 'public' || $filter == 'reassign') {
            $q->where()
                ->equals('private', 0);
        } else {
            if ($filter == 'private') {
                $q->where()
                    ->equals('private', 1);
            }
        }

        $q->orderBy('id', 'ASC');
        $q->select($fields);

        $teamsData = $q->execute();
        foreach ($teamsData as $team) {
            $teamTmp = array();
            $teamTmp['value'] = $team['id'];
            $teamTmp['text'] = $team['name'];
            if (($team['id'] != 'current_team') || ($team['id'] == 'current_team' && $filter == 'reassign')) {
                $output[] = $teamTmp;
            }
        }

        return $output;
    }

    /**
     * Retrieve list of Users
     * @param string $filter
     * @return object
     */
    public function retrieveUsers($filter = '')
    {
        //$beanFactory = $this->getBeanFactory();
        $res = new stdClass();
        $res->search = $filter;
        $res->success = true;
        $output = array();
        $where = 'users.deleted = 0 ';
        $where .= ' AND users.status = \'Active\' ';
        $where .= ' AND NOT (users.is_group = 1 OR users.portal_only = 1)';

        if (!empty($filter)) {
            $where .= ' AND (users.first_name LIKE \'%' . $filter . '%\' ';
            $where .= 'OR users.last_name LIKE \'%' . $filter . '%\' ';
            $where .= 'OR users.user_name LIKE \'%' . $filter . '%\' )';
        }

        $order = 'users.first_name, users.last_name';

        $usersData = $this->usersBean->get_full_list($order, $where);
        if (is_array($usersData)) {
            foreach ($usersData as $user) {
                $userTmp = array();
                $userTmp['value'] = $user->id;
                $userFullName = $this->teamsBean->getDisplayName($user->first_name, $user->last_name);
                $userTmp['text'] = $userFullName;

                $output[] = $userTmp;
            }
        }
        $res->result = $output;
        return $output;
    }


    /**
     * @codeCoverageIgnore
     */
    public function getRelationshipData($relationName)
    {
        return SugarRelationshipFactory::getInstance()->getRelationshipDef($relationName);
    }

    /**
     * Retrieve the target and a related module based on a relationship
     * then retrieves a list of fields of both modules.
     * @param type $filter
     * @param type $relationship
     * @return array
     */
    public function getTargetAndRelatedFields($filter = '', $relationship = 'all')
    {
        $result = array();
//        $bean = BeanFactory::getBean($args['module'], $args['id']);
        $result['success'] = true;
//        $result['base_module'] = $bean->base_module;
//        $result['name'] = $bean->name;
//        $result['description'] = $bean->description;
//        $result['subject'] = $bean->subject;
//        $result['body_html'] = $bean->body_html;
//        $result['body'] = $bean->body;
//        $result['text_only'] = $bean->text_only == 1 ? "checked" : "";
//        $result['from_name'] = $bean->from_name;
//        $result['from_address'] = $bean->from_address;

        $fields = $this->retrieveFields($filter);
        if ($fields['success']) {
            $result['fields'] = $fields['result'];
        } else {
            $result['fields'] = array();
        }
        $related_modules = $this->retrieveRelatedBeans($filter, $relationship);
        if ($related_modules['success']) {
            for ($i = 0; $i < count($related_modules['result']); $i++) {
                $fields = $this->retrieveFields($related_modules['result'][$i]['value']);
                $related_modules['result'][$i]["fields"] = $fields['result'];
            }
            $result['related_modules'] = $related_modules['result'];
        } else {
            $result['related_modules'] = array();
        }
        return $result;
    }

    /**
     * Retrieve list of modules installed in SugarCRM
     * @param string $filter
     * @return object
     */
    public function retrieveModules($filter = '')
    {
        $moduleList = PMSEEngineUtils::getStudioModules();
        $output = array();
        foreach ($moduleList as $module) {
            if (!empty($module->name) && (empty($filter) || stripos($module->name, $filter) !== false)) {
                $tmpField = array();
                $tmpField['value'] = $module->module;
                $tmpField['text'] = $module->name;
                $output[] = $tmpField;
            }
        }

        $value = array();
        $text = array();
        foreach ($output as $key => $row) {
            $value[$key] = $row['value'];
            $text[$key] = $row['text'];
        }
        // Sort the data with volume descending, edition ascending.
        // Add $data as the last parameter, to sort by the common key.
        array_multisort($text, SORT_ASC, $output);
        //$res->result = $output;
        return $output;
    }

    /**
     * Retrieve list of DynaForms
     * @param string $filter
     * @return object
     */
    public function retrieveDynaforms($filter = '')
    {
        $dynaformBean = $this->getDynaformBean();
        $projectBean = $this->getProjectBean();
        $processBean = $this->getProcessBean();

        $output = array();
        if ($projectBean->retrieve($filter)) {

            $processBean->retrieve_by_string_fields(array('prj_id' => $projectBean->id));

            $where = '';
            if (!empty($filter)) {
                $where = "pmse_bpm_dynamic_forms.prj_id='" . $projectBean->id . "'";
            }
            $dynaformList = $dynaformBean->get_full_list('', $where);
            foreach ($dynaformList as $dynaform) {
                $tmpDynaform = array();
                $tmpDynaform['value'] = $dynaform->dyn_uid;
                $tmpDynaform['text'] = $dynaform->name;
                $output[] = $tmpDynaform;
            }
        }
        return $output;
    }

    /**
     * Retrieve list of Activities (USERTASK|SCRIPTTASK).
     * @param string $filter
     * @return object
     */
    public function retrieveActivities($filter = '')
    {
        //$activityBean = new BpmnActivity();
        $activityBean = $this->getActivityBean();
        $fields = array(
            'act_uid',
            'name'
        );
        $this->sugarQueryObject->select($fields);
        $this->sugarQueryObject->from($activityBean, array('alias' => 'a'));

        switch (strtolower($filter)) {
            case 'user':
//                $where = 'pmse_bpmn_activity.act_task_type=\'USERTASK\'';
//                $joinedTables = array(
//                    array('INNER', 'pmse_bpm_activity_definition', 'pmse_bpm_activity_definition.id=pmse_bpmn_activity.id'),
//                    array('INNER', 'pmse_bpm_dynamic_forms', 'pmse_bpm_dynamic_forms.dyn_uid=pmse_bpm_activity_definition.act_type')
//                );
//                $select = array('pmse_bpmn_activity.*');
                $where = "a.act_task_type='USERTASK'";
                $this->sugarQueryObject->joinTable('pmse_bpm_activity_definition', array('alias' => 'b'))
                    ->on()->equalsField('b.id', 'a.id');
                $this->sugarQueryObject->joinTable('pmse_bpm_dynamic_forms', array('alias' => 'c'))
                    ->on()->equalsField('c.dyn_uid', 'b.act_type');
                break;
            case 'script':
//                $where = 'pmse_bpmn_activity.act_task_type=\'SCRIPTTASK\'';
//                $joinedTables = array(
//                    array('INNER', 'pmse_bpm_activity_definition', 'pmse_bpm_activity_definition.id=pmse_bpmn_activity.id')
//                );
//                $select = array('pmse_bpmn_activity.*');
                $where = "a.act_task_type='SCRIPTTASK'";
                $this->sugarQueryObject->joinTable('pmse_bpm_activity_definition', array('alias' => 'b'))
                    ->on()->equalsField('b.id', 'a.id');
                break;
            case '':
                $where = '';
//                $joinedTables = array();
//                $select = array();
                break;
            default:
                $where = 'a.act_task_type=\'USERTASK\' AND a.prj_id=\'' . $filter . '\'';
//                $joinedTables = array(
//                    array('INNER', 'pmse_bpm_activity_definition', 'pmse_bpm_activity_definition.id=pmse_bpmn_activity.id'),
//                    array('INNER', 'pmse_bpm_dynamic_forms', 'pmse_bpm_dynamic_forms.dyn_uid=pmse_bpm_activity_definition.act_type'),
//                    array('INNER', 'pmse_project', 'pmse_project.id=pmse_bpmn_activity.prj_id')
//                );
//                $select = array('pmse_bpmn_activity.*');
//                $where = "a.act_task_type='USERTASK' AND d.id='" . $filter . "'";
//                $this->sugarQueryObject->joinRaw(
//                    "INNER JOIN pmse_bpm_activity_definition b ON (b.id=a.id)",
//                    array('alias' => 'b')
//                );
//                $this->sugarQueryObject->joinRaw(
//                    "INNER JOIN pmse_bpm_dynamic_forms c ON (c.dyn_uid=b.act_type)",
//                    array('alias' => 'c')
//                );
                // replaced the a.prj_id for the c.prj_id field in the last join in order to remove duplicate rows
                // $this->sugarQueryObject->joinRaw("INNER JOIN pmse_project d ON (d.id=a.prj_id)", array('alias' => 'd'));
                // $this->sugarQueryObject->joinRaw("INNER JOIN pmse_project d ON (d.id=c.prj_id)", array('alias' => 'd'));
                break;
        }
        $this->sugarQueryObject->where()->queryAnd()
            ->addRaw($where);
        // TODO add debug log here
        $activityList = $this->sugarQueryObject->execute();

//        $activityList = $this->getSelectRows($activityBean,'', $where, 0, -1, -1, $select, $joinedTables);
//        $activityList = $activityList['rowList'];
        $output = array();
        foreach ($activityList as $activity) {
            $tmpACtivity = array();
            $tmpACtivity['value'] = $activity['act_uid'];
            $tmpACtivity['text'] = $activity['name'];
            $output[] = $tmpACtivity;
        }
        //$res->result = $output;
        return $output;
    }

    /**
     * Retrieve JSON with Process Definition.
     * @param $filter
     * @return object
     * @codeCoverageIgnore
     * @deprecated since version branch sugar7bwc
     * TODO: mark as deprecated since the getSelectRows is no longer required and present in the CrmData class
     */
    public function retrieveProcessDefinition($filter)
    {
        //$processDefinitionBean = new BpmProcessDefinition();
        $processDefinitionBean = $this->getProcessDefinition();
//        $res = new stdClass();
//        $res->search = $filter;
//        $res->success = true;
        $where = 'pmse_project.id=\'' . $filter . '\'';
        $joinedTables = array(
            array('INNER', 'pmse_bpmn_process', 'pmse_bpmn_process.id=pmse_bpm_process_definition.id'),
            array('INNER', 'pmse_project', 'pmse_project.id=pmse_bpmn_process.prj_id')
        );

        $processDefinitionList = $this->getSelectRows(
            $processDefinitionBean,
            '',
            $where,
            0,
            -1,
            -1,
            array(),
            $joinedTables
        );
        $processDefinitionList = $processDefinitionList['rowList'];
        $output = array();
        foreach ($processDefinitionList as $processDefinition) {
            $output[] = $processDefinition;
        }
        $output[0]['prj_name'] = $output[0]['name'];
        //$res->result = $output;
        return $output;
    }

    /**
     * Encode the relationships for this module for display in the Ext grid layout
     * @param $relationships
     * @return array
     */
    public function getAjaxRelationships($relationships)
    {
        $ajaxrels = array();
        $relationshipList = $relationships->getRelationshipList();
        foreach ($relationshipList as $relationshipName) {
            $rel = $relationships->get($relationshipName)->getDefinition();
            $rel ['lhs_module'] = translate($rel['lhs_module']);
            $rel ['rhs_module'] = translate($rel['rhs_module']);

            //#28668  , translate the relationship type before render it .
            switch ($rel['relationship_type']) {
                case 'one-to-one':
                    $rel['relationship_type_render'] = translate('LBL_ONETOONE');
                    break;
                case 'one-to-many':
                    $rel['relationship_type_render'] = translate('LBL_ONETOMANY');
                    break;
                case 'many-to-one':
                    $rel['relationship_type_render'] = translate('LBL_MANYTOONE');
                    break;
                case 'many-to-many':
                    $rel['relationship_type_render'] = translate('LBL_MANYTOMANY');
                    break;
                default:
                    $rel['relationship_type_render'] = '';
            }
            $rel ['name'] = $relationshipName;
            if ($rel ['is_custom'] && isset($rel ['from_studio']) && $rel ['from_studio']) {
                $rel ['name'] = $relationshipName . "*";
            }
            $ajaxrels [] = $rel;
        }
        return $ajaxrels;
    }

    /**
     * Retrieve related Bean
     * @param $filter
     * @return object
     */
    public function retrieveRelatedBeans($filter, $relationship = 'all')
    {
        return $this->pmseRelatedModule->getRelatedBeans($filter, $relationship);
    }

    /**
     * Retrieve related modules
     * @param $filter
     * @return object
     */
    public function retrieveRelatedModules($filter)
    {
//        $res = new stdClass();
//        $res->search = $filter;
//        $res->success = true;
        global $beanList;
        if (isset($beanList[$filter])) {
            $newModuleFilter = $filter;
        } else {
            $newModuleFilter = array_search($filter, $beanList);
        }
        $output_11 = array();
        $output_1m = array();
        $output = array();
        $moduleBean = $this->getModuleFilter($newModuleFilter);
        //$relatedModules = is_object($moduleBean) ? $moduleBean->get_linked_fields() : array();
        $relationshipType = array('one-to-one', 'one-to-many', 'many-to-many');
        $ajaxRelationships = '';
        if (is_object($moduleBean)) {
            $relationships = new DeployedRelationships($newModuleFilter);
            $ajaxRelationships = $this->getAjaxRelationships($relationships);
            if ("ProjectTask" == $newModuleFilter) {
                $newModuleFilter = "Project Tasks";
            }
            foreach ($ajaxRelationships as $related) {
                if (($newModuleFilter == $related['lhs_module'] || strtolower(
                            $newModuleFilter
                        ) == $related['lhs_table']) && in_array($related['relationship_type'], $relationshipType)
                ) {
                    $tmpField = array();
                    $tmpField['value'] = $related['rhs_module'];
                    $tmpField['text'] = $related['rhs_module'];
                    $output[] = $tmpField;
                }
            }
        }
        $moduleName = (translate("LBL_MODULE_NAME", $filter) == "LBL_MODULE_NAME") ? $filter : translate("LBL_MODULE_NAME", $filter);
        $filterArray = array('value' => $filter, 'text' => '<' . $moduleName . '>');
        array_unshift($output, $filterArray);

        //$res->result = $output;
        return $output;
    }

    /**
     * @codeCoverageIgnore
     */
    public function _put(array $args)
    {
        $output = null;
        $data = $args['record'];
        $filter = isset($args['filter']) ? $args['filter'] : '';
        switch ($data) {
            case 'project':
                $args['data']['filter'] = $args['filter'];
                $output = $this->updateProcessDefinitions($args['data']);
                break;
            case 'putData': //TODO: needs a change o message
                $args['data']['filter'] = $args['filter'];
                $output = $this->clearAccordingProcessDefinitions($args['data']);
                break;
            case 'clearEventCriteria':
                $output = $this->clearEventCriteria($filter);
                break;
            default:
                $output = $this->invalidRequest();
        }
        return $output;
    }

    /**
     * @codeCoverageIgnore
     */
    public function _post(array $args)
    {

    }

    /**
     * @codeCoverageIgnore
     */
    public function _delete(array $args)
    {

    }

    /**
     * Function to update the definition of a process
     * @param $args
     * @return object
     */
    public function updateProcessDefinitions($args)
    {
        $res = array(); //new stdClass();
        $res['search'] = $args['filter'];
        $res['success'] = false;

        $processDefinitionBean = $this->getProcessDefinition();
        $projectBean = $this->getProjectBean();
        $processBean = $this->getProcessBean();

        $updateDefaultForm = false;

        if ($projectBean->retrieve($args['filter'])) {
            unset($args['prj_uid']);
            $args['prj_id'] = $projectBean->id;
            $processBean->retrieve_by_string_fields(array('prj_id' => $projectBean->id));
            unset($args['pro_uid']);
            $args['pro_id'] = $processBean->id;
            $processDefinitionBean->retrieve_by_string_fields(
                array('prj_id' => $projectBean->id, 'id' => $processBean->id)
            );
            // If there are group fields (e.g: billing_address) in the definition then we need to expand the group field
            // to lock the individual fields which make up the group field.
            $args['pro_locked_variables'] = $this->getMultiLockedFieldsFromGroupField(
                $processDefinitionBean->pro_module,
                $args['pro_locked_variables']
            );

            foreach ($args as $key => $value) {

                if ($key == 'pro_module' && ($processDefinitionBean->$key != $value)) {
                    $updateDefaultForm = true;
                }

                $processDefinitionBean->$key = $value;
                $projectBean->$key = $value;
                $processBean->$key = $value;
            }

            if (isset($args['name']) || isset($args['description'])) {
                $processBean->name = $projectBean->name;
                $projectBean->save();
                $processBean->save();
            }

            $processDefinitionBean->save();
            $this->setProcessDefinition($processDefinitionBean);
            $res['success'] = true;
        }
        if ($updateDefaultForm) {
            $this->defaultDynaform->generateDefaultDynaform($processDefinitionBean->pro_module, $args, true);
        }
        $this->notify();
        return $res;
    }

    // Reference queries
    //$query_bpm_flow = "select flo_id,flo_uid,prj_id,flo_type,flo_name,flo_element_origin,flo_element_origin_type,flo_element_dest,flo_element_dest_type,flo_is_inmediate from bpmn_flow where prj_id=1;";
    //$query_bpm_activity_definition = "select act_field_module,act_fields,act_id,pro_id,act_type from bpm_activity_definition where act_id = 3;"; //pro_id
    //$query_event = "select * from bpm_event_definition;";
    //$queryfield_event = "select evn_module,evn_criteria from bpm_event_definition where pro_id = 1;";
    //select pro_id,evn_id,evn_message from bpmn_event;
    //select act_id,act_name,act_type from bpmn_activity;
    public function clearAccordingProcessDefinitions($args)
    {
        /* TODO: change the hardcoded queries to Bean procedures */
        $res = new stdClass();
        $res->targetModule = isset($args['pro_old_module']) ? $args['pro_old_module'] : '';
        $res->newModule = isset($args['pro_module']) ? $args['pro_module'] : '';
        if (empty($res->newModule)) {
            $res->newModule = isset($args['pro_new_module']) ? $args['pro_new_module'] : '';
        }
        $res->targetProcess = isset($args['filter']) ? $args['filter'] : '';
        $res->success = false;

        if (empty($res->targetModule) || empty($res->newModule) || empty($res->targetProcess)) {
            return $res;
        }

        try {
            global $db;

            //$get_projID_query = "select prj_id from bpmn_project where prj_uid = '$res->targetProcess'";
            $get_projID_query = "select id from pmse_project where id = " . $db->quoted($res->targetProcess);
            $result = $db->Query($get_projID_query);
            $row = $db->fetchByAssoc($result);
//            $projId = $row['prj_id'];
            $projId = $row['id'];

            //$get_proID_query = "select pro_id from bpmn_process where prj_id = $projId";
            $get_proID_query = "select id from pmse_bpmn_process where prj_id = " . $db->quoted($projId);
            $result = $db->Query($get_proID_query);
            $row = $db->fetchByAssoc($result);
//            $proId = $row['pro_id'];
            $proId = $row['id'];

            //$get_actIds = "select act_id from bpm_activity_definition where pro_id = $proId;";
            $get_actIds = "select id from pmse_bpm_activity_definition where pro_id = " . $db->quoted($proId);
            $result = $db->Query($get_actIds);
            $row = $db->fetchByAssoc($result);
            $activity = array();
            while (is_array($row)) {
//                $activity[$row['act_id']] = '';
                $activity[$row['id']] = '';
                $row = $db->fetchByAssoc($result);
            }

//            $get_act_types = "select act_id, act_script_type from bpmn_activity where pro_id = $proId;";
            $get_act_types = "select id, act_script_type from pmse_bpmn_activity where pro_id = " .
                $db->quoted($proId);
            $result = $db->Query($get_act_types);
            $row = $db->fetchByAssoc($result);
            while (is_array($row)) {
//                if (isset($activity[$row['act_id']])) {
//                    $activity[$row['act_id']] = $row['act_script_type'];
                if (isset($activity[$row['id']])) {
                    $activity[$row['id']] = $row['act_script_type'];
                }
                $row = $db->fetchByAssoc($result);
            }

            $cleanActivityFiels = '';
            foreach ($activity as $actId => $actType) {
                if ($actType == 'BUSINESS_RULE') {
                    $cleanActivityFiels = 'NULL';
                } else {
                    if ($actType == 'CHANGE_FIELD') {
                        //getting the new fields to be load 'n show
                        $result = $this->retrieveFields($res->newModule);
                        $cleanActivityFiels = json_encode($result->result);
                    } else {
                        if ($actType == 'ASSIGN_USER') {
                            $cleanActivityFiels = 'NULL'; // ?
                        } else {
                            if ($actType == 'ASSIGN_TEAM') {
                                $cleanActivityFiels = 'NULL'; // ?
                            }
                        }
                    }
                }

                $update_activity = sprintf(
                    "UPDATE pmse_bpm_activity_definition"
                        . " SET act_field_module = %s, act_fields = %s, act_readonly_fields = '',"
                        . " act_required_fields = ''"
                        . " WHERE pro_id = %s AND id = %s",
                    $db->quoted($res->newModule),
                    $db->quoted($cleanActivityFiels),
                    $db->quoted($proId),
                    $db->quoted($actId)
                );

                $resultUpdate = $db->Query($update_activity);
            }
            //cleaning gateways
            //$clear_gatewayConditions = "update bpmn_flow set flo_condition = '' where flo_element_origin in (select gat_id from bpmn_gateway where prj_id = $projId)";
            $clear_gatewayConditions = "update pmse_bpmn_flow set flo_condition = '' where flo_element_origin in (select id from pmse_bpmn_gateway where id = '$projId')";
            $resultUpdate = $db->Query($clear_gatewayConditions);
            //cleaning other events not email type
//            $update_evendef1 = "update bpm_event_definition set evn_module = '$res->newModule', evn_criteria = '', evn_params = '' where evn_id in (select evn_id from bpmn_event where evn_behavior = 'CATCH' and prj_id = $projId and evn_type = 'INTERMEDIATE');";
            $update_evendef1 = "update pmse_bpm_event_definition set evn_module = '$res->newModule', evn_criteria = '', evn_params = '' where id in (select id from pmse_bpmn_event where evn_behavior = 'CATCH' and prj_id = '$projId' and evn_type = 'INTERMEDIATE');";
            $resultUpdate = $db->Query($update_evendef1);
            //cleaning start events
            /* $update_evendef2 = "update bpm_event_definition set evn_module = '$res->newModule', evn_criteria = '[]' where pro_id = $proId and evn_type = 'START';";
              $resultUpdate = $db->Query($update_evendef2);

              $get_event_name = "select evn_name from bpmn_event where pro_id = $proId and evn_type = 'START';";
              $result = $db->Query($get_event_name);
              $row = $db->fetchByAssoc($result);

              $changedEventName = $row['evn_name'];
              $newModule = substr($res->newModule, 0, -1);
              $targetModule = substr($res->targetModule, 0, -1);
              $changedEventName = str_replace($targetModule, $newModule, $changedEventName);
              $messageEvent = strtoupper($newModule);

              $update_event = "update bpmn_event set evn_name = '$changedEventName', evn_message = '$messageEvent' where pro_id = $proId and evn_type = 'START';";
              $resultUpdate = $db->Query($update_event); */

            $res->success = true;
        } catch (Exception $ex) {
            $res->error = $ex->getMessage();
        }
        return $res;
    }

    /**
     * Retrieve list of fields
     * @param string $filter
     * @param array $additionalArgs
     * @return object
     * @codeCoverageIgnore
     */
    public function retrieveFields($filter = '', ModuleApi $moduleApi = null, $type = '', $baseModule = '')
    {
        global $beanList;
        if (isset($beanList[$filter])) {
            $newModuleFilter = $filter;
        } else {
            $newModuleFilter = $this->pmseRelatedModule->getRelatedModuleName($baseModule, $filter);
        }

        $res = array();
        new stdClass();

        $res['name'] = $newModuleFilter;

        //$module = explode('_', $filter);// pull the related module out.
        //$filter = ucfirst(isset($module[1])?$module[1]:$module[0]);
        //$primal_module = ucfirst(isset($module[0])?$module[0]:'');
        $res['search'] = $filter;
        $res['success'] = true;
        $module_strings = return_module_language('en_us', 'ModuleBuilder');

        $fieldTypes = $module_strings['fieldTypes'];
        //add datetimecombo type field from the vardef overrides to point to Datetime type
        $fieldTypes['datetime'] = $fieldTypes['datetimecombo'];
        $fieldTypes['name'] = "Name";

        global $app_list_strings;
        $output = array();
        $groupFields = array();
        $groupFieldsMap = array();

        $moduleBean = $this->getModuleFilter($newModuleFilter);
        $fieldsData = isset($moduleBean->field_defs) ? $moduleBean->field_defs : array();

        foreach ($fieldsData as $field) {
            $tmpField = array();
            if (isset($field['vname']) && (PMSEEngineUtils::isValidField($field, $type))) {
                // If this is a locked field list AND this field is part of a group
                if ($type === 'RR' && !empty($field['group'])) {
                    // If the group field for this field exists in vardefs then we can skip this field
                    // since it will be covered by the other field
                    // example : phone_alternate has phone_office as its group field and phone_office is defined
                    // in vardefs
                    if (!empty($fieldsData[$field['group']])) {
                        $groupFields[$field['group']] = true;
                    }
                    // If this group field has not been covered before then process it
                    if (empty($groupFields[$field['group']])) {
                        $tmpField['value'] = $field['group'];
                        if (!empty($field['group_label'])) {
                            // use group_label as the label if defined
                            $tmpField['text'] = $this->getFormattedFieldLabel($field['group_label'], $newModuleFilter);
                        } else {
                            // If there is no label defined at all then default to group field as being the label
                            $tmpField['text'] = $field['group'];
                        }
                        // mark the group as being covered so that if any other field has the same group then we
                        // can skip it
                        $groupFields[$field['group']] = true;
                    }
                    $groupFieldsMap[$field['name']] = $field['group'];
                } else {
                    if (PMSEEngineUtils::specialFields($field, $type)) {
                        $field = array_merge($field, $this->replaceItemsValues($field));
                    }
                    $tmpField['value'] = $field['name'];
                    $tmpField['text'] = $this->getFormattedFieldLabel($field['vname'], $newModuleFilter);

                    // Handle field typing, starting with the vardef type for this field
                    $tmpField['type'] = $field['type'];

                    // If there is a known type for this type, use THAT
                    if (isset($fieldTypes[$field['type']])) {
                        $tmpField['type'] = $fieldTypes[$field['type']];
                    }

                    $tmpField['optionItem'] = 'none';
                    if (in_array($field['type'], array('enum', 'radioenum', 'multienum'))) {
                        if (!isset($field['options']) || !isset($app_list_strings[$field['options']])) {
                            if (PMSEEngineUtils::specialFields($field, $type)) {
                                $tmpField['optionItem'] = $this->gatewayModulesMethod($field);
                            } elseif ($moduleApi !== null) {
                                $options = getOptionsFromVardef($field);
                                if ($options === false) {
                                    throw ProcessManager\Factory::getException('InvalidData', "No list options found for {$field['name']} in module {$beanModule->getModuleName()}", 1);
                                }
                                $tmpField['optionItem'] = $options;
                            }
                        } else {
                            $tmpField['optionItem'] = $app_list_strings[$field['options']];
                        };
                    }

                    if ($field['type'] == 'bool') {
                        $tmpField['optionItem'] = array("TRUE" => true, "FALSE" => false);
                    }

                    if (isset($field['required'])) {
                        $tmpField['required'] = $field['required'];
                    }
                    if (isset($field['len'])) {
                        $tmpField['len'] = $field['len'];
                    }
                }

                // For dependent field relationships like a dependent parent child dropdown pass some additional
                // information along so that we are able to lock the parent field as well when the child is locked.
                if (isset($field['visibility_grid']) && (isset($field['visibility_grid']['trigger']))) {
                    $tmpField['trigger'] = $field['visibility_grid']['trigger'];
                }

                if (!empty($tmpField)) {
                    $output[] = $tmpField;
                }
            }
        }

        $text = array();
        foreach ($output as $key => $row) {
            $text[$key] = strtolower($row['text']);
        }
        array_multisort($text, SORT_ASC, $output);

        $res['result'] = $output;
        $res['groupFieldsMap'] = $groupFieldsMap;
        return $res;
    }

    /**
     * Method to display related modules fields
     * @param string $filter
     * @param array $additionalArgs
     * @return object
     */
    public function addRelatedRecord($filter = '', $additionalArgs = array())
    {
        global $beanList;
        if (isset($beanList[$filter])) {
            $newModuleFilter = $filter;
        } else {
            $related = $this->getRelationshipData($filter);
            $newModuleFilter = $related['rhs_module'];
        }

        $res = array(); //new stdClass();

        $res['name'] = $newModuleFilter;

        //$module = explode('_', $filter);// pull the related module out.
        //$filter = ucfirst(isset($module[1])?$module[1]:$module[0]);
        //$primal_module = ucfirst(isset($module[0])?$module[0]:'');
        $res['search'] = $filter;
        $res['success'] = true;
        $module_strings = return_module_language('en_us', 'ModuleBuilder');

        $fieldTypes = $module_strings['fieldTypes'];
        //add datetimecombo type field from the vardef overrides to point to Datetime type
        $fieldTypes['datetime'] = $fieldTypes['datetimecombo'];

        global $app_list_strings;
        $output = array();
        $moduleBean = $this->getModuleFilter($newModuleFilter);
        $fieldsData = isset($moduleBean->field_defs) ? $moduleBean->field_defs : array();
        foreach ($fieldsData as $field) {
            $retrieveId = isset($additionalArgs['retrieveId']) && !empty($additionalArgs['retrieveId']) && $field['name'] == 'id' ? $additionalArgs['retrieveId'] : false;
            if (isset($field['vname']) && (PMSEEngineUtils::isValidField($field, 'AC') || $retrieveId)) {
                $tmpField = array();
                $tmpField['value'] = $field['name'];
                $tmpField['text'] = $this->getFormattedFieldLabel($field['vname'], $newModuleFilter);
                $tmpField['type'] = isset($fieldTypes[$field['type']]) ? $fieldTypes[$field['type']] : ucfirst(
                    $field['type']
                );
                $tmpField['type'] = (isset($tmpField['relationship']) && stristr($tmpField['relationship'], 'email'))
                || stristr($tmpField['value'], 'email') ? 'email' : $tmpField['type'];
                $tmpField['optionItem'] = 'none';
                if ($field['type'] == 'enum') {
                    if (!isset($field['options']) || !isset($app_list_strings[$field['options']])) {
                        $tmpField['optionItem'] = null;
                    } else {
                        $tmpField['optionItem'] = $app_list_strings[$field['options']];
                    }
                }
                if (isset($field['required'])) {
                    $tmpField['required'] = $field['required'];
                }
                if (isset($field['len'])) {
                    $tmpField['len'] = $field['len'];
                }
                $output[] = $tmpField;
            }
        }
        $arrayModules = $this->returnArrayModules($newModuleFilter);
        $customfields = false;
        if (count($arrayModules) > 0) {
            $output = array();
            $customfields = true;
        } else {
            $arrayModules = $this->returnArrayModules('All');
        }
        if (count($arrayModules) > 0) {
            foreach ($fieldsData as $field) {
                $newfield = $this->dataFieldPersonalized($field, $arrayModules, $customfields);
                if (isset($field['vname']) && isset($newfield)) {
                    $tmpField = array();
                    $tmpField['value'] = isset($newfield['value']) ? $newfield['value'] : $field['name'];
                    $tmpField['text'] = isset($newfield['text']) ? $newfield['text'] :
                                            $this->getFormattedFieldLabel($field['vname'], $newModuleFilter);
                    $tmpField['type'] = isset($fieldTypes[$newfield['type']]) ? $fieldTypes[$newfield['type']] : ucfirst(
                        $newfield['type']
                    );
                    $tmpField['optionItem'] = 'none';
                    if ($newfield['type'] == 'enum') {
                        $tmpField['optionItem'] = null;
                        if (isset($newfield['method']) && $newfield['method'] != 'default') {
                            $tmpField['optionItem'] = $this->gatewayModulesMethod($newfield['method']);
                        } elseif (isset($newfield['method']) && $newfield['method'] == 'default') {
                            $tmpField['optionItem'] = $app_list_strings[$field['options']];
                        }
                    }
                    if (isset($field['required']) || isset($newfield['required'])) {
                        $tmpField['required'] = isset($newfield['required']) ? $newfield['required'] : $field['required'];
                    }
                    if (isset($field['len'])) {
                        $tmpField['len'] = $field['len'];
                    }
                    $output[] = $tmpField;
                }
            }
        }
        $text = array();
        foreach ($output as $key => $row) {
            $text[$key] = strtolower($row['text']);
        }
        array_multisort($text, SORT_ASC, $output);

        $res['result'] = $output;
        return $res;
    }

    /**
     * Method that returns a list of type date fields
     * @param $filter
     * @return object
     */
    public function retrieveDateFields($filter)
    {
        if (isset($this->beanList[$filter])) {
            $newModuleFilter = $filter;
        } else {
            $related = $this->getRelationshipData($filter);
            $newModuleFilter = $related['rhs_module'];
        }
        $res = array(); //new stdClass();
        $res['name'] = $newModuleFilter;
        $res['search'] = $filter;
        $res['success'] = true;

        $output = array();
        $moduleBean = $this->getModuleFilter($newModuleFilter);
        $fieldsData = isset($moduleBean->field_defs) ? $moduleBean->field_defs : array();
        foreach ($fieldsData as $field) {
            if (isset($field['vname']) && PMSEEngineUtils::isValidField($field)) {
                if ($field['type'] == 'date' || $field['type'] == 'datetimecombo' || $field['type'] == 'datetime') {
                    $tmpField = array();
                    $tmpField['value'] = $field['name'];
                    $tmpField['text'] = $this->getFormattedFieldLabel($field['vname'], $newModuleFilter);
                    $output[] = $tmpField;
                }
            }
        }
        $arr_Now = array();
        $arr_Now['value'] = 'current_date_time';
        $arr_Now['text'] = 'Current Date Time';
        array_unshift($output, $arr_Now);
        $res['result'] = $output;
        return $res;
    }

    /**
     * Retrieve definition of a Business Rules
     * @param $filter
     * @return object
     */
    public function retrieveRuleSets($filter, $orderBy = 'name')
    {
        $processDefinitionBean = $this->getProcessDefinition();
        $projectBean = $this->getProjectBean();
        $ruleSetBean = $this->getRuleSetBean();
        $output = array();
        if ($projectBean->retrieve($filter)) {
            $processDefinitionBean->retrieve_by_string_fields(array('prj_id' => $projectBean->id));
            if (isset($this->beanList[$processDefinitionBean->pro_module])) {
                $newModuleFilter = $processDefinitionBean->pro_module;
            } else {
                $newModuleFilter = array_search($processDefinitionBean->pro_module, $this->beanList);
            }
            $where = 'pmse_business_rules.rst_module=\'' . $newModuleFilter . '\'';
            $ruleSetList = $ruleSetBean->get_full_list($orderBy, $where);

            if (is_array($ruleSetList)) {
                foreach ($ruleSetList as $key => $ruleset) {
                    $tmpArray = array();
                    $tmpArray['value'] = $ruleset->id;
                    $tmpArray['text'] = $ruleset->name;
                    $output[] = $tmpArray;
                }
            }
        }
        //$res->result = $output;
        return $output;
    }

    /**
     * Retrieve list of Business Rules
     * @param $filter
     * @return object
     */
    public function retrieveBusinessRules($filter)
    {
        $processDefinitionBean = $this->getProcessDefinition();
        $activityDefinitionBean = $this->getActivityDefinitionBean();
        $projectBean = $this->getProjectBean();
        $output = array();

        if ($projectBean->retrieve($filter)) {
            $processDefinitionBean->retrieve($projectBean->id);

            $this->sugarQueryObject->select(array('id', 'name'));
            $this->sugarQueryObject->from($activityDefinitionBean, array('alias' => 'a'));
            $this->sugarQueryObject->joinTable('pmse_bpmn_activity', array('alias' => 'b'))
                ->on()->equalsField('b.id', 'a.id');
            $this->sugarQueryObject->where()->queryAnd()
                ->addRaw(
                    "b.prj_id='" . $projectBean->id . "' AND b.act_script_type = 'BUSINESS_RULE'"
                );
            $this->sugarQueryObject->select->fieldRaw('b.name');
            $rows = $this->sugarQueryObject->execute();

            foreach ($rows as $key => $definition) {
                $tmpArray = array();
                $tmpArray['value'] = $definition['id'];
                $tmpArray['text'] = $definition['name'];
                $output[] = $tmpArray;
            }
        }
        return $output;
    }

    /**
     * Retrieve list of Emails Templates
     * @param $module
     * @return array
     */
    public function retrieveEmailTemplates($module)
    {
        $output = array();
        if (!isset($module)) {
            return $output;
        }

        $emailTemplateBean = $this->getEmailTemplateBean();
        $rows = $emailTemplateBean->get_full_list('', "base_module = '$module'");
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $tmpArray = array();
                $tmpArray['text'] = $row->name;
                $tmpArray['value'] = $row->id;
                $output[] = $tmpArray;
            }
        }
        //$res->result = $output;
        return $output;
    }

    public function getBeanModuleName($beanName)
    {
//        global $beanList;
        $beanModuleName = '';
        if (isset($this->beanList[$beanName])) {
            $beanModuleName = $beanName;
        } else {
            $beanModuleName = array_search($beanName, $this->beanList);
        }
        return $beanModuleName;
    }

    /**
     * Validate the project name to avoid duplicate entries
     * @param $projectName
     * @return object
     */
    public function validateProjectName($projectName)
    {
        $res = array(); //new stdClass();
        $res['success'] = true;
//        $projectObject = new BpmnProject();
        $projectObject = $this->getProjectBean();
        $result = true;
        $rsProject = $projectObject->retrieve_by_string_fields(array('name' => $projectName));
        if (!is_null($rsProject)) {
            $result = false;
            $res['message'] = sprintf(
                translate('LBL_PMSE_MESSAGE_THEPROCESSNAMEALREADYEXISTS', 'pmse_Inbox'),
                $projectName
            );
        }
        $res['result'] = $result;
        return $res;
    }

    /**
     * Validate the email template name to avoid duplicate entries
     * @param $emailName
     * @param $id
     * @return object
     */
    public function validateEmailTemplateName($emailName, $id)
    {
        $res = new stdClass();
        $res->success = true;
//        $emailObject = new BpmEmailTemplate();
        $emailObject = $this->getEmailTemplateBean();
        $result = true;
        $where = "name ='" . $emailName . "'" . (isset($id) ? " and id != '" . $id . "'" : '');
        $rsEmail = $emailObject->get_full_list('', $where);

        if (!empty($rsEmail)) {
            $result = false;
            $res->message = sprintf(
                translate('LBL_PMSE_MESSAGE_THEEMAILTEMPLATENAMEALREADYEXISTS', 'ProcessMaker'),
                $emailName
            );
        }
        $res->result = $result;
        return $res;
    }

    /**
     * Validate the business rules name to avoid duplicate entries
     * @param $brName
     * @param null $brId
     * @return object
     */
    public function validateBusinessRuleName($brName, $brId = null)
    {
        $res = array(); //new stdClass();
        $res['success'] = true;
//        $brObject = new BpmRuleSet();
        $brObject = $this->getRuleSetBean();
        $result = true;
        $where = "rst_name ='" . $brName . "'" . (isset($brId) ? " AND rst_uid !='" . $brId . "'" : '');
        $rsBr = $brObject->get_full_list('', $where);
        if (!empty($rsBr)) {
            $result = false;
            $res['message'] = sprintf(
                translate('LBL_PMSE_MESSAGE_BUSINESSRULENAMEALREADYEXISTS', 'ProcessMaker'),
                $brName
            );
        }
        $res['result'] = $result;
        return $res;
    }


    /**
     * Method return array of users (current_user, supervisor, owner)
     * @return object
     */
    public function defaultUsersList()
    {
//        $res = new stdClass();
//        $res->success = true;
        $tmpArray = array(
            array('value' => 'current_user', 'text' => translate('LBL_PMSE_FORM_OPTION_CURRENT_USER', 'pmse_Project')),
            array('value' => 'supervisor', 'text' => translate('LBL_PMSE_FORM_OPTION_SUPERVISOR', 'pmse_Project')),
            array('value' => 'owner', 'text' => translate('LBL_PMSE_FORM_OPTION_RECORD_OWNER', 'pmse_Project'))
        );
        //$res->result = $tmpArray;
        return $tmpArray;
    }

    /**
     * Method that returns the user roles as Sugar
     * @return object
     */
    public function rolesList()
    {
        $userRoles = ACLRole::getAllRoles(true);
        $tmpArray = array();
        $tmpArray[] = array(
            'value' => 'is_admin',
            'text' => translate('LBL_PMSE_FORM_OPTION_ADMINISTRATOR', 'pmse_Project')
        );
        foreach ($userRoles as $role) {
            $tmpArray[] = array('value' => $role['id'], 'text' => $role['name']);
        }
        return $tmpArray;
    }

    /**
     * Method to validate whether a case been claimed
     * @param $casID
     * @param $casIndex
     * @return object
     */
    public function validateReclaimCase($casID, $casIndex)
    {
        //TODO Review functionality
        $res = array(); //new stdClass();
        $res['success'] = true;
        $res['result'] = false;
        //$caseBean = new BpmInbox();
        $caseBean = $this->getInboxBean();
        $this->sugarQueryObject->select(array('a.cas_id'));
        $this->sugarQueryObject->from($caseBean, array('alias' => 'a'));
        $this->sugarQueryObject->joinTable('pmse_bpm_flow', array('joinType' => 'LEFT', 'alias' => 'b'))
            ->on()->equalsField('a.cas_id', 'b.cas_id');
        $this->sugarQueryObject->where()->queryAnd()
            ->addRaw("b.cas_id = $casID and a.cas_index = $casIndex");
        $rows = $this->sugarQueryObject->execute();


        $caseData = $rows[0];
        $res['message'] = translate('LBL_PMSE_LABEL_ERROR_INVALIDCLAIM', 'pmse_Project');
        if ($caseData['cas_start_date'] == '') {
            $res['result'] = true;
        }

        return $res;
    }

    /**
     * @codeCoverageIgnore
     */
    private function returnArrayModules($module)
    {
        $arraymodules = array(
            'Notes' => array(
                array('name' => 'name', 'type' => 'name'),
                array(
                    'name' => 'assigned_user_name',
                    'type' => 'enum',
                    'value' => 'assigned_user_id',
                    'method' => 'assignedUsers'
                ), //TYPE original relate
                array('name' => 'description', 'type' => 'text'),
                array('name' => 'portal_flag', 'type' => 'bool', 'required' => false),
                //array('name'=>'contact_name', 'type'=>'relate')
            ),
            'Tasks' => array(
                array('name' => 'name', 'type' => 'name'),
                array('name' => 'date_start', 'type' => 'datetimecombo'),
                array('name' => 'date_due', 'type' => 'datetimecombo'),
                array('name' => 'priority', 'type' => 'enum', 'method' => 'default'),
                array('name' => 'description', 'type' => 'text'),
                array('name' => 'status', 'type' => 'enum', 'method' => 'default'),
                //array('name'=>'contact_name', 'type'=>'relate'),
                array(
                    'name' => 'assigned_user_name',
                    'type' => 'enum',
                    'value' => 'assigned_user_id',
                    'method' => 'assignedUsers'
                ) //TYPE original relate
            ),
            'Meetings' => array(
                array('name' => 'name', 'type' => 'name'),
                array('name' => 'type', 'type' => 'enum', 'method' => 'default'),
                array('name' => 'date_start', 'type' => 'datetimecombo'),
                array('name' => 'date_end', 'type' => 'datetimecombo'),
                array('name' => 'duration_hours', 'type' => 'int'),
                array('name' => 'reminder_time', 'type' => 'enum', 'text' => 'Reminder Popup', 'method' => 'default'),
                array(
                    'name' => 'email_reminder_time',
                    'type' => 'enum',
                    'text' => 'Reminder Email all invitees',
                    'method' => 'default'
                ),
                array('name' => 'status', 'type' => 'enum', 'method' => 'default'),
                array('name' => 'location', 'type' => 'varchar'),
                array(
                    'name' => 'assigned_user_name',
                    'type' => 'enum',
                    'value' => 'assigned_user_id',
                    'method' => 'assignedUsers'
                ) //TYPE original relate
            ),
            'Calls' => array(
                array('name' => 'name', 'type' => 'name'),
                array('name' => 'date_start', 'type' => 'datetimecombo'),
                array('name' => 'duration_hours', 'type' => 'int'),
                array('name' => 'description', 'type' => 'text'),
                array('name' => 'status', 'type' => 'enum', 'method' => 'default'),
                array('name' => 'reminder_time', 'type' => 'enum', 'text' => 'Reminder Popup', 'method' => 'default'),
                array(
                    'name' => 'email_reminder_time',
                    'type' => 'enum',
                    'text' => 'Reminder Email all invitees',
                    'method' => 'default'
                ),
                array(
                    'name' => 'assigned_user_name',
                    'type' => 'enum',
                    'value' => 'assigned_user_id',
                    'method' => 'assignedUsers'
                ) //TYPE original relate
            ),
            'All' => array(
                array(
                    'name' => 'assigned_user_name',
                    'type' => 'enum',
                    'value' => 'assigned_user_id',
                    'method' => 'assignedUsers'
                ) //TYPE original relate
            )
        );
        return isset($arraymodules[$module])? $arraymodules[$module] : array();
    }

    /**
     * Insert field personalized
     * @codeCoverageIgnore
     * @param $field
     * @param $arrayModules
     * @param bool $customfields
     * @return mixed|null
     */
    private function dataFieldPersonalized($field, $arrayModules, $customfields = false)
    {
        $row = array_shift($arrayModules);
        while (is_array($row)) {
            if (isset($field['name']) && $field['name'] == $row['name']) {
                return $row;
                break;
            } elseif ($customfields && isset($field['source']) && $field['source'] == 'custom_fields') {
                return $field;
                break;
            }
            $row = array_shift($arrayModules);
        }
        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    private function gatewayModulesMethod($def)
    {
        $values = '';
        switch ($def['name']) {
            case 'assigned_user_id':
                $users = $this->retrieveUsers();
                $values = $users;
                break;
            case 'created_by':
            case 'modified_user_id':
                $users = $this->retrieveUsers();
                foreach ($users as $rows) {
                    $newUsers[$rows['value']] = $rows['text'];
                }
                $values = $newUsers;
                break;
            default:
                $values = null;
                break;
        }
        return $values;
    }

    /**
     * @codeCoverageIgnore
     */
    private function replaceItemsValues($def)
    {
        $field = array();
        switch ($def['name']) {
            case 'assigned_user_id':
                $field['type'] = 'user';
                $field['vname'] = 'LBL_ASSIGNED_TO';
                $field['required'] = true;
                break;
            case 'created_by':
            case 'modified_user_id':
                $field['type'] = 'user';
                break;
            case 'teams':
                $field['type'] = 'team_list';
                break;
            default:
                $result = $field;
        }
        return $field;
    }

    /**
     * Returns the array required when the event, gateway or activity definition is required
     * @param $filter Request type
     * @return array Related Array
     */
    public function getRelatedSearch($filter, $args)
    {
        $out = array();
        switch ($filter) {
            case 'modules':
                $out = $this->retrieveModules('');
                break;
            case 'fields':

                break;
        }
        return $out;
    }

    /**
     * Puts CRM Data endpoint results in format to be used by the client
     * @param $result Set of results
     * @param $filter Filter or search query
     * @return array
     */
    private function retrieveCrmData($result, $filter)
    {
        $output = array();
        $output['success'] = true;
        $output['search'] = $filter;
        $output['result'] = $result;
        return $output;
    }

    /**
     *
     * @param PMSEObserver $observer
     */
    public function attach($observer)
    {
        $i = array_search($observer, $this->observers);
        if ($i === false) {
            $this->observers[] = $observer;
        }
    }

    /**
     *
     * @param PMSEObserver $observer
     */
    public function detach($observer)
    {
        if (!empty($this->observers)) {
            $i = array_search($observer, $this->observers);
            if ($i !== false) {
                unset($this->observers[$i]);
            }
        }
    }

    /**
     *
     */
    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    /**
     * @param $filter
     * @return array
     */
    private function getAllRelated($filter, ModuleApi $moduleApi, $relationship = 'all', $baseModule, $type = '')
    {
        $result = array();
        $result['success'] = true;
        $res = $this->retrieveRelatedBeans($filter, $relationship);
        $arr = array();
        if (is_array($res['result']) && !empty($res['result'])) {
            foreach ($res['result'] as $key => $value) {
                //$aux = $this->addRelatedRecord($value['value']);
                $aux = $this->retrieveFields($value['value'], $moduleApi, $type, $baseModule);
                $value['fields'] = $aux['result'];
                $arr[] = $value;
            }
        } else {
            $result['success'] = false;
        }
        $result['result'] = $arr;
        return $result;
    }

    /**
     * Clear event criteria (evn_criteria) from the database table
     * @param event id (evn_uid)
     */
    public function clearEventCriteria($eventUid)
    {
        $res = new stdClass();
        $res->success = false;

        if (empty($eventUid)) {
            return $res;
        };

        $q = $this->sugarQueryObject;
        $q->select(array('id'));
        $q->from(BeanFactory::newBean('pmse_BpmnEvent'));
        $q->where()->equals('evn_uid', $eventUid);
        $result = $q->execute();

        if (is_array($result)) {
            if (count($result) > 0) {
                $eventDefinitionId = $result[0]['id'];
                $sql = "UPDATE pmse_bpm_event_definition
                    SET evn_criteria = ''
                    WHERE id = " . $this->db->quoted($eventDefinitionId);
                $res->success = (bool)$this->db->Query($sql);
            } else {
                // cannot run update since the row is not there
                $res->success = true;
            }
        }

        return $res;
    }

    /*
     * Given a group field get the multiple fields which are part of that group
     * @param string $module module name
     * @param string $proLockedVariables locked fields json string
     * @return string json string of expanded locked fields
     */
    public function getMultiLockedFieldsFromGroupField($module, $proLockedVariables)
    {
        $groupFieldsArray = array();
        $newLockedFieldsStr = '';

        $moduleBean = BeanFactory::newBean($module);
        $fieldsDataArray = isset($moduleBean->field_defs) ? $moduleBean->field_defs : array();
        $lockedVarsArray = json_decode($proLockedVariables);
        foreach ($fieldsDataArray as $key => $fieldsArray) {
            if (!empty($fieldsArray['group']) && (in_array($fieldsArray['group'], $lockedVarsArray))) {
                $groupFieldsArray[$fieldsArray['group']][] =  $fieldsArray['name'];
                if ((!empty($fieldsDataArray[$fieldsArray['group']])) &&
                    (array_search($fieldsArray['group'], $groupFieldsArray) === false)) {
                    $groupFieldsArray[$fieldsArray['group']][] = $fieldsArray['group'];
                }
            }
        }
        if (!empty($groupFieldsArray)) {
            foreach ($groupFieldsArray as $groupField => $comboFieldsArray) {
                $index = array_search($groupField, $lockedVarsArray);
                if ($index !== false) {
                    unset($lockedVarsArray[$index]);
                }
                foreach ($comboFieldsArray as $comboField) {
                    $lockedVarsArray[] = $comboField;
                }
            }
        }

        if (!empty($lockedVarsArray)) {
            $newLockedFieldsStr = json_encode(array_values($lockedVarsArray));
        }


        return $newLockedFieldsStr;
    }

    public function getFormattedFieldLabel($fieldLabel, $module)
    {
        return str_replace(':', '', translate($fieldLabel, $module));
    }

}
