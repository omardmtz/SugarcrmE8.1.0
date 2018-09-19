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

namespace Sugarcrm\Sugarcrm\MetaData\RefreshQueue;

/**
 * Task
 */
class Task
{
    /**
     * Task items
     *
     * @var array
     */
    protected $items = array();

    /**
     * Task scope
     *
     * @var Scope
     */
    protected $scope;

    /**
     * Constructor
     *
     * @param array $items Task items
     * @param array $params Task parameters
     */
    public function __construct(array $items, array $params)
    {
        $this->items = array_flip($items);
        $this->scope = new Scope($params);
    }

    /**
     * Returns task items
     *
     * @return array
     */
    public function getItems()
    {
        return array_keys($this->items);
    }

    /**
     * Returns task items
     *
     * @return array
     */
    public function getParams()
    {
        return $this->scope->getParams();
    }

    /**
     * Merges items from the other task into the current one
     *
     * @param Task $other Other task
     */
    public function mergeItems(self $other)
    {
        $this->items = array_merge($this->items, $other->items);
    }

    /**
     * Removes items that exist in the other task from the current one
     *
     * @param Task $other Other task
     */
    public function subtractItems(self $other)
    {
        $this->items = array_diff_key($this->items, $other->items);
    }

    /**
     * Checks if the given task completely includes the other
     *
     * @param Task $other Other task
     * @return bool
     */
    public function includes(self $other)
    {
        return array_intersect_key($this->items, $other->items) == $other->items
            && $this->scope->includes($other->scope);
    }

    /**
     * Checks if the other task includes some items from this one and has equal or wider context
     *
     * @param Task $other Other task
     * @return bool
     */
    public function isCoveredBy(self $other)
    {
        return count(array_intersect_key($this->items, $other->items)) != 0
            && $other->scope->includes($this->scope);
    }

    /**
     * Checks if the other task has equal scope
     *
     * @param Task $other Other task
     * @return bool
     */
    public function hasEqualScope(self $other)
    {
        return $this->scope->equals($other->scope);
    }

    /**
     * Checks if the task is empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return count($this->items) == 0;
    }

    /**
    * Add/Update the params of this task
    *
    * @param array $params
    */
    public function mergeParams(array $params)
    {
        $this->scope->mergeParams($params);
    }
}
