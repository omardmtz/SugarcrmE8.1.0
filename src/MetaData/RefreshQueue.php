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

namespace Sugarcrm\Sugarcrm\MetaData;

use Sugarcrm\Sugarcrm\MetaData\RefreshQueue\Task;

/**
 * Metadata rebuild queue
 */
class RefreshQueue
{
    /**
     * @var Task[][]
     */
    protected $tasks = array();

    /**
     * Enqueues new task
     *
     * @param string $category Category (sub-queue) name
     * @param array $items Items
     * @param array $scope Task scope
     */
    public function enqueue($category, array $items, array $scope = array())
    {
        $task = new Task($items, $scope);
        if (!$this->isEnqueued($category, $task)) {
            $this->add($category, $task);
        }
    }

    /**
     * Dequeues task
     *
     * @return array|null
     */
    public function dequeue()
    {
        if (count($this->tasks) == 0) {
            return null;
        }

        reset($this->tasks);
        $category = key($this->tasks);

        $task = array_shift($this->tasks[$category]);
        if (count($this->tasks[$category]) == 0) {
            unset($this->tasks[$category]);
        }

        return array($category, $task->getItems(), $task->getParams());
    }

    /**
     * Clears queue
     *
     *
     * @param array $platforms list of platforms to clear all tasks for.
     * Clears for all if empty or ommited.
     */
    public function clear(array $platforms = array())
    {
        if (empty($platforms)) {
            $this->tasks = array();
        } else {
            foreach ($this->tasks as $category => $tasks) {
                $this->tasks[$category] = array_filter($tasks, function (Task $task) use ($platforms) {
                    $params = $task->getParams();
                    if (!empty($params['platforms'])) {
                        //if all the task platforms are in the clear, drop the task
                        if (sizeof(array_intersect($params['platforms'], $platforms)) == sizeof($params['platforms'])) {
                            return false;
                        }
                        //Otherwise keep the task but update the list of platforms if required.
                        $task->mergeParams(['platforms' => array_diff($params['platforms'], $platforms)]);

                        return true;
                    }
                    //If the task only has one platform and it isn't being cleared, keep it.
                    if (!empty($params['platform']) && !in_array($params['platform'], $platforms)) {
                        return true;
                    }

                    return false;
                });
            }
        }
    }

    /**
     * Checks if the given task or another task that includes it is already enqueued
     *
     * @param string $category Category name
     * @param Task $task Task
     * @return bool
     */
    protected function isEnqueued($category, Task $task)
    {
        if (!isset($this->tasks[$category])) {
            return false;
        }

        foreach ($this->tasks[$category] as $t) {
            if ($t->includes($task)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Adds new task to the queue.
     *
     * In case if the new one has some items and a wider context than an existing task, those items are removed
     * from that task.
     *
     * In case there is an item with the same context, new items are added to that task.
     *
     * @param string $category Category name
     * @param Task $task
     */
    protected function add($category, Task $task)
    {
        if (!isset($this->tasks[$category])) {
            $this->tasks[$category] = array();
        }

        $isAdded = false;
        foreach ($this->tasks[$category] as $i => $t) {
            if ($t->isCoveredBy($task)) {
                $t->subtractItems($task);
                if ($t->isEmpty()) {
                    unset($this->tasks[$category][$i]);
                }
            } elseif (!$isAdded && $task->hasEqualScope($t)) {
                $t->mergeItems($task);
                $isAdded = true;
            }
        }

        if (!$isAdded) {
            $this->tasks[$category][] = $task;
        }
    }
}
