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

// $Id: SugarWidgetField.php 57035 2010-06-19 01:58:47Z kjing $



class SugarWidgetField extends SugarWidget {
    public function display(array $layout_def)
    {
		//print $layout_def['start_link_wrapper']."===";
		$context = $this->layout_manager->getAttribute('context'); //_ppd($context);
		$func_name = 'display'.$context;

		if (!empty ($context) && method_exists($this, $func_name)) {
			return $this-> $func_name ($layout_def);
		} else {
			return 'display not found:'.$func_name;
		}
	}

	function _get_column_alias($layout_def) {
		$alias_arr = array ();

		if (!empty ($layout_def['name']) && $layout_def['name'] == 'count') {
			return 'count';
		}

		if (!empty ($layout_def['table_alias'])) {
			array_push($alias_arr, $layout_def['table_alias']);
		}

		if (!empty ($layout_def['name'])) {
			array_push($alias_arr, $layout_def['name']);
		}

		return $this->getTruncatedColumnAlias(implode("_", $alias_arr));
	}

	function & displayDetailLabel(& $layout_def) {

		return '';
	}

	function & displayDetail($layout_def) {

		return '';
	}
	
	function displayHeaderCellPlain($layout_def) 
	{
		if (!empty ($layout_def['label'])) {
			return $layout_def['label'];
		}
		if (!empty ($layout_def['vname'])) {
			return translate($layout_def['vname'], $this->layout_manager->getAttribute('module_name'));
		}
		return '';
	}

	function displayHeaderCell($layout_def) {
		$module_name = $this->layout_manager->getAttribute('module_name');

		$this->local_current_module = $_REQUEST['module'];
		$this->is_dynamic = true;
		// don't show sort links if name isn't defined
		if ((empty ($layout_def['name']) || (isset ($layout_def['sortable']) && !$layout_def['sortable']))
        && !empty ($layout_def['label'])) {
			return $layout_def['label'];
		}
		if (isset ($layout_def['sortable']) && !$layout_def['sortable']) {
			return $this->displayHeaderCellPlain($layout_def);
		}

		$header_cell_text = $this->displayHeaderCellPlain($layout_def);

		$subpanel_module = $layout_def['subpanel_module'];
		$html_var = $subpanel_module . "_CELL";
        $listView = new ListView();
		if (empty ($this->base_URL)) {
            $this->base_URL = $listView->getBaseURL($html_var);
			$split_url = explode('&to_pdf=true&action=SubPanelViewer&subpanel=', $this->base_URL);
			$this->base_URL = $split_url[0];
			$this->base_URL .= '&inline=true&to_pdf=true&action=SubPanelViewer&subpanel=';
		}
		$sort_by_name = $layout_def['name'];
		if (isset ($layout_def['sort_by'])) {
			$sort_by_name = $layout_def['sort_by'];
		}

        $sort_by = $listView->getSessionVariableName($html_var, "ORDER_BY").'='.$sort_by_name;

		$start = (empty ($layout_def['start_link_wrapper'])) ? '' : $layout_def['start_link_wrapper'];
		$end = (empty ($layout_def['end_link_wrapper'])) ? '' : $layout_def['end_link_wrapper'];

		$header_cell = "<a class=\"listViewThLinkS1\" href=\"".$start.$this->base_URL.$subpanel_module.'&'.$sort_by.$end."\">";
		$header_cell .= $header_cell_text;

		$imgArrow = '';

		if (isset ($layout_def['sort'])) {
			$imgArrow = $layout_def['sort'];
		}

		$arrow_start = ListView::getArrowUpDownStart($imgArrow);
		$arrow_end = ListView::getArrowUpDownEnd($imgArrow);
		$header_cell .= " ".$arrow_start.$arrow_end."</a>";

		return $header_cell;

	}

	function displayList($layout_def) {
		return $this->displayListPlain($layout_def);
	}

	function displayListPlain($layout_def) {
		$value= $this->_get_list_value($layout_def);
		if (isset($layout_def['widget_type']) && $layout_def['widget_type'] =='checkbox') {
			if ($value != '' &&  ($value == 'on' || intval($value) == 1 || $value == 'yes'))
			{
				return "<input name='checkbox_display' class='checkbox' type='checkbox' disabled='true' checked>";
			}
			return "<input name='checkbox_display' class='checkbox' type='checkbox' disabled='true'>";
		}
		return $value;
	}

	function _get_list_value(& $layout_def) 
	{
		$key = '';
		if ( isset($layout_def['varname']) ) {
		    $key = strtoupper($layout_def['varname']);
		} 
		else {
			$key = strtoupper($this->_get_column_alias($layout_def));
		}

		if ( isset($layout_def['fields'][$key]) ) {
			return $layout_def['fields'][$key];
		}
		
		return '';
	}

	function & displayEditLabel($layout_def) {
		return '';
	}

	function & displayEdit($layout_def) {
		return '';
	}

	function & displaySearchLabel($layout_def) {
		return '';
	}

	function & displaySearch($layout_def) {
		return '';
	}

	function displayInput($layout_def) {
		return ' -- Not Implemented --';
	}

    function getVardef($layout_def) {
        $myName = $layout_def['column_key'];
        $vardef = $this->layout_manager->defs['reporter']->all_fields[$myName];

        if ( !isset($vardef) ) {
            // No vardef, return an empty array
            return array();
        } else {
            return $vardef;
        }
    }
}
