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
 * Subpanel tiles
 * @api
 */
class SubPanelTiles
{
	var $id;
	var $module;
	var $focus;
	var $start_on_field;
	var $layout_manager;
	var $layout_def_key;
//	var $show_tabs = false;

	var $subpanel_definitions;

	var $hidden_tabs=array(); //consumer of this class can array of tabs that should be hidden. the tab name
							//should be the array.

    public function __construct(&$focus, $layout_def_key = '', $layout_def_override = '')
	{
		$this->focus = $focus;
		$this->id = $focus->id;
		$this->module = $focus->module_dir;
		$this->layout_def_key = $layout_def_key;
		$this->subpanel_definitions=new SubPanelDefinitions($focus, $layout_def_key, $layout_def_override);
	}

    /*
     * Return all subpanels available (order by user preference)
     *
     * @return array Visible tabs
     */
    function getTabs()
    {
        // Check if the user has a custom subpanel layout.
        global $current_user;
        $user_layout = $current_user->getPreference('subpanelLayout', $this->module);

        // Fetch the global subpanel layout.
        $layout = $this->subpanel_definitions->get_available_tabs();

        // If the user has a custom subpanel layout, merge the global layout onto the
        // end then pull out the unique values to ensure the users defined subpanel
        // layout is taken into account first and any others are appended to the end.
        if (!empty($user_layout)) {

            // Check if any of the panels the user has saved in their layout
            // preference are now hidden. If they are, remove them from the list.
            foreach ($user_layout as $key => $value) {
                if (!in_array($value, $layout)) {
                    unset($user_layout[$key]);
                }
            }

            $layout = array_values(array_unique(array_merge($user_layout, $layout)));
        }

        return $layout;
	}
	function display($showContainer = true)
	{
		global $layout_edit_mode, $sugar_version, $sugar_config, $current_user, $app_strings;
		if(isset($layout_edit_mode) && $layout_edit_mode){
			return;
		}

		global $modListHeader;

		ob_start();
    echo '<script type="text/javascript" src="'. getJSPath('include/SubPanel/SubPanelTiles.js') . '"></script>';
?>
<script>
if(document.DetailView != null &&
   document.DetailView.elements != null &&
   document.DetailView.elements.layout_def_key != null &&
   typeof document.DetailView.elements['layout_def_key'] != 'undefined'){
    document.DetailView.elements['layout_def_key'].value = <?php echo json_encode($this->layout_def_key); ?>;
}
</script>
<?php

		$default_div_display = 'inline';
		if(!empty($sugar_config['hide_subpanels_on_login'])){
			if(!isset($_SESSION['visited_details'][$this->focus->module_dir])){
				setcookie($this->focus->module_dir . '_divs', '');
				unset($_COOKIE[$this->focus->module_dir . '_divs']);
				$_SESSION['visited_details'][$this->focus->module_dir] = true;

			}
			$default_div_display = 'none';
		}
		$div_cookies = get_sub_cookies($this->focus->module_dir . '_divs');

        $tabs = $this->getTabs();

        $tab_names = array();

        if($showContainer)
        {
            echo '<ul class="noBullet" id="subpanel_list">';
        }
        //echo "<li id='hidden_0' style='height: 5px' class='noBullet'>&nbsp;&nbsp;&nbsp;</li>";
        if (empty($GLOBALS['relationships'])) {
        	if (!class_exists('Relationship')) {
        		require('modules/Relationships/Relationship.php');
        	}
        	$rel = BeanFactory::newBean('Relationships');
	        $rel->load_relationship_meta();
        }

        // this array will store names of sub-panels that can contain items
        // of each module
        $module_sub_panels = array();

        foreach ($tabs as $tab)
		{
			//load meta definition of the sub-panel.
			$thisPanel=$this->subpanel_definitions->load_subpanel($tab);
            if ($thisPanel === false)
                continue;
            // Check ACLs for the subpanel
            if(!$this->focus->ACLAccess("subpanel", array("subpanel" => $thisPanel))) {
                continue;
            }
			//this if-block will try to skip over ophaned subpanels. Studio/MB are being delete unloaded modules completely.
			//this check will ignore subpanels that are collections (activities, history, etc)
			if (!isset($thisPanel->_instance_properties['collection_list']) and isset($thisPanel->_instance_properties['get_subpanel_data']) ) {
				//ignore when data source is a function

				if (!isset($this->focus->field_defs[$thisPanel->_instance_properties['get_subpanel_data']])) {
					if (stripos($thisPanel->_instance_properties['get_subpanel_data'],'function:') === false) {
						$GLOBALS['log']->fatal("Bad subpanel definition, it has incorrect value for get_subpanel_data property " .$tab);
						continue;
					}
				} else {
					$rel_name='';
					if (isset($this->focus->field_defs[$thisPanel->_instance_properties['get_subpanel_data']]['relationship'])) {
						$rel_name=$this->focus->field_defs[$thisPanel->_instance_properties['get_subpanel_data']]['relationship'];
					}

					if (empty($rel_name) or !isset($GLOBALS['relationships'][$rel_name])) {
						$GLOBALS['log']->fatal("Missing relationship definition " .$rel_name. ". skipping " .$tab ." subpanel");
						continue;
					}
				}
			}

            if ($thisPanel->isCollection()) {
                // collect names of sub-panels that may contain items of each module
                $collection_list = $thisPanel->get_inst_prop_value('collection_list');
                if (is_array($collection_list)) {
                    foreach ($collection_list as $data) {
                        if (!empty($data['module'])) {
                            $module_sub_panels[$data['module']][$tab] = true;
                        }
                    }
                }
            } else {
                $module = $thisPanel->get_module_name();
                if (!empty($module)) {
                    $module_sub_panels[$module][$tab] = true;
                }
            }

			echo '<li class="noBullet" id="whole_subpanel_' . htmlspecialchars($tab, ENT_QUOTES, 'UTF-8') . '">';

			$display= 'none';
			$div_display = $default_div_display;
			$cookie_name =   $tab . '_v';

			if (isset($thisPanel->_instance_properties['collapsed']) && $thisPanel->_instance_properties['collapsed'])
			{
				$div_display = 'none';
			}

			if(isset($div_cookies[$cookie_name])){
				//If defaultSubPanelExpandCollapse is set, ignore the cookie that remembers whether the panel is expanded or collapsed.
				//To be used with the above 'collapsed' metadata setting so they will always be set the same when the page is loaded.
				if(!isset($sugar_config['defaultSubPanelExpandCollapse']) || $sugar_config['defaultSubPanelExpandCollapse'] == false)
					$div_display = 	$div_cookies[$cookie_name];
			}
			if(!empty($sugar_config['hide_subpanels'])){
				$div_display = 'none';
			}
            if($thisPanel->isDefaultHidden()) {
                $div_display = 'none';
            }
			if($div_display == 'none'){
				$opp_display  = 'inline';
			}else{
				$opp_display  = 'none';
			}

            if (!empty($this->layout_def_key) ) {
                $layout_def_key = $this->layout_def_key;
            } else {
                $layout_def_key = '';
            }

			if (empty($this->show_tabs))
			{
				$show_icon_html = SugarThemeRegistry::current()->getImage( 'advanced_search', 'border="0" align="absmiddle"',null,null,'.gif',translate('LBL_SHOW'));
				$hide_icon_html = SugarThemeRegistry::current()->getImage( 'basic_search', 'border="0" align="absmiddle"',null,null,'.gif',translate('LBL_HIDE'));

 		 		$max_min = "<a name=\"" . htmlspecialchars($tab, ENT_QUOTES, 'UTF-8') . "\"> </a><span id=\"show_link_".htmlspecialchars($tab, ENT_QUOTES, 'UTF-8')."\" style=\"display: $opp_display\"><a href='#' class='utilsLink' onclick=\"current_child_field = ".htmlspecialchars(json_encode($tab), ENT_QUOTES, 'UTF-8').";showSubPanel(".htmlspecialchars(json_encode($tab), ENT_QUOTES, 'UTF-8').",null,null,'".$layout_def_key."');document.getElementById(".htmlspecialchars(json_encode('show_link_'.$tab), ENT_QUOTES, 'UTF-8').").style.display='none';document.getElementById(".htmlspecialchars(json_encode('hide_link_'.$tab), ENT_QUOTES, 'UTF-8').").style.display='';return false;\">"
 		 			. "" . $show_icon_html . "</a></span>";
                $max_min .= "<span id=\"hide_link_".htmlspecialchars($tab, ENT_QUOTES, 'UTF-8')."\" style=\"display: $div_display\"><a href='#' class='utilsLink' onclick=\"hideSubPanel(".htmlspecialchars(json_encode($tab), ENT_QUOTES, 'UTF-8').");document.getElementById(".htmlspecialchars(json_encode('hide_link_'.$tab), ENT_QUOTES, 'UTF-8').").style.display='none';document.getElementById(".htmlspecialchars(json_encode('show_link_'.$tab), ENT_QUOTES, 'UTF-8').").style.display='';return false;\">"
				 . "" . $hide_icon_html . "</a></span>";
				echo '<div id="subpanel_title_' . htmlspecialchars($tab, ENT_QUOTES, 'UTF-8') . '"';
                if(empty($sugar_config['lock_subpanels']) || $sugar_config['lock_subpanels'] == false) echo ' onmouseover="this.style.cursor = \'move\';"';
                echo '>' . get_form_header( $thisPanel->get_title(), $max_min, false) . '</div>';
			}

            printf(
                '<div cookie_name="%s" id="subpanel_%s" style="display:%s">',
                htmlspecialchars($cookie_name, ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($tab, ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($div_display, ENT_QUOTES, 'UTF-8')
            );

            echo '<script>document.getElementById(' . json_encode('subpanel_' . $tab) . ').cookie_name=' . json_encode($cookie_name) . ';</script>';

            $display_spd = '';
            if($div_display != 'none'){
                echo "<script>SUGAR.util.doWhen(\"typeof(markSubPanelLoaded) != 'undefined'\", function() {markSubPanelLoaded(" . json_encode($tab) . ");});</script>";
            	$old_contents = ob_get_contents();
            	@ob_end_clean();

            	ob_start();
            	include_once('include/SubPanel/SubPanel.php');
            	$subpanel_object = new SubPanel($this->module, $_REQUEST['record'], $tab,$thisPanel,$layout_def_key);
            	$subpanel_object->setTemplateFile('include/SubPanel/SubPanelDynamic.html');
            	$subpanel_object->display();
            	$subpanel_data = ob_get_contents();
            	@ob_end_clean();

            	ob_start();
            	echo $this->get_buttons($thisPanel,$subpanel_object->subpanel_query);
            	$buttons = ob_get_contents();
            	@ob_end_clean();

            	ob_start();
            	echo $old_contents;
            	//echo $buttons;
                $display_spd = $subpanel_data;
            }
            echo '<div id="list_subpanel_' . htmlspecialchars($tab, ENT_QUOTES, 'UTF-8') . '">' . $display_spd . '</div></div>';
        	array_push($tab_names, $tab);
        	echo '</li>';
        } // end $tabs foreach
        if($showContainer)
        {
        	echo '</ul>';


            if(!empty($selected_group))
            {
                // closing table from tpls/singletabmenu.tpl
                echo '</td></tr></table>';
            }
        }
        // drag/drop code
        $tab_names = '["' . join($tab_names, '","') . '"]';
        global $sugar_config;

        if(empty($sugar_config['lock_subpanels']) || $sugar_config['lock_subpanels'] == false) {
            echo <<<EOQ
    <script>
    	var SubpanelInit = function() {
    		SubpanelInitTabNames({$tab_names});
    	}
        var SubpanelInitTabNames = function(tabNames) {
    		subpanel_dd = new Array();
    		j = 0;
    		for(i in tabNames) {
    			subpanel_dd[j] = new ygDDList('whole_subpanel_' + tabNames[i]);
    			subpanel_dd[j].setHandleElId('subpanel_title_' + tabNames[i]);
    			subpanel_dd[j].onMouseDown = SUGAR.subpanelUtils.onDrag;
    			subpanel_dd[j].afterEndDrag = SUGAR.subpanelUtils.onDrop;
    			j++;
    		}

    		YAHOO.util.DDM.mode = 1;
    	}
    	currentModule = '{$this->module}';
    	SUGAR.util.doWhen(
    	    "typeof(SUGAR.subpanelUtils) == 'object' && typeof(SUGAR.subpanelUtils.onDrag) == 'function'" +
    	        " && document.getElementById('subpanel_list')",
    	    SubpanelInit
    	);
    </script>
EOQ;
        }

        $module_sub_panels = array_map('array_keys', $module_sub_panels);
        $module_sub_panels = json_encode($module_sub_panels);
        echo <<<EOQ
<script>
var ModuleSubPanels = $module_sub_panels;
</script>
EOQ;

		$ob_contents = ob_get_contents();
		ob_end_clean();
		return $ob_contents;
	}


	function getLayoutManager()
	{
	  	if ( $this->layout_manager == null) {
	    	$this->layout_manager = new LayoutManager();
	  	}
	  	return $this->layout_manager;
	}

	function get_buttons($thisPanel,$panel_query=null)
	{
		$subpanel_def = $thisPanel->get_buttons();
        $layout_manager = $this->getLayoutManager();

        //for action button at the top of each subpanel
        // bug#51275: smarty widget to help provide the action menu functionality as it is currently sprinkled throughout the app with html
        $buttons = array();
        $widget_contents = '';
		foreach($subpanel_def as $widget_data)
		{

			$widget_data['action'] = $_REQUEST['action'];
			$widget_data['module'] =  $thisPanel->get_inst_prop_value('module');
			$widget_data['focus'] = $this->focus;
			$widget_data['subpanel_definition'] = $thisPanel;
			$widget_contents .= '<td class="buttons">' . "\n";

            // don't render subpanel top quick create buttons they don't work
            if (isset($widget_data['widget_class']) && $widget_data['widget_class'] == 'SubPanelTopButtonQuickCreate'
                && $widget_data['module'] == 'Users') {
                continue;
            }

			if(empty($widget_data['widget_class']))
			{
				$buttons[] = "widget_class not defined for top subpanel buttons";
			}
			else
			{
				$buttons[] = $layout_manager->widgetDisplay($widget_data);
			}

        }
        require_once('include/SugarSmarty/plugins/function.sugar_action_menu.php');
        $widget_contents = smarty_function_sugar_action_menu(array(
            'buttons' => $buttons,
            'class' => 'clickMenu fancymenu',
        ), $this->xTemplate);
        return $widget_contents;
	}
}
