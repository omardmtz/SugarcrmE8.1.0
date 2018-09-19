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

// $Id: SubPanelDefinitions.php 56966 2010-06-15 18:20:24Z dwheeler $


//input
//	module directory
//constructor
//	open the layout_definitions file.
//
/**
 * Subpanel implementation
 * @api
 */
class aSubPanel
{

	var $name ;
	var $_instance_properties ;

	var $mod_strings ;
	var $panel_definition ;
	var $sub_subpanels ;
	var $parent_bean ;

    /**
     * Can we display this subpanel?
     *
     * This is set after it loads the def's for the subpanel.  If there are no beans to display in the collection
     * we don't want to display this as it will just throw errors.
     *
     * @var bool
     */
    var $canDisplay = true;

	//module's table name and column fields.
	var $table_name ;
	var $db_fields ;
	var $bean_name ;
	var $template_instance ;

    public function __construct($name, $instance_properties, $parent_bean, $reload = false, $original_only = false, $forApi = false)
	{

		$this->_instance_properties = $instance_properties ;
		$this->name = $name ;
		$this->parent_bean = $parent_bean ;

		//set language
		global $current_language ;
		if (! isset ( $parent_bean->mbvardefs ))
		{
			$mod_strings = return_module_language ( $current_language, $parent_bean->module_dir ) ;
		}
		$this->mod_strings = $mod_strings ;

        if ($this->isCollection ())
		{
			$this->canDisplay = $this->load_sub_subpanels () ; //load sub-panel definition.
		} else
		{
			if (!SugarAutoLoader::existing('modules/' . $this->_instance_properties [ 'module' ])){
			    $GLOBALS['log']->fatal("Directory for {$this->_instance_properties [ 'module' ]} does not exist!");
			}
			$def_path = array('modules/' . $this->_instance_properties [ 'module' ] . '/metadata/subpanels/' . $this->_instance_properties [ 'subpanel_name' ] . '.php');

            if(!$original_only) {
                $def_path[] = 'custom/'.$def_path[0];
                if(isset ($this->_instance_properties['override_subpanel_name'])) {
                    $def_path[] = 'custom/modules/' . $this->_instance_properties [ 'module' ] . '/metadata/subpanels/' . $this->_instance_properties [ 'override_subpanel_name' ] . '.php';
                }
            }
            $loaded = false;
            foreach(SugarAutoLoader::existing($def_path) as $file) {
                require $file;
                $loaded = true;
			}

            if (!$loaded) {
                $defaultSubpanelFile = "modules/{$this->_instance_properties['module']}/metadata/subpanels/default.php";
                if (!file_exists($defaultSubpanelFile)) {
                    $GLOBALS['log']->fatal("Failed to load original or custom subpanel data for $name in ".join(DIRECTORY_SEPARATOR, $def_path));
                    $this->canDisplay = false;
                } else {
                    require $defaultSubpanelFile;
                }
            }

            // load module info from the module's bean file
            $this->load_module_info();

            // check that the loaded subpanel definition includes a $subpanel_layout section - some, such as
            // projecttasks/default do not...
            $this->panel_definition = array();
            if (isset($subpanel_layout) && is_array($subpanel_layout)) {
                $this->set_panel_definition($subpanel_layout);

                if (!$forApi) {
                    SugarACL::listFilter(
                        $this->_instance_properties['module'],
                        $this->panel_definition['list_fields'],
                        array("owner_override" => true)
                    );
                }
			}
		}

	}

    /**
     * is the sub panel default hidden?
     *
     * @return bool
     */
    public function isDefaultHidden()
    {
        if(isset($this->_instance_properties['default_hidden']) && $this->_instance_properties['default_hidden'] == true) {
            return true;
        }

        return false;
    }


	function distinct_query ()
	{
		if (isset ( $this->_instance_properties [ 'get_distinct_data' ] ))
		{
			return !empty($this->_instance_properties['get_distinct_data']) ? true : false;
		}
		return false ;
	}

	//return the translated header value.
	function get_title ()
	{
		if (empty ( $this->mod_strings [ $this->_instance_properties [ 'title_key' ] ] ))
		{
			return translate ( $this->_instance_properties [ 'title_key' ], $this->_instance_properties [ 'module' ] ) ;
		}
		return $this->mod_strings [ $this->_instance_properties [ 'title_key' ] ] ;
	}

	//return the definition of buttons. looks for buttons in 2 locations.
	function get_buttons ()
	{
		global $sugar_config;

		if (isset ( $this->_instance_properties [ 'top_buttons' ] ))
		{
			//this will happen only in the case of sub-panels with multiple sources(activities).
			$buttons = $this->_instance_properties [ 'top_buttons' ] ;
		} else
		{
			$buttons = $this->panel_definition [ 'top_buttons' ] ;
		}

		// permissions. hide SubPanelTopComposeEmailButton from activities if email module is disabled.
		//only email is  being tested becuase other submodules in activites/history such as notes, tasks, meetings and calls cannot be disabled.
		//as of today these are the only 2 sub-panels that use the union clause.
		$mod_name = $this->get_module_name () ;
		if ($mod_name == 'Activities' || $mod_name == 'History')
		{
			global $modListHeader ;
			global $modules_exempt_from_availability_check ;

            // Bug 58087 - Compose Email in activities sub panel for offline client
            // Need to add logic to check for offline client since the Compose Email
            // action was looking at the module list and Emails are in the exempt list.
			if (
                (isset($modListHeader) && (!(array_key_exists('Emails', $modListHeader) || array_key_exists('Emails', $modules_exempt_from_availability_check))))
            )
			{
				foreach ( $buttons as $key => $button )
				{
					foreach ( $button as $property => $value )
					{
						if ($value === 'SubPanelTopComposeEmailButton' || $value === 'SubPanelTopArchiveEmailButton')
						{
							//remove this button from the array.
							unset ( $buttons [ $key ] ) ;
						}
					}
				}
			}
		}

		return $buttons ;
	}


    /**
     * Load the Sub-Panel objects if it can from the metadata files.
     *
     * call this function for sub-panels that have unions.
     *
     * @todo Decide whether to make all activities modules exempt from visibility
     *       checking or not. As of 6.4.5, Notes was no longer exempt but the
     *       other activities modules were which is causing causing rendering of
     *       subpanels for these modules even when these modules should not be shown.
     *
     * @return bool         True by default if the subpanel was loaded.  Will return false if none in the collection are
     *                      allowed by the current user.
     */
	function load_sub_subpanels ()
	{
        //by default all the activities modules are exempt, so hiding them won't
        //affect their appearance unless the 'activity' subpanel itself is hidden.
        //add email to the list temporarily so it is not affected in activities subpanel
        global $modules_exempt_from_availability_check ;
        $modules_exempt_from_availability_check['Emails'] = 'Emails';

		$listFieldMap = array();

		if (empty ( $this->sub_subpanels ))
		{
            // Bug 57699 - Notes subpanel missing from Calls module after upgrade
            // Originally caused by the fix for Bug 49439
            // Get the shown subpanel module list
            $subPanelDefinitions = new SubPanelDefinitions($this->parent_bean);

            // Bug 58089 - History sub panel doesn't show any record.
            // Rather than check ALL subpanels, we only need to really check to
            // see if the module(s) we are checking are not hidden.
            $hiddenSubPanels = $subPanelDefinitions->get_hidden_subpanels();

			$panels = $this->get_inst_prop_value ( 'collection_list' ) ;
			foreach ( $panels as $panel => $properties )
			{
                // Lowercase the collection module to check against the subpanel list
                $lcModule = strtolower($properties['module']);

                // Add a check for module subpanel visibility. If hidden, but exempt, pass it
                if ((is_array($hiddenSubPanels) && !in_array($lcModule, $hiddenSubPanels)) || isset($modules_exempt_from_availability_check[$properties['module']]))
				{
					$this->sub_subpanels [ $panel ] = new aSubPanel ( $panel, $properties, $this->parent_bean ) ;
				}
			}

            // if it's empty just dump out as there is nothing to process.
            if(empty($this->sub_subpanels)) return false;
			//Sync displayed list fields across the subpanels
			$display_fields = $this->getDisplayFieldsFromCollection($this->sub_subpanels);
		 	$query_fields = array();
			foreach ( $this->sub_subpanels as $key => $subpanel )
			{
				$list_fields = $subpanel->get_list_fields();
				$listFieldMap[$key] = array();
				$index = 0;
				foreach($list_fields as $field => $def)
				{
					if (isset($def['vname']) && isset($def['width']))
					{
						$index++;
						if(!empty($def['alias']))
							$listFieldMap[$key][$def['alias']] = $field;
						else
							$listFieldMap[$key][$field] = $field;
						if (!isset($display_fields[$def['vname']]))
						{
							if(sizeof($display_fields) > $index)
							{
								//Try to insert the new field in an order that makes sense
								$start = array_slice($display_fields, 0, $index);
								$end = array_slice($display_fields, $index);
								$display_fields = array_merge(
									$start,
									array($def['vname'] => array('name' => $field, 'vname' => $def['vname'], 'width' => $def['width'] )),
									$end
								);
							} else
							{
								$display_fields[$def['vname']] = array(
									'name' => empty($def['alias']) ? $field : $def['alias'],
									'vname' => $def['vname'],
									'width' => $def['width'],
								);
							}
						}
					} else {
						$query_fields[$field] = $def;
					}
				}
			}
			foreach ( $this->sub_subpanels as $key => $subpanel )
			{
				$list_fields = array();
				foreach($display_fields as $vname => $def)
				{
					$field = $def['name'];
					$list_key = isset($listFieldMap[$key][$field]) ? $listFieldMap[$key][$field] : $field;

					if (isset($subpanel->panel_definition['list_fields'][$field]))
					{
						$list_fields[$field] = $subpanel->panel_definition['list_fields'][$field];
					}
				    else if ($list_key != $field && isset($subpanel->panel_definition['list_fields'][$list_key]))
                    {
                        $list_fields[$list_key] = $subpanel->panel_definition['list_fields'][$list_key];

                    }
					else {
						$list_fields[$field] = $display_fields[$vname];
					}
				}
				foreach($query_fields as $field => $def)
				{
					if (isset($subpanel->panel_definition['list_fields'][$field]))
					{
						$list_fields[$field] = $subpanel->panel_definition['list_fields'][$field];
					}
					else {
						$list_fields[$field] = $def;
					}
				}
				$subpanel->panel_definition['list_fields'] = $list_fields;
			}
		}

        return true;
	}

	protected function getDisplayFieldsFromCollection($sub_subpanels)
	{
		$display_fields = array();
		foreach ($sub_subpanels as $key => $subpanel )
		{
			$list_fields = $subpanel->get_list_fields();
			$index = 0;
			foreach($list_fields as $field => $def)
			{
				if (isset($def['vname']) && isset($def['width']))
				{
					$index++;
					if (!isset($display_fields[$def['vname']]))
					{
						if(sizeof($display_fields) > $index)
						{
							//Try to insert the new field in an order that makes sense
							$start = array_slice($display_fields, 0, $index);
							$end = array_slice($display_fields, $index);
							$display_fields = array_merge(
								$start,
								array($def['vname'] => array('name' => $field, 'vname' => $def['vname'], 'width' => $def['width'] )),
								$end
							);
						} else
						{
							$display_fields[$def['vname']] = array(
								'name' => $field,
								'vname' => $def['vname'],
								'width' => $def['width'],
							);
						}
					}
				}
			}
		}
	}

	function isDatasourceFunction ()
	{
		if (strpos ( $this->get_inst_prop_value ( 'get_subpanel_data' ), 'function' ) === false)
		{
			return false ;
		}
		return true ;
	}

    /**
     * Test to see if the sub panels defs contain a collection
     *
     * @return bool
     */
	function isCollection ()
	{
		return ($this->get_inst_prop_value ( 'type' ) == 'collection');
	}

	//get value of a property defined at the panel instance level.
	function get_inst_prop_value ( $name )
	{
		return isset($this->_instance_properties[$name]) ? $this->_instance_properties [ $name ] : null;
	}
	//get value of a property defined at the panel definition level.
	function get_def_prop_value ( $name )
	{
		if (isset ( $this->panel_definition [ $name ] ))
		{
			return $this->panel_definition [ $name ] ;
		} else
		{
			return null ;
		}
	}

	//if datasource is of the type function then return the function name
	//else return the value as is.
	function get_function_parameters ()
	{
		$parameters = array ( ) ;
		if ($this->isDatasourceFunction ())
		{
			$parameters = $this->get_inst_prop_value ( 'function_parameters' ) ;
		}
		return $parameters ;
	}

	function get_data_source_name ( $check_set_subpanel_data = false )
	{
		$prop_value = null ;
		if ($check_set_subpanel_data)
		{
			$prop_value = $this->get_inst_prop_value ( 'set_subpanel_data' ) ;
		}
		if (! empty ( $prop_value ))
		{
			return $prop_value ;
		} else
		{
			//fall back to default behavior.
		}
		if ($this->isDatasourceFunction ())
		{
			return (substr_replace ( $this->get_inst_prop_value ( 'get_subpanel_data' ), '', 0, 9 )) ;
		} else
		{
			return $this->get_inst_prop_value ( 'get_subpanel_data' ) ;
		}
	}

	//returns the where clause for the query.
	function get_where ()
	{
		return $this->get_def_prop_value ( 'where' ) ;
	}

	function is_fill_in_additional_fields ()
	{
		// do both. inst_prop returns values from metadata/subpaneldefs.php and def_prop returns from subpanel/default.php
		$temp = $this->get_inst_prop_value ( 'fill_in_additional_fields' ) || $this->get_def_prop_value ( 'fill_in_additional_fields' ) ;
		return $temp ;
	}

	function get_list_fields ()
	{
		if (isset ( $this->panel_definition [ 'list_fields' ] ))
		{
			return $this->panel_definition [ 'list_fields' ] ;
		} else
		{
			return array ( ) ;
		}
	}

	function get_module_name ()
	{
		return $this->get_inst_prop_value ( 'module' ) ;
	}

	function get_name ()
	{
		return $this->name ;
	}

	//load subpanel module's table name and column fields.
	function load_module_info ()
	{
		global $beanList ;
		global $beanFiles ;

		$module_name = $this->get_module_name () ;
		if (! empty ( $module_name ))
		{
		    $this->template_instance = BeanFactory::newBean($module_name);
		    if(empty($this->template_instance)) {
		        $GLOBALS['log']->fatal("Bad module name for subpanel: $module_name");
		        return null;
		    }
			$this->template_instance->force_load_details = true ;
			$this->table_name = $this->template_instance->table_name ;
		}
	}
	//this function is to be used only with sub-panels that are based
	//on collections.
	function get_header_panel_def ()
	{
		if (! empty ( $this->sub_subpanels ))
		{
			if (! empty ( $this->_instance_properties [ 'header_definition_from_subpanel' ] ) && ! empty ( $this->sub_subpanels [ $this->_instance_properties [ 'header_definition_from_subpanel' ] ] ))
			{
				return $this->sub_subpanels [ $this->_instance_properties [ 'header_definition_from_subpanel' ] ] ;
			} else
			{
				reset ( $this->sub_subpanels ) ;
				return current ( $this->sub_subpanels ) ;
			}
		}
		return null ;
	}

	/**
	 * Returns an array of current properties of the class.
	 * It will simply give the class name for instances of classes.
	 */
	function _to_array ()
	{
		return array ( '_instance_properties' => $this->_instance_properties , 'db_fields' => $this->db_fields , 'mod_strings' => $this->mod_strings , 'name' => $this->name , 'panel_definition' => $this->panel_definition , 'parent_bean' => get_class ( $this->parent_bean ) , 'sub_subpanels' => $this->sub_subpanels , 'table_name' => $this->table_name , 'template_instance' => get_class ( $this->template_instance ) ) ;
	}

    /**
     * Sets definition of the subpanel
     *
     * @param array $definition
     */
    protected function set_panel_definition(array $definition)
    {
        // Check if there is a list_field defs, done in two line to make lines
        // more readable
        $hasListFields = isset($definition['list_fields'])
                         && is_array($definition['list_fields']);

        // Check if there is a bean, also done in two lines for readability
        $hasBean = isset($this->template_instance)
                   && $this->template_instance instanceof SugarBean;

        // Now call expand_list_fields if we have what we need
        if ($hasListFields && $hasBean) {
            $definition['list_fields'] = $this->expand_list_fields(
                $this->template_instance,
                $definition['list_fields']
            );
        }
        $this->panel_definition = $definition;
    }

    /**
     * Expands list fields by adding those ones which existing fields depend on.
     *
     * @param  SugarBean $bean   Instance of SugarBean which is displayed
     *                           in the subpanel
     * @param  array     $fields Definition if list fields
     *
     * @return array             Expanded definition
     */
    public function expand_list_fields(SugarBean $bean, array $fields)
    {
        $expanded = array();
        foreach (array_keys($fields) as $name) {
            if (!empty($bean->field_defs[$name]['dependency'])) {
                $expr = $bean->field_defs[$name]['dependency'];
                $extracted = Parser::getFieldsFromExpression($expr, $bean->field_defs);
                $extracted = array_flip($extracted);

                // remove fields that do not exist in field definitions
                $expanded += array_intersect_key($extracted, $bean->field_defs);

                // make the dependent field non-sortable since availability of the field
                // is calculated after the data is retrieved from database
                $fields[$name]['sortable'] = false;
            }

            // get currency symbol if this is a currency field
            if (isset($bean->field_defs[$name]['type']) && $bean->field_defs[$name]['type'] == 'currency' && !empty($bean->field_defs['currency_id'])) {
                $expanded['currency_id'] = $bean->field_defs['currency_id'];
                $fields[$name]['sortable'] = false;
            }
        }

        // ignore dependencies that already present in the list
        $expanded = array_diff_key($expanded, $fields);

        foreach (array_keys($expanded) as $name) {
            $fields[$name] = array(
                'name'  => $name,
                'usage' => 'query_only',
            );
        }

        return $fields;
    }
}
;

class SubPanelDefinitions
{

	var $_focus ;
	var $_visible_tabs_array ;
	var $panels ;
	var $layout_defs ;
	var $platform ;
    static $refreshHiddenSubpanels = false;

	/**
	 * Enter description here...
	 *
	 * @param BEAN $focus - this is the bean you want to get the data from
	 * @param STRING $layout_def_key - if you wish to use a layout_def defined in the default metadata/subpaneldefs.php that is not keyed off of $bean->module_dir pass in the key here
	 * @param ARRAY $layout_def_override - if you wish to override the default loaded layout defs you pass them in here.
	 * @return SubPanelDefinitions
	 */
    public function __construct($focus, $layout_def_key = '', $layout_def_override = '', $platform = null)
	{
		$this->_focus = $focus ;
		$this->platform = $platform;
		if (! empty ( $layout_def_override ))
		{
			$this->layout_defs = $layout_def_override ;

		} else
		{
			$this->open_layout_defs ( false, $layout_def_key ) ;
		}
	}

    /**
     * This function returns an ordered list of all "tabs", actually subpanels, for this module
     * The source list is obtained from the subpanel layout contained in the layout_defs for this module,
     * found either in the modules metadata/subpaneldefs.php file, or in the modules custom/.../Ext/Layoutdefs/layoutdefs.ext.php file
     * and filtered through an ACL check.
     * Note that the keys for the resulting array of tabs are in practice the name of the underlying source relationship for the subpanel
     * So for example, the key for a custom module's subpanel with Accounts might be 'one_one_accounts', as generated by the Studio Relationship Editor
     * Although OOB module subpanels have keys such as 'accounts', which might on the face of it appear to be a reference to the related module, in fact 'accounts' is still the relationship name
     *
     * @param bool optional - include the subpanel title label in the return array (false)
     * @param bool $filter when true, the tabs are filtered by the current user's ACLs
     *
     * @return array All tabs that pass an ACL check
     */
    function get_available_tabs($FromGetModuleSubpanels = false, $filter = true)
    {
        global $modListHeader;
        global $modules_exempt_from_availability_check;

        if (isset($this->_visible_tabs_array)) {
            return $this->_visible_tabs_array;
        }

        if (empty($modListHeader)) {
            $modListHeader = query_module_access_list($GLOBALS['current_user']);
        }

        $this->_visible_tabs_array = array(); // bug 16820 - make sure this is an array for the later ksort
        if (isset ($this->layout_defs ['subpanel_setup'])) { // bug 17434 - belts-and-braces - check that we have some subpanels first
            //retrieve list of hidden subpanels
            $hidden_panels = $this->get_hidden_subpanels();

            //activities is a special use case in that if it is hidden,
            //then the history tab should be hidden too.
            if (!empty($hidden_panels) && is_array($hidden_panels) && in_array('activities', $hidden_panels)) {
                //add history to list hidden_panels
                $hidden_panels['history'] = 'history';
            }

            foreach ($this->layout_defs ['subpanel_setup'] as $key => $values_array) {
                if (empty($values_array['module'])) {
                    continue;
                }
                //exclude if this subpanel is hidden from admin screens
                $module = $key;
                if (isset($values_array['module'])) {
                    $module = strtolower($values_array['module']);
                }
                if ($hidden_panels && is_array($hidden_panels) && (in_array($module, $hidden_panels) || array_key_exists($module, $hidden_panels))) {
                    //this panel is hidden, skip it
                    continue;
                }

                // make sure the module attribute is set, else none of this works...
                if (!isset($values_array ['module'])) {
                    $GLOBALS['log']->debug("SubPanelDefinitions->get_available_tabs(): no module defined in subpaneldefs for '$key' =>"
                                         . var_export($values_array, true) . " - ingoring subpanel defintion");
                    continue;
                }

                //check permissions.
                $exempt = array_key_exists($values_array ['module'], $modules_exempt_from_availability_check);
                $ok = $exempt || !$filter || (
                    (!ACLController::moduleSupportsACL($values_array ['module']) ||
                        ACLController::checkAccess($values_array ['module'], 'list', true)
                    )
                );

                $GLOBALS ['log']->debug("SubPanelDefinitions->get_available_tabs(): " . $key . "= " . ($exempt ? "exempt " : "not exempt " . ($ok ? " ACL OK" : "")));

                if ($ok) {
                    while (!empty ($this->_visible_tabs_array [$values_array ['order']])) {
                        $values_array ['order']++;
                    }

                    $this->_visible_tabs_array [$values_array ['order']] = ($FromGetModuleSubpanels) ? array($key => $values_array['title_key']) : $key;
                }
            }
        }

        ksort($this->_visible_tabs_array);

        return $this->_visible_tabs_array;
    }

	/**
	 * Load the definition of the a sub-panel.
	 * Also the sub-panel is added to an array of sub-panels.
	 * use of reload has been deprecated, since the subpanel is initialized every time.
     *
     * @param string $name              The name of the sub-panel to reload
     * @param boolean $reload           Reload the sub-panel (unused)
     * @param boolean $original_only    Only load the original sub-panel and no custom ones
     * @return boolean|aSubPanel        Returns aSubPanel object or boolean false if one is not found or it can't be
     *      displayed due to ACL reasons.
	 */
	function load_subpanel ( $name , $reload = false , $original_only = false, $forApi = false )
	{
        // mobile doesn't have subpanel def
        if ($this->platform == 'mobile') {
            return false;
        }

        $defs = $this->layout_defs['subpanel_setup'][strtolower($name)];

        if(empty($defs)) {
            $GLOBALS['log']->fatal("Subpanel " . $name . "does not exist!");
            return false;
        }
        if (!is_dir('modules/' . $defs['module'])) {
            return false;
        }

        $subpanel = new aSubPanel($name, $defs, $this->_focus, $reload, $original_only, $forApi);

        // only return the subpanel object if we can display it.
        if($subpanel->canDisplay == true) {
            return $subpanel;
        }

        // by default return false so we don't show anything if it's not required.
        return false;
	}

    /**
     * Load the layout def file and associate the definition with a variable in the file.
     */
    function open_layout_defs($reload = false, $layout_def_key = '', $original_only = false)
    {

        $mm = MetaDataManager::getManager();

        $layout_defs [$this->_focus->module_dir] = array();
        $layout_defs [$layout_def_key] = array();
        $def_path = array();
        if (empty ($this->layout_defs) || $reload || (!empty ($layout_def_key) && !isset ($layout_defs [$layout_def_key]))) {
            if (!$original_only) {
                if (isModuleBWC($this->_focus->module_dir)) {
                    $def_path = array('modules/' . $this->_focus->module_dir . '/metadata/'.($this->platform == 'mobile' ? 'wireless.' : '').'subpaneldefs.php');
                    $def_path[] = SugarAutoLoader::loadExtension($this->platform == 'mobile' ? 'wireless_subpanels' : 'layoutdefs', $this->_focus->module_dir);
                    foreach (SugarAutoLoader::existing($def_path) as $file) {
                        require $file;
                    }
                } else {
                    $viewdefs = $mm->getModuleLayouts($this->_focus->module_dir);
                    $viewdefs = !empty($viewdefs['subpanels']['meta']['components']) ? $viewdefs['subpanels']['meta']['components'] : array();
                }
            }


            $layoutDefsKey = !empty($layout_def_key) ? $layout_def_key : $this->_focus->module_dir;
            // convert sidecar subpanels to the array the SubpanelDefinitions are looking for
            if ($this->_focus instanceof SugarBean && !isModuleBWC($this->_focus->module_dir) && isset($viewdefs)) {
                $metaDataConverter = new MetaDataConverter();
                $layout_defs[$layoutDefsKey] = $metaDataConverter->toLegacySubpanelLayoutDefs($viewdefs, $this->_focus);
            }

            $this->layout_defs = $layout_defs[$layoutDefsKey];
        }

    }

	/**
	 * Removes a tab from the list of loaded tabs.
	 * Returns true if successful, false otherwise.
	 * Hint: Used by Campaign's DetailView.
	 */
	function exclude_tab ( $tab_name )
	{
		$result = false ;
		//unset layout definition
		if (! empty ( $this->layout_defs [ 'subpanel_setup' ] [ $tab_name ] ))
		{
			unset ( $this->layout_defs [ 'subpanel_setup' ] [ $tab_name ] ) ;
		}
		//unset instance from _visible_tab_array
		if (! empty ( $this->_visible_tabs_array ))
		{
			$key = array_search ( $tab_name, $this->_visible_tabs_array ) ;
			if ($key !== false)
			{
				unset ( $this->_visible_tabs_array [ $key ] ) ;
			}
		}
		return $result ;
	}


	/**
	 * return all available subpanels that belong to the list of tab modules.  You can optionally return all
	 * available subpanels, and also optionally group by module (prepends the key with the bean class name).
	 */
        public static function get_all_subpanels($return_tab_modules_only = true, $group_by_module = false, $filter = true)
	{
		global $moduleList;

		//use tab controller function to get module list with named keys
		$modules_to_check = TabController::get_key_array($moduleList);

		//change case to match subpanel processing later on
		$modules_to_check = array_change_key_case($modules_to_check);
        // Append on the CampaignLog module, because that is where the subpanels point, not directly to Campaigns
        $modules_to_check['campaignlog'] = "CampaignLog";

        // Get hidden subpanels to make sure they are not included
        $hidden = self::get_hidden_subpanels();

        $spd = '';
		$spd_arr = array();
		//iterate through modules and build subpanel array
		foreach($modules_to_check as $mod_name){

		    $bean_class = BeanFactory::newBean($mod_name);
            if(empty($bean_class)) continue;

			//create new subpanel definition instance and get list of tabs
			$spd = new SubPanelDefinitions($bean_class) ;
			$sub_tabs = $spd->get_available_tabs(false, $filter);

			//add each subpanel to array of total subpanles
			foreach( $sub_tabs as $panel_key){
				$panel_key = strtolower($panel_key);
                $panel_module = $panel_key;
                if ( isset($spd->layout_defs['subpanel_setup'][$panel_key]['module']) )
                    $panel_module = strtolower($spd->layout_defs['subpanel_setup'][$panel_key]['module']);
                //if module_only flag is set, only if it is also in module array
				if($return_tab_modules_only && !array_key_exists($panel_module, $modules_to_check)) continue;
				$panel_key_name = $panel_module;

				//group_by_key_name is set to true, then array will hold an entry for each
				//subpanel, with the module name prepended in the key
				if($group_by_module) $panel_key_name = $class.'_'.$panel_key_name;
				//add panel name to subpanel array
				$spd_arr[$panel_key_name] = $panel_module;
			}
		}
		return 	$spd_arr;
	}

	/*
	 * save array of hidden panels to mysettings category in config table
	 */
    public static function set_hidden_subpanels($panels)
    {
		$administration = BeanFactory::newBean('Administration');
		$serialized = base64_encode(serialize($panels));
		$administration->saveSetting('MySettings', 'hide_subpanels', $serialized);
        // Allow the hidden subpanel cache to refresh
        self::$refreshHiddenSubpanels = true;
	}

	/*
	 * retrieve hidden subpanels
	 */
    public static function get_hidden_subpanels()
    {
		//create variable as static to minimize queries
		static $hidden_subpanels = null;

		// if the static value is not already cached, or explicitly directed to, then retrieve it.
		if($hidden_subpanels === null || self::$refreshHiddenSubpanels)
		{
            // Set hidden subpanels to an array. This allows an empty hidden
            // subpanel list to pass checks later. - rgonzalez
            $hidden_subpanels = array();

			//create Administration object and retrieve any settings for panels
			$administration = Administration::getSettings('MySettings');

			if(isset($administration->settings) && isset($administration->settings['MySettings_hide_subpanels'])){
				$hidden_subpanels = $administration->settings['MySettings_hide_subpanels'];
				$hidden_subpanels = trim($hidden_subpanels);

				//make sure serialized string is not empty
				if (!empty($hidden_subpanels)){
					//decode and unserialize to retrieve the array
					$hidden_subpanels = base64_decode($hidden_subpanels);
					$hidden_subpanels = unserialize($hidden_subpanels);

					//Ensure modules saved in the preferences exist.
					//get user preference
					//unserialize and add to array if not empty
					$pref_hidden = array();
					foreach($pref_hidden as $id => $pref_hidden_panel) {
						$hidden_subpanels[] = $pref_hidden_panel;
					}

                    self::$refreshHiddenSubpanels = false;
				}else{
					//no settings found, return empty
					return $hidden_subpanels;
				}
			}
			else
			{	//no settings found, return empty
				return $hidden_subpanels;
			}
		}

		return $hidden_subpanels;
	}

    /**
     * Allows refresh of the hidden subpanels list from outside of this class
     *
     * @param $bool
     */
    public static function setRefreshHiddenSubpanels($bool) {
        self::$refreshHiddenSubpanels = (bool) $bool;
    }
}
