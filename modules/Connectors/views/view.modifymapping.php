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


class ViewModifyMapping extends SugarView 
{   
 	/**
	 * @see SugarView::_getModuleTitleParams()
	 */
	protected function _getModuleTitleParams($browserTitle = false)
	{
	    global $mod_strings;
	    
    	return array(
    	   "<a href='index.php?module=Administration&action=index'>".translate('LBL_MODULE_NAME','Administration')."</a>",
    	   "<a href='index.php?module=Connectors&action=ConnectorSettings'>".$mod_strings['LBL_ADMINISTRATION_MAIN']."</a>",
    	   $mod_strings['LBL_MODIFY_MAPPING_TITLE']
    	   );
    }
    
    /**
	 * @see SugarView::_getModuleTab()
	 */
	protected function _getModuleTab()
    {
        return 'Administration';
    }
    
    /**
	 * @see SugarView::display()
	 */
	public function display() 
	{	
		require_once('include/connectors/utils/ConnectorUtils.php');
		global $mod_strings, $app_strings;
		$this->ss->assign('mod', $mod_strings);
		$this->ss->assign('APP', $app_strings);
		$connectors = ConnectorUtils::getConnectors(true);
        foreach($connectors as $id=>$source) {
            $s = SourceFactory::getSource($id);
            $mapping = $s->getMapping();

            if(!$s->isEnabledInAdminMapping() || empty($mapping))
            {
			   unset($connectors[$id]);
			}
		}

		$this->ss->assign('SOURCES', $connectors);
	    echo $this->getModuleTitle(false);
		$this->ss->display($this->getCustomFilePathIfExists('modules/Connectors/tpls/modify_mapping.tpl'));
    }
}
