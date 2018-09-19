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

//TODO move me out of generic

// $Id: SugarWidget.php 38393 2008-07-29 19:44:00Z Collin Lee $

/**
 * Generic Sugar widget
 * @api
 */
class SugarWidget
{
	var $layout_manager = null;
	var $widget_id;
    protected $form_value;
    protected $parent_bean;

    public function __construct(&$layout_manager)
	{
		$this->layout_manager = $layout_manager;
	}

    /**
     * Display widget
     *
     * @param array $layout_def Layout definitions
     *
     * @return string
     */
    public function display(array $layout_def)
	{
		return 'display class undefined';
	}

	/**
	 * getSubpanelWidgetId
	 * This is a utility function to return a widget's unique id
	 * @return id String label of the widget's unique id
	 */
	public function getWidgetId() {
	   return $this->widget_id;
	}

	/**
	 * setSubpanelWidgetId
	 * This is a utility function to set the id for a widget
	 * @param id String value to set the widget's unique id
	 */
	public function setWidgetId($id='') {
		$this->widget_id = $id;
	}

    public function getDisplayName()
    {
        return $this->form_value;
    }
    function getParentBean()
    {
        return $this->parent_bean;
    }

    function setParentBean($parent_bean)
    {
        $this->parent_bean = $parent_bean;
    }
   /**
    * getTruncatedColumnAlias
    * This function ensures that a column alias is no more than 28 characters.  Should the column_name
    * argument exceed 28 charcters, it creates an alias using the first 22 characters of the column_name
    * plus an md5 of the first 6 characters of the lowercased column_name value.
    *
    */
    protected function getTruncatedColumnAlias($column_name)
    {
	  	if(empty($column_name) || !is_string($column_name) || strlen($column_name) < 28)
	  	{
	  	   return $column_name;
	  	}
	    return strtoupper(substr($column_name,0,22) . substr(md5(strtolower($column_name)), 0, 6));
    }

    /**
     * check was module hidden in the top navigation bar or as subpanels
     * @param string moduleName - name of module to chaeck e.g. Notes, Tasks
     * @return bool
     * @see Bug #55632 : Hiding Notes Module does not prevent creation of notes.
     */
    static public function isModuleHidden( $moduleName )
    {
        global $modules_exempt_from_availability_check;
        if(isset($modules_exempt_from_availability_check[$moduleName])) {
            return false;
        }

        $tabs = new TabController();
        if ( in_array(strtolower($moduleName), SubPanelDefinitions::get_hidden_subpanels()) )
        {
            return true;
        }

        return false;
    }
}

