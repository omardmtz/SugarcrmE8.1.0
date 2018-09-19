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
 * Class gets the data type and value which is evaluated each token
 * this token includes a new element that contains the current value of the case
 *
 */
class PMSEBusinessRuleParser implements PMSEDataParserInterface
{
    private $evaluatedBean;
    private $currentUser;
    private $beanList;

    /**
     * gets the bean list
     * @return array list all modules
     * @codeCoverageIgnore
     */
    public function getBeanList()
    {
        return $this->beanList;
    }

    /**
     * sets the bean list
     * @param array $beanList set list all modules
     * @codeCoverageIgnore
     */
    public function setBeanList($beanList)
    {
        $this->beanList = $beanList;
    }

    /**
     * gets the bean
     * @return object
     * @codeCoverageIgnore
     */
    public function getEvaluatedBean()
    {
        return $this->evaluatedBean;
    }

    /**
     * sets the bean
     * @param object $evaluatedBean
     * @codeCoverageIgnore
     */
    public function setEvaluatedBean($evaluatedBean)
    {
        $this->evaluatedBean = $evaluatedBean;
    }

    /**
     * gets the current user
     * @return object
     * @codeCoverageIgnore
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
    }

    /**
     * sets the current user
     * @param object $currentUser
     * @codeCoverageIgnore
     */
    public function setCurrentUser($currentUser)
    {
        $this->currentUser = $currentUser;
    }

    /**
     * parser state of a form according to their business rule
     * @param object $criteriaToken token with the form data
     * @param array $args enter the global variable $db and id case
     * @return object
     */
    public function parseCriteriaToken($criteriaToken, $args = array())
    {
        $db = $args['db'];
        $query = "select frm_action, pmse_bpm_form_action.act_id, act_uid from pmse_bpm_form_action " .
            "left join pmse_bpmn_activity on (pmse_bpm_form_action.act_id = pmse_bpmn_activity.id) " .
            "where cas_id = {$args['cas_id']} and frm_last = 1 ";
        $result = $db->Query($query);
        $row = $db->fetchByAssoc($result);
        //$this->bpmLog('DEBUG', "before : $condition");
        $tokenValue = '';
        $tokenUid = '';
        while (is_array($row)) {
            $uidStr = '{::_form_::' . $row['act_uid'] . '::}';
            $idStr = '{::_form_::' . $row['act_id'] . '::}';
            $existsUID = stristr($uidStr, $criteriaToken->expField);
            $existsID = stristr($idStr, $criteriaToken->expField);
            if ($existsUID || $existsID) {
                $row['frm_action'] = html_entity_decode($row['frm_action']);
                $resultToken = json_decode($row['frm_action']);
                $valType = strtolower($resultToken->type);
                //settype($resultToken->value,$valType);
                $tokenValue = $resultToken->value;
                $tokenUid = $existsUID ? $row['act_uid'] : $row['act_id'];
                break;
            } else {
                $row = $db->fetchByAssoc($result);
            }
        }
        $tokenDelimiter = '::';
        $newTokenArray = array('{', '_form_', $tokenUid, '}');
        $assembledTokenString = implode($tokenDelimiter, $newTokenArray);
        $criteriaToken->expToken = $assembledTokenString;
        $criteriaToken = $this->processValueExpression($criteriaToken);
        $criteriaToken->currentValue = $tokenValue;
        return $criteriaToken;
    }

    /**
     * method that takes a cast type
     * @param object $token original
     * @return object modified
     */
    public function processValueExpression($token)
    {
        switch (strtoupper($token->expFieldType)) {
            case 'INT':
                $token->expValue = (int)$token->expValue;
                break;
            case 'FLOAT':
                $token->expValue = (float)$token->expValue;
                break;
            case 'DOUBLE':
                $token->expValue = (double)$token->expValue;
                break;
            case 'BOOL':
                $token->expValue = $token->expValue == 'true' ? true : false;//(bool) $token->expValue;
                break;
            default :
                $token->expValue = $token->expValue;
                break;
        }
        return $token;
    }
}
