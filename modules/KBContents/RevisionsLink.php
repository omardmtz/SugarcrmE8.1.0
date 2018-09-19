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


class RevisionsLink extends Link2
{
    /**
     * {@inheritdoc}
     */
    function buildJoinSugarQuery($sugar_query, $options = array())
    {
        $sugar_query->where()
            ->notEquals('id', $this->focus->id)
            ->equals('kbdocument_id', $this->focus->kbdocument_id)
            ->equals('kbarticle_id', $this->focus->kbarticle_id);

        return $this->relationship->buildJoinSugarQuery($this, $sugar_query, $options);
    }
}
