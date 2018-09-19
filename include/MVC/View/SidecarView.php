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
 * SidecarView.php
 *
 * This class extends SugarView to provide sidecar framework specific support.  Modules
 * that may wish to use the sidecar framework may extend this class to provide module
 * specific support.
 *
 */


class SidecarView extends SugarView
{
    protected $configFileName = "config.js";
    protected $configFile;
    
    public function __construct()
    {
        $this->configFile = sugar_cached($this->configFileName);
        parent::__construct();
    }

    /**
     * Authorization token to integrate into the view
     * @var array
     */
    protected $authorization;

    /**
     * This method checks to see if the configuration file exists and, if not, creates one by default
     *
     * @param array $params additional view paramters passed through from the controller
     */
    public function preDisplay($params = array())
    {
        global $app_strings;

        SugarAutoLoader::requireWithCustom('ModuleInstall/ModuleInstaller.php');
        $moduleInstallerClass = SugarAutoLoader::customClass('ModuleInstaller');
        //Rebuild config file if it doesn't exist
        if(!file_exists($this->configFile)) {
           $moduleInstallerClass::handleBaseConfig();
        }
        $this->ss->assign("configFile", $this->configFile);
        $config = $moduleInstallerClass::getBaseConfig();
        $this->ss->assign('configHash', md5(serialize($config)));

        $sugarSidecarPath = ensureJSCacheFilesExist();
        $this->ss->assign("sugarSidecarPath", $sugarSidecarPath);

        // TODO: come up with a better way to deal with the various JS files
        // littered in sidecar.tpl.
        $voodooFile = 'custom/include/javascript/voodoo.js';
        if (file_exists($voodooFile)) {
            $this->ss->assign('voodooFile', $voodooFile);
        }

        $this->ss->assign('processAuthorFiles', true);

        //Load sidecar theme css
        $theme = new SidecarTheme();
        $this->ss->assign("css_url", $theme->getCSSURL());
        $this->ss->assign("developerMode", inDeveloperMode());
        $this->ss->assign('shouldResourcesBeMinified', shouldResourcesBeMinified());

        //Loading label
        $this->ss->assign('LBL_LOADING', $app_strings['LBL_ALERT_TITLE_LOADING']);
        $this->ss->assign('LBL_ENABLE_JAVASCRIPT', $app_strings['LBL_ENABLE_JAVASCRIPT']);

        $slFunctionsPath = shouldResourcesBeMinified()
            ? 'cache/Expressions/functions_cache.js'
            : 'cache/Expressions/functions_cache_debug.js';
        if (!is_file($slFunctionsPath)) {
            $GLOBALS['updateSilent'] = true;
            include("include/Expressions/updatecache.php");
        }
        $this->ss->assign("SLFunctionsPath", $slFunctionsPath);
        if(!empty($this->authorization)) {
            $this->ss->assign('appPrefix', $config['env'].":".$config['appId'].":");
            $this->ss->assign('authorization', $this->authorization);
        }
    }

    /**
     * This method sets the config file to use and renders the template
     *
     * @param array $params additional view paramters passed through from the controller
     */
    public function display($params = array())
    {
        $this->ss->display(SugarAutoLoader::existingCustomOne('include/MVC/View/tpls/sidecar.tpl'));
    }

    /**
     * This method returns the theme specific CSS code to be used for the view
     *
     * @return string HTML formatted string of the CSS stylesheet files to use for view
     */
    public function getThemeCss()
    {
        // this is left empty since we are generating the CSS via the API
    }

    protected function _initSmarty()
    {
        $this->ss = new Sugar_Smarty();
        // no app_strings and mod_strings needed for sidecar
    }
}
