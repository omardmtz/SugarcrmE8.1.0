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
 * Portal visibility class replaces the team security restrictions for portal users
 * For non-portal users this class will not modify the query in any way.
 */
class SupportPortalVisibility extends SugarVisibility
{
    protected $wherePart = '';
    protected static $accountIds;

    /**
     * Pull the list of account id's for a particular contact, we can't cache this because
     * there are requirements about the account id's changing fairly often for a contact.
     */
    public function getAccountIds() 
    {
        if (!isset(self::$accountIds)) {
            $db = DBManagerFactory::getInstance();

            if ( !empty($_SESSION['contact_id']) ) {
                // Using a raw query here, if we attempt to load in a bean and relationships
                // we end up back here again trying to load the same data.
                $ret = $db->query("SELECT accounts_contacts.account_id FROM accounts_contacts INNER JOIN accounts ON accounts_contacts.account_id = accounts.id WHERE accounts_contacts.contact_id = '".$db->quote($_SESSION['contact_id'])."' AND accounts_contacts.deleted = 0 AND accounts.deleted = 0");
                self::$accountIds = array();
                while ( $row = $db->fetchByAssoc($ret) ) {
                    self::$accountIds[] = $row['account_id'];
                }
            } else {
                self::$accountIds = array();
            }
        }
        return self::$accountIds;
    }

    /**
     * This function is here so we can put all the rules in one local section and just have it return the part of the query for that particular type
     * @param $query string The query string (probably shouldn't need to modify it here, but just in case)
     * @param $queryType string Either 'from' or 'where' to match the two types we understand right now.
     * @return string What to append to the query
     * @see static::addVisibilityPortalFromQuery(), should be kept synced
     */
    protected function addVisibilityPortal($query, $queryType) 
    {
        if ( empty($_SESSION['type']) || $_SESSION['type'] != 'support_portal' ) {
            $GLOBALS['log']->error("Not a portal user, but running through the portal visibility class.");
            return;
        }
        if ($this->bean->disable_row_level_security) {
            $GLOBALS['log']->debug("No portal security applied to module (row-level security disabled): ".$this->bean->module_dir);
            return;
        }


        $table_alias = $this->getOption('table_alias');
        if(empty($table_alias)) {
            $table_alias = $this->bean->table_name;
        }

        $accountIds = $this->getAccountIds();

        if ( !empty($accountIds) ) {
            $accountIn = "('".implode("','",$accountIds)."')";
        } else {
            // No accounts
            // According to the SQL specification this should never return any records
            $accountIn = "(NULL)";
        }

        $queryPart = '';

        // The Portal Rules Of Visibility:
        switch ($this->bean->module_dir) {
            case 'Categories':
                if ($queryType == 'where') {
                    // the root category (lvl=0) should be visible to portal users
                    $queryPart = " $table_alias.is_external=1 OR $table_alias.lvl=0 ";
                }
                break;
            case 'KBContents':
                if ($queryType == 'where') {
                    $status = KBContent::ST_PUBLISHED;
                    $queryPart = " $table_alias.active_rev=1 AND"
                                ." $table_alias.is_external=1 AND"
                                ." ($table_alias.status = '{$status}') ";
                }
                break;
            case 'Contacts':
                // Contacts: Any contact related to the account list
                // Special case, if there are no accounts in the list, at least allow them access to their own contact
                if ( count($accountIds) == 0 && !empty($_SESSION['contact_id']) ) {
                    if ( $queryType == 'where' ) {
                        $queryPart = " $table_alias.id = '".$_SESSION['contact_id']."' ";
                    }
                } else {
                    if ( $queryType == 'from' ) {
                        $this->bean->load_relationship('accounts');
                        $queryPart = $this->bean->accounts->getJoin(array('join_table_alias'=>'accounts_pv', 'ignore_role'=>true))." AND accounts_pv.id IN $accountIn ";
                    }
                }
                break;
            case 'Accounts':
                // Accounts: Any account in the account list
                if ( $queryType == 'where' ) {
                    $queryPart = " $table_alias.id IN $accountIn ";
                }
                break;
            case 'Bugs':
                // Bugs: Any bug that has the portal_viewable flag set to true
                if ( $queryType == 'where' ) {
                    $queryPart = " $table_alias.portal_viewable = 1 ";
                }

                break;
            case 'Notes':
                // Notes: Notes that are connected to a Case or a Bug that is connected to one of our Accounts and has the portal_flag set to true
                if ($queryType == 'from') {
                    if ( !empty($accountIds) ) {
                        // Only add in this join if the user can see bugs related to cases
                        $this->bean->load_relationship('cases');
                        
                        $caseBean = BeanFactory::newBean('Cases');
                        $caseBean->load_relationship('accounts');
                        
                        $queryPart = $this->bean->cases->getJoin(array('join_table_alias'=>'cases_pv','join_type'=>' LEFT JOIN '));
                        $queryPart .= " AND cases_pv.portal_viewable = 1 ";
                        $queryPart .= " ".$caseBean->accounts->getJoin(array('join_table_alias'=>'accounts_cases_pv','right_join_table_alias'=>'cases_pv','join_type' => ' LEFT JOIN '))." AND accounts_cases_pv.id IN $accountIn ";
                    }
                    $this->bean->load_relationship('bugs');
                    
                    $bugBean = BeanFactory::newBean('Bugs');

                    $queryPart .= " ".$this->bean->bugs->getJoin(array('join_table_alias'=>'bugs_pv','join_type'=>' LEFT JOIN '));
                    $queryPart .= " AND bugs_pv.portal_viewable = 1 ";
                } elseif ($queryType == 'where') {
                    $KBContentsCondition = "{$table_alias}.parent_type = 'KBContents' "
                                    . "OR {$table_alias}.parent_type = 'KBContentsAttachments'";

                    if ( !empty($accountIds) ) {
                        $queryPart = " {$table_alias}.portal_flag = 1 AND ( bugs_pv.id IS NOT NULL OR accounts_cases_pv.id IS NOT NULL OR {$KBContentsCondition}) ";
                    } else {
                        $queryPart = " {$table_alias}.portal_flag = 1 AND "
                                    . "(bugs_pv.id IS NOT NULL OR {$KBContentsCondition}) ";
                    }
                }
                break;
            case 'pmse_Inbox':
            case 'pmse_Business_Rules':
            case 'pmse_Emails_Templates':
            case 'pmse_Project':
            case 'pmse_Project/pmse_BpmActivityDefinition':
            case 'pmse_Project/pmse_BpmActivityStep':
            case 'pmse_Project/pmse_BpmActivityUser':
            case 'pmse_Project/pmse_BpmDynaForm':
            case 'pmse_Project/pmse_BpmEventDefinition':
            case 'pmse_Project/pmse_BpmFlow':
            case 'pmse_Project/pmse_BpmFormAction':
            case 'pmse_Project/pmse_BpmGatewayDefinition':
            case 'pmse_Project/pmse_BpmGroup':
            case 'pmse_Project/pmse_BpmGroupUser':
            case 'pmse_Project/pmse_BpmnActivity':
            case 'pmse_Project/pmse_BpmnArtifact':
            case 'pmse_Project/pmse_BpmnBound':
            case 'pmse_Project/pmse_BpmnData':
            case 'pmse_Project/pmse_BpmnDiagram':
            case 'pmse_Project/pmse_BpmnDocumentation':
            case 'pmse_Project/pmse_BpmnEvent':
            case 'pmse_Project/pmse_BpmnExtension':
            case 'pmse_Project/pmse_BpmnFlow':
            case 'pmse_Project/pmse_BpmnGateway':
            case 'pmse_Project/pmse_BpmnLane':
            case 'pmse_Project/pmse_BpmnLaneset':
            case 'pmse_Project/pmse_BpmnNotes':
            case 'pmse_Project/pmse_BpmnParticipant':
            case 'pmse_Project/pmse_BpmnProcess':
            case 'pmse_Project/pmse_BpmProcessDefinition':
            case 'pmse_Project/pmse_BpmRelatedDependency':
            case 'pmse_Project/pmse_BpmThread':
                // PA tables need to be visible in Portal without restrictions because they are acting globally
            case 'pmse_Project/pmse_BpmConfig':
                // This is absolutely needed otherwise PMSESettings cannot
                // retrieve config and will end up in a infinite loop.
                break;
            case 'Cases':
                // Cases: Any case that has the portal_viewable flag set to true and is related to the account list
            default:
                // Other: Same as cases, if there is no portal_viewable (or portal_viewable_c) field, it is not portal accessible
                if ( $this->bean->module_dir == 'Cases' ) {
                    $portalEnabled = true;
                    $linkName = 'accounts';
                    $wherePart = " $table_alias.portal_viewable = 1 ";
                } else {
                    $portalEnabled = false;
                }
                
                if ( $portalEnabled ) {
                    if ( $queryType == 'from' ) {
                        $this->bean->load_relationship($linkName);
                        $queryPart = $this->bean->$linkName->getJoin(array('right_join_table_alias'=>$table_alias, 'join_table_alias'=>$linkName.'_pv', 'ignore_role'=>true))." AND {$linkName}_pv.id IN $accountIn ";
                    } else if ( $queryType == 'where' ) {
                        $queryPart = $wherePart;
                    }
                } else {
                    // This module is not a default portal module, nor can I figure out how the portal visibility for this module works, so I am here to make sure they don't see anything.
                    if ( $queryType == 'where' ) {
                        $queryPart = ' 1=0 ';
                    }
                    
                }
                break;
        }
        
        return $queryPart;
    }

    public function addVisibilityFrom(&$query)
    {
        $query .= $this->addVisibilityPortal($query, 'from');
        return;
    }

    public function addVisibilityWhere(&$query)
    {
        $queryPart = $this->addVisibilityPortal($query, 'where');
        if (!empty($query) && !empty($queryPart)) {
            $query .= " AND ".$queryPart;
        } else if (!empty($queryPart)) {
            $query .= $queryPart;
        }
        return;
    }

    /**
     * Add Visibility to a SugarQuery Object
     *
     * @param SugarQuery $sugarQuery
     * @param array      $options
     *
     * @return SugarQuery
     */
    public function addVisibilityFromQuery(SugarQuery $sugarQuery, $options = array())
    {
        if (empty($_SESSION['type']) || $_SESSION['type'] != 'support_portal') {
            $GLOBALS['log']->error('Not a portal user, but running through the portal visibility class.');
            return $sugarQuery;
        }
        if ($this->bean->disable_row_level_security) {
            $GLOBALS['log']->debug(
                'No portal security applied to module (row-level security disabled): ' . $this->bean->module_dir
            );
            return $sugarQuery;
        }
        $table_alias = $this->getOption('table_alias');
        if (empty($table_alias)) {
            $table_alias = $this->bean->table_name;
        }
        $accountIds = $this->getAccountIds();
        // The Portal Rules Of Visibility:
        switch ($this->bean->module_dir) {
            case 'Contacts':
                // Contacts: Any contact related to the account list
                if (count($accountIds) || empty($_SESSION['contact_id'])) {
                    $this->bean->load_relationship('accounts');
                    $joins = $this->bean->accounts->buildJoinSugarQuery(
                        $sugarQuery,
                        array('joinTableAlias' => 'accounts_pv', 'ignoreRole' => true)
                    );
                    $joins['accounts_pv']->in('accounts_pv.id', $accountIds);
                }
                break;
            case 'Notes':
                // Notes: Notes that are connected to a Case or a Bug that is connected to one of our Accounts and
                // has the portal_flag set to true
                $this->bean->load_relationship('bugs');
                if (!empty($accountIds)) {
                    $this->bean->load_relationship('cases');
                    $join = $this->bean->cases->buildJoinSugarQuery(
                        $sugarQuery,
                        array('joinTableAlias' => 'cases_pv', 'joinType' => 'LEFT')
                    );
                    $join->on()->equals('cases_pv.portal_viewable', 1);
                    $caseBean = BeanFactory::newBean('Cases');
                    $caseBean->load_relationship('accounts');
                    $join = $caseBean->accounts->buildJoinSugarQuery(
                        $sugarQuery,
                        array('joinTableAlias' => 'accounts_cases_pv', 'joinType' => 'LEFT')
                    );
                    $join->on()->equalsField('cases_pv.account_id', 'accounts_cases_pv.id')
                        ->in('accounts_cases_pv.id', $accountIds);
                }
                $join = $this->bean->bugs->buildJoinSugarQuery(
                    $sugarQuery,
                    array('joinTableAlias' => 'bugs_pv', 'joinType' => 'LEFT')
                );
                $join->on()->equals('bugs_pv.portal_viewable', 1);
                break;
            case 'Cases':
                $this->bean->load_relationship('accounts');
                $join = $this->bean->accounts->buildJoinSugarQuery(
                    $sugarQuery,
                    array('joinTableAlias' => 'accounts_pv', 'ignoreRole' => true)
                );
                $join->on()->in('accounts_pv.id', $accountIds);
                break;
        }
        return $sugarQuery;
    } 

    /**
     * Add Visibility to a SugarQuery Object
     * @param SugarQuery $sugarQuery
     * @param array $options
     * @return object|SugarQuery
     */
    public function addVisibilityWhereQuery(SugarQuery $sugarQuery, $options = array())
    {
        $where = $this->addVisibilityPortal('', 'where');
        if(!empty($where)) {
            $sugarQuery->whereRaw($where);
        }
        return $sugarQuery;
    }
}
