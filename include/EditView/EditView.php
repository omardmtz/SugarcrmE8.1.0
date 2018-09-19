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
 * Old EditView
 * @deprecated
 */
class EditView {
    /**
     * smarty object
     * @var object
     */
    var $ss;
    /**
     * location of template to use
     * @var string
     */
    var $template;
    /**
     * Module to use
     * @var string
     */
    var $module;

    /**
     *
     * @param string $module module to use
     * @param string $template template of the form to retreive
     */
    public function __construct($module, $template)
    {
        $this->module = $module;
        $this->template = $template;
        $this->ss = new Sugar_Smarty();
    }

    /**
     * Processes / setups the template
     * assigns all things to the template like mod_srings and app_strings
     *
     */
    function process() {
        global $current_language, $app_strings, $sugar_version, $sugar_config, $timedate, $theme;;
        $module_strings = return_module_language($current_language, $this->module);

        $this->ss->assign('SUGAR_VERSION', $sugar_version);
        $this->ss->assign('JS_CUSTOM_VERSION', $sugar_config['js_custom_version']);
        $this->ss->assign('VERSION_MARK', getVersionedPath(''));
        $this->ss->assign('THEME', $theme);
        $this->ss->assign('APP', $app_strings);
        $this->ss->assign('MOD', $module_strings);
    }


    /**
     * Displays the template
     *
     * @return string HTML of parsed template
     */
    function display() {
        return $this->ss->fetch($this->template);
    }

}

