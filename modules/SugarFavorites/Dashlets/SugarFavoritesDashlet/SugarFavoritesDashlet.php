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
 * $Id$
 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/


class SugarFavoritesDashlet extends DashletGeneric 
{ 
    public function __construct(
        $id, 
        $def = null
        ) 
    {
		global $current_user, $app_strings;
		require('modules/SugarFavorites/metadata/dashletviewdefs.php');
		$this->loadLanguage('SugarFavoritesDashlet', 'modules/SugarFavorites/Dashlets/');
        parent::__construct($id, $def);

        if(empty($def['title'])) $this->title = translate('LBL_HOMEPAGE_TITLE', 'SugarFavorites');

        $this->searchFields = $dashletData['SugarFavoritesDashlet']['searchFields'];
        $this->columns = $dashletData['SugarFavoritesDashlet']['columns'];
        $this->isConfigurable = true;
        $this->seedBean = BeanFactory::newBean('SugarFavorites');   
        $this->filters = array();
    }

    public function process($lvsParams = array())
    {
        $this->lvs->quickViewLinks = false;
        parent::process($lvsParams);
    }
    
    /**
     * Displays the configuration form for the dashlet
     * 
     * @return string html to display form
     */
    public function displayOptions() 
    {
        global $app_strings;
        
        $ss = new Sugar_Smarty();
        $this->dashletStrings['LBL_SAVE'] = $app_strings['LBL_SAVE_BUTTON_LABEL'];
        $ss->assign('lang', $this->dashletStrings);
        $ss->assign('id', $this->id);
        $ss->assign('title', $this->title);
        $ss->assign('titleLbl', $this->dashletStrings['LBL_CONFIGURE_TITLE']);
        if($this->isAutoRefreshable()) {
       		$ss->assign('isRefreshable', true);
			$ss->assign('autoRefresh', $GLOBALS['app_strings']['LBL_DASHLET_CONFIGURE_AUTOREFRESH']);
			$ss->assign('autoRefreshOptions', $this->getAutoRefreshOptions());
			$ss->assign('autoRefreshSelect', $this->autoRefresh);
		}
       
        $str = $ss->fetch('modules/SugarFavorites/Dashlets/SugarFavoritesDashlet/SugarFavoritesDashletOptions.tpl');  
        return Dashlet::displayOptions() . $str;
    }  

    /**
     * called to filter out $_REQUEST object when the user submits the configure dropdown
     * 
     * @param array $req $_REQUEST
     * @return array filtered options to save
     */  
    public function saveOptions($req) 
    {
        $options = array();
        $options['title'] = $req['title'];
        $options['autoRefresh'] = empty($req['autoRefresh']) ? '0' : $req['autoRefresh'];
        
        return $options;
    }
}