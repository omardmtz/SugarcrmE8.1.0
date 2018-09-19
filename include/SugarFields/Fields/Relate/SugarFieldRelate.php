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


class SugarFieldRelate extends SugarFieldBase {

    function getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
        $nolink = array('Users', 'Teams');
        if(in_array($vardef['module'], $nolink)){
            $this->ss->assign('nolink', true);
        }else{
            $this->ss->assign('nolink', false);
        }
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        return $this->fetch($this->findTemplate('DetailView'));
    }

    /**
     * @see SugarFieldBase::getEditViewSmarty()
     */
    public function getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex)
    {
        if(!empty($vardef['function']['returns']) && $vardef['function']['returns'] == 'html'){
            return parent::getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
        }

        $call_back_function = 'set_return';
        if(isset($displayParams['call_back_function'])) {
            $call_back_function = $displayParams['call_back_function'];
        }
        $form_name = 'EditView';
        if(isset($displayParams['formName'])) {
            $form_name = $displayParams['formName'];
        }

        if (isset($displayParams['idName']))
        {
            $rpos = strrpos($displayParams['idName'], $vardef['name']);
            $displayParams['idNameHidden'] = substr($displayParams['idName'], 0, $rpos);
        }
        //Special Case for accounts; use the displayParams array and retrieve
        //the key and copy indexes.  'key' is the suffix of the field we are searching
        //the Account's address with.  'copy' is the suffix we are copying the addresses
        //form fields into.
        if(isset($vardef['module']) && preg_match('/Accounts/si',$vardef['module'])
           && isset($displayParams['key']) && isset($displayParams['copy'])) {

            if(isset($displayParams['key']) && is_array($displayParams['key'])) {
              $database_key = $displayParams['key'];
            } else {
              $database_key[] = $displayParams['key'];
            }

            if(isset($displayParams['copy']) && is_array($displayParams['copy'])) {
                $form = $displayParams['copy'];
            } else {
                $form[] = $displayParams['copy'];
            }

            if(count($database_key) != count($form)) {
              global $app_list_strings;
              $this->ss->trigger_error($app_list_strings['ERR_SMARTY_UNEQUAL_RELATED_FIELD_PARAMETERS']);
            } //if

            $copy_phone = isset($displayParams['copyPhone']) ? $displayParams['copyPhone'] : true;

            $field_to_name = array();
            $field_to_name['id'] = $vardef['id_name'];
            $field_to_name['name'] = $vardef['name'];
            $address_fields = isset($displayParams['field_to_name_array']) ? $displayParams['field_to_name_array'] : array('_address_street', '_address_city', '_address_state', '_address_postalcode', '_address_country');
            $count = 0;
            foreach($form as $f) {
                foreach($address_fields as $afield) {
                    $field_to_name[$database_key[$count] . $afield] = $f . $afield;
                }
                $count++;
            }

            $popup_request_data = array(
                'call_back_function' => $call_back_function,
                'form_name' => $form_name,
                'field_to_name_array' => $field_to_name,
            );

            if($copy_phone) {
              $popup_request_data['field_to_name_array']['phone_office'] = 'phone_work';
            }
        } elseif(isset($displayParams['field_to_name_array'])) {
            $popup_request_data = array(
                'call_back_function' => $call_back_function,
                'form_name' => $form_name,
                'field_to_name_array' => $displayParams['field_to_name_array'],
            );
        } else {
            $popup_request_data = array(
                'call_back_function' => $call_back_function,
                'form_name' => $form_name,
                'field_to_name_array' => array(
                          //bug 43770: Assigned to value could not be saved during lead conversion
                          'id' => (empty($displayParams['idNameHidden']) ? $vardef['id_name'] : ($displayParams['idNameHidden'] . $vardef['id_name'])) ,
                          ((empty($vardef['rname'])) ? 'name' : $vardef['rname']) => (empty($displayParams['idName']) ? $vardef['name'] : $displayParams['idName']),
                    ),
                );
        }
        $json = getJSONobj();
        $displayParams['popupData'] = '{literal}'.$json->encode($popup_request_data). '{/literal}';
        if(!isset($displayParams['readOnly'])) {
           $displayParams['readOnly'] = '';
        } else {
           $displayParams['readOnly'] = $displayParams['readOnly'] == false ? '' : 'READONLY';
        }

        $keys = $this->getAccessKey($vardef,'RELATE',$vardef['module']);
        $displayParams['accessKeySelect'] = $keys['accessKeySelect'];
        $displayParams['accessKeySelectLabel'] = $keys['accessKeySelectLabel'];
        $displayParams['accessKeySelectTitle'] = $keys['accessKeySelectTitle'];
        $displayParams['accessKeyClear'] = $keys['accessKeyClear'];
        $displayParams['accessKeyClearLabel'] = $keys['accessKeyClearLabel'];
        $displayParams['accessKeyClearTitle'] = $keys['accessKeyClearTitle'];

        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        return $this->fetch($this->findTemplate('EditView'));
    }

    function getSearchViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
        $call_back_function = 'set_return';
        if(isset($displayParams['call_back_function'])) {
            $call_back_function = $displayParams['call_back_function'];
        }
        $form_name = 'search_form';
        if(isset($displayParams['formName'])) {
            $form_name = $displayParams['formName'];
        }
        if (!empty($vardef['rname']) && $vardef['rname'] == 'full_name') {
        	$displayParams['useIdSearch'] = true;
        }

        //Special Case for accounts; use the displayParams array and retrieve
        //the key and copy indexes.  'key' is the suffix of the field we are searching
        //the Account's address with.  'copy' is the suffix we are copying the addresses
        //form fields into.
        if(isset($vardef['module']) && preg_match('/Accounts/si',$vardef['module'])
           && isset($displayParams['key']) && isset($displayParams['copy'])) {

            if(isset($displayParams['key']) && is_array($displayParams['key'])) {
              $database_key = $displayParams['key'];
            } else {
              $database_key[] = $displayParams['key'];
            }

            if(isset($displayParams['copy']) && is_array($displayParams['copy'])) {
                $form = $displayParams['copy'];
            } else {
                $form[] = $displayParams['copy'];
            }

            if(count($database_key) != count($form)) {
              global $app_list_strings;
              $this->ss->trigger_error($app_list_strings['ERR_SMARTY_UNEQUAL_RELATED_FIELD_PARAMETERS']);
            } //if

            $copy_phone = isset($displayParams['copyPhone']) ? $displayParams['copyPhone'] : true;

            $field_to_name = array();
            $field_to_name['id'] = $vardef['id_name'];
            $field_to_name['name'] = $vardef['name'];
            $address_fields = array('_address_street', '_address_city', '_address_state', '_address_postalcode', '_address_country');
            $count = 0;
            foreach($form as $f) {
                foreach($address_fields as $afield) {
                    $field_to_name[$database_key[$count] . $afield] = $f . $afield;
                }
                $count++;
            }

            $popup_request_data = array(
                'call_back_function' => $call_back_function,
                'form_name' => $form_name,
                'field_to_name_array' => $field_to_name,
            );

            if($copy_phone) {
              $popup_request_data['field_to_name_array']['phone_office'] = 'phone_work';
            }
        } elseif(isset($displayParams['field_to_name_array'])) {
            $popup_request_data = array(
                'call_back_function' => $call_back_function,
                'form_name' => $form_name,
                'field_to_name_array' => $displayParams['field_to_name_array'],
            );
        } else {
            $popup_request_data = array(
                'call_back_function' => $call_back_function,
                'form_name' => $form_name,
                'field_to_name_array' => array(
                          'id' => $vardef['id_name'],
                          ((empty($vardef['rname'])) ? 'name' : $vardef['rname']) => $vardef['name'],
                    ),
                );
        }
        $json = getJSONobj();
        $displayParams['popupData'] = '{literal}'.$json->encode($popup_request_data). '{/literal}';
        if(!isset($displayParams['readOnly'])) {
           $displayParams['readOnly'] = '';
        } else {
           $displayParams['readOnly'] = $displayParams['readOnly'] == false ? '' : 'READONLY';
        }
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        return $this->fetch($this->findTemplate('SearchView'));
    }

    /** {@inheritDoc} */
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

        /*
         * If we have a related field, use its formatter to format it
         */
        $rbean = null;
        if(!empty($properties['link']) && !empty($bean->related_beans[$properties['link']])) {
            $rbean = $bean->related_beans[$properties['link']];
        } else if (!empty($bean->related_beans[$fieldName])) {
            $rbean = $bean->related_beans[$fieldName];
        }
        if (!empty($rbean)) {
            if(empty($rbean->field_defs[$properties['rname']])) {
                $data[$fieldName] = '';
                $this->formatRelateField($bean, $data, $fieldName, $properties, $service);
                return;
            }
            $rdefs = $rbean->field_defs[$properties['rname']];
            if(!empty($rdefs) && !empty($rdefs['type'])) {
                $sfh = new SugarFieldHandler();
                $field = $sfh->getSugarField($rdefs['type']);
                $rdata = array();
                $field->apiFormatField($rdata, $rbean, $args, $properties['rname'], $rdefs, $fieldList, $service);
                $data[$fieldName] = $rdata[$properties['rname']];
                if(!empty($data[$fieldName])) {
                    $this->formatRelateField($bean, $data, $fieldName, $properties, $service);
                    return;
                }
            }
        }
        if(empty($bean->$fieldName)) {
            $data[$fieldName] = '';
        } else {
            $data[$fieldName] = $this->formatField($bean->$fieldName, $properties);
        }

        $this->formatRelateField($bean, $data, $fieldName, $properties, $service);
    }

    /**
     * Formats the field representing related record record
     *
     * @param SugarBean $bean Primary bean
     * @param array $data Resulting representation
     * @param string $fieldName Relate field name
     * @param array $properties Relate field definition
     * @param ServiceBase $service The calling API service
     */
    protected function formatRelateField(SugarBean $bean, array &$data, $fieldName, $properties, ServiceBase $service)
    {
        if (!empty($properties['link'])) {
            $link = $properties['link'];
            $rName = $properties['rname'];

            // replicate the relate field value in the new format
            $data[$link][$rName] = $data[$fieldName];

            if (isset($properties['id_name'])) {
                $idName = $properties['id_name'];
                $data[$link]['id'] = $bean->$idName;
            }

            // check if the ACL metadata has been already populated by another relate field
            if (isset($properties['module'])) {
                // trying to reconstruct relate bean from the fetched data
                $relate = BeanFactory::newBean($properties['module']);
                $row = array('id' => $bean->{$properties['id_name']});

                $ownerField = $relate->getOwnerField();
                $ownerAlias = $fieldName . '_owner';
                if ($ownerField && isset($bean->$ownerAlias)) {
                    $row[$ownerField] = $bean->$ownerAlias;
                }

                // make sure fetched row is populated as SugarACLStatic::beanACL() may use it
                $relate->fetched_row = $relate->populateFromRow($row);

                $mm = MetaDataManager::getManager($service->platform);
                $data[$link]['_acl'] = $mm->getAclForModule($relate->module_dir, $service->user, $relate);

                $linkErasedFields = $link . '_erased_fields';

                if (isset($bean->$linkErasedFields)) {
                    $data[$link]['_erased_fields'] = $bean->$linkErasedFields;
                }
            }
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
        if ( !isset($vardef['module']) )
            return false;
        $newbean = BeanFactory::newBean($vardef['module']);

        // Bug 32869 - Assumed related field name is 'name' if it is not specified
        if ( !isset($vardef['rname']) )
            $vardef['rname'] = 'name';

        // Bug 27046 - Validate field against type as it is in the related field
        $rvardef = $newbean->getFieldDefinition($vardef['rname']);
        if ( isset($rvardef['type'])
                && method_exists($this,$rvardef['type']) ) {
            $fieldtype = $rvardef['type'];
            $returnValue = $settings->$fieldtype($value,$rvardef);
            if ( !$returnValue )
                return false;
            else
                $value = $returnValue;
        }

        if ( isset($vardef['id_name']) ) {
            $idField = $vardef['id_name'];

            // Bug 24075 - clear out id field value if it is invalid
            if ( isset($focus->$idField) ) {
                $checkfocus = BeanFactory::newBean($vardef['module']);
                if ( $checkfocus && is_null($checkfocus->retrieve($focus->$idField)) )
                    $focus->$idField = '';
            }

            // fixing bug #47722: Imports to Custom Relate Fields Do Not Work
            if (!isset($vardef['table']))
            {
                // Set target module table as the default table name
                $vardef['table'] = $newbean->table_name;
            }
            // be sure that the id isn't already set for this row
            if ( empty($focus->$idField)
                    && $idField != $vardef['name']
                    && !empty($vardef['rname'])
                    && !empty($vardef['table'])) {
                // Bug 27562 - Check db_concat_fields first to see if the field name is a concatenation.
                $relatedFieldDef = $newbean->getFieldDefinition($vardef['rname']);
                if ( isset($relatedFieldDef['db_concat_fields'])
                        && is_array($relatedFieldDef['db_concat_fields']) )
                    $fieldname = $focus->db->concat($vardef['table'],$relatedFieldDef['db_concat_fields']);
                else
                    $fieldname = $vardef['rname'];
                // lookup first record that matches in linked table
                $query = "SELECT id
                            FROM {$vardef['table']}
                            WHERE {$fieldname} = '" . $focus->db->quote($value) . "'
                                AND deleted != 1";

                $relaterow = $focus->db->fetchOneOffset($query, 0, true, "Want only a single row");
                if ($relaterow)
                    $focus->$idField = $relaterow['id'];
                elseif ( !$settings->addRelatedBean
                        || ( $newbean->bean_implements('ACL') && !$newbean->ACLAccess('save') )
                        || ( in_array($newbean->module_dir,array('Teams','Users')) )
                        )
                    return false;
                else {
                    // add this as a new record in that bean, then relate
                    if ( isset($relatedFieldDef['db_concat_fields'])
                            && is_array($relatedFieldDef['db_concat_fields']) ) {
                        assignConcatenatedValue($newbean, $relatedFieldDef, $value);
                    }
                    else {
                        $newbean->{$vardef['rname']} = $value;
                    }
                    if ( !isset($focus->assigned_user_id) || $focus->assigned_user_id == '' )
                        $newbean->assigned_user_id = $GLOBALS['current_user']->id;
                    else
                        $newbean->assigned_user_id = $focus->assigned_user_id;
                    if ( !isset($focus->modified_user_id) || $focus->modified_user_id == '' )
                        $newbean->modified_user_id = $GLOBALS['current_user']->id;
                    else
                        $newbean->modified_user_id = $focus->modified_user_id;

                    // populate fields from the parent bean to the child bean
                    $focus->populateRelatedBean($newbean);

                    $newbean->save(false);
                    $focus->$idField = $newbean->id;
                    $settings->createdBeans[] = ImportFile::writeRowToLastImport(
                            $focus->module_dir,$newbean->object_name,$newbean->id);
                }

            }
        }

        return $value;
    }

    /**
     * For Relate fields we should not be sending the len vardef back
     * @param array $vardef
     * @return array of $vardef
     */
    public function getNormalizedDefs($vardef, $defs) {
        unset($vardef['len']);
        return parent::getNormalizedDefs($vardef, $defs);
    }

}
