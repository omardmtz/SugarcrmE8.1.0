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

require_once('include/json_config.php');

class ViewRetrieveSource extends ViewList {
    function display() {

        $source_id = $this->request->getValidInputRequest('source_id', 'Assert\ComponentName');

        if(empty($source_id)) {
           $GLOBALS['log']->error($GLOBALS['mod_strings']['ERROR_EMPTY_SOURCE_ID']);
           echo $GLOBALS['mod_strings']['ERROR_EMPTY_SOURCE'];
           return;
        }

        $record_id = $_REQUEST['record'];

        if(empty($record_id)) {
           $GLOBALS['log']->error($GLOBALS['mod_strings']['ERROR_EMPTY_RECORD_ID']);
           echo $GLOBALS['mod_strings']['ERROR_EMPTY_RECORD_ID'];
           return;
        }

        $merge_module = $_SESSION['merge_module'];
        if(empty($_SESSION['searchDefs'][$merge_module][$record_id][$source_id])) {
           $GLOBALS['log']->error("ERROR_NO_SEARCHDEFS_MAPPING");
           echo $GLOBALS['mod_strings']['ERROR_NO_SEARCHDEFS_MAPPING'];
           return;
        }

		$args = $_SESSION['searchDefs'][$merge_module][$record_id][$source_id];
		$source_instance = ConnectorFactory::getInstance($source_id);

        try {
          $beans = $source_instance->fillBeans($args, $merge_module);
        } catch(Exception $ex) {
          echo $ex->getMessage();
          return;
        }

    	if(empty($beans)) {
    	   echo '<br>'.$GLOBALS['mod_strings']['LBL_EMPTY_BEANS'];
		   return;
		}

        $json = getJSONobj();
        $json_data = array();
        foreach($beans as $id=>$record) {
            $json_data[$id] = $json->encode($record->get_list_view_array());
        }

        $this->ss->assign('source_id', $source_id);
        $this->ss->assign('json_data', $json_data);
		$this->ss->assign('DATA', $beans);

        //Setup listview to display
		$lv = new ListViewSmarty();
		if(is_array($beans)) {

			$displayColumns = $this->getDisplayColumns($source_instance, $beans);
            $this->ss->assign('displayColumns', $displayColumns);

			global $odd_bg, $even_bg, $hilite_bg, $click_bg;
			$this->ss->assign('bgHilite', $hilite_bg);
			$this->ss->assign('rowColor', array('oddListRow', 'evenListRow'));
			$this->ss->assign('bgColor', array($odd_bg, $even_bg));

			$this->ss->assign('module', $merge_module);
			echo $this->ss->fetch($this->getCustomFilePathIfExists('modules/Connectors/tpls/listview.tpl'));
		}
    }

    /**
     * getDisplayColumns
     * This is a private method to return a PHP array of display columns used for the list view.
     * It will first check in the Connector's custom directories for a listviewdefs.php file.
     * Then it will check the modules/Connector's directory to see if a listviewdefs.php file was
     * defined there.  If not, it will generate the array based on the fields mapped.
     *
     * @param source_instance The Connector source instance to retrieve the display columns for
     * @param beans The PHP array of filled SugarBean results
     * @return displayColumns A PHP array of display columns
     */
    private function getDisplayColumns($source_instance, $beans) {

    	$source_id = $_REQUEST['source_id'];

    	require_once('include/connectors/utils/ConnectorUtils.php');
    	$connector_strings = ConnectorUtils::getConnectorStrings($source_id);

    	$dir = str_replace('_','/',$source_id);
		$file = SugarAutoLoader::existingCustomOne("modules/Connectors/connectors/sources/{$dir}/listviewdefs.php");
		if($file) {
		    require $file;
		}

    	$displayColumns = array();
        $output_list = $source_instance->getModuleMapping($_SESSION['merge_module']);
		$output_list = !empty($output_list) ?  $output_list : $beans[0]->get_list_view_array();
        $sugar_bean_field_defs = $beans[0]->getFieldDefinitions();
        $connector_field_defs = $source_instance->getFieldDefs();

		//There was a listviewdefs.php file specified, now we have to figure out the mapping based on
		//the Connector fields specified.  In the listviewdefs.php files, the key is the Connector's
		//vardef entry key.   We are allowing this to be case-insensitive.

        //Use listviewdefs.php file if there is one
        if(isset($listViewDefs[$source_id])) {
        	foreach($listViewDefs[$source_id] as $key=>$listDef) {
                $check_key = strtolower($key);
        		if(isset($output_list[$check_key])) {
        		   $width = isset($listDef['width']) ? $listDef['width'] : round((95 / count($listViewDefs)), 1);

        		   //Use the Connector's field label, but fall back on the bean's label if we need to
        		   $label = isset($connector_field_defs[$key]['vname']) ? $connector_strings[$connector_field_defs[$key]['vname']] : $sugar_bean_field_defs[$output_list[$check_key]]['vname'];
        		   $displayColumns[$output_list[$check_key]] = array('width'=>$width, 'label'=>$label);
        		}
        	}
        } else {
		    $width = round((95 / count($output_list)), 1);
			foreach($output_list as $key=>$value) {

				if(isset($connector_field_defs[$key]['vname'])) {
				  $label = $connector_strings[$connector_field_defs[$key]['vname']];
				} else if(isset($sugar_bean_field_defs[$value]['vname'])){
				  $label = $sugar_bean_field_defs[$value]['vname'];
				} else {
				  $label = $value;
				}
				$displayColumns[$value] = array('width'=>$width, 'label'=>$label);
			}
        }
		return $displayColumns;
    }
}

