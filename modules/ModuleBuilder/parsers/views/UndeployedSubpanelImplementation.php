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
/*
 * Changes to AbstractSubpanelImplementation for DeployedSubpanels
 * The main differences are in the load and save of the definitions
 * For subpanels we must make use of the SubPanelDefinitions class to do this; this also means that the history mechanism,
 * which tracks files, not objects, needs us to create an intermediate file representation of the definition that it can manage and restore
 */

require_once 'modules/ModuleBuilder/parsers/constants.php' ;

class UndeployedSubpanelImplementation extends AbstractMetaDataImplementation implements MetaDataImplementationInterface
{
    /**
     * Fields that are on the existing layout that aren't really fields, like
     * action buttons, that still need to stay on the layout after saving
     * 
     * @var array
     */
    protected $nonFields = array();

    const HISTORYFILENAME = 'restored.php' ;
    const HISTORYVARIABLENAME = 'layout_defs' ;

    /*
     * Constructor
     * @param string subpanelName   The name of this subpanel
     * @param string moduleName     The name of the module to which this subpanel belongs
     * @param string packageName    If not empty, the name of the package to which this subpanel belongs
     */
    function __construct ($subpanelName , $moduleName , $packageName)
    {
        // Needed to tap into the abstract for loading and saving
        $this->_view = 'subpanel_layout';
        $this->_fileVariables['subpanel_layout'] = 'subpanel_layout';

        $this->_subpanelName = $subpanelName ;
        $this->_moduleName = $moduleName ;

        // TODO: history
        $this->historyPathname = 'custom/history/modulebuilder/packages/' . $packageName . '/modules/' . $moduleName . '/metadata/' . self::HISTORYFILENAME ;
        $this->_history = new History ( $this->historyPathname ) ;

        //get the bean from ModuleBuilder
        $mb = new ModuleBuilder ( ) ;
        $this->module = & $mb->getPackageModule ( $packageName, $moduleName ) ;
        $this->module->mbvardefs->updateVardefs () ;
        $this->_fielddefs = & $this->module->mbvardefs->vardefs [ 'fields' ] ;

        $templates = & $this->module->config['templates'];
        $template_def="";
        foreach ($templates as $template => $a) {
            if ($a === 1) {
                $template_def = $template;
            }
        }

        $templateFile = 'include/SugarObjects/templates/'.$template_def. '/metadata/subpanels/default.php';
         if (file_exists($templateFile)){
            $subpanel_layout = $this->_loadFromFile($templateFile);
            if (!empty($subpanel_layout['list_fields'])) {
                $originalDef = $subpanel_layout['list_fields'];
                // This has to be done early because once they are in field defs
                // they won't be picked up
                $this->setNonFields($originalDef);
                $this->_mergeFielddefs($this->_fielddefs, $originalDef);
            }
        }

        $filename = $this->module->getSubpanelFilePath($this->_subpanelName, '', true);
        $subpanel_layout = $this->_loadFromFile($filename);
        $this->_originalViewdefs = $subpanel_layout['list_fields'];
        $this->_viewdefs = & $subpanel_layout [ 'list_fields' ] ;
        $this->_mergeFielddefs($this->_fielddefs, $this->_viewdefs);
        
        // Set the global mod_strings directly as Sugar does not automatically load the language files for undeployed modules (how could it?)
        $selected_lang = 'en_us';
        if(isset($GLOBALS['current_language']) &&!empty($GLOBALS['current_language'])) {
            $selected_lang = $GLOBALS['current_language'];
        }
        $GLOBALS [ 'mod_strings' ] = array_merge ( $GLOBALS [ 'mod_strings' ], $this->module->getModStrings ($selected_lang) ) ;
    }

    function getLanguage ()
    {
        return "" ; // '' is the signal to translate() to use the global mod_strings
    }

    /*
     * Save a subpanel
     * @param array defs    Layout definition in the same format as received by the constructor
     * @param string type   The location for the file - for example, MB_BASEMETADATALOCATION for a location in the OOB metadata directory
     */
    function deploy ($defs)
    {
        $outputDefs = $this->module->getAvailableSubpanelDef($this->_subpanelName, '', true);
        // first sort out the historical record...
        // copy the definition to a temporary file then let the history object add it
        write_array_to_file ( self::HISTORYVARIABLENAME, $outputDefs, $this->historyPathname, 'w', '' ) ;
        $this->_history->append ( $this->historyPathname ) ;

        // Add in the non fields that might have been stripped out earlier, using
        // the latest from the field defs since those might have transformed 
        // placeholders
        $nonFields = array();
        foreach ($this->nonFields as $field => $fieldDef) {
            if (isset($this->_fielddefs[$field])) {
                $nonFields[$field] = $this->_fielddefs[$field];
            }
        }

        // Bring in the non fields now that they have been cleaned up
        $defs = array_merge($defs, $nonFields);
        $outputDefs [ 'list_fields' ] = $defs ;
        $this->_viewdefs = $defs ;
        
        $filename = $this->module->getSubpanelFilePath($this->_subpanelName, '', true);
        $this->_saveToFile($filename, $outputDefs, true, true);

    }

    /** 
     * Sets fields from the layout that are not really fields into a temporary
     * placeholder for inclusion just prior to saving
     * 
     * @param array $defs The defs to scan for non fields
     */
    public function setNonFields($defs)
    {
        foreach ($defs as $field => $def) {
            $field = strtolower($field);
            if (!isset($this->_fielddefs[$field]) || (isset($def['usage']) && $def['usage'] == 'query_only')) {
                $this->nonFields[$field] = $def;
            }
        }
    }
}
