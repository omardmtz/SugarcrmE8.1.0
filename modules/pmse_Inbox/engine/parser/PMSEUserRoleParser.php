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
 * Convert a role user  condition into a criteria
 * based one in order to evaluate a more compatible format
 * parser according to the types of users  USER_ADMIN, USER_ROLE and USER_IDENTITY
 * and in each group there current_user,owner and supervisor
 *
 */
class PMSEUserRoleParser implements PMSEDataParserInterface
{
    /**
     * Object bean
     * @var object
     */
    private $evaluatedBean;

    /**
     * Object of users
     * @var object
     */
    private $userBean;

    /**
     * Object of class ADAMBeanFactory
     * @var object
     */
    private $beanFactory;

    /**
     * Array list bean
     * @var array
     */
    private $beanList;

    /**
     * object of current user
     * @var object
     */
    private $currentUser;

    /**
     * Object of database
     * @var object
     */
    private $dbHandler;

    /**
     * sets variables: database, current user, User bean
     * @global type $db
     * @global type $current_user
     */
    public function __construct()
    {
        global $db, $current_user;
        $this->beanFactory = new BeanFactory();
        $this->userBean = $this->beanFactory->getBean('Users');
        $this->dbHandler = $db;
        $this->currentUser = $current_user;
    }

    /**
     * gets the globla $db
     * @codeCoverageIgnore
     */
    public function getDbHandler()
    {
        return $this->dbHandler;
    }

    /**
     * sets the globla $db
     * @codeCoverageIgnore
     */
    public function setDbHandler($dbHandler)
    {
        $this->dbHandler = $dbHandler;
    }

    /**
     * gets the user bean
     * @codeCoverageIgnore
     */
    public function getUserBean()
    {
        return $this->userBean;
    }

    /**
     * sets the user bean
     * @codeCoverageIgnore
     */
    public function setUserBean($userBean)
    {
        $this->userBean = $userBean;
    }

    /**
     * gets the bean
     * @codeCoverageIgnore
     */
    public function getEvaluatedBean()
    {
        return $this->evaluatedBean;
    }

    /**
     * sets the bean
     * @codeCoverageIgnore
     */
    public function setEvaluatedBean($evaluatedBean)
    {
        $this->evaluatedBean = $evaluatedBean;
    }

    /**
     * sets the bena list
     * @codeCoverageIgnore
     */
    public function setBeanList($beanList)
    {
        $this->beanList = $beanList;
    }

    /**
     * sets the current user
     * @codeCoverageIgnore
     */
    public function setCurrentUser($currentUser)
    {
        $this->currentUser = $currentUser;
    }

    /**
     * sets the value of the token according to user type
     * @param object $criteriaToken token
     * @param array $params
     * @return object
     */
    public function parseCriteriaToken($criteriaToken, $params = array())
    {
        //$tokenValueArray = explode($delimiter, $criteriaToken->expLabel);
        $tokenDelimiter = '::';
        if ($criteriaToken->expType == 'USER_ADMIN') {
            $criteriaToken->expValue = 'is_admin';
        }
        $tokenValue = $this->parseTokenValue($criteriaToken);
        $newTokenArray = array('{', 'future', 'Users', 'id', '}');
        $assembledTokenString = implode($tokenDelimiter, $newTokenArray);
        $criteriaToken->expToken = $assembledTokenString;
        $criteriaToken->currentValue = $tokenValue;
        return $criteriaToken;
    }

    /**
     * Parser according to the types of users  USER_ADMIN, USER_ROLE and USER_IDENTITY
     * and in each group there current_user, owner and supervisor
     * @param object $token
     * @return string
     */
    public function parseTokenValue($token)
    {
        $variableUsers = array(
            'owner' => isset($this->evaluatedBean) ? $this->evaluatedBean->assigned_user_id : '',
            'supervisor' => isset($this->currentUser) ? $this->currentUser->reports_to_id : '',
        );

        $output = '';
        switch ($token->expType) {
            case 'USER_ADMIN':
                switch ($token->expField) {
                    case 'current_user':
                        $output = ($this->currentUser->is_admin == 0) ? '' : 'is_admin';
                        break;
                    case 'owner':
                    case 'supervisor':
                        $this->userBean->retrieve($variableUsers[$token->expField]);
                        if (!is_null($this->userBean)) {
                            $output = ($this->userBean->is_admin == 0) ? '' : 'is_admin';
                        }
                        break;
                }
                break;
            case 'USER_ROLE':
                switch ($token->expField) {
                    case 'current_user':
                        if ($token->expValue == 'is_admin') {
                            $output = ($this->currentUser->is_admin == 0) ? '' : 'is_admin';
                            break;
                        }
                        $get_acl_roles = "SELECT acl_roles_users.* FROM acl_roles_users WHERE role_id = '" .
                            $token->expValue . "' AND user_id = '" .
                            $this->currentUser->id . "' AND deleted = 0";
                        $result = $this->dbHandler->query($get_acl_roles);
                        $row = $this->dbHandler->fetchByAssoc($result);
                        $output = !empty($row) ? $token->expValue : '';
                        break;
                    case 'owner':
                    case 'supervisor':
                        $this->userBean->retrieve($variableUsers[$token->expField]);
                        if (!is_null($this->userBean)) {
                            if ($token->expValue == 'is_admin') {
                                $output = ($this->userBean->is_admin == 0) ? '' : 'is_admin';
                                break;
                            }
                            $get_acl_roles = "SELECT acl_roles_users.* FROM acl_roles_users WHERE role_id = '" .
                                $token->expValue . "' AND user_id = '" .
                                $this->userBean->id . "' AND deleted = 0";
                            $result = $this->dbHandler->query($get_acl_roles);
                            $row = $this->dbHandler->fetchByAssoc($result);
                            $output = !empty($row) ? $token->expValue : '';
                        }
                        break;
                }
                break;
            case 'USER_IDENTITY':
                switch ($token->expField) {
                    case 'current_user':
                        $output = $this->currentUser->id;
                        break;
                    case 'owner':
                    case 'supervisor':
                        if (!is_null($variableUsers[$token->expField])) {
                            $output = $variableUsers[$token->expField];
                        } else {
                            $output = 'false';
                        }
                        break;
                }
                break;
        }
        return $output;
    }

    /**
     * converts a string {:: future :: Users :: id ::} to an array ('future','Users','id')
     * @param string $token
     * @return array
     */
    public function decomposeToken($token)
    {
        $response = array();
        $tokenArray = explode('::', $token);
        foreach ($tokenArray as $key => $value) {
            if ($value != '{' && $value != '}' && !empty($value)) {
                $response[] = $value;
            }
        }
        return $response;
    }
}
