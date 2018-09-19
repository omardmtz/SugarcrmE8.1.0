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


class EAPMViewDetail extends ViewDetail {

    private $_returnId;

    protected function _getModuleTab()
    {
        return 'Users';
    }

    /**
	 * @see SugarView::_getModuleTitleParams()
	 */
	protected function _getModuleTitleParams($browserTitle = false)
	{
	    global $mod_strings;

        $returnAction = 'DetailView';
        $returnModule = 'Users';
        $returnId = $GLOBALS['current_user']->id;
        $returnName = $GLOBALS['current_user']->full_name;

        $returnModuleFromRequest = $this->request->getValidInputRequest('return_module', 'Assert\Mvc\ModuleName');
        $returnActionFromRequest = $this->request->getValidInputRequest('return_action');
        $returnNameFromRequest = $this->request->getValidInputRequest('return_name');
        $returnIdFromRequest = $this->request->getValidInputRequest('user_id', 'Assert\Guid');

        if(!empty($returnActionFromRequest) && !empty($returnModuleFromRequest)){
            if('Users' == $returnModuleFromRequest){
                if('EditView' == $returnActionFromRequest){
                    $returnAction = 'EditView';
                }
                if(!empty($returnNameFromRequest)){
                    $returnName = $returnNameFromRequest;
                }
                if(!empty($returnIdFromRequest)){
                    $returnId = $returnIdFromRequest;
                }
            }
        }

        $this->_returnId = $returnId;

        $iconPath = $this->getModuleTitleIconPath($this->module);
        $params = array();
        if (!empty($iconPath) && !$browserTitle) {
            $params[] = "<a href='index.php?module=Users&action=index'><!--not_in_theme!--><img src='{$iconPath}' alt='".translate('LBL_MODULE_NAME','Users')."' title='".translate('LBL_MODULE_NAME','Users')."' align='absmiddle'></a>";

        }
        else {
            $params[] = translate('LBL_MODULE_NAME','Users');
        }
        $params[] = "<a href='index.php?module={$returnModule}&action=EditView&record={$returnId}'>".$returnName."</a>";
        if($returnAction == 'EditView'){
            $params[] = $GLOBALS['app_strings']['LBL_EDIT_BUTTON_LABEL'];
        }
        return $params;
    }

    /**
	 * @see SugarView::getModuleTitleIconPath()
	 */
	protected function getModuleTitleIconPath($module) 
    {
        return parent::getModuleTitleIconPath('Users');
    }

 	function display(){
        $this->bean->password = empty($this->bean->password) ? '' : EAPM::$passwordPlaceholder;
        $this->ss->assign('return_id', $this->_returnId);
        if($GLOBALS['current_user']->is_admin || empty($this->bean) || empty($this->bean->id) || $this->bean->isOwner($GLOBALS['current_user']->id)){
 			parent::display();
        } else {
        	ACLController::displayNoAccess();
        }
 	}
}

?>
