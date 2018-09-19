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
 * Collection API
 */
abstract class CollectionApi extends SugarApi
{
    /**
     * The key appended to each resulting record designating which collection source it was retrieved from
     *
     * @var string
     */
    protected static $sourceKey = '_source';

    /**
     * Function to compare string values when sorting records
     *
     * @var callable
     */
    protected $collator = 'strcasecmp';

    /**
     * Sets the function to compare string values when sorting records
     *
     * @param callable $collator
     */
    public function setCollator($collator)
    {
        $this->collator = $collator;
    }

    /**
     * API endpoint
     *
     * If sub-requests result in errors, those errors will be appended to the response under the `errors` key.
     *
     * @param ServiceBase $api
     * @param array $args
     *
     * @return array
     * @throws SugarApiExceptionError
     * @throws SugarApiExceptionInvalidParameter
     * @throws SugarApiExceptionMissingParameter
     * @throws SugarApiExceptionNotAuthorized
     * @throws SugarApiExceptionNotFound
     */
    public function getCollection(ServiceBase $api, array $args)
    {
        $definition = $this->getCollectionDefinition($api, $args);
        $args = $this->normalizeArguments($args, $definition);

        $sortSpec = $this->getSortSpec($definition, $args['order_by']);
        $sortFields = $this->getAdditionalSortFields($args, $definition, $sortSpec);

        $data = $this->getData($api, $args, $definition, $sortFields);

        $records = $this->sortData($data, $sortSpec, $args['offset'], $args['max_num'], $nextOffset);

        // remove unwanted fields from the data
        $records = $this->cleanData($records, $sortFields);

        $errors = $this->extractErrors($data);

        return $this->buildResponse($records, $nextOffset, $errors);
    }

    /**
     * Returns an array representing the API response from the sets of records, offsets, and errors.
     *
     * @param array $records
     * @param array $nextOffset
     * @param array $errors
     * @return array
     */
    protected function buildResponse(array $records, array $nextOffset, array $errors)
    {
        $response = array(
            'records' => $records,
            'next_offset' => $nextOffset,
        );

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return $response;
    }

    /**
     * API endpoint
     *
     * @param ServiceBase $api
     * @param array $args
     *
     * @return array
     * @throws SugarApiExceptionError
     * @throws SugarApiExceptionInvalidParameter
     * @throws SugarApiExceptionMissingParameter
     * @throws SugarApiExceptionNotAuthorized
     * @throws SugarApiExceptionNotFound
     */
    public function getCollectionCount(ServiceBase $api, array $args)
    {
        $definition = $this->getCollectionDefinition($api, $args);
        $args = $this->normalizeArguments($args, $definition);

        $count = $this->getCount($api, $args, $definition);

        return array(
            'record_count' => $count,
        );
    }

    /**
     * Retrieves records from collection sources
     *
     * Any SugarApiException's that are thrown when retrieving related records are caught and added to the return value.
     * Even though the API may respond with "200 OK", the client should handle these errors appropriately. In these
     * cases, the HTTP error code, the application error string, and the error message are included in the response.
     *
     * @param ServiceBase $api
     * @param array $args API arguments
     * @param CollectionDefinitionInterface $definition
     * @param array $sortFields Additional fields required for client side sort
     *
     * @return array
     */
    protected function getData(
        ServiceBase $api,
        array $args,
        CollectionDefinitionInterface $definition,
        array $sortFields
    ) {
        $data = array();
        foreach ($definition->getSources() as $source) {
            if ($args['offset'][$source] >= 0) {
                $sourceArgs = $this->getSourceArguments($api, $args, $definition, $source, $sortFields[$source]);
                $response = array();

                try {
                    $response = $this->getSourceData($api, $source, $sourceArgs);
                } catch (SugarApiException $e) {
                    $response['next_offset'] = -1;
                    $response['records'] = array();
                    $response['error'] = array(
                        'code' => $e->getHttpCode(),
                        'error' => $e->getErrorLabel(),
                        'error_message' => $e->getMessage(),
                    );
                }

                $data[$source] = $response;
            }
        }

        return $data;
    }

    /**
     * Counts records in collection sources
     *
     * @param ServiceBase $api
     * @param array $args API arguments
     * @param CollectionDefinitionInterface $definition
     *
     * @return array
     * @throws SugarApiExceptionNotAuthorized
     * @throws SugarApiExceptionNotFound
     */
    protected function getCount(
        ServiceBase $api,
        array $args,
        CollectionDefinitionInterface $definition
    ) {
        $count = array();
        foreach ($definition->getSources() as $source) {
            $sourceArgs = $this->getSourceArguments($api, $args, $definition, $source);
            $response = $this->getSourceCount($api, $source, $sourceArgs);
            $count[$source] = $response['record_count'];
        }

        return $count;
    }

    /**
     * Creates arguments for RelateApi for specific source
     *
     * @param ServiceBase $api
     * @param array $args CollectionApi arguments
     * @param CollectionDefinitionInterface $definition Collection definition
     * @param string $source Collection source name
     * @param array $sortFields Additional fields required for client side sort
     *
     * @return array RelateApi arguments
     */
    protected function getSourceArguments(
        ServiceBase $api,
        array $args,
        CollectionDefinitionInterface $definition,
        $source,
        array $sortFields = array()
    ) {
        $args = array_merge($args, array(
            'offset' => $args['offset'][$source],
        ));

        $args['filter'] = $this->getSourceFilter($args, $definition, $source);
        unset($args['stored_filter']);

        $args = $this->mapSourceArguments($definition, $source, $args);

        if (count($args['fields']) > 0 && count($sortFields) > 0) {
            $args['fields'] = array_merge($args['fields'], $sortFields);
        }

        $args['order_by'] = $this->formatOrderBy($args['order_by']);

        // view name is only applicable to primary module, and it doesn't make
        // sense to pass it to related module
        unset($args['view']);

        return $args;
    }

    /**
     * Returns filter API argument for the given source
     *
     * @param array $args API arguments
     * @param CollectionDefinitionInterface $definition
     * @param string $source Collection source name
     * @return array
     */
    protected function getSourceFilter(array $args, CollectionDefinitionInterface $definition, $source)
    {
        $filters = array();

        if ($definition->hasSourceFilter($source)) {
            $filters[] = $definition->getSourceFilter($source);
        }

        foreach ($args['stored_filter'] as $filterId) {
            $filters[] = $definition->getStoredFilter($filterId);
        }

        if (isset($args['filter'])) {
            $filter = $args['filter'];
            if ($definition->hasFieldMap($source)) {
                $fieldMap = $definition->getFieldMap($source);
                $filter = $this->mapFilter($filter, $fieldMap);
            }
            $filters[] = $filter;
        }

        if (count($filters) > 0) {
            return call_user_func_array('array_merge', $filters);
        }

        return array();
    }

    /**
     * Maps API arguments using the mapping from collection definition for the given source
     *
     * @param CollectionDefinitionInterface $definition Collection definition
     * @param string $source Collection source name
     * @param array $args Arguments for underlying API call
     *
     * @return array Mapped arguments
     */
    protected function mapSourceArguments(CollectionDefinitionInterface $definition, $source, array $args)
    {
        if ($definition->hasFieldMap($source)) {
            $fieldMap = $definition->getFieldMap($source);
            $args['fields'] = $this->mapFields($args['fields'], $fieldMap);
            $args['order_by'] = $this->mapOrderBy($args['order_by'], $fieldMap);
        }

        return $args;
    }

    /**
     * Retrieves records from the given collection source
     *
     * @param ServiceBase $api
     * @param string $source Source name
     * @param array $args API arguments
     *
     * @return array
     */
    abstract protected function getSourceData(ServiceBase $api, $source, array $args);

    /**
     * Counts records from the given collection source
     *
     * @param ServiceBase $api
     * @param string $source Source name
     * @param array $args API arguments
     *
     * @return array
     */
    abstract protected function getSourceCount(ServiceBase $api, $source, array $args);

    /**
     * Returns collection definition
     *
     * @param ServiceBase $api
     * @param array $args API arguments
     *
     * @return CollectionDefinitionInterface
     */
    abstract protected function getCollectionDefinition(ServiceBase $api, array $args);

    /**
     * Normalizes API arguments according to collection field definition
     *
     * @param array $args API arguments
     * @param CollectionDefinitionInterface $definition
     *
     * @return array Normalized arguments
     * @throws SugarApiExceptionInvalidParameter when arguments are invalid and thus cannot be normalized
     */
    protected function normalizeArguments(array $args, CollectionDefinitionInterface $definition)
    {
        $args['offset'] = $this->normalizeOffset($args, $definition);
        if (!isset($args['max_num'])) {
            $args['max_num'] = $this->getDefaultLimit();
        }

        $args['order_by'] = $this->getOrderByFromArgs($args);

        if (!$args['order_by']) {
            $orderBy = $definition->getOrderBy();
            if ($orderBy) {
                $args['order_by'] = $this->getOrderByFromArgs(array(
                    'order_by' => $orderBy,
                ));
            } else {
                $args['order_by'] = $this->getDefaultOrderBy();
            }
        }

        // convert fields to a array for consistent behavior with SugarApi::formatBeans
        if (!empty($args['fields']) && !is_array($args['fields'])) {
            $args['fields'] = explode(',',$args['fields']);
        }

        $args['stored_filter'] = $this->normalizeStoredFilter($args, $definition);

        return $args;
    }

    /**
     * Normalizes and validates offset API argument
     *
     * @param array $args API arguments
     * @param array CollectionDefinitionInterface $definition
     *
     * @return array Normalized value
     * @throws SugarApiExceptionInvalidParameter
     */
    protected function normalizeOffset(array $args, CollectionDefinitionInterface $definition)
    {
        if (isset($args['offset'])) {
            if (!is_array($args['offset'])) {
                throw new SugarApiExceptionInvalidParameter(
                    sprintf('Offset must be an array, %s given', gettype($args['offset']))
                );
            }

            $offset = $args['offset'];
        } else {
            $offset = array();
        }

        $keys = array();
        foreach ($definition->getSources() as $source) {
            $keys[$source] = true;
            if (!isset($offset[$source])) {
                $offset[$source] = 0;
            } else {
                $offset[$source] = (int) $offset[$source];
                if ($offset[$source] < 0) {
                    $offset[$source] = -1;
                }
            }
        }

        // we remove all irrelevant offsets here, since later we'll be returning new offsets,
        // and we don't need irrelevant offsets to be returned
        $offset = array_intersect_key($offset, $keys);

        return $offset;
    }

    /**
     * Normalizes and validates stored_filter API argument
     *
     * @param array $args API arguments
     * @param array CollectionDefinitionInterface $definition
     *
     * @return array Normalized value
     * @throws SugarApiExceptionInvalidParameter when the stored filter given isn't found.
     */
    protected function normalizeStoredFilter(array $args, CollectionDefinitionInterface $definition)
    {
        if (!isset($args['stored_filter'])) {
            return array();
        }

        $filter = (array) $args['stored_filter'];
        foreach ($filter as $filterName) {
            if (!$definition->hasStoredFilter($filterName)) {
                throw new SugarApiExceptionInvalidParameter('Stored filter not found');
            }
        }

        return $filter;
    }

    /**
     * Returns an array of errors, grouped by link, that were encountered.
     *
     * Any errors are removed from $data. The records and offset are preserved so that they can be used in generating
     * the response.
     *
     * @param array $data Collection data group by link
     * @return array
     */
    protected function extractErrors(array &$data)
    {
        $errors = array();

        foreach ($data as $linkName => $linkData) {
            if (isset($linkData['error'])) {
                $errors[$linkName] = $linkData['error'];
                unset($data[$linkName]['error']);
            }
        }

        return $errors;
    }

    /**
     * Sorts collection data by preserving original order of records of the same module and populates offset
     * with the value corresponding to the next page
     *
     * @param array $data Collection data grouped by source
     * @param array $spec Sorting specification
     * @param array $offset Offset corresponding to the current page
     * @param int $limit Maximum number of resulting records
     * @param array $nextOffset Offset corresponding to the next page
     *
     * @return array Plain list of records
     */
    protected function sortData(array $data, array $spec, $offset, $limit, &$nextOffset)
    {
        $comparator = $this->getSourceDataComparator($spec);

        $sourceRecords = $returnedBySource = array();

        // put source name into every record
        foreach ($data as $source => $sourceData) {
            $sourceRecords[$source] = $sourceData['records'];
            $returnedBySource[$source] = 0;
            foreach ($sourceRecords[$source] as $i => $_) {
                $sourceRecords[$source][$i][static::$sourceKey] = $source;
            }
            $nextOffset[$source] = $sourceData['next_offset'];
        }

        $records = $index = array();
        while (true) {
            uasort($sourceRecords, $comparator);
            $source = key($sourceRecords);
            $record = array_shift($sourceRecords[$source]);
            if (!$record) {
                break;
            }
            if (!isset($index[$record['_module']][$record['id']])) {
                if ($limit >= 0 && count($records) >= $limit) {
                    array_unshift($sourceRecords[$source], $record);
                    break;
                }
                $records[] = $record;
                $index[$record['_module']][$record['id']] = true;
            }
            $returnedBySource[$record[static::$sourceKey]]++;
        }

        $nextOffset = $this->getNextOffset($offset, $returnedBySource, $nextOffset, $sourceRecords);

        return $records;
    }

    /**
     * Creates sorting specification from the given set of sources and ORDER BY expression
     *
     * @param CollectionDefinitionInterface $definition
     * @param array $orderBy ORDER BY expression
     *
     * @return array The sorting specification
     * @throws SugarApiExceptionError
     */
    protected function getSortSpec(CollectionDefinitionInterface $definition, $orderBy)
    {
        $sourceData = array();
        foreach ($definition->getSources() as $source) {
            $moduleName = $definition->getSourceModuleName($source);
            $bean = BeanFactory::newBean($moduleName);
            if ($definition->hasFieldMap($source)) {
                $fieldMap = $definition->getFieldMap($source);
            } else {
                $fieldMap = array();
            }
            $sourceData[$source] = array($bean, $fieldMap);
        }

        $spec = array();
        foreach ($orderBy as $alias => $direction) {
            $isNumeric = null;
            $map = array();
            foreach ($sourceData as $source => $data) {
                /** @var SugarBean $bean */
                list($bean, $fieldMap) = $data;

                if (isset($fieldMap[$alias])) {
                    $field = $fieldMap[$alias];
                } else {
                    $field = $alias;
                }

                $fieldDef = $bean->getFieldDefinition($field);
                if (!$fieldDef) {
                    // do not display alias since it may come from API arguments
                    throw new SugarApiExceptionError('Unable to load field definition');
                }

                $type = $bean->db->getFieldType($fieldDef);
                if ($type) {
                    $isFieldNumeric = $bean->db->isNumericType($type);
                } else {
                    // assume field is varchar in case type is not specified
                    $isFieldNumeric = false;
                }

                if (isset($fieldDef['sort_on'])) {
                    if ($isFieldNumeric && count($fieldDef['sort_on']) > 1) {
                        throw new SugarApiExceptionError(
                            'Cannot use "sort_on" more than one columns for numeric fields in collections'
                        );
                    }
                    $map[$source] = (array) $fieldDef['sort_on'];
                } else {
                    $map[$source] = array($field);
                }

                if ($isNumeric === null) {
                    $isNumeric = $isFieldNumeric;
                } elseif ($isNumeric != $isFieldNumeric) {
                    throw new SugarApiExceptionError(
                        sprintf('Alias %s points to both string and numeric fields', $field)
                    );
                }
            }

            $spec[] = array(
                'map' => $map,
                'is_numeric' => $isNumeric,
                'direction' => $direction,
            );
        }

        return $spec;
    }

    /**
     * Returns additional fields needed for client side sorting
     *
     * @param array $args API arguments
     * @param CollectionDefinitionInterface $definition
     * @param array $sortSpec Sorting specification
     *
     * @return array Map of source names to their additional fields
     */
    protected function getAdditionalSortFields(array $args, CollectionDefinitionInterface $definition, array $sortSpec)
    {
        $result = array();

        // make sure result contains entry for every source in order to make less checks in future
        foreach ($definition->getSources() as $source) {
            $result[$source] = array();
        }

        if (!empty($args['fields'])) {
            $fields = $this->normalizeFields($args['fields'], $displayParams);
            foreach ($sortSpec as $column) {
                foreach ($column['map'] as $source => $sortFields) {
                    $addedFields = array_diff($sortFields, $fields);
                    foreach ($addedFields as $addedField) {
                        $result[$source][$addedField] = true;
                    }
                }
            }
        }

        $result = array_map('array_keys', $result);

        return $result;
    }

    /**
     * Builds column comparison function
     *
     * @param array $map Map of source name to field name for the given alias
     * @param boolean $isNumeric Whether the column is numeric
     * @param boolean $direction Sorting direction
     *
     * @return callable
     */
    protected function getColumnComparator($map, $isNumeric, $direction)
    {
        $comparator = $isNumeric ? function ($a, $b) {
            return $a - $b;
        } : $this->collator;

        $getValue = function ($row, $fields) {
            // do not concat values in case there's only one field in order to preserve value type
            if (count($fields) == 1) {
                return $row[$fields[0]];
            } else {
                return implode(' ', array_map(function ($field) use ($row) {
                    return $row[$field];
                }, $fields));
            }
        };

        $factor = $direction ? 1 : -1;

        $sourceKey = static::$sourceKey;
        return function ($a, $b) use ($comparator, $map, $getValue, $factor, $sourceKey) {
            return $comparator(
                $getValue($a, $map[$a[$sourceKey]]),
                $getValue($b, $map[$b[$sourceKey]])
            ) * $factor;
        };
    }

    /**
     * Builds record comparison function according to specification
     *
     * @param array $spec
     *
     * @return callable
     */
    protected function getRecordComparator(array $spec)
    {
        $comparators = array();
        foreach ($spec as $alias => $properties) {
            $comparators[] = $this->getColumnComparator(
                $properties['map'],
                $properties['is_numeric'],
                $properties['direction']
            );
        }

        return function ($a, $b) use ($comparators) {
            foreach ($comparators as $comparator) {
                $result = $comparator($a, $b);
                if ($result != 0) {
                    return $result;
                }
            }

            return 0;
        };
    }

    /**
     * Builds function for sorting source data by first record in order to decide which source to take the record from
     *
     * @param array $spec
     *
     * @return callable
     */
    protected function getSourceDataComparator(array $spec)
    {
        $recordComparator = $this->getRecordComparator($spec);

        return function ($a, $b) use ($recordComparator) {
            $countA = count($a);
            $countB = count($b);
            if (!$countA || !$countB) {
                // non-empty array should go first
                return $countB - $countA;
            }

            return $recordComparator($a[0], $b[0]);
        };
    }

    /**
     * Generates the value of the next offset based on next offsets returned by underlying APIs, initial offset
     * and the set of records being returned
     *
     * @param array $offset Initial value of offset
     * @param array $returned Count of returned records by source
     * @param array $nextOffset Collection of offsets returned by Relate API
     * @param array $remainder Not returned records
     *
     * @return array New value of offset
     */
    protected function getNextOffset(array $offset, array $returned, array $nextOffset, array $remainder)
    {
        $truncated = array();

        foreach ($remainder as $source => $records) {
            $truncated[$source] = count($records) > 0;
        }

        foreach ($offset as $source => $value) {
            if (!isset($nextOffset[$source])) {
                $nextOffset[$source] = $value;
            } elseif ($truncated[$source]) {
                $nextOffset[$source] = $offset[$source] + $returned[$source];
            }
        }

        return $nextOffset;
    }

    /**
     * Maps field names
     *
     * @param array $fields
     * @param array $fieldMap
     *
     * @return array
     */
    protected function mapFields(array $fields, array $fieldMap)
    {
        return $this->mapArrayValues($fields, $fieldMap);
    }

    /**
     * Map filter definition using field map
     *
     * @param array $filter
     * @param array $fieldMap
     *
     * @return array
     */
    protected function mapFilter(array $filter, array $fieldMap)
    {
        foreach ($filter as $key => $value) {
            if (is_array($value)) {
                $filter[$key] = $this->mapFilter($filter[$key], $fieldMap);
            }
        }

        return $this->mapArrayKeys($filter, $fieldMap);
    }

    /**
     * Maps internal representation of ORDER BY definition
     *
     * @param array $orderBy
     * @param array $fieldMap
     *
     * @return array
     */
    protected function mapOrderBy(array $orderBy, array $fieldMap)
    {
        return $this->mapArrayKeys($orderBy, $fieldMap);
    }

    /**
     * Converts array by replacing aliased keys with real fields
     *
     * @param array $array
     * @param array $fieldMap
     *
     * @return array
     * @throws SugarApiExceptionInvalidParameter
     */
    protected function mapArrayKeys(array $array, array $fieldMap)
    {
        $mapped = array();
        foreach ($array as $alias => $value) {
            if (isset($fieldMap[$alias])) {
                $field = $fieldMap[$alias];
            } else {
                $field = $alias;
            }

            if (isset($mapped[$field])) {
                throw new SugarApiExceptionInvalidParameter(
                    'More than one alias pointing to the same field is used in expression'
                );
            }

            $mapped[$field] = $value;
        }

        return $mapped;
    }

    /**
     * Converts array by replacing aliased values with real fields
     *
     * @param array $array
     * @param array $fieldMap
     *
     * @return array
     */
    protected function mapArrayValues(array $array, array $fieldMap)
    {
        return array_map(function ($value) use ($fieldMap) {
            if (isset($fieldMap[$value])) {
                return $fieldMap[$value];
            }
            return  $value;
        }, $array);
    }

    /**
     * Formats ORDER BY from internal representation
     *
     * @param array $orderBy
     *
     * @return string
     */
    protected function formatOrderBy(array $orderBy)
    {
        $formatted = array();
        foreach ($orderBy as $field => $direction) {
            $column = $field;
            if (!$direction) {
                $column .= ':desc';
            }
            $formatted[] = $column;
        }

        return implode(',', $formatted);
    }

    /**
     * Returns default ORDER BY in internal representation
     *
     * @return array
     */
    protected function getDefaultOrderBy()
    {
        return array(
            'date_modified' => false,
        );
    }

    /**
     * Returns default records limit for the given API
     *
     * @return int
     */
    abstract protected function getDefaultLimit();

    /**
     * Clean up the data from unwanted fields that were not requested. For the purpose of sorting
     * we may have requested additional fields from the database. However these need not be
     * displayed to the user.
     *
     * @param array $records Resulting set of records
     * @param array $sortFields Additionally requested sort fields
     * @return array Modified Data Array is returned back
     */
    protected function cleanData(array $records, array $sortFields)
    {
        $fieldsToRemove = array_map('array_flip', $sortFields);
        $sourceKey = static::$sourceKey;
        $records = array_map(function ($record) use ($fieldsToRemove, $sourceKey) {
            return array_diff_key($record, $fieldsToRemove[$record[$sourceKey]]);
        }, $records);

        return $records;
    }
}
