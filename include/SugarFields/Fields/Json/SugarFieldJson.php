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
 * SugarFieldJson.php
 * 
 * A sugar field that json encodes the content of the field.
 *
 */


class SugarFieldJson extends SugarFieldBase {
	/**
     * This function handles turning the API's version of a teamset into what we actually store
     * @param SugarBean $bean - the bean performing the save
     * @param array $params - an array of paramester relevant to the save, which will be an array passed up to the API
     * @param string $fieldName - The name of the field to save (the vardef name, not the form element name)
     * @param array $properties - Any properties for this field
     */
    public function apiSave(SugarBean $bean, array $params, $fieldName, $properties) {
        // json encode the content
    	$bean->$fieldName = json_encode($params[$fieldName]);
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
        $this->ensureApiFormatFieldArguments($fieldList, $service);

        if(isset($bean->$fieldName)) {
            $data[$fieldName] = json_decode($bean->$fieldName, true);
        }
    }

    
}
?>
