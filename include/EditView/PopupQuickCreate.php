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
 * Quick create form as a pop-up window
 * @api
 */
class PopupQuickCreate extends SubpanelQuickCreate{

    public function __construct($module, $view = 'QuickCreate')
    {
		$this->defaultProcess = false;
        parent::__construct($module, $view, true);
		$this->ev->defs['templateMeta']['form']['buttons'] = array('POPUPSAVE', 'POPUPCANCEL');
	}

	function process($module){
        $form_name = 'form_QuickCreate_' . $module;
        $this->ev->formName = $form_name;
        $this->ev->process(true, $form_name);
		return $this->ev->display(false, true);
	}
}
