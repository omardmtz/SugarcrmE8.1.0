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

use Sugarcrm\Sugarcrm\Util\Arrays\OrderedHash\OrderedHash;
use Sugarcrm\Sugarcrm\Util\Arrays\OrderedHash\Element;

class DropdownMerger
{
    /**
     * Performs a three-way merge of the hashes representing dropdown lists and returns the resulting hash.
     *
     * The algorithm starts by generating a new list from the core list in the new version. Any customizations are
     * played on top of the new version. This behaves similar to how rebasing is performed in git.
     *
     * @param array $old The core options in the previous version.
     * @param array $new The core options in the new version.
     * @param array $custom The customized options from the previous version.
     * @return Array
     */
    public function merge(array $old, array $new, array $custom)
    {
        $old = new OrderedHash($old);
        $new = new OrderedHash($new);
        $custom = new OrderedHash($custom);

        $merged = $this->copyList($new);
        $this->reorderOptionsMovedInCustom($merged, $old, $custom);
        $this->removeDeletedOptions($merged, $old, $new, $custom);
        $this->insertCustomOptions($merged, $old, $new, $custom);
        $this->reorderOptionsInsertedIntoNew($merged, $old, $new);

        return $merged->toArray();
    }

    /**
     * Searches `$list` for any of the options that come before `$option` and returns the first one that is encountered.
     *
     * This method is used to find the most accurate location in which to insert or move an option. Walking toward the
     * head of the list containing `$option` and identifying the first intersection between that list and `$list`
     * points to a reasonable spot to place `$option` while preserving order as much as possible. In most cases, the
     * first intersection points to the exact same location as before and nothing is changed.
     *
     * NULL is returned when no intersection is found between `$option` and the head. Upon reaching the head without any
     * intersections, NULL is always considered an intersection since it is guaranteed to exist for all lists.
     *
     * @param Element $option The option to be placed.
     * @param OrderedHash $list The list within which to find the first intersection.
     * @return null|Element
     */
    protected function findTheFirstPreviousOptionInList(Element $option, OrderedHash $list)
    {
        $needle = null;

        while (is_null($needle) && ($before = $option->getBefore())) {
            $needle = $list[$before->getKey()];
            $option = $before;
        }

        return $needle;
    }

    /**
     * Returns TRUE if the option identified by `$key` is not in the correct place in the unordered list and FALSE if it
     * is in the correct place in the unordered list.
     *
     * @param string $key The name by which the option can be identified in other lists.
     * @param OrderedHash $orderedList The list with the correct ordering.
     * @param OrderedHash $unorderedList The list with the potentially incorrect ordering.
     * @return bool
     */
    protected function shouldReorder($key, OrderedHash $orderedList, OrderedHash $unorderedList)
    {
        $optionInOrdered = $orderedList[$key];
        $optionInUnordered = $unorderedList[$key];

        // the option doesn't need to be moved if it doesn't exist in both lists
        if (is_null($optionInOrdered) || is_null($optionInUnordered)) {
            return false;
        }

        // the option should be moved if the options returned are different
        $beforeOptionInOrdered = $this->findTheFirstPreviousOptionInList($optionInOrdered, $unorderedList);
        $beforeOptionInUnordered = $this->findTheFirstPreviousOptionInList($optionInUnordered, $orderedList);

        if (is_null($beforeOptionInOrdered) && is_null($beforeOptionInUnordered)) {
            // even if the order changed, it is impossible to know how to effectively move the option
            // assume that the list is already ordered in the most accurate way possible
            return false;
        }

        if (is_null($beforeOptionInOrdered) || is_null($beforeOptionInUnordered)) {
            return true;
        }

        return !($beforeOptionInOrdered->getKey() === $beforeOptionInUnordered->getKey());
    }

    /**
     * Returns a deep copy of the passed in list.
     *
     * @param OrderedHash $source The list to copy.
     * @return OrderedHash
     */
    protected function copyList(OrderedHash $source)
    {
        $copy = new OrderedHash();

        if (!$source->isEmpty()) {
            foreach ($source as $key => $option) {
                $copy[$key] = $option->getValue();
            }
        }

        return $copy;
    }

    /**
     * Identifies options that were moved during a customization and attempts to move them to the location that is most
     * in accordance with the customization, in relation to the new version of the core list.
     *
     * @param OrderedHash $merged The current state of the merge operation. Changes are played on this list.
     * @param OrderedHash $old The core list from the previous version.
     * @param OrderedHash $custom The customized list from the previous version.
     */
    protected function reorderOptionsMovedInCustom(OrderedHash $merged, OrderedHash $old, OrderedHash $custom)
    {
        if (!$custom->isEmpty()) {
            foreach ($custom as $key => $optionInCustom) {
                // only move the option if it's location in custom is different than it's location in old
                if ($this->shouldReorder($key, $old, $custom)) {
                    $before = $this->findTheFirstPreviousOptionInList($optionInCustom, $merged);
                    $merged->move($key, $before);
                }
            }
        }
    }

    /**
     * Removes any options that were either deleted from the core list in the new version or from the customized list in
     * the previous version.
     *
     * @param OrderedHash $merged The current state of the merge operation. Changes are played on this list.
     * @param OrderedHash $old The core list from the previous version.
     * @param OrderedHash $new The core list from the new version.
     * @param OrderedHash $custom The customized list from the previous version.
     */
    protected function removeDeletedOptions(
        OrderedHash $merged,
        OrderedHash $old,
        OrderedHash $new,
        OrderedHash $custom
    ) {
        if (!$old->isEmpty()) {
            $newEmpty = $new->isEmpty();
            $customEmpty = $custom->isEmpty();
            foreach ($old as $key => $optionInOld) {
                if ((!$newEmpty && !$new[$key]) || (!$customEmpty && !$custom[$key])) {
                    unset($merged[$key]);
                }
            }
        }
    }

    /**
     * Inserts options that were created as a part of a customization.
     *
     * In addition to inserting, this method handles customizations made to the values of the options. Any customized
     * values are prioritized over the core values. Special care is taken to skip any options that were deleted from the
     * core list in the new version in order to avoid re-introducing an option that should not persist.
     *
     * @param OrderedHash $merged The current state of the merge operation. Changes are played on this list.
     * @param OrderedHash $old The core list from the previous version.
     * @param OrderedHash $new The core list from the new version.
     * @param OrderedHash $custom The customized list from the previous version.
     */
    protected function insertCustomOptions(OrderedHash $merged, OrderedHash $old, OrderedHash $new, OrderedHash $custom)
    {
        if (!$custom->isEmpty()) {
            foreach ($custom as $key => $optionInCustom) {
                if ($old[$key] && !$new[$key]) {
                    continue;
                }

                if ($optionInMerged = $merged[$key]) {
                    // use the value from custom if it had been customized
                    $optionInOld = $old[$key];

                    if (!$optionInOld || $optionInCustom->getValue() !== $optionInOld->getValue()) {
                        $optionInMerged->setValue($optionInCustom->getValue());
                    }
                } else {
                    if ($custom->bottom() === $optionInCustom) {
                        $merged->unshift($key, $optionInCustom->getValue());
                    } else {
                        $before = $this->findTheFirstPreviousOptionInList($optionInCustom, $merged);
                        $merged->add($before, $key, $optionInCustom->getValue());
                    }
                }
            }
        }
    }

    /**
     * Identifies options that were introduced in the core list from the new version and attempts to move them to the
     * location that is most in accordance with that list.
     *
     * This does the job of moving any brand new options that might have been misplaced while handling the
     * customizations.
     *
     * @param OrderedHash $merged The current state of the merge operation. Changes are played on this list.
     * @param OrderedHash $old The core list from the previous version.
     * @param OrderedHash $new The core list from the new version.
     */
    protected function reorderOptionsInsertedIntoNew(OrderedHash $merged, OrderedHash $old, OrderedHash $new)
    {
        if (!$new->isEmpty()) {
            foreach ($new as $key => $optionInNew) {
                if (!$old[$key]) {
                    // the option is moved to the head if $optionInNew is the head
                    // otherwise, the option is moved to follow the same option from the core list in the new version
                    $before = null;

                    if ($new->bottom() !== $optionInNew) {
                        $before = $merged[$optionInNew->getBefore()->getKey()];
                    }

                    $merged->move($key, $before);
                }
            }
        }
    }
}
