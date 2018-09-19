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
 * SugarFieldBase translates and displays fields from a vardef definition into different formats
 * including DetailView, ListView, EditView. It also provides Search Inputs and database queries
 * to handle searching
 *
 * @property Sugar_Smarty $ss
 */
class SugarFieldBase {
    /**
     * A simple error property to be accessed by calling code for child object
     * methods that process data rather than just manipulate it (like SugarFieldFile
     * and SugarFieldImage when uploading)
     *
     * @var string
     */
    public $error;
    var $hasButton = false;
    protected static $base = array();

    protected $needsSecondaryQuery = false;

    /**
     * The name of the module the field belongs to
     *
     * @var string
     */
    protected $module;

    /**
     * Field options
     *
     * @var array
     */
    protected $options = array();

    public function __construct($type)
    {
    	$this->type = $type;
    }

    public function __get($name)
    {
        if ($name == 'ss') {
            $this->ss = new Sugar_Smarty();
            return $this->ss;
        }

        return null;
    }

    /**
     * Sets the field options
     *
     * @param $options
     */
    public function setOptions($options) {
        $this->options = $options;
    }

    /**
     * Sets the field module
     *
     * @param $module
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    function fetch($path)
    {
    	$additional = '';
    	if(!$this->hasButton && !empty($this->button)){
    		$additional .= '<input type="button" class="button" ' . $this->button . '>';
    	}
        if(!empty($this->buttons)){
            foreach($this->buttons as $v){
                $additional .= ' <input type="button" class="button" ' . $v . '>';
            }

        }
        if(!empty($this->image)){
            $additional .= ' <img ' . $this->image . '>';
        }
    	return $this->ss->fetch($path) . $additional;
    }

    /**
     * Get base view - cache it since base view is the same for all fields
     * @param string $view
     * @return string Base view filename
     */
    protected function getBase($view)
    {
        if(!isset(self::$base[$view])) {
            if(!empty($GLOBALS['current_language'])) {
            	self::$base[$view] = SugarAutoLoader::existingCustomOne("include/SugarFields/Fields/Base/{$GLOBALS['current_language']}.$view.tpl");
            }
            if(empty(self::$base[$view])) {
            	self::$base[$view] = SugarAutoLoader::existingCustomOne("include/SugarFields/Fields/Base/$view.tpl");
            }
        }
        return self::$base[$view];
    }

    protected function findTemplate($view, $formName = '')
    {
        static $tplCache = array();

        if ( isset($tplCache[$this->type][$view]) ) {
            return $tplCache[$this->type][$view];
        }

        if (isset($this->formTemplateMap[$formName])) {
            $classList = array($this->formTemplateMap[$formName]);
        } else {
            $lastClass = get_class($this);
            $classList = array($this->type, str_replace('SugarField', '', $lastClass));
            while ($lastClass = get_parent_class($lastClass)) {
                $classList[] = str_replace('SugarField', '', $lastClass);
            }
            array_pop($classList); // remove this class - $base handles that
        }

        $tplName = '';
        global $current_language;
        foreach ( $classList as $className ) {
            if(isset($current_language)) {
                $tplName = SugarAutoLoader::existingCustomOne('include/SugarFields/Fields/'. $className .'/'. $current_language . '.' . $view .'.tpl');
                if ($tplName) {
                    break;
                }
            }
            $tplName = SugarAutoLoader::existingCustomOne('include/SugarFields/Fields/'. $className .'/'. $view .'.tpl');
            if ($tplName) {
                break;
            }
        }
        if(empty($tplName)) {
            $tplName = $this->getBase($view);
        }

        $tplCache[$this->type][$view] = $tplName;

        return $tplName;
    }

    public function formatField($rawField, $vardef){
        // The base field doesn't do any formatting, so override it in subclasses for more specific actions
        return $rawField;
    }

    /**
     * Formats a field for the Sugar API
     *
     * @param array     $data
     * @param SugarBean $bean
     * @param array     $args
     * @param string    $fieldName
     * @param array     $properties
     * @param array     $fieldList
     * @param ServiceBase $service
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
            $data[$fieldName] = $bean->$fieldName;
        } else {
            $data[$fieldName] = '';
        }
    }

    /**
     * Ensures that necessary arguments of apiFormatField() are passed
     *
     * @param array $fieldList The $fieldList argument of apiFormatField()
     * @param ServiceBase $service The $service argument of apiFormatField()
     */
    protected function ensureApiFormatFieldArguments(array $fieldList = null, ServiceBase $service = null)
    {
        if ($fieldList === null) {
            trigger_error('$fieldList argument of apiFormatField() is missing', E_USER_DEPRECATED);
        }

        if ($service === null) {
            trigger_error('$service argument of apiFormatField() is missing', E_USER_DEPRECATED);
        }
    }

    function getWirelessSmartyView($parentFieldArray, $vardef, $displayParams, $tabindex = -1, $view){
    	$this->setup($parentFieldArray, $vardef, $displayParams, $tabindex, false);
        return $this->fetch($this->findTemplate($view));
    }

    public function unformatField($formattedField, $vardef){
        // The base field doesn't do any formatting, so override it in subclasses for more specific actions
        return $formattedField;
    }

    function getSmartyView($parentFieldArray, $vardef, $displayParams, $tabindex = -1, $view){
    	$this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);

        $formName = isset($displayParams['formName']) ? $displayParams['formName'] : '';
        return $this->fetch($this->findTemplate($view, $formName));
    }

    function getListViewSmarty($parentFieldArray, $vardef, $displayParams, $col) {
        $tabindex = 1;
        //fixing bug #46666: don't need to format enum and radioenum fields
        //because they are already formated in SugarBean.php in the function get_list_view_array() as fix of bug #21672
        if ($this->type != 'Enum' && $this->type != 'Radioenum')
        {
            $parentFieldArray = $this->setupFieldArray($parentFieldArray, $vardef);
        }
		else
        {
        	$vardef['name'] = strtoupper($vardef['name']);
        }

    	$this->setup($parentFieldArray, $vardef, $displayParams, $tabindex, false);

        $this->ss->left_delimiter = '{';
        $this->ss->right_delimiter = '}';
        $this->ss->assign('col',$vardef['name']);

        return $this->fetch($this->findTemplate('ListView'));
    }

    /**
     * Returns a smarty template for the DetailViews
     *
     * @param parentFieldArray string name of the variable in the parent template for the bean's data
     * @param vardef vardef field defintion
     * @param displayParam parameters for display
     *      available paramters are:
     *      * labelSpan - column span for the label
     *      * fieldSpan - column span for the field
     */
    function getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
        return $this->getSmartyView($parentFieldArray, $vardef, $displayParams, $tabindex, 'DetailView');
    }

 	// 99% of all fields will just format like a listview, but just in case, it's here to override
    function getChangeLogSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
        return $this->formatField($parentFieldArray[$vardef['name']],$vardef);
    }

    function getWirelessDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
        return $this->getWirelessSmartyView($parentFieldArray, $vardef, $displayParams, $tabindex, 'WirelessDetailView');
    }

    function getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
    	if(!empty($vardef['function']['returns']) && $vardef['function']['returns'] == 'html'){
    		$type = $this->type;
    		$this->type = 'Base';
    		$result= $this->getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
    		$this->type = $type;
    		return $result;
    	}
    	// jpereira@dri - #Bug49513 - Readonly type not working as expected
	// If readonly is set in displayParams, the vardef will be displayed as in DetailView.
	if (isset($displayParams['readonly']) && $displayParams['readonly']) {
		return $this->getSmartyView($parentFieldArray, $vardef, $displayParams, $tabindex, 'DetailView');
	}
	// ~ jpereira@dri - #Bug49513 - Readonly type not working as expected
       return $this->getSmartyView($parentFieldArray, $vardef, $displayParams, $tabindex, 'EditView');
    }

    function getImportViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex)
    {
        return $this->getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
    }

    function getWirelessEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
    	if(!empty($vardef['function']['returns']) && $vardef['function']['returns'] == 'html'){
    		$type = $this->type;
    		$this->type = 'Base';
    		$result= $this->getWirelessDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
    		$this->type = $type;
    		return $result;
    	}

       	return $this->getWirelessSmartyView($parentFieldArray, $vardef, $displayParams, $tabindex, 'WirelessEditView');
    }

    function getWirelessListViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
        $vardef['name'] = $vardef['name'].'_advanced';
        return $this->getWirelessEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
    }


    function getSearchViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
		if(!empty($vardef['auto_increment']))$vardef['len']=255;
    	return $this->getSmartyView($parentFieldArray, $vardef, $displayParams, $tabindex, 'EditView');
    }

    function getPopupViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex){
    	 if (is_array($displayParams) && !isset($displayParams['formName']))
		     $displayParams['formName'] = 'popup_query_form';
	     else if (empty($displayParams))
		     $displayParams = array('formName' => 'popup_query_form');
		 return $this->getSearchViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
    }

    public function getEmailTemplateValue($inputField, $vardef, $context = null){
        // This does not return a smarty section, instead it returns a direct value
        return $this->formatField($inputField,$vardef);
    }

    /**
     * Calls defined function to display the field.
     *
     * @param string $displayType View name.
     * @param array $parentFieldArray Name of the variable in the parent template
     * @param array $vardef Field definition.
     * @param array $displayParams Parameters for display.
     * @param int $tabindex
     * @return string
     */
    public function displayFromFunc($displayType, $parentFieldArray, $vardef, $displayParams, $tabindex = 0)
    {

        if ( ! is_array($vardef['function']) ) {
            $funcName = $vardef['function'];
            $includeFile = '';
            $onListView = false;
            $returnsHtml = false;
            if (isset($vardef['function']) && $vardef['function'] == 'getCurrencies') {
                // there is more hacks in this code to allow this to happen since we moved the values to the rest
                // way for getting the values
                $returnsHtml = true;
            }
        } else {
            $funcName = $vardef['function']['name'];
            $includeFile = '';
            if ( isset($vardef['function']['include']) ) {
                $includeFile = $vardef['function']['include'];
            }
            if ( isset($vardef['function']['onListView']) && $vardef['function']['onListView'] == true ) {
                $onListView = true;
            } else {
                $onListView = false;
            }
            if ( isset($vardef['function']['returns']) && $vardef['function']['returns'] == 'html' ) {
                $returnsHtml = true;
            } else {
                $returnsHtml = false;
            }
        }

        if ( $displayType == 'ListView'
                || $displayType == 'popupView'
                || $displayType == 'searchView'
                || $displayType == 'wirelessEditView'
                || $displayType == 'wirelessDetailView'
                || $displayType == 'wirelessListView'
                ) {
            // Traditionally, before 6.0, additional functions were never called, so this code doesn't get called unless the vardef forces it
            if ( $onListView ) {
                if ( !empty($includeFile) ) {
                    require_once($includeFile);
                }

                return $funcName($parentFieldArray, $vardef['name'], $parentFieldArray[strtoupper($vardef['name'])], $displayType);
            } else {
                $displayTypeFunc = 'get'.$displayType.'Smarty';
                return $this->$displayTypeFunc($parentFieldArray, $vardef, $displayParams, $tabindex);
            }
        } else {
            if ( !empty($displayParams['idName']) ) {
                $fieldName = $displayParams['idName'];
            } else {
                $fieldName = $vardef['name'];
            }
            if ( $returnsHtml ) {
                $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
                $tpl = $this->findTemplate($displayType.'Function');
                if ( $tpl == '' ) {
                    // Didn't find the wireless version, try the non-wireless version.
                    $tpl = $this->findTemplate(str_replace('wireless','',$displayType));
                }
                if ( $tpl == '' ) {
                    // Can't find a function template, just use the base
                    $tpl = $this->findTemplate('DetailViewFunction');
                }
                return "<span id='{$vardef['name']}_span'>" . $this->fetch($tpl) . '</span>';
            } else {
                return '{sugar_run_helper include="'.$includeFile.'" func="'.$funcName.'" bean=$bean field="'.$fieldName.'" value=$fields.'.$fieldName.'.value displayType="'.$displayType.'"}';
            }
        }
    }

    function getEditView() {
    }

    /**
     * getSearchWhereValue
     *
     * Checks and returns a sane value based on the field type that can be used when building the where clause in a
     * search form.
     *
     * @param $value Mixed value being searched on
     * @return Mixed the value for the where clause used in search
     */
    function getSearchWhereValue($value) {
        return $value;
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

    	return isset($args[$key]) ? $args[$key] : '';
    }

    function getQueryLike() {

    }

    function getQueryIn() {
    }

    /**
     * Setup function to assign values to the smarty template, should be called before every display function
     */
    function setup($parentFieldArray, $vardef, $displayParams, $tabindex, $twopass=true) {
    	$this->button = '';
    	$this->buttons = '';
    	$this->image = '';
        if ($twopass)
        {
            $this->ss->left_delimiter = '{{';
            $this->ss->right_delimiter = '}}';
        }
        else
        {
            $this->ss->left_delimiter = '{';
            $this->ss->right_delimiter = '}';
        }
        $this->ss->assign('parentFieldArray', $parentFieldArray);
        $this->ss->assign('vardef', $vardef);
        $this->ss->assign('tabindex', $tabindex);

        //for adding attributes to the field

        if(!empty($displayParams['field'])){
        	$plusField = '';
        	foreach($displayParams['field'] as $key=>$value){
        		$plusField .= ' ' . $key . '="' . $value . '"';//bug 27381
        	}
        	$displayParams['field'] = $plusField;
        }
        //for adding attributes to the button
    	if(!empty($displayParams['button'])){
        	$plusField = '';
        	foreach($displayParams['button'] as $key=>$value){
        		$plusField .= ' ' . $key . '="' . $value . '"';
        	}
        	$displayParams['button'] = $plusField;
        	$this->button = $displayParams['button'];
        }
        if(!empty($displayParams['buttons'])){
            $plusField = '';
            foreach($displayParams['buttons'] as $keys=>$values){
                foreach($values as $key=>$value){
                    $plusField[$keys] .= ' ' . $key . '="' . $value . '"';
                }
            }
            $displayParams['buttons'] = $plusField;
            $this->buttons = $displayParams['buttons'];
        }
        if(!empty($displayParams['image'])){
            $plusField = '';
            foreach($displayParams['image'] as $key=>$value){
                $plusField .= ' ' . $key . '="' . $value . '"';
            }
            $displayParams['image'] = $plusField;
            $this->image = $displayParams['image'];
        }
        $this->ss->assign('displayParams', $displayParams);


    }

    protected function getAccessKey($vardef, $fieldType = null, $module = null) {
        global $app_strings;

        $labelList = array(
            'accessKey' => array(),
            'accessKeySelect' => array(),
            'accessKeyClear' => array(),
        );

        // Labels are always in uppercase
        if ( isset($fieldType) ) {
            $fieldType = strtoupper($fieldType);
        }

        if ( isset($module) ) {
            $module = strtoupper($module);
        }

        // The vardef is the most specific, then the module + fieldType, then the module, then the fieldType
        if ( isset($vardef['accessKey']) ) {
            $labelList['accessKey'][] = $vardef['accessKey'];
        }
        if ( isset($vardef['accessKeySelect']) ) {
            $labelList['accessKeySelect'][] = $vardef['accessKeySelect'];
        }
        if ( isset($vardef['accessKeyClear']) ) {
            $labelList['accessKeyClear'][] = $vardef['accessKeyClear'];
        }

        if ( isset($fieldType) && isset($module) ) {
            $labelList['accessKey'][] = 'LBL_ACCESSKEY_'.$fieldType.'_'.$module;
            $labelList['accessKeySelect'][] = 'LBL_ACCESSKEY_SELECT_'.$fieldType.'_'.$module;
            $labelList['accessKeyClear'][] = 'LBL_ACCESSKEY_CLEAR_'.$fieldType.'_'.$module;
        }

        if ( isset($module) ) {
            $labelList['accessKey'][] = 'LBL_ACCESSKEY_'.$module;
            $labelList['accessKeySelect'][] = 'LBL_ACCESSKEY_SELECT_'.$module;
            $labelList['accessKeyClear'][] = 'LBL_ACCESSKEY_CLEAR_'.$module;
        }

        if ( isset($fieldType) ) {
            $labelList['accessKey'][] = 'LBL_ACCESSKEY_'.$fieldType;
            $labelList['accessKeySelect'][] = 'LBL_ACCESSKEY_SELECT_'.$fieldType;
            $labelList['accessKeyClear'][] = 'LBL_ACCESSKEY_CLEAR_'.$fieldType;
        }

        // Attach the defaults to the ends
        $labelList['accessKey'][] = 'LBL_ACCESSKEY';
        $labelList['accessKeySelect'][] = 'LBL_SELECT_BUTTON';
        $labelList['accessKeyClear'][] = 'LBL_CLEAR_BUTTON';

        // Figure out the label and the key for the button.
        // Later on we may attempt to make sure there are no two buttons with the same keys, but for now we will just use whatever is specified.
        $keyTypes = array('accessKey','accessKeySelect','accessKeyClear');
        $accessKeyList = array(
            'accessKey' => '',
            'accessKeyLabel' => '',
            'accessKeyTitle' => '',
            'accessKeySelect' => '',
            'accessKeySelectLabel' => '',
            'accessKeySelectTitle' => '',
            'accessKeyClear' => '',
            'accessKeyClearLabel' => '',
            'accessKeyClearTitle' => '',
        );
        foreach( $keyTypes as $type ) {
            foreach ( $labelList[$type] as $tryThis ) {
                if ( isset($app_strings[$tryThis.'_KEY']) && isset($app_strings[$tryThis.'_TITLE']) && isset($app_strings[$tryThis.'_LABEL']) ) {
                    $accessKeyList[$type] = $tryThis.'_KEY';
                    $accessKeyList[$type.'Title'] = $tryThis.'_TITLE';
                    $accessKeyList[$type.'Label'] = $tryThis.'_LABEL';
                    break;
                }
            }
        }

        return $accessKeyList;
    }

	/**
     * This should be called when the bean is saved. The bean itself will be passed by reference
     * @param SugarBean bean - the bean performing the save
     * @param array params - an array of paramester relevant to the save, most likely will be $_REQUEST
     * @param string $field - The name of the field to save (the vardef name, not the form element name)
     * @param array $properties - Any properties for this field
     */
    public function save($bean, $params, $field, $properties, $prefix = '') {
        if (isset($params[$prefix.$field])) {
            if (isset($properties['len']) && isset($properties['type']) && $this->isTrimmable($properties['type'])) {
                $bean->$field = trim($this->unformatField($params[$prefix.$field], $properties));
            } else {
                $bean->$field = $this->unformatField($params[$prefix.$field], $properties);
            }
        }
    }

    /**
     * This should be called when the bean is saved from the API. Most fields can just use default, which calls the field's individual ->save() function instead.
     * @param SugarBean $bean - the bean performing the save
     * @param array $params - an array of paramester relevant to the save, which will be an array passed up to the API
     * @param string $field - The name of the field to save (the vardef name, not the form element name)
     * @param array $properties - Any properties for this field
     */
    public function apiSave(SugarBean $bean, array $params, $field, $properties) {
        if (isset($params[$field])) {
            if (isset($properties['len']) && isset($properties['type']) && $this->isTrimmable($properties['type'])) {
                $bean->$field = trim($this->apiUnformatField($params[$field], $properties));
            } else {
                $bean->$field = $this->apiUnformatField($params[$field], $properties);
            }
        }
    }

    /**
     * Validates submitted data
     * @param SugarBean $bean
     * @param array $params
     * @param string $field
     * @param array $properties
     * @return boolean
     */
    public function apiValidate(SugarBean $bean, array $params, $field, $properties) 
    {
        return true;
    }

    /**
     * This should be called when the bean is mass updated from the API. Most fields can just use default, which calls the field's individual ->apiSave() function instead
     *
     * @param SugarBean $bean - the bean performing the mass update
     * @param array $params - an array of paramester relevant to the save, which will be an array passed up to the API
     * @param string $field - The name of the field to save (the vardef name, not the form element name)
     * @param array $properties - Any properties for this field
     */
    public function apiMassUpdate(SugarBean $bean, array $params, $field, $properties) {
        return $this->apiSave($bean, $params, $field, $properties);
    }

     /**
      * Check if the field is allowed to be trimmed
      *
      * @param string $type
      * @return boolean
      */
     protected function isTrimmable($type) {
         return in_array($type, array('varchar', 'name'));
     }

    /**
     * Handles import field sanitizing for an field type
     *
     * @param  $value    string value to be sanitized
     * @param  $vardefs  array
     * @param  $focus    SugarBean object
     * @param  $settings ImportFieldSanitize object
     * @return string sanitized value or boolean false if there's a problem with the value
     */
    public function importSanitize(
        $value,
        $vardef,
        $focus,
        ImportFieldSanitize $settings
        )
    {
        if( isset($vardef['len']) ) {
            // check for field length
            $value = sugar_substr($value, $vardef['len']);
        }

        return $value;
    }

    /**
     * Handles export field sanitizing for field type
     *
     * @param $value string value to be sanitized
     * @param $vardef array representing the vardef definition
     * @param $focus SugarBean object
     * @param $row Array of a row of data to be exported
     *
     * @return string sanitized value
     */
    public function exportSanitize($value, $vardef, $focus, $row=array())
    {
        return $value;
    }

    /**
     * isRangeSearchView
     * This method helps determine whether or not to display the range search view code for the sugar field
     * @param array $vardef entry representing the sugar field's definition
     * @return boolean true if range search view should be displayed, false otherwise
     */
    protected function isRangeSearchView($vardef)
    {
     	return !empty($vardef['enable_range_search']) && !empty($_REQUEST['action']) && $_REQUEST['action']!='Popup';
    }

    /**
     * setupFieldArray
     * This method takes the $parentFieldArray mixed variable which may be an Array or object and attempts
     * to call any custom fieldSpecific formatting to the value depending on the field type.
     *
     * @see SugarFieldEnum.php, SugarFieldInt.php, SugarFieldFloat.php, SugarFieldRelate.php
     * @param	mixed	$parentFieldArray Array or Object of data where the field's value comes from
     * @param	array	$vardef The vardef entry linked to the SugarField instance
     * @return	array   $parentFieldArray The formatted $parentFieldArray with the formatField method possibly applied
     */
    protected function setupFieldArray($parentFieldArray, $vardef)
    {
        $fieldName = $vardef['name'];
        if ( is_array($parentFieldArray) )
        {
            $fieldNameUpper = strtoupper($fieldName);
            if ( isset($parentFieldArray[$fieldNameUpper]))
            {
                $parentFieldArray[$fieldName] = $this->formatField($parentFieldArray[$fieldNameUpper],$vardef);
            } else {
                $parentFieldArray[$fieldName] = '';
            }
        } elseif (is_object($parentFieldArray)) {
            if ( isset($parentFieldArray->$fieldName) )
            {
                $parentFieldArray->$fieldName = $this->formatField($parentFieldArray->$fieldName,$vardef);
            } else {
                $parentFieldArray->$fieldName = '';
            }
        }
        return $parentFieldArray;
    }

    /**
     * Gets normalized values for defs. Used by the MetaDataManager at first for
     * API responses, but can be used througout the app.
     *
     * @param array $vardef
     * @param array $defs Full vardefs for the same module
     * @return array A transformed vardef with normalizations applied
     */
    public function getNormalizedDefs($vardef, $defs) {
        // Bug 57802 - REST API Metadata: vardef len property must be number, not string
        // Some vardefs set len and size as strings. Custom fields do the same
        // so clean that up here before the metadata is returned.
        if (isset($vardef['len'])) {
            $vardef['len'] = $this->normalizeNumeric($vardef['len']);
        }

        if (isset($vardef['size'])) {
            $vardef['size'] = $this->normalizeNumeric($vardef['size']);
        }

        $attributeNameWithBoolValue = [
            'audited',
            'exportable',
            'massupdate',
            'pii',
            'readonly',
            'reportable',
            'required',
            'sortable',
        ];

        // normalize bool value
        foreach ($attributeNameWithBoolValue as $attributeName) {
            if (isset($vardef[$attributeName])) {
                $vardef[$attributeName] = $this->normalizeBoolean($vardef[$attributeName]);
            }
        }

        // Handle normalizations that need to be applied
        if (isset($vardef['default'])) {
            $vardef['default'] = $this->normalizeDefaultValue($vardef['default']);
        }

        if (isset($vardef['default_value'])) {
            $vardef['default_value'] = $this->normalizeDefaultValue($vardef['default_value']);
        }

        return $vardef;
    }

    /**
     * Normalizes a default value
     *
     * @param mixed $value The value to normalize
     * @return string
     */
    public function normalizeDefaultValue($value) {
        return $value;
    }

    /**
     * Normalizes numeric def values. For non numeric values, returns null
     *
     * @param mixed $value
     * @return int|null
     */
    public function normalizeNumeric($value) {
        if (is_numeric($value)) {
            return intval($value);
        }

        return null;
    }

    public function normalizeBoolean($value) {
        // If the value is already boolean, send it back
        if ($value === true || $value === false) {
            return $value;
        }

        // Check against known values of booleans
        $bools = array(
            0 => false,
            '0' => false,
            'no' => false,
            'off' => false,
            'false' => false,
            1 => true,
            '1' => true,
            'yes' => true,
            'on' => true,
            'true' => true,
        );

        if (isset($bools[$value])) {
            return $bools[$value];
        }

        // Just send it back
        return $value;
    }

    /**
     * Overloaded in field specific classes
     * @param $value
     * @return mixed
     */
    public function convertFieldForDB($value)
    {
        return $value;
    }

    /**
     * Unformat a value from an API format.
     *
     * Note: this method will be removed in a later API version. apiUnformatField() is the correct
     * method to use. apiUnformat() is being deprecated and continued to be used by the endpoints
     * to avoid breaking any code that extends it.
     *
     * @deprecated
     * @see apiUnformatField()
     * @param $value - the value that needs unformatted
     * @return string - the unformatted value
     */
    public function apiUnformat($value)
    {
        return $this->apiUnformatField($value);
    }


    /**
     * Unformat a value from an API format
     * @param $value - the value that needs unformatted
     * @return string - the unformatted value
     */
    public function apiUnformatField($value)
    {
        return $value;
    }

    /**
     * Fix a value(s) for a Filter statement
     * @param $value - the value that needs fixing
     * @param $columnName - the column name we are fixing
     * @param SugarBean $bean - the Bean
     * @param SugarQuery $q - the Query
     * @param SugarQuery_Builder_Where $where - the Where statement
     * @param $op - the filter operand
     * @return bool - true if everything can pass as normal, false if new filters needed to be added to override the existing $op
     */
    public function fixForFilter(&$value, $columnName, SugarBean $bean, SugarQuery $q, SugarQuery_Builder_Where $where, $op)
    {
        return true;
    }

    /**
     * Does this field need to have a secondary query
     * @param string $fieldName - the field we are fixing
     * @param SugarBean $bean - the Bean
     * @return bool - True if the field needs to run a secondary query to fetch data
     */
    public function fieldNeedsSecondaryQuery($fieldName, SugarBean $bean)
    {
        return $this->needsSecondaryQuery;
    }

    /**
     * Run a secondary query and populate the results into the array of beans
     * @param string $fieldName - the field we are fixing
     * @param SugarBean $seed - The seed bean to run the query off of
     * @param Array $beans - An array of SugarBeans keyed by the ID.
     */
    public function runSecondaryQuery($fieldName, SugarBean $seed, array $beans)
    {
        return;
    }

    /**
     * Adds field to the list of fields to be selected
     *
     * @param string $field
     * @param array $fields
     */
    public function addFieldToQuery($field, array &$fields)
    {
        $fields[] = $field;
    }

    /**
     * Processes view field by calling callbacks with its attributes and iterating over nested fields
     *
     * @param ViewIterator $iterator View iterator
     * @param array $field The view definition of the field being processed
     * @param callable $callback Iterator callback
     */
    public function iterateViewField(
        ViewIterator $iterator,
        array $field,
        /* callable */ $callback
    ) {
        $fieldSetAttributes = array('fields', 'related_fields');
        $fieldSets = array();
        foreach ($fieldSetAttributes as $attribute) {
            if (isset($field[$attribute]) && is_array($field[$attribute])) {
                $fieldSets[] = $field[$attribute];
                unset($field[$attribute]);
            }
        }

        if (isset($field['name'])) {
            $callback($field);
        }

        foreach ($fieldSets as $fieldSet) {
            $iterator->apply($fieldSet, $callback);
        }
    }
}
