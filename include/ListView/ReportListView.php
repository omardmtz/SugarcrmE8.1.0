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
/*********************************************************************************

 * Description:  generic list view class.
 ********************************************************************************/



//This is used for advanced reporting building dynamic listviews
class ReportListView  extends ListView {

	//construction variables
	var $x_block;
	var $seed_object;
	var $data_set_object;
	var $html_var;
	var $interlock;
	var $test;
	var $table_width = "100";
	var $data_set_exportable = '1';
	var $data_set_header = '1';
	
	var $layout_array;
	var $column_array;
	
	var $prev_width_array;
	
	//data set parameter
	var $custom_layout = false;
	//this is so we don't see the data set UI tools during report viewing
	var $final_report_view = false;
	
	//related to reporting
	var $export_type = "Normal";

	function setup($seed_object, $data_object=null, $x_block, $html_var, $interlock=false){
	
		$this->seed_object = $seed_object;
		$this->x_block = $x_block;
		$this->html_var = $html_var;	
		$this->interlock = $interlock;
        
		if(!empty($data_object)){
			$this->data_set_object = $data_object;
			$this->table_width = $data_object->table_width;
			$this->data_set_exportable = $data_object->exportable;
			$this->data_set_header = $data_object->header;

				//grab the custom layout array (layout_array)	
				//the value of final_report_view will mimic the hide_columns value so use here
				$this->layout_array = $data_object->get_layout_array($this->final_report_view);	
		
				//Setup accounting format --might move this somewhere else
				$this->data_set_object->setup_money_symbol();
		
				//set the custom_layout variable to true
				$this->custom_layout = true;
        }
		//if data_object is empty
	//end function setup	
	}	

	function processDataSet(){
        global $currentModule;

		if(!isset($this->xTemplate)) $this->createXTemplate();
	
		//check the error results
		$query_error = $this->seed_object->get_custom_results(true);
	
		if($query_error['result']=="Error"){
			//Invalid Query, Display Error Message
			return $query_error;
		} else {
			//rerun query
			$this->seed_object->get_custom_results();
			//capture standard column information array
			$this->column_array = $this->seed_object->get_column_array();	
		//end if query_results produces an error
		}

	//PROCESS TABLES

		//PROCESS EXPORT BUTTON AND PAGINATION IF NECESSARY
		if(isset($this->data_set_exportable) && $this->data_set_exportable=="1"){
			$this->processDataSetNavigation($this->seed_object, $this->x_block, $this->html_var);
		}
	
		//Show header if on	
		if(isset($this->data_set_header) && $this->data_set_header=="1"){
			$this->processDataSetHeader();
		}
	
		//show custom layout editor tools if enabled
        if ($this->custom_layout && !$this->final_report_view && SugarACL::checkAccess($currentModule, 'edit')) {
			$this->get_layout_head_editor();
		}

		//General Data Set Settings	
		if(!empty($this->data_set_object))
		$this->xTemplateAssign('TABLE_WIDTH', $this->table_width."".$this->data_set_object->table_width_type);

		$this->processDataSetRows();

		//Display Form Footer
		if($this->display_header_and_footer){
			$this->getAdditionalHeader();
			echo get_form_header( $this->header_title, $this->header_text, false);
		}

		//Process Interlock if necessary
		if(!empty($this->interlock) && $this->interlock==true){
			return $this->xTemplate->text($this->x_block);

		} else {
			$this->xTemplate->out($this->x_block);
		}	
	
	
	///END TABLE PROCESSING


		if(isset($_SESSION['validation'])){
			print base64_decode('PGEgaHJlZj0naHR0cDovL3d3dy5zdWdhcmNybS5jb20nPlBPV0VSRUQmbmJzcDtCWSZuYnNwO1NVR0FSQ1JNPC9hPg==');
		//end ifset
		}


	//end function processdataset
	}

	function processDataSetHeader($sub_header=false){
		
		//sub_header is set if this is a sub query data set and we need to see the header for each row
		if($this->custom_layout==false){
			//process header layout parameters for all columns
			$this->configure_header_layout();
			if(!empty($this->column_array)){
				foreach($this->column_array as $key => $column_name){
   					$this->xTemplate->assign('COLUMN_NAME', $column_name);
   					$this->xTemplate->parse($this->x_block.".column.field");
				//end foreach column_array
				}
			}
		//end if custom_layout==false	
		}else{
			//custom layout is true		
			foreach($this->layout_array as $key => $column_array){
				//configure each header layout parameter individually if needed
				$this->configure_header_layout($column_array);
   				$this->xTemplate->assign('COLUMN_NAME', $column_array['display_name']);
   				$this->xTemplate->parse($this->x_block.".column.field");
			//end foreach column_array
			}
		//end if custom layout false or true
		}

		if($sub_header==false){	
			$this->xTemplate->parse($this->x_block.".column");
		} else {	
			//utilized for each row
			return $this->xTemplate->text($this->x_block.".column");
		}	

	//end function processDataSetHeader
	}	


	function processDataSetRows(){
 		global $odd_bg;
		global $even_bg;
 		global $hilite_bg;
		global $click_bg;

		$this->xTemplate->assign("BG_HILITE", $hilite_bg);
		$this->xTemplate->assign('CHECKALL', SugarThemeRegistry::current()->getImage('blank', '', 1, 1, ".gif", ''));
		$oddRow = true;
		$count = 0;
		if(!empty($this->seed_object->data_set)){
		
			while ($row = $this->seed_object->db->fetchByAssoc($this->seed_object->data_set)) {
				//added this loop to account for ucfirst field name is seed reports.
				foreach ($row as $row_key=>$row_value)	{
					unset($row[$row_key]);
					$row[strtolower($row_key)]=$row_value;
					
				}							
				$prev_width_count = 0;	
				//This is where it should get list view data or special map or something in report maker

				$count++;
				
				if($oddRow) {
					$this->xTemplate->assign("ROW_COLOR", 'oddListRow');
					$this->xTemplate->assign("BG_COLOR", $odd_bg);
				} else {
					$this->xTemplate->assign("ROW_COLOR", 'evenListRow');
					$this->xTemplate->assign("BG_COLOR", $even_bg);
				}
				$oddRow = !$oddRow;

				//check for all the UI & layout settings
			

				//Show header for each row if this is a parent query and there is a sub query
				//Also only show if show_header is set to on
				if(!empty($this->data_set_object->interlock)
				&& $this->data_set_object->interlock==true
				&& isset($this->data_set_header)
				&& $this->data_set_header=="on"
				&& $count>1){
						
					$this->xTemplate->assign('SUB_HEADER', $this->processDataSetHeader(true));
				
				//end if this is parent query
				}
				
	
				if($this->custom_layout==false){
				//for all columns
				$this->configure_body_layout();	
					foreach($this->column_array as $key => $column_name){
					//Right now the user_prev_header function only works if you don't have the custom layout enabled
	
						if(!empty($this->data_set_object->use_prev_header) && $this->data_set_object->use_prev_header=="on"){
							$column_width = $this->prev_width_array[$prev_width_count];
						} else {	
							if (isset($this->data_set_object->table_width_type)) { 
								$column_width = $this->table_width / $this->seed_object->column_quantity."".$this->data_set_object->table_width_type;
							} else {
								$column_width = $this->table_width / $this->seed_object->column_quantity."%";
							}
							//push this width on end in case the next data set needs this information
							$this->prev_width_array[$prev_width_count] = $column_width;
						} //end if the use prev header option is set or not
					
						$this->xTemplate->assign('COLUMN_WIDTH', $column_width);
					
						//end column_width settings for if there is no custom layout					
						if(empty($row[$column_name]) and empty($row[strtolower($column_name)])){
							$field_name = "&nbsp;";
						} else {
							$field_name = $row[$column_name];
						}	
   						$this->xTemplate->assign('ROW_FIELD', $field_name);
						$this->xTemplate->parse($this->x_block.".row.field");
					
						//add one for prev width count
						$prev_width_count++;
					
					//end foreach column_array
					}
			
				//custom layout is true
				}else{
				
					foreach($this->layout_array as $key => $column_array){
						//individual column array attributes
						$this->configure_body_layout($column_array);				
					
					////////////Format Type///////////////////
						if(!empty($column_array['body']['format_type']) && $column_array['body']['format_type']=="Accounting"){
							
							$formatted_value = $this->data_set_object->format_accounting($row[strtolower($column_array['default_name'])]);
						
						}
						elseif(!empty($column_array['body']['format_type']) && $column_array['body']['format_type']=="Date")
						{
								$formatted_value = $this->data_set_object->format_date($row[strtolower($column_array['default_name'])]);
						}
						elseif(!empty($column_array['body']['format_type']) && $column_array['body']['format_type']=="Datetime")
						{
								$formatted_value = $this->data_set_object->format_datetime($row[strtolower($column_array['default_name'])]);
						}
						else {
							$formatted_value = $row[strtolower($column_array['default_name'])];	
						}

						if(!empty($this->data_set_object->use_prev_header) && $this->data_set_object->use_prev_header=="on" && isset($this->prev_width_array[$prev_width_count])){
							
							$column_width = $this->prev_width_array[$prev_width_count];
					
						} else {	
						
							//setup for prev_width_array if necessary;
							$this->prev_width_array[$prev_width_count] = $column_array['column_width'];
							$column_width = $column_array['column_width'];
						}
									
   						$this->xTemplate->assign('COLUMN_WIDTH', $column_width);
   						$this->xTemplate->assign('ROW_FIELD', $formatted_value);
						$this->xTemplate->parse($this->x_block.".row.field");
					
						//add one for prev width count
						$prev_width_count++;
					
					//end foreach column_array
					}
	
				//end if custom layout false or true
				}			
			
				//check for sub queries
				if(!empty($this->data_set_object->interlock) && $this->data_set_object->interlock==true){				

					$sub_block = $this->data_set_object->process_interlock($row);		
				
					$this->xTemplate->assign("COL_SPAN", "colspan=\"".$this->seed_object->column_quantity."\"");
					$this->xTemplate->assign("PRESPACE_Y", "<p><p>");
					$this->xTemplate->assign("SUB_BLOCK", $sub_block);
					$this->xTemplate->assign("POSTSPACE_Y", "<p>");
					$this->xTemplate->parse($this->x_block.".row.sub_block");
				
				//end if sub queries
				}
			
				$this->xTemplate->parse($this->x_block.".row");			
							
			//end while			
			}	
		//end if empty rows
		}	
	
		$this->xTemplate->parse($this->x_block);

	//end function processdatasetrows	
	}

	function configure_body_layout($column_array=""){
	
		//Setup temp body layout information
		//font_size
		if(!empty($column_array['body']) && !empty($column_array['body']['font_size'])){
			$font_size = $column_array['body']['font_size'];
		} else {	
			if (!isset($this->data_set_object->font_size)) {
				$font_size=-5;
			} else { 
				$font_size = $this->data_set_object->font_size;	
			}
		}
		
		//font_color
		if(!empty($column_array['body']) && !empty($column_array['body']['font_color'])){
			$font_color = $column_array['body']['font_color'];
		} else {	
			if (!isset($this->data_set_object->body_text_color)) {
				$font_colorr='black';
			} else { 
				$font_color = $this->data_set_object->body_text_color;	
			}
		}
		
		//bg_color
		if(!empty($column_array['body']) && !empty($column_array['body']['bg_color'])){
			$bg_color = $column_array['body']['bg_color'];
		} else {	
			if (!isset($this->data_set_object->body_back_color)) {
				$bg_color='white';
			} else { 
				$bg_color = $this->data_set_object->body_back_color;	
			}
		}	
					
		//wrap
		if(!empty($column_array['body']) && !empty($column_array['body']['wrap'])){
			$wrap = $column_array['body']['wrap'];
		} else {	
			$wrap = "";	
		}

		//style
		if(!empty($column_array['body']) && !empty($column_array['body']['style'])){
			$style = $column_array['body']['style'];
		} else {	
			$style = "";	
		}	
					
		//configure font_size					
		if(!empty($font_size)){
		
			if($font_size=="Default"){
				$field_font_size_amount = 12;
			} else {
				$field_font_size_amount = ($font_size) + 12;
			}
				
			if(!empty($inline_style)){
				$inline_style .= "font-size: ".$field_font_size_amount."px; ";
			} else {
				$inline_style = "style=\"font-size: ".$field_font_size_amount."px; ";
			}			
		//end if empty font size
		}
	
		//configure font_color					
		if(!empty($font_color)){
		
			if(!empty($inline_style)){
				$inline_style .= "color: ".$font_color."; ";	
			} else {	
				$inline_style = "style=\"color: ".$font_color."; ";
			}
		//if empty font_color
		}
	
		//configure body background					
		if(!empty($bg_color)){
		
			if(!empty($inline_style)){
			$inline_style .= "background-color: ".$bg_color."; ";	
			} else {	
			$inline_style = "style=\"background-color: ".$bg_color."; ";
			}
		//end if empty bg_color
		}
	
		//configure body_style
		if(!empty($style) && $style!="Normal"){
			$style_type = "style";
			if($style=="bold") $style_type = "weight";

			if(!empty($inline_style)){
				$inline_style .= "font-".$style_type.": ".$style.";";
			} else {
				$inline_style = "style=\"font-".$style_type.": ".$style.";";
			}
		//end if empty body_style
		}
			
		//configure body_wrap
		if(!empty($wrap)){
			$this->xTemplate->assign('WRAP', $wrap);
		}			
	

		//add ending quote	
		if(!empty($inline_style)){
			$inline_style .=" \"";	
			$this->xTemplate->assign('INLINE_STYLE', $inline_style);
		}	

	//end function configure_body_layout
	}


	function configure_header_layout($column_array=""){
	
		//Setup temp header layout information
	
		//font_size
		if(!empty($column_array['head']) && !empty($column_array['head']['font_size'])){
			$font_size = $column_array['head']['font_size'];
		} elseif(!empty($this->data_set_object->font_size)) {
			$font_size = $this->data_set_object->font_size;	
		}
        else {
            $font_size = '';
        }

		//font_color
		if(!empty($column_array['head']) && !empty($column_array['head']['font_color'])){
			$font_color = $column_array['head']['font_color'];
		} elseif(!empty($this->data_set_object->header_text_color)){	
			$font_color = $this->data_set_object->header_text_color;	
		}
        else {
            $font_color = '';
        }

		//bg_color
		if(!empty($column_array['head']) && !empty($column_array['head']['bg_color'])){
			$bg_color = $column_array['head']['bg_color'];
        } elseif(!empty($this->data_set_object->header_back_color)){
            $bg_color = $this->data_set_object->header_back_color;
		} else {	
			$bg_color ='';
		}				
	
		//wrap
		if(!empty($column_array['head']) && !empty($column_array['head']['wrap'])){
			$wrap = $column_array['head']['wrap'];
		} else {	
			$wrap = "";	
		}
		
		//style
		if(!empty($column_array['head']) && !empty($column_array['head']['style'])){
			$style = $column_array['head']['style'];
		} else {	
			$style = "";	
		}			
	
	
	/////OUTPUT CSS STYLE INFORMATION	
	
		//configure font_size					
		if(!empty($font_size)){
		
			if($font_size=="Default"){
				$field_font_size_amount = 12;
			} else {
				$field_font_size_amount = ($font_size) + 12;
			}
			
			if(!empty($inline_header_style)){
				$inline_header_style .= "font-size: ".$field_font_size_amount."px; ";
			} else {
				$inline_header_style = "style=\"font-size: ".$field_font_size_amount."px; ";
			}			
		//end if empty font_size
		}
	
		//configure header_font_color					
		if(!empty($font_color)){
			
			if(!empty($inline_header_style)){
			$inline_header_style .= "color: ".$font_color."; ";	
			} else {	
			$inline_header_style = "style=\"color: ".$font_color."; ";
			}
		//end if empty font_color
		}
	
		//configure header background					
		if(!empty($bg_color)){
		
			if(!empty($inline_header_style)){
				$inline_header_style .= "background-image: none; background-color: ".$bg_color."; ";	
			} else {	
				$inline_header_style = "style=\"background-image: none; background-color: ".$bg_color."; ";
			}
		//end if empty bg_color
		}	
	
	
		//configure header_style
		if(!empty($style) && $style!="Normal"){
			$style_type = "style";
			if($style=="bold") $style_type = "weight";
			
			if(!empty($inline_header_style)){
				$inline_header_style .= "font-".$style_type.": ".$style.";";
			} else {
				$inline_header_style = "style=\"font-".$style_type.": ".$style.";";
			}
		//end if empty style
		}	
		
		//configure header_wrap
		if(!empty($wrap)){

			$this->xTemplate->assign('WRAP', $wrap);
		//end if empty wrap
		}		
	
		//add ending quote	
		if(!empty($inline_header_style)){
			$inline_header_style .=" \"";	
			$this->xTemplate->assign('INLINE_HEADER_STYLE', $inline_header_style);
		}
	
	//end function configure_header_layout
	}	

	
	
	function processDataSetNavigation(&$list, $xtemplateSection, $html_varName){
		global $currentModule, $export_module, $sugar_config, $current_user;

		if(!empty($this->export_type) && $this->export_type=="Normal"){	
			$export_link = "<a target=\"_blank\" href=\"index.php?entryPoint=export&record=".$this->seed_object->id."&module=".$export_module."\" >".SugarThemeRegistry::current()->getImage("export","border='0' align='absmiddle'",null,null,'.gif',$this->local_app_strings['LBL_EXPORT'])."&nbsp;".$this->local_app_strings['LBL_EXPORT']."</a>";
		} else {
			//export type must be enterprise reporting related and only valid if the data set object exists
			if(!empty($this->data_set_object->id)){	
				if (!isset($export_module)) {
					$export_module = 'ReportMaker';
                }
                    $export_link = "<a href=\"javascript: void(0);\"  onclick=\"window.location.href='index.php?entryPoint=export_dataset&record=".$this->data_set_object->id."&module=".$export_module."'\" >".SugarThemeRegistry::current()->getImage("export","border='0' align='absmiddle'",null,null,'.gif',$this->local_app_strings['LBL_EXPORT'])."&nbsp;".$this->local_app_strings['LBL_EXPORT']."</a>";
			}
		//end if export type is normal
		}	
	
		if ($_REQUEST['module']== 'Home' || $this->local_current_module == 'Import'

		|| $_REQUEST['module'] == "Teams"

		|| $this->show_export_button == false
		|| (isset($sugar_config['disable_export']) && $sugar_config['disable_export'])
		|| (isset($sugar_config['admin_export_only']) && $sugar_config['admin_export_only'] && !(is_admin($current_user)))
		)

		{
			$export_link = "&nbsp;";
		}


		if ( $this->show_paging == true){
			$html_text = "";
			$html_text .= "<tr class='pagination'  role='presentation'>\n";
			$html_text .= "<td COLSPAN=\"20\" align=\"right\">\n";
			$html_text .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td align=\"left\">$export_link</td>\n";
			$html_text .= "</tr></table>\n";
			$html_text .= "</td>\n";
			$html_text .= "</tr>\n";
			$this->xTemplate->assign("PAGINATION",$html_text);
		}

		$_SESSION['export_where'] = $this->query_where;
		$this->xTemplate->parse($xtemplateSection.".list_nav_row");
	
		//end function processdatasetnavigation
	}

////////////////////CUSTOM LAYOUT FUNCTIONS


	function get_layout_head_editor(){

		foreach($this->layout_array as $key => $column_array){

   			//CREATE POPUP LINK VARIABLES
   			$this->xTemplate->assign('DATA_SET_ID', $this->data_set_object->id);
   			$this->xTemplate->assign('LAYOUT_ID', $this->data_set_object->get_layout_id_from_parent_value($column_array['default_name']));
   			$this->xTemplate->parse($this->x_block.".column_layout.field");
		//end foreach
		}

		$this->xTemplate->parse($this->x_block.".column_layout");
	
	//end function get_layout_head_editor
	}	

	function get_column_width($parent_value=""){

	//end function get_column_width
	}

//end class
}
