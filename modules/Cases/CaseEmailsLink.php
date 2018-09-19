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
 * Links connected emails for a case
 */
class CaseEmailsLink extends ArchivedEmailsBeanLink
{
    public function __construct($linkName, $bean, $linkDef = false)
    {
        parent::__construct($linkName, $bean, $linkDef);
        $this->addSource = true;
    }

    protected function joinEmails(SugarQuery $query, $fromAlias, $alias)
    {
        if ($this->focus instanceof aCase && !empty($this->focus->case_number)) {
            $relation = $this->def['link'];
            $this->focus->load_relationship($relation);
            $emailsSubQuery = $this->getEmailsSubquery($relation);
            $subQuery = "( SELECT id, email_id, MIN(source) sources
                FROM ($emailsSubQuery) email_ids2 GROUP BY id, email_id )";
            $where = str_replace("%1", $this->focus->case_number, $this->focus->getEmailSubjectMacro());
            $where = DBManagerFactory::getInstance()->sqlLikeString($where, '%', false);
            $join = $query->joinTable($subQuery, array('alias' => $alias));
            $join->on()->equalsField($fromAlias . '.id', $alias . '.email_id');
            $condition = $join->on()->queryAnd()->queryOr();
            $condition->equals($alias . '.sources', 1)->contains($fromAlias . '.name', $where);
            return $join;
        } else {
            return parent::joinEmails($query, $fromAlias, $alias);
        }
    }

    /**
     * We need this one because cases have match by subject macro
     * @see ArchivedEmailsBeanLink::getEmailsJoin()
     */
    protected function getEmailsJoin($params = array())
    {
        if ($this->focus instanceof aCase && !empty($this->focus->case_number)) {
            $where = str_replace("%1", $this->focus->case_number, $this->focus->getEmailSubjectMacro());
            if (!empty($params['join_table_alias'])) {
                $table_name = $params['join_table_alias'];
            } else {
                $table_name = 'emails';
            }
            $where = DBManagerFactory::getInstance()->sqlLikeString($where, '%', false);
            $relation = $this->def['link'];
            $this->focus->load_relationship($relation);
            $inside = $this->getEmailsSubquery($relation);
            $join = "INNER JOIN ( SELECT email_id, MIN(source) sources FROM ($inside) email_ids2 GROUP BY email_id ) email_ids ON $table_name.id=email_ids.email_id";
            $join .= " AND (email_ids.sources = 1 OR {$table_name}.name LIKE '%$where%')";
            return $join;
        } else {
            return parent::getEmailsJoin($params);
        }
    }
}
