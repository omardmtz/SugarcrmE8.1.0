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
 * Represents 1-1 relationship
 * @api
 */
class One2OneRelationship extends M2MRelationship
{

    public function __construct($def)
    {
        parent::__construct($def);
    }
    /**
     * @param  $lhs SugarBean left side bean to add to the relationship.
     * @param  $rhs SugarBean right side bean to add to the relationship.
     * @param  $additionalFields key=>value pairs of fields to save on the relationship
     * @return boolean true if successful
     */
    public function add($lhs, $rhs, $additionalFields = array())
    {
        $dataToInsert = $this->getRowToInsert($lhs, $rhs, $additionalFields);
        //If the current data matches the existing data, don't do anything
        if (!$this->checkExisting($dataToInsert))
        {
            $success = true;
            $lhsLinkName = $this->lhsLink;
            $rhsLinkName = $this->rhsLink;
            //In a one to one, any existing links from both sides must be removed first.
            //one2Many will take care of the right side, so we'll do the left.
            $lhs->load_relationship($lhsLinkName);
            if ($this->removeAll($lhs->$lhsLinkName) === false) {
                LoggerManager::getLogger()->error("Warning: failed calling removeAll() on lhsLinkName: $lhsLinkName for relationship {$this->name} within One2OneRelationship->add().");
            }
            $rhs->load_relationship($rhsLinkName);
            if ($this->removeAll($rhs->$rhsLinkName) === false) {
                $success = false;
                LoggerManager::getLogger()->error("Warning: failed calling removeAll() on rhsLinkName: $rhsLinkName for relationship {$this->name} within One2OneRelationship->add().");
            }
            if (parent::add($lhs, $rhs, $additionalFields) === false) {
                $success = false;
                LoggerManager::getLogger()->error("Warning: failed calling parent add() for relationship {$this->name} within One2OneRelationship->add().");
            }
            return $success;
        }
        // data matched what was there so return false, since nothing happened
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getType($side)
    {
        return REL_TYPE_ONE;
    }

}
