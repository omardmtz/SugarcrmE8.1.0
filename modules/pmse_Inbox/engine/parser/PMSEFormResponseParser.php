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
 * Performs the analysis of case actions are: APPROVE, REJECT and ROUTE
 * according to the type of evaluation you want returns current value or null
 *
 */
class PMSEFormResponseParser implements PMSEDataParserInterface
{
    /**
     * Object Bean
     * @var object
     */
    private $evaluatedBean;

    /**
     * Object current user
     * @var object
     */
    private $currentUser;

    /**
     * Array list bean
     * @var array
     */
    private $beanList;

    /**
     * gets the bean list
     * @codeCoverageIgnore
     */
    public function getBeanList()
    {
        return $this->beanList;
    }

    /**
     * sets the bean list
     * @codeCoverageIgnore
     */
    public function setBeanList($beanList)
    {
        $this->beanList = $beanList;
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
     * gets the current user
     * @codeCoverageIgnore
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
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
     * parser state of a form according to their action: APPROVE, REJECT and ROUTE
     * @param type $criteriaToken token with the form data
     * @param type $args enter the global variable $db and id case
     * @return type
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
                $tokenValue = $row['frm_action'];
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
        $criteriaToken->currentValue = $tokenValue;
        return $criteriaToken;
    }
}
