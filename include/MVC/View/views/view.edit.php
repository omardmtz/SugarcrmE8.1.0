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

require_once('include/EditView/EditView2.php');
class ViewEdit extends SugarView
{
 	var $ev;
 	var $type ='edit';
 	var $useForSubpanel = false;  //boolean variable to determine whether view can be used for subpanel creates
 	var $useModuleQuickCreateTemplate = false; //boolean variable to determine whether or not SubpanelQuickCreate has a separate display function
 	var $showTitle = true;

    /**
     * @see SugarView::preDisplay()
     */
    public function preDisplay()
    {
        $metadataFile = $this->getMetaDataFile();
        $this->ev = $this->getEditView();
        $this->ev->ss = $this->ss;
        $this->ev->setup($this->module, $this->bean, $metadataFile, SugarAutoLoader::existingCustomOne('include/EditView/EditView.tpl'));
    }

 	function display(){
		$this->ev->process();
		echo $this->ev->display($this->showTitle);
 	}

    /**
     * Get EditView object
     * @return EditView
     */
    protected function getEditView()
    {
        return new EditView();
    }
}

