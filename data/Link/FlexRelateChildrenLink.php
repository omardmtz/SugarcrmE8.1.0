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
 * Left-hand side link which aggregates related beans and the beans whose parent is current bean
 */
class FlexRelateChildrenLink extends Link2
{
    /**
     * {@inheritDoc}
     */
    public function getSide()
    {
        return REL_LHS;
    }

    /**
     * Reconstructs the query so that it fetches beans using both "related" and "parent" relationships
     *
     * {@inheritDoc}
     */
    public function buildJoinSugarQuery($sugar_query, $options = array())
    {
        parent::buildJoinSugarQuery($sugar_query, $options);

        $alias = $options['joinTableAlias'];

        /** @var SugarQuery_Builder_Join $join */
        $join = $sugar_query->join[$alias];
        $onContactId = array_shift($join->on()->conditions);

        $on = new SugarQuery_Builder_Orwhere($sugar_query);
        $on->add($onContactId);
        $on->queryAnd()
            ->equalsField('parent_id', $alias . '.id')
            ->equals('parent_type', $this->relationship->getLHSModule());

        array_unshift($join->on()->conditions, $on);
    }

    /**
     * Unlinks related beans and removes parent relation in case if it points to current bean
     *
     * {@inheritDoc}
     */
    public function delete($id, $related_id = '')
    {
        parent::delete($id, $related_id);

        if (!($related_id instanceof SugarBean)) {
            $related_id = $this->getRelatedBean($related_id);
        }

        /** @var SugarBean $relatedBean */
        if ($related_id
            && $related_id->parent_type == $this->relationship->getLHSModule()
            && $related_id->parent_id == $id) {
            $related_id->parent_type = '';
            $related_id->parent_id = '';
            $related_id->save();
        }
    }
}
