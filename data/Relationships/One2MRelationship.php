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
 * Represents a one to many relationship that is table based.
 * @api
 */
class One2MRelationship extends M2MRelationship
{

    public function __construct($def)
    {
        global $dictionary;

        $this->def = $def;
        $this->name = $def['name'];

        $this->selfReferencing = $def['lhs_module'] == $def['rhs_module'];
        $lhsModule = $def['lhs_module'];
        $rhsModule = $def['rhs_module'];

        if ($this->selfReferencing)
        {
            $links = VardefManager::getLinkFieldForRelationship(
                $lhsModule, BeanFactory::getObjectName($lhsModule), $this->name
            );
            if (empty($links))
            {
                $GLOBALS['log']->fatal("No Links found for relationship {$this->name}");
            }
            else {
                if (!isset($links[0]) && !isset($links['name'])) {
                    $GLOBALS['log']->fatal("Bad link found for relationship {$this->name}");
                }
                else if (!isset($links[1])&&isset($links['name'])) //Only one link for a self referencing relationship, this is very bad.
                {
                    $this->lhsLinkDef = $this->rhsLinkDef = $links;
                }
                else if (!empty($links[0]) && !empty($links[1]))
                {

                    if ((!empty($links[0]['side']) && $links[0]['side'] == "right")
                        || (!empty($links[0]['link_type']) && $links[0]['link_type'] == "one"))
                    {
                        //$links[0] is the RHS
                        $this->lhsLinkDef = $links[1];
                        $this->rhsLinkDef = $links[0];
                    } else
                    {
                        //$links[0] is the LHS
                        $this->lhsLinkDef = $links[0];
                        $this->rhsLinkDef = $links[1];
                    }
                }
            }
        } else
        {
            $this->lhsLinkDef = $this->getLinkedDefForModuleByRelationship($lhsModule);
            $this->rhsLinkDef = $this->getLinkedDefForModuleByRelationship($rhsModule);
        }
        $this->lhsLink = $this->lhsLinkDef['name'];
        $this->rhsLink = $this->rhsLinkDef['name'];
    }

    protected function linkIsLHS($link) {
        if ( $this->lhsLink != $this->rhsLink ) {
            return $link->getSide() == REL_LHS;
        } else {
            return ($link->getSide() == REL_LHS && !$this->selfReferencing)
                || ($link->getSide() == REL_RHS && $this->selfReferencing);
        }
    }

    /**
     * @param  $lhs SugarBean left side bean to add to the relationship.
     * @param  $rhs SugarBean right side bean to add to the relationship.
     * @param  $additionalFields key=>value pairs of fields to save on the relationship
     * @return boolean true if successful
     */
    public function add($lhs, $rhs, $additionalFields = array())
    {
        $success = false;
        $dataToInsert = $this->getRowToInsert($lhs, $rhs, $additionalFields);

        //If the current data matches the existing data, don't do anything
        $currentRow = $this->relationship_exists($lhs, $rhs, true);
        if (!$currentRow || !$this->compareRow($currentRow, $dataToInsert)) {
            // Pre-load the RHS relationship, which is used later in the add() function and expects a Bean
            // and we also use it for clearing relationships in case of non self-referencing O2M relations
            // (should be preloaded because when using the relate_to field for updating/saving relationships,
            // only the bean id is loaded into $rhs->$rhsLinkName)
            $success = true;
            $rhsLinkName = $this->rhsLink;
            $rhs->load_relationship($rhsLinkName);

            // For self-referencing from 6.5.x
            // The left side is one, and right side is many

            if ($this->isRHSMany()) {
                $lhsLinkName = $this->lhsLink;
                $lhs->load_relationship($lhsLinkName);
                if (!$currentRow && $this->removeAll($lhs->$lhsLinkName) === false) {
                    $success = false;
                    LoggerManager::getLogger()->error("Warning: failed calling removeAll() on lhsLinkName: $lhsLinkName for relationship {$this->name} within One2MRelationship->add().");
                }
            } else { // For non self-referencing, remove all the relationships from the many (RHS) side
                if (!$currentRow && $this->removeAll($rhs->$rhsLinkName) === false) {
                    $success = false;
                    LoggerManager::getLogger()->error("Warning: failed calling removeAll() on rhsLinkName: $rhsLinkName for relationship {$this->name} within One2MRelationship->add().");
                }
            }

            // Add relationship
            if (parent::add($lhs, $rhs, $additionalFields) === false) {
                $success = false;
                LoggerManager::getLogger()->error("Warning: failed calling parent add() for relationship {$this->name} within One2MRelationship->add().");
            }
        }
        return $success;
    }


    /**
     * Just overriding the function from M2M to prevent it from occuring
     * 
     * The logic for dealing with adding self-referencing one-to-many relations is in the add() method
     */
    protected function addSelfReferencing($lhs, $rhs, $additionalFields = array())
    {
        //No-op on One2M.
    }

    /**
     * Just overriding the function from M2M to prevent it from occuring
     */
    protected function removeSelfReferencing($lhs, $rhs, $additionalFields = array())
    {
        //No-op on One2M.
    }

    /**
     * {@inheritdoc}
     */
    public function getType($side)
    {
        return $side == REL_LHS ? REL_TYPE_MANY : REL_TYPE_ONE;
    }

    /**
     * Check if the relationship is coming from 6.5.x where we have the
     * left and right sides swapped
     *
     * @return bool
     */
    private function isRHSMany()
    {
        return ($this->selfReferencing &&
            (
                (isset($this->lhsLinkDef['side']) && isset($this->lhsLinkDef['link-type']) &&
                    $this->lhsLinkDef['side'] == 'left' && $this->lhsLinkDef['link-type'] == 'one') ||
                (isset($this->rhsLinkDef['side']) && isset($this->rhsLinkDef['link-type']) &&
                    $this->rhsLinkDef['side'] == 'right' && $this->rhsLinkDef['link-type'] == 'many')
            )
        );
    }

    /**
     * @return String right link in relationship.
     */
    public function getRHSLink()
    {
        if ($this->isRHSMany()) {
            return $this->lhsLink;
        }

        return $this->rhsLink;
    }
}
