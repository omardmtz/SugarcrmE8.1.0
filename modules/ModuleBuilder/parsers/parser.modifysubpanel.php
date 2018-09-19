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

// $Id: SubpanelParser.php 20826 2007-03-09 20:18:29Z clee $
/*
 * Modify an exising Subpanel
 * Typically called from Studio for a deployed (OOB or installed custom module)
 *
 * Uses the listview editor to modify the subpanel
 * Uses SubPanelDefinitions to load the definitions for the subpanel, and SubPanel to save them, using the unique SubPanel override mechanism
 * There are two relevant modules for every subpanel - the module whose detailview this subpanel will appear in ($module_name), and the module that is the source of the data for the subpanel ($subPanelParentModule)
 */


class ParserModifySubPanel extends ParserModifyListView
{

    var $listViewDefs = false ;
    var $defaults = array ( ) ;
    var $additional = array ( ) ;
    var $available = array ( ) ;
    var $columns = array ( 'LBL_DEFAULT' => 'getDefaultFields' , 'LBL_HIDDEN' => 'getAvailableFields' ) ;

    /**
     * {@inheritDoc}
     *
     * @param string $module_name
     * @param string $subPanelName
     */
    public function init($module_name, $subPanelName = '')
    {
        if (empty($subPanelName)) {
            throw new \BadMethodCallException('Missing required argument $subPanelName');
        }
        $GLOBALS [ 'log' ]->debug ( "in ParserModifySubPanel: module_name={$module_name} child_module={$subPanelName}" ) ;
        $this->moduleName = $module_name ;
        $this->subPanelName = $subPanelName ;
        global $beanList ;

        // Sometimes we receive a module name which is not in the correct CamelCase, so shift to lower case for all beanList lookups
        $beanListLower = array_change_key_case ( $beanList ) ;

        // Retrieve the definitions for all the available subpanels for this module
        $module = BeanFactory::newBeanByName($beanListLower[ strtolower( $this->moduleName )]);

        $spd = new SubPanelDefinitions ( $module ) ;

        // Get the lists of fields already in the subpanel and those that can be added in
        // Get the fields lists from an aSubPanel object describing this subpanel from the SubPanelDefinitions object
        $this->originalListViewDefs = array ( ) ;
        if (array_key_exists ( strtolower ( $this->subPanelName ), $spd->layout_defs [ 'subpanel_setup' ] ))
        {
            $originalPanel = $spd->load_subpanel ( $this->subPanelName, true ) ;
            $this->originalListViewDefs = $originalPanel->get_list_fields () ;
            $this->panel = $spd->load_subpanel ( $subPanelName, false ) ;
            $this->listViewDefs = $this->panel->get_list_fields () ;

            // Retrieve a copy of the bean for the parent module of this subpanel - so we can find additional fields for the layout
            $subPanelParentModuleName = $this->panel->get_module_name () ;
            $this->subPanelParentModule = null ;

            if (! empty ( $subPanelParentModuleName ) && isset($beanListLower[strtolower($subPanelParentModuleName)]))
            {
                $this->subPanelParentModule = BeanFactory::newBeanByName($beanListLower[strtolower($subPanelParentModuleName)]);
            }
        }

        $this->language_module = $this->panel->template_instance->module_dir ;
    }

    /**
     * Return a list of the fields that will be displayed in the subpanel
     */
    function getDefaultFields ()
    {
        $this->defaults = array ( ) ;
        foreach ( $this->listViewDefs as $key => $def )
        {
            if (! empty ( $def [ 'usage' ] ) && strcmp ( $def [ 'usage' ], 'query_only' ) == 0)
                continue ;
            if (! empty ( $def [ 'vname' ] ))
                $def [ 'label' ] = $def [ 'vname' ] ;
            $this->defaults [ $key ] = $def ;
        }
        return $this->defaults ;
    }

    /**
     * Return a list of fields that are not currently included in the subpanel but that are available for use
     */
    function getAvailableFields ()
    {
        $this->availableFields = array ( ) ;
        if ($this->subPanelParentModule != null)
        {
            $lowerFieldList = array_change_key_case ( $this->listViewDefs ) ;
            foreach ( $this->originalListViewDefs as $key => $def )
            {
                $key = strtolower ( $key ) ;
                if (! isset ( $lowerFieldList [ $key ] ))
                {
                    $this->availableFields [ $key ] = $def ;
                }
            }
            $GLOBALS [ 'log' ]->debug ( 'parser.modifylistview.php->getAvailableFields(): field_defs=' . print_r ( $this->availableFields, true ) ) ;
            foreach ( $this->subPanelParentModule->field_defs as $key => $fieldDefinition )
            {
                $fieldName = strtolower ( $key ) ;
                if (! isset ( $lowerFieldList [ $fieldName ] )) // bug 16728 - check this first, so that other conditions (e.g., studio == visible) can't override and add duplicate entries
                {
                    if ((empty ( $fieldDefinition [ 'source' ] ) || $fieldDefinition [ 'source' ] == 'db' || $fieldDefinition [ 'source' ] == 'custom_fields') && $fieldDefinition [ 'type' ] != 'id' && strcmp ( $fieldName, 'deleted' ) != 0 || (isset ( $def [ 'name' ] ) && strpos ( $def [ 'name' ], "_name" ) != false) || ! empty ( $def [ 'custom_type' ] ) && (empty ( $fieldDefinition [ 'dbType' ] ) || $fieldDefinition [ 'dbType' ] != 'id') && (empty ( $fieldDefinition [ 'dbtype' ] ) || $fieldDefinition [ 'dbtype' ] != 'id') || (! empty ( $fieldDefinition [ 'studio' ] ) && $fieldDefinition [ 'studio' ] == 'visible'))
                    {
                        $label = (isset ( $fieldDefinition [ 'vname' ] )) ? $fieldDefinition [ 'vname' ] : (isset ( $fieldDefinition [ 'label' ] ) ? $fieldDefinition [ 'label' ] : $fieldDefinition [ 'name' ]) ;
                        $this->availableFields [ $fieldName ] = array ( 'width' => '10' , 'label' => $label ) ;
                    }
                }
            }
        }

        return $this->availableFields ;
    }

    function getField ($fieldName)
    {
        foreach ( $this->listViewDefs as $key => $def )
        {
            $key = strtolower ( $key ) ;
            if ($key == $fieldName)
            {
                return $def ;
            }
        }
        foreach ( $this->originalListViewDefs as $key => $def )
        {
            $key = strtolower ( $key ) ;
            if ($key == $fieldName)
            {
                return $def ;
            }
        }
        foreach ( $this->panel->template_instance->field_defs as $key => $def )
        {
            $key = strtolower ( $key ) ;
            if ($key == $fieldName)
            {
                return $def ;
            }
        }
        return array ( ) ;
    }

    /*
     * Save the modified definitions for a subpanel
     * Obtains the field definitions from a _REQUEST array, and merges them with the other fields from the original definitions
     * Uses the subpanel override mechanism from SubPanel to save them
     */
    function handleSave ()
    {
        $GLOBALS [ 'log' ]->debug ( "in ParserModifySubPanel->handleSave()" ) ;
        $subpanel = new SubPanel ( $this->moduleName, 'fab4', $this->subPanelName, $this->panel ) ;

        $newFields = array ( ) ;
        foreach ( $this->listViewDefs as $name => $field )
        {
            if (! isset ( $field [ 'usage' ] ) || $field [ 'usage' ] != 'query_only')
            {
                $existingFields [ $name ] = $field ;

            } else
            {
                $newFields [ $name ] = $field ;
            }
        }

        // Loop through all of the fields defined in the 'Default' group of the ListView data in $_REQUEST
        // Replace the field specification in the originalListViewDef with this new updated specification
        foreach ( $_REQUEST [ 'group_0' ] as $field )
        {
            if (! empty ( $this->originalListViewDefs [ $field ] ))
            {
                $newFields [ $field ] = $this->originalListViewDefs [ $field ] ;
            } else
            {

                $vname = '' ;
                if (isset ( $this->panel->template_instance->field_defs [ $field ] ))
                {
                    $vname = $this->panel->template_instance->field_defs [ $field ] [ 'vname' ] ;
                }
                if ($this->subPanelParentModule != null
                    && (isset($this->subPanelParentModule->field_defs[$field])
                        && ($this->subPanelParentModule->field_defs[$field]['type'] == 'bool'
                            || (isset($this->subPanelParentModule->field_defs[$field]['custom_type'])
                                && $this->subPanelParentModule->field_defs[$field]['custom_type'] == 'bool')))) {
                    $newFields [ $field ] = array ( 'name' => $field , 'vname' => $vname , 'widget_type' => 'checkbox' ) ;
                } else
                {
                    $newFields [ $field ] = array ( 'name' => $field , 'vname' => $vname ) ;
                }
            }

            // Now set the field width if specified in the $_REQUEST data
            if (isset ( $_REQUEST [ strtolower ( $field ) . 'width' ] ))
            {
                $width = substr ( $_REQUEST [ strtolower ( $field ) . 'width' ], 6, 3 ) ;
                if (strpos ( $width, "%" ) != false)
                {
                    $width = substr ( $width, 0, 2 ) ;
                }
                if ($width < 101 && $width > 0)
                {
                    $newFields [ $field ] [ 'width' ] = $width ;
                }
            } else if (isset ( $this->listViewDefs [ $field ] [ 'width' ] ))
            {
                $newFields [ $field ] [ 'width' ] = $this->listViewDefs [ $field ] [ 'width' ] ;
            }
        }
        $subpanel->saveSubPanelDefOverride ( $this->panel, 'list_fields', $newFields ) ;

    }

}
