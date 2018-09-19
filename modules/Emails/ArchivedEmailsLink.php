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
 * related by email address.
 */
class ArchivedEmailsLink extends Link2
{
    /**
     * DB
     *
     * @var DBManager
     */
    protected $db;

    public function __construct($linkName, $bean, $linkDef = false)
    {
        $this->focus = $bean;
        $this->name = $linkName;
        $this->db = DBManagerFactory::getInstance();
        if (empty($linkDef)) {
            $this->def = $bean->field_defs[$linkName];
        } else {
            $this->def = $linkDef;
        }
    }

    /**
     * Returns false if no relationship was found for this link
     *
     * @return bool
     */
    public function loadedSuccesfully()
    {
        // this link always loads successfully
        return true;
    }

    /**
     * Get all beans from link
     * @see Link2::query()
     */
    public function query($params = array())
    {
        unset($params['return_as_array']);
        $query = $this->getQuery($params);
        $result = $this->db->query($query);
        $rows = array();
        while ($row = $this->db->fetchByAssoc($result, false)) {
            $rows[$row['id']] = $row;
        }
        return array("rows" => $rows);
    }

    /**
     * @see Link2::getRelatedModuleName()
     */
    public function getRelatedModuleName()
    {
        return 'Emails';
    }

    /**
     * @see Link2::getRelatedModuleLinkName()
     */
    public function getRelatedModuleLinkName()
    {
        // this is one-side link, other side (Emails) won't have the link
        return false;
    }

    /**
     * @see Link2::getType()
     */
    public function getType()
    {
        return "many";
    }

    /**
     * @see Link2::getSide()
     */
    public function getSide()
    {
        return REL_LHS;
    }

    /**
     * @see Link2::is_self_relationship()
     */
    public function is_self_relationship()
    {
        return false;
    }

    /**
     * @see Link2::isParentRelationship()
     */
    public function isParentRelationship()
    {
        return false;
    }

    /**
     * This function may not actually be useful due to the fact it's true M2M relationship, so
     * you'd get multiple rows for each original row.
     *
     * @see Link2::buildJoinSugarQuery()
     */
    public function buildJoinSugarQuery($sugar_query, $options = array())
    {
        $joinParams = array('joinType' => isset($options['joinType']) ? $options['joinType'] : 'INNER');
        $jta = 'emails';
        if (!empty($options['joinTableAlias'])) {
            $jta = $joinParams['alias'] = $options['joinTableAlias'];
        }

        if (!empty($options['myAlias'])) {
            $fromAlias = $options['myAlias'];
        } else {
            $fromAlias = 'emails';
        }

        if (!empty($options['reverse'])) {
            return $this->joinEmails($sugar_query, $fromAlias, $jta);
        } else {
            $sugar_query->joinTable('emails', $joinParams);
            return $this->joinEmails($sugar_query, $fromAlias, $jta);
        }
    }

    /**
     * This function may not actually be useful due to the fact it's true M2M relationship, so
     * you'd get multiple rows for each original row.
     *
     * @param $params array
     *            of parameters. Possible parameters include:
     *            'join_table_link_alias': alias the relationship join table in the query (for M2M relationships),
     *            'join_table_alias': alias for the final table to be joined against (usually a module main table)
     * @param bool $return_array
     *            if true the query is returned as a array broken up into
     *            select, join, where, type, rel_key, and joined_tables
     * @return string/array join query for this link
     */
    public function getJoin($params = array(), $return_as_array = false)
    {
        $return_array['join'] = $this->getEmailsJoin($params);

        $join_type = !empty($params['join_type']) ? $params['join_type'] : ' INNER JOIN ';

        if (isset($params['join_table_alias'])) {
            $return_array['join'] = "$join_type emails {$params['join_table_alias']} ON {$params['join_table_alias']}.deleted=0 " .
                 $return_array['join'];
        } else {
            $return_array['join'] = "$join_type emails ON emails.deleted=0 " . $return_array['join'];
        }

        if (!empty($return_as_array) || !empty($params['return_as_array'])) {
            return $return_array;
        } else {
            return $return_array['join'] . $return_array['where'];
        }
    }

    /**
     * Builds SugarQuery main join for archived emails
     *
     * @param SugarQuery $query The query
     * @param string $fromAlias The alias of the primary table
     * @param string $alias The alias which the joined table needs to have
     *
     * @return SugarQuery_Builder_Join
     */
    protected function joinEmails(SugarQuery $query, $fromAlias, $alias)
    {
        $bean_id = $this->db->quoted($this->focus->id);

        $table = <<<SQL
(
  /* directly assigned emails */
  SELECT
    bean_id id,
    eb.email_id
  FROM emails_beans eb
  WHERE eb.bean_id = $bean_id
        AND eb.bean_module = '{$this->focus->module_dir}'
        AND eb.deleted = 0
  UNION
  /* related by email address */
  SELECT DISTINCT
    eabr.bean_id id,
    eear.email_id
  FROM
    emails_email_addr_rel eear
    INNER JOIN email_addr_bean_rel eabr
      ON eabr.bean_id = $bean_id
         AND eabr.bean_module = '{$this->focus->module_dir}'
         AND eabr.email_address_id = eear.email_address_id
         AND eabr.deleted = 0
  WHERE eear.deleted = 0
)
SQL;

        $join = $query->joinTable($table, array(
            'alias' => $alias,
        ));
        $join->on()->equalsField($alias . '.email_id', $fromAlias . '.id');

        return $join;
    }

    /**
     * Builds main join for archived emails
     * @param string $params
     * @return string JOIN clause
     */
    protected function getEmailsJoin($params = array())
    {
        $bean_id = $this->db->quoted($this->focus->id);
        if (!empty($params['join_table_alias'])) {
            $table_name = $params['join_table_alias'];
        } else {
            $table_name = 'emails';
        }

        return "INNER JOIN (\n".
                // directly assigned emails
            "select eb.email_id FROM emails_beans eb where eb.bean_module = '{$this->focus->module_dir}'
                AND eb.bean_id = $bean_id AND eb.deleted=0\n" .
  " UNION ".
        // Related by directly by email
            "select DISTINCT eear.email_id  from emails_email_addr_rel eear INNER JOIN email_addr_bean_rel eabr
            ON eabr.bean_id = $bean_id AND eabr.bean_module = '{$this->focus->module_dir}' AND
            eabr.email_address_id = eear.email_address_id and eabr.deleted=0 where eear.deleted=0\n" .
             ") email_ids ON $table_name.id=email_ids.email_id ";
    }

    /**
     * Get query for retrieving beans from this link
     * @param array $params
     *            optional parameters. Possible Values;
     *            'return_as_array': returns the query broken into
     * @return String/Array query to grab just ids for this relationship
     *
     * @deprecated Use ArchivedEmailsLink::query() instead
     */
    public function getQuery($params = array())
    {
        $query_array['select'] = "SELECT DISTINCT emails.* ";
        $query_array['from'] = "FROM emails ";
        $query_array['join'] = $this->getEmailsJoin();

        $deleted = !empty($params['deleted']) ? 1 : 0;
        $query_array['where'] = " WHERE emails.deleted=$deleted ";

        // Add any optional where clause
        if (!empty($params['where'])) {
            $query_array['where'] .= " AND ({$params['where']}) ";
        }

        if (!empty($params['enforce_teams'])) {
            $seed = BeanFactory::newBean($this->getRelatedModuleName());
            $seed->addVisibilityFrom($query_array['join']);
            $seed->addVisibilityWhere($query_array['where']);
        }

        if (!empty($params['return_as_array'])) {
            return $query_array;
        }

        $query = $query_array['select'] . $query_array['from'] . $query_array['join'] .
             $query_array['where'];
        if (!empty($params['orderby'])) {
            $query .= "ORDER BY {$params['orderby']}";
        }
        if (!empty($params['limit']) && $params['limit'] > 0) {
            $offset = isset($params['offset']) ? $params['offset'] : 0;
            $query = $this->db->limitQuery($query, $offset, $params['limit'], false, "", false);
        }
        return $query;
    }

    /**
     * This function is similair getJoin except for M2m relationships it won't
     * join agaist the final table.
     * Its used to retrieve the ID of the related beans only
     *
     * @param $params array
     *            of parameters. Possible parameters include:
     *            'return_as_array': returns the query broken into
     * @param bool $return_array
     *            same as passing 'return_as_array' into parameters
     * @return string/array query to use when joining for subpanels
     */
    public function getSubpanelQuery($params = array(), $return_array = false)
    {
        $query_array['join'] = $this->getEmailsJoin($params);
        $query_array['select'] = "";
        $query_array['from'] = "";
        $query_array['join_tables'] = 'email_ids';

        if (!empty($params['return_as_array'])) {
            $return_array = true;
        }
        if ($return_array) {
            return $query_array;
        }
        return $query_array['join'];
    }

    /**
     * If there are any relationship fields, we need to figure out the mapping
     * from the relationship fields to the
     * fields in the module vardefs
     */
    public function getRelationshipFieldMapping(SugarBean $seed = null)
    {
        return array();
    }

    /**
     * use this function to create link between 2 objects
     */
    public function add($rel_keys, $additional_values = array())
    {
        // cannot add to this relationship as it is implicit
        return false;
    }

    /**
     * Marks the relationship deleted for this given record pair.
     */
    public function delete($id, $related_id = '')
    {
        // cannot delete from this relationship as it is implicit
        return false;
    }
}
