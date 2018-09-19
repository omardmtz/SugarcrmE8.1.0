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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

class SubPanelViewTeams {

	var $users_list = null;
	var $focus;

	function setFocus(&$value){
		$this->focus = (object) $value;
	}

	function setUsersList(&$value){
		$this->users_list = $value;
	}

	function setHideNewButton($value){
		$this->hideNewButton = $value;
	}

	function getHeaderText($action, $currentModule){
		global $app_strings;
		$button  = "<form border='0' action='index.php' method='post' name='TeamsDetailView' id='TeamsDetailView'>\n";
		$button .= "<input type='hidden' name='record' value=''>\n";
		$button .= "<input type='hidden' name='module' value='Teams'>\n";
		$button .= "<input type='hidden' name='action' value='AddUserToTeam'>\n";
		$button .= "<input type='hidden' name='team_id' value='{$this->focus->id}'>\n";
		$button .= "<input type='hidden' name='return_module' value='Teams'>\n";
		$button .= "<input type='hidden' name='return_action' value='DetailView'>\n";
		$button .= "<input type='hidden' name='return_id' value='{$this->focus->id}'>\n";
		$button .= "<input title='".$app_strings['LBL_SELECT_BUTTON_TITLE']."'  type='button' class='button' value='  ".$app_strings['LBL_SELECT_BUTTON_LABEL']."  ' name='button' LANGUAGE='javascript' onclick='window.open(\"index.php?module=Users&action=Popup&html=Popup_picker&form=TeamsDetailView&form_submit=true\",\"new\",\"width=600,height=400,resizable=1,scrollbars=1\");'>\n";
		$button .= "</form>\n";
		return $button;
	}

	function ProcessSubPanelListView($xTemplatePath, &$mod_strings, $action, $curModule = "") {
		global $currentModule,$app_strings;

		if (empty($curModule)) {
			$curModule = $currentModule;
		}

		$ListView = new ListView();
		$ListView->initNewXTemplate($xTemplatePath, $mod_strings);
		$ListView->xTemplateAssign("RETURN_URL", "&return_module=".$curModule."&return_action=DetailView&return_id=".$this->focus->id);
		$ListView->xTemplateAssign("RECORD_ID",  $this->focus->id);
		$ListView->xTemplateAssign("EDIT_INLINE_PNG",  SugarThemeRegistry::current()->getImage('edit_inline.png','align="absmiddle" border="0"',null,null,'.gif',$app_strings['LNK_EDIT']));
		$ListView->xTemplateAssign("DELETE_INLINE_PNG",  SugarThemeRegistry::current()->getImage('delete_inline.png','align="absmiddle" border="0"',null,null,'.gif',$app_strings['LNK_REMOVE']));
		$ListView->setHeaderTitle($mod_strings['LBL_TEAM_MEMBERS']);
		$ListView->setHeaderText($this->getHeaderText($action, $curModule));
		$ListView->processListView($this->users_list, "users", "USER");
	}
}
