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

class SugarFieldText extends SugarFieldBase {

	function getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
		$displayParams['nl2br'] = true;
		$displayParams['htmlescape'] = true;
		$displayParams['url2html'] = true;
		return parent::getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
    }

	function getWirelessEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
		$displayParams['nl2br'] = true;
		$displayParams['url2html'] = true;
		return parent::getWirelessEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
    }
    function getClassicEditView($field_id='description', $value='', $prefix='', $rich_text=false, $maxlength='', $tabindex=1, $cols=80, $rows=4) {

        $this->ss->assign('prefix', $prefix);
        $this->ss->assign('field_id', $field_id);
        $this->ss->assign('value', $value);
        $this->ss->assign('tabindex', $tabindex);

        $displayParams = array();
        $displayParams['textonly'] = $rich_text ? false : true;
        $displayParams['maxlength'] = $maxlength;
        $displayParams['rows'] = $rows;
        $displayParams['cols'] = $cols;


        $this->ss->assign('displayParams', $displayParams);
		if(isset($GLOBALS['current_user'])) {
			$height = $GLOBALS['current_user']->getPreference('text_editor_height');
			$width = $GLOBALS['current_user']->getPreference('text_editor_width');
			$height = isset($height) ? $height : '300px';
	        $width = isset($width) ? $width : '95%';
			$this->ss->assign('RICH_TEXT_EDITOR_HEIGHT', $height);
			$this->ss->assign('RICH_TEXT_EDITOR_WIDTH', $width);
		} else {
			$this->ss->assign('RICH_TEXT_EDITOR_HEIGHT', '100px');
			$this->ss->assign('RICH_TEXT_EDITOR_WIDTH', '95%');
		}

		return $this->ss->fetch($this->findTemplate('ClassicEditView'));
    }
}
?>
