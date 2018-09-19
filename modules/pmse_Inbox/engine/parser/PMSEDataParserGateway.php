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

/**
 * Class that routed to class to be parcera according to their type
 * in each class a different treatment is performed
 * the classes that routes: ADAMUserRoleParser, ADAMFormResponseParser, ADAMFieldParser and ADAMBusinessRuleParser
 *
 * @codeCoverageIgnore
 */

use Sugarcrm\Sugarcrm\ProcessManager;

class PMSEDataParserGateway implements PMSEDataParserInterface
{
    protected $beanFactory;
    protected $dataParser;
    protected $beanList;
    protected $currentUser;
    protected $evaluatedBean;

    /**
     * Constructor
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->beanFactory = new BeanFactory();
    }

    /**
     * gets the bean factory
     * @return object
     * @codeCoverageIgnore
     */
    public function getBeanFactory()
    {
        return $this->beanFactory;
    }

    /**
     * sets the bean factory
     * @param object $beanFactory
     * @codeCoverageIgnore
     */
    public function setBeanFactory($beanFactory)
    {
        $this->beanFactory = $beanFactory;
    }

    /**
     * gets the bean list
     * @return object
     * @codeCoverageIgnore
     */
    public function getBeanList()
    {
        return $this->beanList;
    }

    /**
     * sets the bean list
     * @param object $beanList
     * @codeCoverageIgnore
     */
    public function setBeanList($beanList)
    {
        $this->beanList = $beanList;
    }

    /**
     * set the current user
     * @param object $currentUser
     * @codeCoverageIgnore
     */
    public function setCurrentUser($currentUser)
    {
        $this->currentUser = $currentUser;
    }

    /**
     * sets the bean
     * @param object $bean
     * @codeCoverageIgnore
     */
    public function setEvaluatedBean($bean)
    {
        $this->evaluatedBean = $bean;
    }

    /**
     * method that routes the class indicated to perform parser
     * @param array $criteriaArray in each position contains a token
     * @param object $bean this is the bean object
     * @param object $currentUser this is the current user object
     * @param array $beanList list of all modules of sugar
     * @param array $params if additional parameters
     * @return object
     */
    public function parseCriteriaArray($criteriaArray, $bean, $currentUser, $beanList = array(), $params = array())
    {
        $parsedArray = array();
        if (!empty($criteriaArray) && is_array($criteriaArray)) {
            foreach ($criteriaArray as $key => $criteriaToken) {
                $isDefault = false;
                switch ($criteriaToken->expType) {
                    case 'USER_ROLE':
                    case 'USER_IDENTITY':
                    case 'USER_ADMIN':
                        $this->dataParser = ProcessManager\Factory::getPMSEObject('PMSEUserRoleParser');
                        break;
                    case 'CONTROL':
                        $this->dataParser = ProcessManager\Factory::getPMSEObject('PMSEFormResponseParser');
                        break;
                    case 'MODULE':
                    case 'VARIABLE':
                    case 'DEFAULT_MODULE':
                        $this->dataParser = ProcessManager\Factory::getPMSEObject('PMSEFieldParser');
                        break;
                    case 'BUSINESS_RULES':
                        $this->dataParser = ProcessManager\Factory::getPMSEObject('PMSEBusinessRuleParser');
                        break;
                    default:
                        $isDefault = true;
                        break;
                }

                if ($isDefault) {
                    $parsedArray[$key] = $criteriaToken;
                } else {
                    $this->dataParser->setEvaluatedBean($bean);
                    $this->dataParser->setCurrentUser($currentUser);
                    $this->dataParser->setBeanList($beanList);
                    $parsedArray[$key] = $this->parseCriteriaToken($criteriaToken, $params);
                }
            }
        }
        return $parsedArray;
    }

    /**
     * method that parser a token
     * @param object $criteriaToken the original token
     * @param array $params if additional parameters
     * @return object the modified token
     */
    public function parseCriteriaToken($criteriaToken, $params = array())
    {
        return $this->dataParser->parseCriteriaToken($criteriaToken, $params);
    }
}
