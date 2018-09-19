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


class SugarFieldFullname extends SugarFieldBase
{
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
        global $locale;
        $this->ensureApiFormatFieldArguments($fieldList, $service);

        $data[$fieldName] = $locale->formatName($bean);
    }

    function getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex)
	{
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        return $this->fetch($this->findTemplate('DetailView'));
    }

    public function getNormalizedDefs($vardef, $defs)
    {
         $vardef = parent::getNormalizedDefs($vardef, $defs);
         if(!empty($defs['name_format_map'])) {
             $vardef['fields'] = array_unique(array_values($defs['name_format_map']));
         }
         return $vardef;
    }

    /**
     * {@inheritDoc}
     *
     * Instead of applying the callback to the field itself, applies it to fields required for localized name formatting
     */
    public function iterateViewField(
        ViewIterator $iterator,
        array $field,
        /* callable */ $callback
    ) {
        global $locale;

        if (!$this->module) {
            throw new Exception('Field module name is not set');
        }

        $nameFormatFields = $locale->getNameFormatFields($this->module);
        $iterator->apply($nameFormatFields, $callback);
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
        // TODO: figure out how we can parse arbitrary imports
        if(empty($focus->name_format_map)) {
            $fn = 'first_name';
            $ln = 'last_name';
        } else {
            $fn = $focus->name_format_map['f'];
            $ln = $focus->name_format_map['l'];
        }
        if ( property_exists($focus, $fn) && property_exists($focus, $ln) ) {
            $name_arr = preg_split('/\s+/',$value);
            if ( count($name_arr) == 1) {
                $focus->$ln = $value;
            }
            else {
                // figure out what comes first, the last name or first name
                if ( strpos($settings->default_locale_name_format,'l') > strpos($settings->default_locale_name_format,'f') ) {
                    $focus->$fn = array_shift($name_arr);
                    $focus->$ln = join(' ',$name_arr);
                }
                else {
                    $focus->$ln = array_shift($name_arr);
                    $focus->$fn = join(' ',$name_arr);
                }
            }
        }
    }
}
