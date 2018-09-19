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


class SugarFieldBool extends SugarFieldBase {
	/**
	 *
	 * @return The html for a drop down if the search field is not 'my_items_only' or a dropdown for all other fields.
	 *			This strange behavior arises from the special needs of PM. They want the my items to be checkboxes and all other boolean fields to be dropdowns.
	 * @author Navjeet Singh
	 * @param $parentFieldArray -
	 **/
	function getSearchViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
		$this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
		//If there was a type override to specifically render it as a boolean, show the EditView checkbox
		if( preg_match("/(favorites|current_user|open)_only.*/", $vardef['name']))
		{
			return $this->fetch($this->findTemplate('EditView'));
		} else {
			return $this->fetch($this->findTemplate('SearchView'));
		}
	}

    /**
     * @see SugarFieldBase::importSanitize()
     */
    public function importSanitize(
        $value,
        $vardef,
        $focus,
        ImportFieldSanitize $settings
        )
    {
        $bool_values = array(0=>'0',1=>'no',2=>'off',3=>'n',4=>'yes',5=>'y',6=>'on',7=>'1');
        $bool_search = array_search($value,$bool_values);
        if ( $bool_search === false ) {
            return false;
        }
        else {
            //Convert all the values to a real bool.
            $value = (int) ( $bool_search > 3 );
        }
        if ( isset($vardef['dbType']) && $vardef['dbType'] == 'varchar' )
            $value = ( $value ? 'on' : 'off' );

        return $value;
    }

    public function getEmailTemplateValue($inputField, $vardef, $context = null){
        global $app_list_strings;
        $inputField = $this->normalizeBoolean($inputField);
        // This does not return a smarty section, instead it returns a direct value
        if ( $inputField == 'bool_true' || $inputField === true ) { // Note: true must be absolute true
            return $app_list_strings['checkbox_dom']['1'];
        } else if ( $inputField == 'bool_false' || $inputField === false){ // Note: false must be absolute false
            return $app_list_strings['checkbox_dom']['2'];
        } else { // otherwise we return blank display
            return '';
        }
    }

    public function unformatField($formattedField, $vardef){
        if ( empty($formattedField) ) {
            $unformattedField = false;
            return $unformattedField;
        }
        if ( $formattedField === '0' || $formattedField === 'off' || $formattedField === 'false' || $formattedField === 'no' ) {
            $unformattedField = false;
        } else {
            $unformattedField = true;
        }

        return $unformattedField;
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

        if (isset($bean->$fieldName)) {
            $data[$fieldName] = $this->normalizeBoolean($bean->$fieldName);
        } else {
            $data[$fieldName] = null;
        }
    }

    /**
     * Normalizes the default value by making sure it is a real boolean value.
     *
     * @param mixed $value The value to normalize.
     * @return bool Normalized value.
     * @override
     * @see SugarFieldBase::normalizeBoolean
     */
    public function normalizeDefaultValue($value)
    {
        return $this->normalizeBoolean($value);
    }
}
