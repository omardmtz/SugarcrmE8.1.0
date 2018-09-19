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

class SugarUpgradeUpdateTeamsFilters extends UpgradeScript
{
    public $order = 7600;
    public $type = self::UPGRADE_DB;

    /**
     * Convert Teams filters to use multiselect operators if upgrading from
     * before 7.10.
     */
    public function run()
    {
        if (version_compare($this->from_version, '7.10', '<')) {
            $this->convertFilters();
        }
    }

    /**
     * Convert all Teams filters in the database from using equals/not equals
     * operators to multiselect operators.
     */
    private function convertFilters()
    {
        $fieldDefs = VardefManager::getFieldDefs('Filters');

        $query = new SugarQuery();
        $query->from(BeanFactory::newBean('Filters'));
        $query->select(array('id', 'filter_definition', 'filter_template'));

        foreach ($query->execute() as $filter) {
            try {
                $newFilterDefinition = $this->convertFilter($filter['filter_definition']);
            } catch (InvalidArgumentException $exception) {
                $this->log('Filter definition for filter ' . $filter['id'] . ' is invalid JSON; ' .
                    'not attempting to convert.');
                $newFilterDefinition = $filter['filter_definition'];
            }
            try {
                $newFilterTemplate = $this->convertFilter($filter['filter_template']);
            } catch (InvalidArgumentException $exception) {
                $this->log('Filter template for filter ' . $filter['id'] . ' is invalid JSON; ' .
                    'not attempting to convert.');
                $newFilterTemplate = $filter['filter_template'];
            }

            $this->db->updateParams('filters', $fieldDefs, array(
                'filter_definition' => $newFilterDefinition,
                'filter_template' => $newFilterTemplate,
            ), array(
                'id' => $filter['id'],
            ));
        }
    }

    /**
     * Convert all uses of equals/not equals operators in a given filter
     * definition (or template) to in/not in operators.
     *
     * @param string $filterString The (stringified) filter to convert.
     * @return string The (stringified) converted filter.
     * @throws InvalidArgumentException if the filter cannot be decoded as JSON.
     */
    public function convertFilter($filterString)
    {
        $filterComponent = json_decode($filterString, true);
        if ($filterComponent === null) {
            throw new InvalidArgumentException('Invalid JSON in filter');
        }

        return json_encode($this->parseFilterComponent($filterComponent));
    }

    /**
     * Create a new filter component equivalent to the given component
     * except with Teams filters converted to multiselects.
     *
     * @param array $filterComponent The filter component to convert.
     * @return array The converted filter component.
     */
    public function parseFilterComponent(array $filterComponent)
    {
        foreach ($filterComponent as $filterIndex => &$filterPart) {
            foreach ($filterPart as $filterKey => $filterValue) {
                if ($filterKey !== 'team_id') {
                    continue;
                }

                $newFilterPart = $this->convertTeamsFilterValue($filterValue);
                if (isset($newFilterPart)) {
                    $filterPart[$filterKey] = $newFilterPart;
                }
            }
        }

        return $filterComponent;
    }

    /**
     * Convert a Teams filter value that uses equals/not equals operators
     * to one that uses in/not in operators.
     *
     * @param string|array $filterValue The filter value to convert.
     * @return array|null The converted filter value or null if we did not
     *   convert it.
     */
    public function convertTeamsFilterValue($filterValue)
    {
        if (is_string($filterValue)) {
            return array(
                '$in' => array(
                    $filterValue,
                ),
            );
        }

        if (is_array($filterValue)) {
            // note that there might be custom operators as well; just ignore them
            if (isset($filterValue['$equals'])) {
                return array(
                    '$in' => array(
                        $filterValue['$equals'],
                    ),
                );
            }

            if (isset($filterValue['$not_equals'])) {
                return array(
                    '$not_in' => array(
                        $filterValue['$not_equals'],
                    ),
                );
            }
        }

        return null;
    }
}
