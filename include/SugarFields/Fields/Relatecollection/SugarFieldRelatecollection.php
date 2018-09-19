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
 *
 * Related collection field
 *
 * This field type can be used on record views to show related records for
 * the given bean context. Essentially a related collection field acts the
 * same as a subpanel, except that the records are shown directly in the
 * record view itself.
 *
 * There are already certain implementation which look alike, for example
 * email addresses and team sets. This sugar field type uses a generic
 * approach from which can be extended.
 *
 * Available vardef settings:
 *
 * array(
 * 	'type' => 'relatedcollection'
 *   'link' => pointer to a vardef link field defining the relationship
 *   	Required parameter without a default
 * 	'collection_fields' => array of fields to return from related object
 * 		Optional, defaults to id and name field
 * 	'collection_limit' => maximum amount of related records to return
 * 		Optional, defaults to unlimited
 *  'collection_create' =>  ability to create new objects while linking
 *  	Optional, defaults to false
 * )
 *
 */
class SugarFieldRelatecollection extends SugarFieldBase
{
    /**
     *
     * Base fields for collection
     * @var array
     */
    protected static $baseFields = array(
        'id',
        'name',
    );

    /**
     *
     * Base collection limit
     * @var integer
     */
    protected static $baseLimit = -1;

    /**
     *
     * {@inheritdoc}
     */
    public function apiFormatField(array &$data, SugarBean $bean, array $args, $fieldName, $properties, array $fieldList = null, ServiceBase $service = null)
    {
        list ($relName, $fields, $limit) = $this->parseProperties($properties);
        $records = $this->getLinkedRecords($bean, $relName, $fields, $limit);
        $data[$fieldName] = array_values($records);
    }

    /**
     *
     * {@inheritdoc}
     */
    public function apiSave(SugarBean $bean, array $params, $field, $properties)
    {
        if (empty($params[$field]) || !is_array($params[$field])) {
            return;
        }

        // retrieve current linked objects
        list ($relName, $fields, $limit, $create) = $this->parseProperties($properties);
        $currentList = $this->getLinkedRecords($bean, $relName, $fields, $limit);

        /*
         * We do not require the client to send back the full list of related
         * items. Only explicit additions/removals are required. Already
         * present links are maintained if not explicitly defined during save.
         */
        foreach ($params[$field] as $record) {

            // validate required fields
            if (!$this->validateRequiredFields($record)) {
                continue;
            }

            // handle (new) related records
            if (empty($record['removed'])) {

                // create new bean first if supported when no id is provided
                if ($record['id'] === false && $create) {
                    $new = $this->createNewBeanBeforeLink($bean, $relName, $record);
                    if (!empty($new->id)) {
                        $bean->$relName->add($new);
                    }
                }

                // add new link if it doesn't exist yet
                if ($record['id'] && !isset($currentList[$record['id']])) {
                    $bean->$relName->add($record['id']);
                }

            // handle related records flagged for removal
            } elseif (!empty($record['removed']) && !empty($record['id'])) {

                // just remove the link, Link2 will take care of the checks
                $bean->$relName->delete($bean->id, $record['id']);
            }
        }
    }

    /**
     *
     * Create a new bean before linking it to the parent
     * @param SugarBean $parent
     * @param string $relName Relationship name
     * @param array $record Data to use to create related bean
     * @return SugarBean
     */
    protected function createNewBeanBeforeLink(SugarBean $parent, $relName, array $record)
    {
        $relSeed = $this->getRelatedSeedBean($parent, $relName);
        $new = BeanFactory::newBean($relSeed->module_name);
        $new->fromArray($record);
        $new->save();
        return $new;
    }

    /**
     *
     * Check if required fields are present for given record (base fields).
     * @param array $record
     * @return boolean
     */
    protected function validateRequiredFields(array $record)
    {
        foreach (self::$baseFields as $field) {
            if (!isset($record[$field])) {
                return false;
            }
        }
        return true;
    }

    /**
     *
     * Return linked object data for given bean/relationship.
     * @param SugarBean $parent
     * @param string    $relName
     * @param array     $fields
     * @param integer   $limit
     * @param string|array $orderBy field name or array of field name and direction to sort by
     * @return array
     */
    protected function getLinkedRecords(SugarBean $parent, $relName, array $fields, $limit, $orderBy = '')
    {
        if (! $relSeed = $this->getRelatedSeedBean($parent, $relName)) {
            return array();
        }

        // base query object for related module
        $sq = $this->getSugarQuery();
        $sq->select($fields);
        $sq->from($relSeed);

        if ($limit > 0) {
            $sq->limit($limit);
        }

        if (is_array($orderBy)) {
            $sq->orderBy($orderBy[0], $orderBy[1]);
        } elseif (is_string($orderBy)) {
            $sq->orderBy($orderBy);
        }

        // join against parent module
        $sq->joinSubpanel($parent, $relName);

        $result = array();
        foreach ($sq->execute() as $record) {
            $result[$record['id']] = $record;
        }
        return $result;
    }

    /**
     *
     * Parse field properties, return defaults if not set.
     * @param array $properties
     * @return array
     */
    protected function parseProperties(array $properties)
    {
        // link is required
        $link = empty($properties['link']) ? false : $properties['link'];

        // field list
        $fields = self::$baseFields;
        if (!empty($properties['collection_fields']) && is_array($properties['collection_fields'])) {
            $fields = array_unique(array_merge(self::$baseFields, $properties['collection_fields']));
        }

        // maximum related records
        $limit = (int) empty($properties['collection_limit']) ? self::$baseLimit : $properties['collection_limit'];

        // create linked object (disabled by default)
        $create = !empty($properties['collection_create']) ?: false;

        return array($link, $fields, $limit, $create);
    }

    /**
     *
     * Return a SugarBean for the other end of a given bean/relationship.
     * @param SugarBean $bean
     * @param string     $rel Link name
     * @return mixed (SugarBean|null)
     */
    protected function getRelatedSeedBean(SugarBean $bean, $rel)
    {
        if ($bean->load_relationship($rel)) {
            return BeanFactory::newBean($bean->$rel->getRelatedModuleName());
        }
    }

    /**
     *
     * Return a new SugarQuery object.
     * @return SugarQuery
     */
    protected function getSugarQuery()
    {
        return new SugarQuery();
    }

    /**
     * Used during mass update process
     * @param SugarBean $bean
     * @param array     $params
     * @param string    $fieldName
     * @param array     $properties
     * @return void
     */
    public function apiMassUpdate(SugarBean $bean, array $params, $fieldName, $properties)
    {
        return $this->apiSave($bean, $params, $fieldName, $properties);
    }

    /**
     * {@inheritDoc}
     *
     * We don't need to add relate collections to listview queries since we will grab those
     * records later on
     */
    public function addFieldToQuery($field, array &$fields)
    {
    }
}
