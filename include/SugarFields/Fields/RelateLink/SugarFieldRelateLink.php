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
 * SugarField implementation for link fields
 */
class SugarFieldRelateLink extends SugarFieldBase
{
    /**
     * @var RelateApi
     */
    protected $relateApi;

    /**
     * {@inheritDoc}
     *
     * Does nothing since link field data is fetched by internal API call
     */
    public function addFieldToQuery($field, array &$fields)
    {
    }

    /**
     * {@inheritDoc}
     *
     * Does nothing since link field data can't be processed or saved here.
     */
    public function apiSave(SugarBean $bean, array $params, $field, $properties)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function apiFormatField(
        array &$data,
        SugarBean $bean,
        array $args,
        $fieldName,
        $properties,
        array $fieldList = null,
        ServiceBase $service = null
    ) {
        if (!is_array($fieldList)) {
            throw new SugarApiExceptionError('$fieldList argument of apiFormatField() is missing');
        }

        // don't render link fields unless it's explicitly requested
        if (!in_array($fieldName, $fieldList)) {
            return;
        }

        if (!$service) {
            throw new SugarApiExceptionError('$service argument of apiFormatField() is missing');
        }

        if (isset($args['display_params'][$fieldName])) {
            $displayParams = $args['display_params'][$fieldName];
        } else {
            $displayParams = array();
        }

        $data[$fieldName] = $this->getBeanCollection($bean, $properties, $displayParams, $service);
    }

    /**
     * {@inheritDoc}
     *
     * Applies the callback only to the given field and does not iterate over "fields" since they mean collection fields
     * to be retrieved, not nested fields as in base field.
     */
    public function iterateViewField(
        ViewIterator $iterator,
        array $field,
        /* callable */ $callback
    ) {
        $callback($field);
    }

    /**
     * Return the data that should be returned for link or collection field
     *
     * @param SugarBean $bean Source bean
     * @param array $field Link or collection field definition
     * @param array $displayParams Field display parameters
     * @param ServiceBase $service
     *
     * @return array
     * @throws SugarApiExceptionError
     */
    protected function getBeanCollection(SugarBean $bean, array $field, array $displayParams, ServiceBase $service)
    {
        $args = array_merge(array(
            // make sure "fields" argument is always passed to the API
            // since otherwise it will return all fields by default
            'fields' => array('id', 'date_modified'),
        ), $displayParams, array(
            'module' => $bean->module_name,
            'record' => $bean->id,
            'link_name' => $field['name'],
        ));

        $response = $this->getRelateApi()->filterRelated($service, $args);

        return $response;
    }

    /**
     * Lazily loads Relate API
     *
     * @return RelateApi
     */
    protected function getRelateApi()
    {
        if (!$this->relateApi) {
            $this->relateApi = new RelateApi();
        }

        return $this->relateApi;
    }
}
