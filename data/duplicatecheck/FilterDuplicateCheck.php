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
 * This method of duplicate check passes a configurable set of filters off to the Filter API to find duplicates.
 */
class FilterDuplicateCheck extends DuplicateCheckStrategy
{
    const DUPE_CHECK_RANK = 'duplicate_check_rank';
    const FIELD_PLACEHOLDER = '$';
    const FILTER_QUERY_LIMIT = 20;

    var $filterTemplate = array();
    var $rankingFields = array();

    /**
     * Parses out the duplicate check filter and rankings into protected variables
     *
     * @param $metadata
     */
    protected function setMetadata($metadata)
    {
        if (isset($metadata['filter_template'])) {
            $this->filterTemplate = $metadata['filter_template'];
        }

        if (isset($metadata['ranking_fields'])) {
            $this->rankingFields = $metadata['ranking_fields'];
        }
    }

    /**
     * Finds possible duplicate records for a given set of field data.
     *
     * @access public
     */
    public function findDuplicates()
    {
        if (empty($this->filterTemplate)) {
            return null;
        }

        //build filter to hand off to the FilterApi
        $filter = $this->buildDupeCheckFilter($this->filterTemplate);

        //if filter is empty, don't bother continuing
        if (empty($filter)) {
            return null;
        }

        if (!empty($this->bean->id)) {
            $filter = $this->addFilterForEdits($filter[0], $this->bean->id);
        }

        $duplicates = $this->callFilterApi($filter);

        //rank the duplicates found
        $duplicates = $this->rankAndSortDuplicates($duplicates);

        return $duplicates;
    }

    /**
     * Build the filter array to hand off the the Filter API
     * Based on the filter template in the vardef
     *
     * @param array $dupeCheckFilterTemplate
     * @return array
     */
    protected function buildDupeCheckFilter($dupeCheckFilterTemplate)
    {
        foreach ($dupeCheckFilterTemplate as &$filterDef) {
            foreach ($filterDef as $field => &$filter) {
                if ($field == '$or' || $field == '$and') {
                    $filter = $this->buildDupeCheckFilter($filter);
                    if (empty($filter)) {
                        unset($filterDef[$field]);
                    }
                } else {
                    // make sure we have read access to this field
                    if ($this->bean->ACLFieldAccess($field, 'read')) {
                        foreach ($filter as $op => &$value) {
                            $inField = $this->getIncomingFieldFromPlaceholder($value);
                            if ($inField !== false) {
                                if (isset($this->bean->$inField) && !empty($this->bean->$inField)) {
                                    $value = $this->getValueFromField($inField);
                                } else {
                                    unset($filterDef[$field]);
                                }
                            }
                        }
                    } else {
                        unset($filterDef[$field]);
                    }
                }
            }
        }
        $dupeCheckFilterTemplate = array_filter($dupeCheckFilterTemplate);
        return $dupeCheckFilterTemplate;
    }

    /**
     * Gets a value from a field on a bean
     * @param string $inField The field to get the value from
     * @return string
     */
    public function getValueFromField($inField)
    {
        return $this->bean->$inField;
    }

    /**
     * Add condition to filter to exclude existing record when running dupe check during edit
     *
     * @param string $filter
     * @param string $id
     * @return array
     */
    protected function addFilterForEdits($filter, $id)
    {
        return array(
            array('$and' => array(
                array('id' => array('$not_equals' => $id)),
                $filter,
            ))
        );
    }

    /**
     * If filter value starts with the field placeholder, returns the name of the incoming field
     * otherwise, returns false
     *
     * @param $filterValue
     * @return bool|mixed
     */
    protected function getIncomingFieldFromPlaceholder($filterValue)
    {
        if (strpos($filterValue, self::FIELD_PLACEHOLDER) === 0) {
            return substr($filterValue, 1);
        }
        return false;
    }

    protected function callFilterApi($filter)
    {
        // call filter to get data
        $filterApi = new FilterApi();
        $api = new RestService();
        $api->user = $GLOBALS['current_user'];
        $filterArgs = array(
            'filter' => $filter,
            'module' => $this->bean->module_name,
            'max_num' => self::FILTER_QUERY_LIMIT,
        );
        return $filterApi->filterList($api, $filterArgs, 'view');
    }

    /**
     * Rank the duplicates returned from the Filter API based on the ranking field metadata from the vardef
     *
     * @param array $results
     * @return array
     */
    protected function rankAndSortDuplicates($results)
    {
        if (empty($this->rankingFields)) {
            return $results;
        }

        $duplicates = $results['records'];
        //calculate rank of each duplicate based on rank field metadata
        $startingFieldWeight = count($this->rankingFields);
        foreach ($duplicates as &$duplicate) {
            $rank = 0;
            $fieldWeight = $startingFieldWeight;
            foreach ($this->rankingFields as $rankingField) {
                $inFieldName = $rankingField['in_field_name'];
                $dupeFieldName = $rankingField['dupe_field_name'];
                //if ranking field is on the dupe and on the field data passed to the api...
                if (isset($this->bean->$inFieldName) && isset($duplicate[$dupeFieldName])) {
                    $rank += $this->calculateFieldMatchQuality($this->bean->$inFieldName, $duplicate[$dupeFieldName], $fieldWeight);
                }
                $fieldWeight--;
            }
            $duplicate[self::DUPE_CHECK_RANK] = $rank;
        }

        //sort the duplicates based on rank
        usort($duplicates, array($this, 'compareDuplicateRanks'));
        $results['records'] = $duplicates;

        return $results;
    }

    /**
     * Calculates quality of a field match
     *
     * @param $incomingFieldValue
     * @param $dupeFieldValue
     * @param $fieldWeight
     * @return int|number
     */
    protected function calculateFieldMatchQuality($incomingFieldValue, $dupeFieldValue, $fieldWeight)
    {
        $incomingFieldValue = trim($incomingFieldValue);
        $dupeFieldValue = trim($dupeFieldValue);
        if ($incomingFieldValue === $dupeFieldValue) {
            return pow(2, $fieldWeight);
        }
        return 0;
    }

    /**
     * Compare function for use in sorting the duplicates
     *
     * @param array $dupe1
     * @param array $dupe2
     * @return int
     */
    protected function compareDuplicateRanks($dupe1, $dupe2)
    {
        $dupe1Rank = $dupe1[self::DUPE_CHECK_RANK];
        $dupe2Rank = $dupe2[self::DUPE_CHECK_RANK];
        if ($dupe1Rank == $dupe2Rank) {
            return 0;
        }
        return ($dupe1Rank < $dupe2Rank) ? 1 : -1;
    }

}
