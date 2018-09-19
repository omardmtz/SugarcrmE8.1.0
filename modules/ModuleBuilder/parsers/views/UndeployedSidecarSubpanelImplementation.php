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

require_once 'modules/ModuleBuilder/parsers/constants.php';

use Sugarcrm\Sugarcrm\Util\Files\FileLoader;

class UndeployedSidecarSubpanelImplementation extends AbstractMetaDataImplementation implements MetaDataImplementationInterface
{

    const HISTORYFILENAME = 'restored.php';
    const HISTORYVARIABLENAME = 'viewdefs';

    /**
     * The constructor
     * @param string $subpanelName
     * @param string $moduleName
     * @param string $packageName
     * @param string $client
     */
    public function __construct($subpanelName, $moduleName, $packageName, $client = '')
    {
        $this->mdc = new MetaDataConverter();
        $this->_subpanelName = $subpanelName;
        $this->_moduleName = $moduleName;
        $this->client = (empty($client)) ? 'base' : $client;

        // TODO: history
        $this->historyPathname = "custom/history/modulebuilder/packages/{$packageName}/modules/{$moduleName}/metadata/" . self::HISTORYFILENAME;
        $this->_history = new History($this->historyPathname);

        //get the bean from ModuleBuilder
        $mb = new ModuleBuilder();
        $this->module = $mb->getPackageModule($packageName, $moduleName);
        $this->module->mbvardefs->updateVardefs();

        $templates = $this->module->config['templates'];
        $template_def = "";
        foreach ($templates as $template => $a) {
            if ($a === 1) {
                $template_def = $template;
            }
        }

        $template_subpanel_def = "include/SugarObjects/templates/{$template_def}/clients/{$this->client}/views/subpanel-list/subpanel-list.php";

        $viewdefs = array();
        if (file_exists($template_subpanel_def)) {
            include $template_subpanel_def;
            if (isset($viewdefs['<module_name>'])) {
                $viewdefs[$this->module->key_name] = $viewdefs['<module_name>'];
                unset($viewdefs['<module_name>']);
            }
        }

        if ($subpanelName != 'default' && !stristr($subpanelName, 'for')) {
            $subpanelName = 'For' . ucfirst($subpanelName);
        }
        $this->sidecarSubpanelName = $this->mdc->fromLegacySubpanelName($subpanelName);

        // Set the original view defs from the loaded file if there are any
        $this->_originalViewdefs = $this->getNewViewDefs($viewdefs);

        $this->sidecarFile = $this->module->getSubpanelFilePath($subpanelName, $this->client);

        if (file_exists($this->sidecarFile)) {
            include FileLoader::validateFilePath($this->sidecarFile);
        }
        $viewdefs = empty($viewdefs) ? array() : $viewdefs;

        $this->_viewdefs = $this->getNewViewDefs($viewdefs);

        $this->_fielddefs = $this->getFieldDefs();
        $this->_paneldefs = isset($this->_viewdefs['panels']) ? $this->_viewdefs['panels'] : array();

        // Set the global mod_strings directly as Sugar does not automatically load the
        // language files for undeployed modules (how could it?)
        $selected_lang = 'en_us';
        if (isset($GLOBALS['current_language']) && !empty($GLOBALS['current_language'])) {
            $selected_lang = $GLOBALS['current_language'];
        }
        $GLOBALS ['mod_strings'] = array_merge($GLOBALS['mod_strings'], $this->module->getModStrings($selected_lang));
    }

    /**
     * Get the new listview defs in viewdefs
     * @param array $viewDefs the viewdefs array
     * @return array the listviewDefs
     */
    public function getNewViewDefs(array $viewDefs)
    {
        if (isset($viewDefs[$this->module->key_name][$this->client]['view'][$this->sidecarSubpanelName])) {
            return $viewDefs[$this->module->key_name][$this->client]['view'][$this->sidecarSubpanelName];
        }

        return array();
    }

    /**
     * Get the Fielddefs
     * @return array
     */
    public function getFieldDefs()
    {
        $vardef = $this->module->getVardefs();
        return $vardef['fields'];
    }

    /**
     * Get the language
     * @return string
     */
    public function getLanguage()
    {
        return ""; // '' is the signal to translate() to use the global mod_strings
    }

    /**
     * Returns subpanel primary module name
     *
     * @return string
     */
    public function getPrimaryModuleName()
    {
        return $this->_moduleName;
    }

    /**
     * Returns subpanel link name
     *
     * @return string
     */
    public function getLinkName()
    {
        return null;
    }

    /*
     * Save a subpanel
     * @param array defs    Layout definition in the same format as received by the constructor
     */
    public function deploy($defs)
    {
        $outputDefs = $this->module->getAvailableSubpanelDef($this->_subpanelName);
        write_array_to_file(self::HISTORYVARIABLENAME, $outputDefs, $this->historyPathname);
        $this->_history->append($this->historyPathname);
        $this->_viewdefs = $defs;

        if (!is_dir(dirname($this->sidecarFile))) {
            if (!mkdir(dirname($this->sidecarFile), 0755, true)) {
                throw new Exception(sprintf("Cannot create directory %s", $this->sidecarFile));
            }
        }
        $this->module->saveAvailableSubpanelDef($this->sidecarSubpanelName, $this->_viewdefs);
    }

}
