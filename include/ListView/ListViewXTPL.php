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
 
 // $Id: ListViewXTPL.php 55799 2010-04-05 20:00:37Z jmertic $

	
class ListViewXTPL extends ListViewDisplay{
	var $row_block = 'main.row';
	var $main_block = 'main';
	var $pro_block = 'main.row.pro';
	var $os_block  = 'main.row.os';
	var $nav_block = 'main.list_nav_row';
	var $pro_nav_block = 'main.pro_nav';
	var $data;
	var $xtpl;

    /**
     * Processes the request. Calls ListViewData process. Also assigns all lang strings, export links,
     * This is called from ListViewDisplay
     *    
     * @param file file Template file to use
     * @param data array from ListViewData
     * @param html_var string the corresponding html var in xtpl per row
     *
     */ 
	function process($file, $data, $html_var) {
		global $odd_bg, $even_bg, $hilite_bg, $click_bg;
		
		parent::process($file, $data, $html_var);
		$this->data = $data;
		$html_var = strtoupper($html_var);
		$this->xtpl = new XTemplate($file);
		$this->xtpl->assign('MOD', $GLOBALS['mod_strings']);
		$this->xtpl->assign('APP', $GLOBALS['app_strings']);
		$this->xtpl->assign('BG_HILITE', $hilite_bg);
		$this->xtpl->assign('ORDER_BY', $data['pageData']['urls']['orderBy']);
		
		$this->processPagination();
		$this->xtpl->parse($this->nav_block);
		
		$this->processArrows($data['pageData']['ordering']);
		
		$oddRow = false;
		if($this->xtpl->exists($this->pro_nav_block)) $this->xtpl->parse($this->pro_nav_block);
		$this->xtpl->assign('CHECKALL', "<input type='checkbox' title='".$GLOBALS['app_strings']['LBL_SELECT_ALL_TITLE']."' class='checkbox' id='massall' name='massall' value='' onclick='sListView.check_all(document.MassUpdate, \"mass[]\", this.checked);' />");
		foreach($data['data'] as $id=>$row) {
			$this->xtpl->assign($html_var, $row);
			if(!empty($data['pageData']['tag'][$id])) {
				$this->xtpl->assign('TAG', $data['pageData']['tag'][$id]);
			}
						
			$this->xtpl->assign('ROW_COLOR', ($oddRow) ? 'oddListRow' : 'evenListRow');
			$this->xtpl->assign('BG_COLOR', ($oddRow) ? $odd_bg : $even_bg);
			$oddRow = !$oddRow;
			
			if($this->xtpl->exists($this->pro_block)) $this->xtpl->parse($this->pro_block);


			$prerow =  "&nbsp;<input onclick='sListView.check_item(this, document.MassUpdate)' type='checkbox' class='checkbox' name='mass[]' value='". $id. "'>";
			$this->xtpl->assign('PREROW', $prerow);
		
			$this->xtpl->parse($this->row_block);
		}
	}
	
    /**
     * Assigns the sort arrows in the tpl
     *    
     * @param ordering array data that contains the ordering info
     *
     */
	function processArrows($ordering) {
	
		$pathParts = pathinfo(SugarThemeRegistry::current()->getImageURL('arrow.gif',false));
		
        list($width,$height) = getimagesize($pathParts['dirname'].'/'.$pathParts['basename']);
		
		$this->xtpl->assign('arrow_start', "&nbsp;<!--not_in_theme!--><img border='0' src='".getJSPath($pathParts['dirname'].'/'.$pathParts['filename']));
		$this->xtpl->assign('arrow_end', "' width='$width' height='$height' align='absmiddle' alt=".translate('LBL_SORT').">");
		$arrow_order = (strcmp($ordering['sortOrder'], 'ASC'))?'_up': '_down';
		$this->xtpl->assign($ordering['orderBy'].'_arrow', $arrow_order);
	}	
	
    /**
     * Assigns the pagination links at the top and bottom of the listview
     *    
     */
	function processPagination() {
		global $app_strings;
		if(empty($this->data['pageData']['urls']['prevPage'])) {
			$startLink = SugarThemeRegistry::current()->getImage("start_off", "border='0' align='absmiddle'",null,null,'.gif',$app_strings['LNK_LIST_START'])."&nbsp;".$app_strings['LNK_LIST_START'];
			$prevLink = SugarThemeRegistry::current()->getImage("previous_off", "border='0' align='absmiddle'",null,null,'.gif',$app_strings['LNK_LIST_PREVIOUS'])."&nbsp;".$app_strings['LNK_LIST_PREVIOUS'];
		}
		else {
				$startLink = "<a href=\"{$this->data['pageData']['urls']['startPage']}\" >".SugarThemeRegistry::current()->getImage("start","border='0' align='absmiddle'",null,null,'.gif',$app_strings['LNK_LIST_START'])."&nbsp;".$app_strings['LNK_LIST_START']."</a>";
				$prevLink = "<a href=\"{$this->data['pageData']['urls']['prevPage']}\" >".SugarThemeRegistry::current()->getImage("previous","border='0' align='absmiddle'",null,null,'.gif',$app_strings['LNK_LIST_PREVIOUS'])."&nbsp;".$app_strings['LNK_LIST_PREVIOUS']."</a>";
		}

		if(!$this->data['pageData']['offsets']['totalCounted']) {
			$endLink = $app_strings['LNK_LIST_END']."&nbsp;".SugarThemeRegistry::current()->getImage("end_off","border='0' align='absmiddle'",null,null,'.gif',$app_strings['LNK_LIST_END']);
		}
		else {
				$endLink = "<a href=\"{$this->data['pageData']['urls']['endPage']}\" >".$app_strings['LNK_LIST_END']."&nbsp;".SugarThemeRegistry::current()->getImage("end","border='0' align='absmiddle'",null,null,'.gif',$app_strings['LNK_LIST_END'])."</a>";
				
		}
		if(empty($this->data['pageData']['urls']['nextPage'])){
			$nextLink = $app_strings['LNK_LIST_NEXT']."&nbsp;".SugarThemeRegistry::current()->getImage("next_off","border='0' align='absmiddle'",null,null,'.gif',$app_strings['LNK_LIST_NEXT']);
		}else{
				$nextLink = "<a href=\"{$this->data['pageData']['urls']['nextPage']}\" >".$app_strings['LNK_LIST_NEXT']."&nbsp;".SugarThemeRegistry::current()->getImage("next","border='0' align='absmiddle'",null,null,'.gif',$app_strings['LNK_LIST_NEXT'])."</a>";
		}
		
		if($this->export) $export_link = $this->buildExportLink();
		else $export_link = '';
		if($this->mailMerge)$merge_link = $this->buildMergeLink();
		else $merge_link = '';
		if($this->multiSelect) $selected_objects_span = $this->buildSelectedObjectsSpan();
		else $selected_objects_span = '';

		$htmlText = "<tr class='pagination' role='presentation'>\n"
				. "<td COLSPAN=\"20\" align=\"right\">\n"
				. "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td align=\"left\">$export_link$merge_link$selected_objects_span</td>\n"
				. "<td nowrap align=\"right\">".$startLink."&nbsp;&nbsp;".$prevLink."&nbsp;&nbsp;<span class='pageNumbers'>(".($this->data['pageData']['offsets']['current'] + 1) ." - ".($this->data['pageData']['offsets']['current'] + $this->rowCount)
				. " ".$app_strings['LBL_LIST_OF']." ".$this->data['pageData']['offsets']['total'];
		if(!$this->data['pageData']['offsets']['totalCounted']){
			$htmlText .= '+';	
		}
		$htmlText .=")</span>&nbsp;&nbsp;".$nextLink."&nbsp;&nbsp;";
		if($this->data['pageData']['offsets']['totalCounted']){
			$htmlText .= $endLink;
		}
		$htmlText .="</td></tr></table>\n</td>\n</tr>\n";

		$this->xtpl->assign("PAGINATION", $htmlText);
	}
	
    /**
     * Displays the xtpl, either echo or returning the contents
     *    
     * @param echo bool echo or return contents
     *
     */
	function display($echo = true) {
		$str = parent::display();
		$strend = parent::displayEnd();
		$this->xtpl->parse($this->main_block);
		if($echo) {
			echo $str;
			$this->xtpl->out($this->main_block);
			echo $strend;
		}
		else {
			return $str . $this->xtpl->text() . $strend;
		}
	}
}

