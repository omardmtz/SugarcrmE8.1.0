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

class OutboundEmailVisibility extends SugarVisibility
{
    /**
     * OutboundEmail records can only be seen by their owner. When the admin allows users to use the system default
     * outbound account, all users can also see the system default outbound account.
     *
     * {@inheritdoc}
     */
    public function addVisibilityWhere(&$query)
    {
        $alias = $this->getOption('table_alias');
        $where = $this->bean->getOwnerWhere($GLOBALS['current_user']->id, $alias);

        if (empty($alias)) {
            $alias = $this->bean->getTableName();
        }

        if ($this->bean->isAllowUserAccessToSystemDefaultOutbound()) {
            $where = "({$where} OR {$alias}.type="  .  $GLOBALS['db']->quoted(OutboundEmail::TYPE_SYSTEM) .
                ") AND {$alias}.type<>" . $GLOBALS['db']->quoted(OutboundEmail::TYPE_SYSTEM_OVERRIDE);
        }

        $where = "({$where})";
        $query = empty($query) ? $where : "{$query} AND {$where}";

        return $query;
    }

    /**
     * OutboundEmail records can only be seen by their owner. When the admin allows users to use the system default
     * outbound account, all users can also see the system default outbound account.
     *
     * {@inheritdoc}
     */
    public function addVisibilityWhereQuery(SugarQuery $query)
    {
        $where = null;
        $this->addVisibilityWhere($where);

        if (!empty($where)) {
            $query->where()->queryAnd()->addRaw($where);
        }

        return $query;
    }
}
