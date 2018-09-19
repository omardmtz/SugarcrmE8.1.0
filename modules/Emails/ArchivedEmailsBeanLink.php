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
 * Link collects archived emails - both directly assigned and
 * related by email address, and also the same from related bean
 */
class ArchivedEmailsBeanLink extends ArchivedEmailsLink
{
    /**
     * Add source column
     *
     * @var bool
     */
    protected $addSource = false;

    protected function joinEmails(SugarQuery $query, $fromAlias, $alias)
    {
        $relation = $this->def['link'];
        $this->focus->load_relationship($relation);
        if (empty($this->focus->$relation)) {
            $GLOBALS['log']->error(
                "Bad relation '$relation' for bean '{$this->focus->object_name}' id '{$this->focus->id}'"
            );
            // produce join that is always empty
            $dummy = $this->focus->db->getFromDummyTable();
            $join = $query->joinTable("(select null id $dummy)", array('alias' => 'nothing'));
            $join->on()->addRaw('1 != 1');
            return $join;
        }

        $subQuery = $this->getEmailsSubquery($relation);
        $join = $query->joinTable("($subQuery)", array('alias' => $alias));
        $join->on()->equalsField($fromAlias . '.id', $alias . '.email_id');

        return $join;
    }

    /**
     * Subquery for emails
     *
     * @param string $relation  Relation name
     *
     * @return string
     */
    protected function getEmailsSubquery($relation)
    {
        $rel_module = $this->focus->$relation->getRelatedModuleName();
        $rel_join = $this->focus->$relation->getJoin(
            array('join_table_alias' => 'link_bean', 'join_table_link_alias' => 'linkt')
        );

        $bean_id = $this->db->quoted($this->focus->id);
        $rel_join = str_replace("{$this->focus->table_name}.id", $bean_id, $rel_join);

        $hideHistoryContactsEmails
            = !empty($GLOBALS['sugar_config']['hide_history_contacts_emails'][$this->focus->module_name]);

        $source = $this->addSource ? ", 1 /* direct */ source" : "";
        // directly assigned emails
        $subQuery = "SELECT eb.bean_id AS id, eb.email_id $source FROM emails_beans eb
            WHERE eb.bean_module = '{$this->focus->module_dir}' AND eb.bean_id = $bean_id AND eb.deleted=0\n";

        $source = $this->addSource ? ", 2 /* related */ source" : "";
        $subQuery .= " UNION ".
        // Related by directly by email
            "SELECT DISTINCT eabr.bean_id AS id, eear.email_id $source from emails_email_addr_rel eear
            INNER JOIN email_addr_bean_rel eabr
                ON eabr.bean_id = $bean_id AND eabr.bean_module = '{$this->focus->module_dir}' AND
                eabr.email_address_id = eear.email_address_id and eabr.deleted=0 where eear.deleted=0\n";

        if (!$hideHistoryContactsEmails) {
            // Assigned to contacts
            $source = $this->addSource ? ", 4 /* contact */ source" : "";
            $subQuery .= " UNION ".
                "SELECT DISTINCT $bean_id AS id, eb.email_id $source FROM emails_beans eb
                $rel_join AND link_bean.id = eb.bean_id
                where eb.bean_module = '$rel_module' AND eb.deleted=0\n";
            // Related by email to linked contact
            $source = $this->addSource ? ", 8 /* related_contact */  source" : "";
            $subQuery .= " UNION SELECT DISTINCT $bean_id AS id, eear.email_id $source
                FROM emails_email_addr_rel eear
                INNER JOIN email_addr_bean_rel eabr
                ON eabr.email_address_id=eear.email_address_id AND eabr.bean_module = '$rel_module' AND eabr.deleted=0
                $rel_join AND link_bean.id = eabr.bean_id
                where eear.deleted=0\n";
        }
        return $subQuery;
    }


    /**
     * Override to go to both direct emails and linked bean
     * @see ArchivedEmailsLink::getEmailsJoin()
     */
    protected function getEmailsJoin($params = array())
    {
        $relation = $this->def['link'];
        $this->focus->load_relationship($relation);
        if (empty($this->focus->$relation)) {
            $GLOBALS['log']->error(
                "Bad relation '$relation' for bean '{$this->focus->object_name}' id '{$this->focus->id}'"
            );
            // produce join that is always empty
            $dummy = $this->focus->db->getFromDummyTable();
            return "inner join (select null id $dummy) nothing on 1 != 1";
        }

        $subQuery = $this->getEmailsSubquery($relation);
        if (!empty($params['join_table_alias'])) {
            $table_name = $params['join_table_alias'];
        } else {
            $table_name = 'emails';
        }
        $query = "INNER JOIN ($subQuery) email_ids ON $table_name.id=email_ids.email_id ";

        return $query;
    }
}
