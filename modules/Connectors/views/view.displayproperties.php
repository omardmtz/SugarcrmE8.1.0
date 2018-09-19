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

require_once 'include/connectors/utils/ConnectorUtils.php';

class ViewDisplayProperties extends ViewList
{
 	/**
	 * @see SugarView::process()
	 */
    public function process($params = array())
	{
 		$this->options['show_all'] = false;
 		$this->options['show_javascript'] = true;
 		$this->options['show_footer'] = false;
 		$this->options['show_header'] = false;
        parent::process($params);
 	}

    /**
     * Gets the list of enabled modules for a connector source
     *
     * @param array $sources The list of sources in the connector display config
     * @param  string $source The source that we are checking enabled modules for
     * @return array
     */
    public function getEnabledModules($sources, $source)
    {
        global $app_list_strings;

        $return = array();
        foreach ($sources as $module => $mapping) {
            foreach ($mapping as $entry) {
                if ($entry == $source) {
                    $return[$module] = isset($app_list_strings['moduleList'][$module])
                                       ? $app_list_strings['moduleList'][$module]
                                       : $module;
                }
            }
        }

        return $return;
    }

    /**
     * Gets the modules that are disabled for a connector
     *
     * @param array $enabled Current enabled module list
     * @return array
     */
    public function getDisabledModules(array $enabled)
    {
        global $app_list_strings;

        $return = array();
        foreach (SugarAutoLoader::getDirFiles("modules", true) as $e) {
            //Strip the 'modules/' portion out from beginning of $e
            $e = substr($e, 8);

            // Shortcuts for comparison
            $i = $this->isModuleIncluded($e);
            $a = $this->isModuleAccessible($e);

            // If the module is not enabled and is included and accessible, add it
            if (empty($enabled[$e]) && $i && $a) {
                $return[$e] = isset($app_list_strings['moduleList'][$e])
                                        ? $app_list_strings['moduleList'][$e]
                                        : $e;
            }
        }

        return $return;
    }

    /**
     * Checks to see if a module is accessible to be included in enabled/disabled
     * lists
     * @param string $module The module to check
     * @return boolean
     */
    public function isModuleAccessible($module)
    {
        global $beanList, $current_user;
        $access = $current_user->getDeveloperModules();

        // Is the module in the beanList AND accessible to the user?
        return isset($beanList[$module]) 
               && (in_array($module, $access) || is_admin($current_user));
    }

    /**
     * Checks to see if a module should be included in the enabled/disabled lists
     *
     * @param string $module The module to check
     * @return boolean
     */
    public function isModuleIncluded($module)
    {
        // The base of the files needed for checking
        $base = "modules/$module/";

        // The studio.php file path for this module
        $studioFile = "{$base}metadata/studio.php";

        // The state of this module, it determines the view file to check
        $bwc = isModuleBWC($module);
        if ($bwc) {
            $viewFile = $base . 'metadata/detailviewdefs.php';
        } else {
            $viewFile = $base . 'clients/base/views/record/record.php';
        }

        // Send back our result
        return SugarAutoLoader::existingCustomOne($studioFile)
               && file_exists($viewFile);
    }

    /**
     * @see SugarView::display()
     */
    public function display()
    {
        $source = $this->request->getValidInputRequest('source_id', 'Assert\ComponentName');
        $sources = ConnectorUtils::getConnectors();
        $modules_sources = ConnectorUtils::getDisplayConfig();

        $enabled_modules = $this->getEnabledModules($modules_sources, $source);
        $disabled_modules = $this->getDisabledModules($enabled_modules);

        // Used for filtering the lists
        $s = SourceFactory::getSource($source);

        // Not all sources can be connected to all modules
        $enabled_modules = $s->filterAllowedModules($enabled_modules);
        $disabled_modules = $s->filterAllowedModules($disabled_modules);

        // Sort the lists
        asort($enabled_modules);
        asort($disabled_modules);

        // Template assignments...
        $this->ss->assign('enabled_modules', $enabled_modules);
        $this->ss->assign('disabled_modules', $disabled_modules);
        $this->ss->assign('source_id', $source);
        $this->ss->assign('mod', $GLOBALS['mod_strings']);
        $this->ss->assign('APP', $GLOBALS['app_strings']);
        $this->ss->assign('theme', $GLOBALS['theme']);
        $this->ss->assign('external', !empty($sources[$source]['eapm']));
        $this->ss->assign('externalOnly', !empty($sources[$source]['eapm']['only']));

        // We don't want to tell the user to set the properties of the connector
        // if there aren't any
        $fields = $s->getRequiredConfigFields();
        $this->ss->assign('externalHasProperties', !empty($fields));

        $this->ss->assign('externalChecked', !empty($sources[$source]['eapm']['enabled']) ? " checked" : "");
        echo $this->ss->fetch($this->getCustomFilePathIfExists('modules/Connectors/tpls/display_properties.tpl'));
    }
}
