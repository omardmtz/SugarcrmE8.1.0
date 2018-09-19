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


class SugarFieldParent extends SugarFieldRelate {

    public $needsSecondaryQuery = true;

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

    function getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
    	$form_name = 'EditView';
    	if(isset($displayParams['formName'])) {
    		$form_name = $displayParams['formName'];
    	}

    	$popup_request_data = array(
			'call_back_function' => 'set_return',
			'form_name' => $form_name,
			'field_to_name_array' => array(
											'id' => $vardef['id_name'],
											'name' => $vardef['name'],
									 ),
		);


		global $app_list_strings;
		$parent_types = $app_list_strings['record_type_display'];

		$disabled_parent_types = SugarACL::disabledModuleList($parent_types);
		foreach($disabled_parent_types as $disabled_parent_type){
			if($disabled_parent_type != $focus->parent_type){
				unset($parent_types[$disabled_parent_type]);
			}
		}
		asort($parent_types);
		$json = getJSONobj();
		$displayParams['popupData'] = '{literal}'.$json->encode($popup_request_data).'{/literal}';
    	$displayParams['disabled_parent_types'] = '<script>var disabledModules='. $json->encode($disabled_parent_types).';</script>';
    	$this->ss->assign('quickSearchCode', $this->createQuickSearchCode($form_name, $vardef));

        $keys = $this->getAccessKey($vardef,'PARENT',$vardef['module']);
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
		$form_name = 'search_form';

    	if(isset($displayParams['formName'])) {
    		$form_name = $displayParams['formName'];
    	}

    	if(preg_match('/(_basic|_advanced)$/', $vardef['name'], $match))
    	{
    	   $vardef['type_name'] = $vardef['type_name'] . $match[1];
    	}

    	$this->ss->assign('form_name', $form_name);

    	$popup_request_data = array(
			'call_back_function' => 'set_return',
			'form_name' => $form_name,
			'field_to_name_array' => array(
											'id' => $vardef['id_name'],
											'name' => $vardef['name'],
									 ),
		);


		global $app_list_strings;
		$parent_types = $app_list_strings['record_type_display'];
		$disabled_parent_types = SugarACL::disabledModuleList($parent_types);
		foreach($disabled_parent_types as $disabled_parent_type){
			if($disabled_parent_type != $focus->parent_type){
				unset($parent_types[$disabled_parent_type]);
			}
		}

		$json = getJSONobj();
		$displayParams['popupData'] = '{literal}'.$json->encode($popup_request_data).'{/literal}';
    	$displayParams['disabled_parent_types'] = '<script>var disabledModules='. $json->encode($disabled_parent_types).';</script>';
    	$this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        return $this->fetch($this->findTemplate('SearchView'));
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
        global $beanList;

        if ( isset($vardef['type_name']) ) {
            $moduleName = $vardef['type_name'];
            if ( isset($focus->$moduleName) && isset($beanList[$focus->$moduleName]) ) {
                $vardef['module'] = $focus->$moduleName;
                $vardef['rname'] = 'name';
                $relatedBean = BeanFactory::newBean($focus->$moduleName);
                $vardef['table'] = $relatedBean->table_name;
                return parent::importSanitize($value,$vardef,$focus,$settings);
            }
        }

        return false;
    }

    function createQuickSearchCode($formName = 'EditView', $vardef){

        $json = getJSONobj();

        $dynamicParentTypePlaceHolder = "**@**"; //Placeholder for dynamic parent so smarty tags are not escaped in json encoding.
        $dynamicParentType = '{/literal}{if !empty($fields.parent_type.value)}{$fields.parent_type.value}{else}Accounts{/if}{literal}';

        //Get the parent sqs definition
        $qsd = QuickSearchDefaults::getQuickSearchDefaults();
        $qsd->setFormName($formName);
        $sqsFieldArray = $qsd->getQSParent($dynamicParentTypePlaceHolder);
        $qsFieldName = $formName . "_" . $vardef['name'];

        //Build the javascript
        $quicksearch_js = '<script language="javascript">';
        $quicksearch_js.= "if(typeof sqs_objects == 'undefined'){var sqs_objects = new Array;}";
        $quicksearch_js .= "sqs_objects['$qsFieldName']=" . str_replace($dynamicParentTypePlaceHolder, $dynamicParentType,$json->encode($sqsFieldArray)) .';';

        return $quicksearch_js .= '</script>';
    }

    /**
     * getSearchInput
     *
     * This function allows the SugarFields to handle returning the search input value given arguments (typically from $_REQUEST/$_POST)
     * and a search string.
     *
     * @param $key String value of key to search for
     * @param $args Mixed value containing haystack to search for value in
     * @return $value Mixed value that the SugarField should return
     */
    function getSearchInput($key='', $args=array())
    {
    	//Nothing specified return empty string
    	if(empty($key) || empty($args))
    	{
    		return '';
    	}

    	//We are probably getting "parent_type" as the $key value, but this is likely not set since there are
    	//advanced and basic tabs.  This next section attempts to resolve this issue.
    	$isBasicSearch = isset($args['searchFormTab']) && $args['searchFormTab'] == 'basic_search' ? true : false;
    	$searchKey = $isBasicSearch ? "{$key}_basic" : "{$key}_advanced";

    	if(isset($args[$searchKey]))
    	{
    	   return $args[$searchKey];
    	}

    	return isset($args[$key]) ? $args[$key] : '';
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

        // API will fill in the name
        if(empty($bean->$fieldName)) {
        	$data[$fieldName] = '';
        } else {
        	$data[$fieldName] = $this->formatField($bean->$fieldName, $properties);
        }

        $this->formatParentField($bean, $data, $fieldName, $service);
    }

    /**
     * Formats the field representing the parent record
     *
     * @param SugarBean $bean Primary bean
     * @param array $data Resulting representation
     * @param string $fieldName Parent field name
     * @param ServiceBase $service The calling API service
     */
    protected function formatParentField(SugarBean $bean, array &$data, $fieldName, ServiceBase $service)
    {
        if ($bean->parent_type && $bean->parent_id) {
            // trying to reconstruct the parent bean from the fetched data
            $parent = BeanFactory::newBean($bean->parent_type);

            //processing case when parent_type contains name of non-existing bean
            if ($parent === null) {
                $data['parent'] = array();
                return;
            }

            $row = array('id' => $bean->parent_id);
            if (isset($bean->parent_name_owner)) {
                $row['assigned_user_id'] = $bean->parent_name_owner;
            }

            // make sure fetched row is populated as SugarACLStatic::beanACL() may use it
            $parent->fetched_row = $parent->populateFromRow($row);

            $mm = MetaDataManager::getManager($service->platform);
            $data['parent'] = array(
                '_acl' => $mm->getAclForModule($parent->module_dir, $service->user, $parent),
                'id' => $bean->parent_id,
                'type' => $bean->parent_type,
                'name' => $data[$fieldName],
            );

            if (isset($bean->parent_erased_fields)) {
                $data['parent']['_erased_fields'] = $bean->parent_erased_fields;
            }
        } else {
            $data['parent'] = array();
        }
    }

    /**
     * Run a secondary query and populate the results into the array of beans
     *
     * @overrides SugarFieldBase::runSecondaryQuery
     */
    public function runSecondaryQuery($fieldName, SugarBean $seed, array $beans)
    {
        if (empty($beans)) {
            return;
        }

        $child_info = array();
        foreach ($beans as $bean) {
            if (!empty($bean->id) && !empty($bean->parent_type)) {
                $child_info[$bean->parent_type][] = array(
                    'child_id' => $bean->id,
                    'parent_id' => $bean->parent_id,
                );
            }
        }

        // Load parent records
        if (!empty($child_info)) {
            $parent_beans = $seed->retrieve_parent_fields($child_info);
            foreach ($parent_beans as $id => $parent_data) {
                unset($parent_data['id']);
                foreach ($parent_data as $field => $value) {
                    $beans[$id]->$field = $value;
                }
            }
        }
    }
}
