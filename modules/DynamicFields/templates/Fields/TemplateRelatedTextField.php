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

class TemplateRelatedTextField extends TemplateText{
    var $type = 'relate';

    public function __construct()
    {
        // ext2 is the related module
        // Adding the match for this (module => ext2) has unexpected consequences
        // in module builder, since 'module' in module builder is 'ModuleBuilder'.
        // There is code down below that handles the mapping of module to ext2.
        // This mapping will set $this->module = $_REQUEST['ext2'].
        $this->vardef_map['ext2'] = 'module';
    }

    function get_html_edit(){
        $this->prepare();
        $name = $this->name .'_name';
        $value_name = strtoupper('{'.$name.'}');
        $id = $this->name ;
        $value_id = strtoupper('{'.$id .'}');
        return "<input type='text' name='$name' id='$name' size='".$this->size."' readonly value='$value_name'><input type='button' onclick='open_popup(\"{". strtoupper($this->name). "_MODULE}\", 600, 400,\" \", true, false, {ENCODED_". strtoupper($this->name). "_POPUP_REQUEST_DATA})' type='button'  class='button' value='{APP.LBL_SELECT_BUTTON_LABEL}' ><input type='hidden' name='$id' value='$value_id'>";
    }

    function get_html_detail(){
        $name = $this->name .'_name';
        $value_name = strtoupper('{'.$name.'}');
        $id = $this->name ;
        $value_id = strtoupper('{'.$id .'}');

        return "<a href='index.php?module=$this->ext2&action=DetailView&record={$value_id}'>{$value_name}</a>" ;
    }

    function get_html_list(){
        if(isset($this->bean)){
            $name = $this->bean->object_name . '.'. $this->ext1;
        }else{
            $name = $this->ext1;
        }
        return '{'. strtoupper($name) . '}';
    }

    function get_html_search(){
        $searchable=array();
        $def = $this->bean->field_defs[$this->name];
        $searchable = array('team_id');
        if(!empty($def['id_name']) && in_array($def['id_name'], $searchable)){
            $name = $def['id_name'];
            return "<select size='3' name='{$name}[]' tabindex='1' multiple='multiple'>{".strtoupper($name). "_FILTER}</select>";
        }
        //return 'NOT AVAILABLE';
        return $this->get_html_edit();
    }

    function get_xtpl_search(){
        $searchable=array();
        $def = $this->bean->field_defs[$this->name];
        $searchable = array('team_id');
        $returnXTPL = array();
        if(!empty($def['id_name']) && in_array($def['id_name'], $searchable)){
            $name = $def['id_name'];
            $team_list = '';
            foreach(get_team_array() as $id=>$team){
                $selected = '';

                if(!empty($_REQUEST[$name]) && is_array($_REQUEST[$name]) && in_array($id, $_REQUEST[$name])){
                    $selected = 'selected';
                }
                $team_list .= "<option  $selected value='$id'>$team</option>";
            }
            $returnXTPL[strtoupper($name). '_FILTER'] = $team_list;
        } else {
            $id = $this->name;
            $name = $this->name .'_name';
            $module = $this->ext2;
            $popup_request_data = array(
                                        'call_back_function' => 'set_return',
                                        'form_name' => 'search_form',
                                        'field_to_name_array' => array(
                                        'id' => $this->name,
                                        $this->ext1 => $name,
                                    ),
            );

            $json = getJSONobj();
            $encoded_popup_request_data = $json->encode($popup_request_data);
            $returnXTPL['ENCODED_'.strtoupper($id).'_POPUP_REQUEST_DATA'] = $encoded_popup_request_data;
            $returnXTPL[strtoupper($id).'_MODULE'] = $module;

            if(isset( $_REQUEST[$name])){
               $returnXTPL[strtoupper($name)] =  $_REQUEST[$name];
            }
            if(isset( $_REQUEST[$id])){
               $returnXTPL[strtoupper($id)] =  $_REQUEST[$id];
            }
        }
        return $returnXTPL;
    }


    function get_xtpl_edit(){
    global $beanList;

        $name = $this->name .'_name';
        $id = $this->name;
        $module = $this->ext2;
        $returnXTPL = array();
        $popup_request_data = array(
            'call_back_function' => 'set_return',
            'form_name' => 'EditView',
            'field_to_name_array' => array(
            'id' => $this->name,
            $this->ext1 => $name,
        ),
        );

        $json = getJSONobj();
        $encoded_contact_popup_request_data = $json->encode($popup_request_data);
        $returnXTPL['ENCODED_'.strtoupper($id).'_POPUP_REQUEST_DATA'] = $encoded_contact_popup_request_data;
        $returnXTPL[strtoupper($id).'_MODULE'] = $module;

        if(isset($this->bean->$id)){
            if(!isset($this->bean->$name)){
                $mod_field = $this->ext1;
                $mod = BeanFactory::getBean($module, $this->bean->$id);
                if(isset($mod->$mod_field)){
                    $this->bean->$name = $mod->$mod_field;
                }
            }


            $returnXTPL[strtoupper($id)] = $this->bean->$id;
        }
        if(isset($this->bean->$name)){
            $returnXTPL[strtoupper($name)] = $this->bean->$name;
        }
        if(isset($this->bean->$id)) {
            $returnXTPL[strtoupper($id)] = $this->bean->$id;
        }


        return $returnXTPL;
    }

    function get_xtpl_detail(){
        return $this->get_xtpl_edit();
    }

    function get_related_info(){

    }

     function get_field_def(){
        $def = parent::get_field_def();
        $def['id_name'] = $this->ext3;

        // This should pretty much always eval to true, but better safe than sorry
        if (empty($def['ext2'])) {
            // In most cases $this->ext2 will have already been set by either the
            // request or the vardef row from module builder
            $def['ext2'] = empty($this->ext2) ? '' : $this->ext2;
        }

        // Handle setting the related module. In most cases this will be driven
        // by $this->ext2
        if (empty($def['module'])) {
            if (!empty($this->module)) {
                $def['module'] = $this->module;
            } else {
                if (empty($def['ext2']) && !empty($this->ext2)) {
                    $def['ext2'] = $this->ext2;
                }
                
                $def['module'] = $def['ext2'];
            }
        } else {
            if (empty($def['ext2'])) {
                $def['ext2'] = $def['module'];
            }
            
            $this->ext2 = $def['ext2'];
        }

        //Special case for documents, which use a document_name rather than name
        if ($def['module'] == "Documents") {
        	$def['rname'] = 'document_name';
        } else {
        	$def['rname'] = 'name';
        }
        $def['quicksearch'] = 'enabled';
        $def['studio'] = 'visible';
        $def['source'] = 'non-db';
        return $def;
    }


    
    /**
     * Delete field
     *
     * @param DynamicField $df
     */
    public function delete($df)
    {
        $fieldId = null;
        if ($df instanceof DynamicField) {
            $fieldId = $df->getFieldWidget($df->module, $this->id_name);
        } elseif ($df instanceof MBModule) {
            $fieldId = $df->getField($this->id_name);
        } else {
            $GLOBALS['log']->fatal('Unsupported DynamicField type');
        }

        // the field may have already been deleted
        if ($fieldId) {
            $this->deleteIdLabel($fieldId, $df);
            $fieldId->delete($df);
        }

        parent::delete($df);
    }
        
    /**
     * Delete label of id field
     * @param TemplateField $fieldId
     * @param $df
     */
    protected function deleteIdLabel(TemplateField $fieldId, $df)
    {
        if ($df instanceof DynamicField) {
            foreach (array_keys($GLOBALS['sugar_config']['languages']) AS $language) {
                foreach (ModuleBuilder::getModuleAliases($df->module) AS $module) {
                    $mod_strings = return_module_language($language, $module);
                    if (isset($mod_strings[$fieldId->vname])) {
                        ParserLabel::removeLabel($language, $fieldId->vname, $mod_strings[$fieldId->vname], $module);
                    }
                }
            }
    
        } elseif ($df instanceof MBModule) {
            foreach (array_keys($GLOBALS['sugar_config']['languages']) AS $language) {
                $df->deleteLabel($language, $fieldId->vname);
                $df->save();
            }
        }
    }


    function save($df){
        // create the new ID field associated with this relate field - this will hold the id of the related record
        // this field must have a unique name as the name is used when constructing quicksearches and when saving the field
        //Check if we have not saved this field so we don't create two ID fields.
        //Users should not be able to switch the module after having saved it once.
        if (!$df->fieldExists($this->name)) {
	    	$id = new TemplateId();
	        $id->len = 36;
            $id->label = strtoupper("LBL_{$this->name}_".BeanFactory::getBeanClass($this->ext2)."_ID");
            $id->vname = $id->label;
            $this->saveIdLabel($id->label, $df);

	        $count = 0;
	        $basename = strtolower(get_singular_bean_name($this->ext2)).'_id' ;
	        $idName = $basename.'_c' ;

	        while ( $df->fieldExists($idName, 'id') )
	        {
	            $idName = $basename.++$count.'_c' ;
	        }
	        $id->name = $idName ;
			$id->reportable = false;
	        $id->save($df);

	        // record the id field's name, and save
	        $this->ext3 = $id->name;
            $this->id_name = $id->name;
        }

        parent::save($df);
    }

    /**
     * Save label for id field
     *
     * @param string $idLabelName
     * @param DynamicField $df
     */
    protected function saveIdLabel($idLabelName, $df)
    {
        if ($df instanceof DynamicField) {
            $module = $df->module;
        } elseif ($df instanceof MBModule) {
            $module = $df->name;
        }else{
            $GLOBALS['log']->fatal('Unsupported DynamicField type');
        }
        $viewPackage = isset($df->package)?$df->package:null;

        $idLabelValue = string_format(
            translate('LBL_RELATED_FIELD_ID_NAME_LABEL', 'ModuleBuilder'),
            array($this->label_value, $GLOBALS['app_list_strings']['moduleListSingular'][$this->ext2])
        );

        $idFieldLabelArr = array(
            "label_{$idLabelName}" => $idLabelValue
        );

        foreach(ModuleBuilder::getModuleAliases($module) AS  $moduleName) {
            if ($df instanceof DynamicField) {
                $parser = new ParserLabel($moduleName, $viewPackage);
                $parser->handleSave($idFieldLabelArr, $GLOBALS['current_language']);
            } elseif ($df instanceof MBModule) {
                $df->setLabel($GLOBALS ['current_language'], $idLabelName, $idLabelValue);
                $df->save();
            }
        }

    }
    
    function get_db_add_alter_table($table){
    	return "";
    }

    function get_db_delete_alter_table($table) {
    	return "";
    }

    function get_db_modify_alter_table($table){
    	return "";
    }

    public function populateFromRow(array $row)
    {
        parent::populateFromRow($row);
        // In some cases, MB Controller sets $this->module to a bean or mbmodule
        // object. If that's the case get the string module value from what we know.
        $this->module = $this->getModuleNameFromModule($this->module);
        if (!empty($this->module) && empty($this->ext2)) {
            $this->ext2 = $this->module;
        }
    }

    /**
     * {@inheritDoc}
     * 
     * @return array
     */
    public function getFieldMetaDataMapping()
    {
        $fmdMap = parent::getFieldMetaDataMapping();
        $fmdMap['module'] = 'ext2';
        return $fmdMap;
    }

    /**
     * {@inheritDoc}
     */
    protected function applyVardefRules()
    {
        parent::applyVardefRules();
        if (!empty($this->ext2) && !empty($this->module) && $this->ext2 !== $this->module) {
            $this->module = $this->ext2;
        }
    }

    /**
     * Gets the module name from the existing module property that was set in 
     * the controller
     * 
     * @param mixed $module A string value, or a SugarBean or an MBModule object
     * @return string
     */
    protected function getModuleNameFromModule($module)
    {
        if (is_string($module)) {
            return $module;
        }

        if ($module instanceof SugarBean) {
            return $module->module_dir;
        }

        if ($module instanceof MBModule && !empty($module->key_name)) {
            return $module->key_name;
        }

        return '';
    }
}


