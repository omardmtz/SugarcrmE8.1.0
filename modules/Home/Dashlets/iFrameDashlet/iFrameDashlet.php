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



class iFrameDashlet extends Dashlet {
    var $displayTpl = 'modules/Home/Dashlets/iFrameDashlet/display.tpl';
    var $configureTpl = 'modules/Home/Dashlets/iFrameDashlet/configure.tpl';
    var $defaultURL = 'http://apps.sugarcrm.com/dashlet/sugarcrm-news-dashlet.html?lang=@@LANG@@&edition=@@EDITION@@&ver=@@VER@@';
    var $url;
    protected $allowed_schemes = array("http", "https");

    public function __construct($id, $options = null)
    {
        parent::__construct($id);
        $this->isConfigurable = true;

        if(!empty($options['titleLabel'])) {
        	$this->title = translate($options['titleLabel'], 'Home');
        } else {
	        if(empty($options['title'])) {
	            $this->title = translate('LBL_DASHLET_TITLE', 'Home');
	        } else {
	            $this->title = $options['title'];
	        }
        }
        if(empty($options['url'])) {
            $this->url = $this->defaultURL;
        } else {
            $this->url = $options['url'];
        }

        $this->checkURL();

        if(empty($options['height']) || (int)$options['height'] < 1 ) {
            $this->height = 315;
        } else {
            $this->height = (int)$options['height'];
        }

        if(isset($options['autoRefresh'])) $this->autoRefresh = $options['autoRefresh'];
    }

    protected function checkURL()
    {
        $scheme = parse_url($this->url, PHP_URL_SCHEME);
        if(!in_array($scheme, $this->allowed_schemes)) {
            $this->url = 'about:blank';
        }
    }

    function displayOptions() {
        global $app_strings;
        $ss = new Sugar_Smarty();
        $ss->assign('titleLBL', translate('LBL_DASHLET_OPT_TITLE', 'Home'));
		$ss->assign('urlLBL', translate('LBL_DASHLET_OPT_URL', 'Home'));
		$ss->assign('heightLBL', translate('LBL_DASHLET_OPT_HEIGHT', 'Home'));
        $ss->assign('title', $this->title);
        $ss->assign('url', $this->url);
        $ss->assign('id', $this->id);
        $ss->assign('height', $this->height);
        $ss->assign('saveLBL', $app_strings['LBL_SAVE_BUTTON_LABEL']);
        $ss->assign('clearLBL', $app_strings['LBL_CLEAR_BUTTON_LABEL']);
        if($this->isAutoRefreshable()) {
       		$ss->assign('isRefreshable', true);
			$ss->assign('autoRefresh', $GLOBALS['app_strings']['LBL_DASHLET_CONFIGURE_AUTOREFRESH']);
			$ss->assign('autoRefreshOptions', $this->getAutoRefreshOptions());
			$ss->assign('autoRefreshSelect', $this->autoRefresh);
		}
        
        return  $ss->fetch('modules/Home/Dashlets/iFrameDashlet/configure.tpl');        
    }

    function saveOptions($req) {
        $options = array();

        if ( isset($req['title']) ) {
            $options['title'] = $req['title'];
        }
        if ( isset($req['url']) ) {
            $options['url'] = $req['url'];
        }
        if ( isset($req['height']) ) {
            $options['height'] = (int)$req['height'];
        }
        $options['autoRefresh'] = empty($req['autoRefresh']) ? '0' : $req['autoRefresh'];

        return $options;
    }

    public function display($text = '')
    {
        $sugar_edition = 'PRO';
        $sugar_edition = 'ENT';


        $out_url = str_replace(
            array('@@LANG@@','@@VER@@','@@EDITION@@'),
            array($GLOBALS['current_language'],$GLOBALS['sugar_config']['sugar_version'],$sugar_edition),
            $this->url);
        $title = $this->title;
        if(empty($title)){
            $title = 'empty';
        }
        return parent::display($text)
            . "<iframe class='teamNoticeBox' title='{$title}' src='{$out_url}' height='{$this->height}px'></iframe>";
    }
}
