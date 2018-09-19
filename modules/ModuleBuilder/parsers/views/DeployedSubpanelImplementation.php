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
use Sugarcrm\Sugarcrm\Util\Files\FileLoader;

require_once 'modules/ModuleBuilder/parsers/constants.php' ;

class DeployedSubpanelImplementation extends AbstractMetaDataImplementation implements MetaDataImplementationInterface
{

    const HISTORYFILENAME = 'restored.php' ;
    const HISTORYVARIABLENAME = 'layout_defs' ;

    private $_subpanelName ;
    private $_aSubPanelObject ; // an aSubPanel Object representing the current subpanel


    /*
     * Constructor
     * @param string subpanelName   The name of this subpanel
     * @param string moduleName     The name of the module to which this subpanel belongs
     */
    function __construct ($subpanelName , $moduleName)
    {
        $GLOBALS [ 'log' ]->debug ( get_class ( $this ) . "->__construct($subpanelName , $moduleName)" ) ;
        $this->_subpanelName = $subpanelName ;
        $this->_moduleName = $moduleName ;

        $module = BeanFactory::newBean($moduleName);
        // BEGIN ASSERTIONS
        if (empty($module))
        {
            sugar_die ( get_class ( $this ) . ": Modulename $moduleName is not a Deployed Module" ) ;
        }
        // END ASSERTIONS

        $this->historyPathname = 'custom/history/modules/' . $moduleName . '/subpanels/' . $subpanelName . '/' . self::HISTORYFILENAME ;
        $this->_history = new History ( $this->historyPathname ) ;

        // retrieve the definitions for all the available subpanels for this module from the subpanel
        $spd = new SubPanelDefinitions ( $module ) ;

        // Get the lists of fields already in the subpanel and those that can be added in
        // Get the fields lists from an aSubPanel object describing this subpanel from the SubPanelDefinitions object
        $this->_viewdefs = array ( ) ;
        $this->_fielddefs = array ( ) ;
        $this->_language = '' ;
        if (! empty ( $spd->layout_defs ))
            if (array_key_exists ( strtolower ( $subpanelName ), $spd->layout_defs [ 'subpanel_setup' ] ))
            {
                //First load the original defs from the module folder
                $originalSubpanel = $spd->load_subpanel( $subpanelName , false, true);
                $this->_fullFielddefs = $originalSubpanel ? $originalSubpanel->get_list_fields () : array();
                $this->_mergeFielddefs ( $this->_fielddefs , $this->_fullFielddefs ) ;

                $this->_aSubPanelObject = $spd->load_subpanel ( $subpanelName ) ;
                // now check if there is a restored subpanel in the history area - if there is, then go ahead and use it
                if (file_exists ( $this->historyPathname ))
                {
                    // load in the subpanelDefOverride from the history file
                    $GLOBALS [ 'log' ]->debug ( get_class ( $this ) . ": loading from history" ) ;
                    require FileLoader::validateFilePath($this->historyPathname);
                    $this->_viewdefs = $layout_defs;
                } else
                {
                    $this->_viewdefs = $this->_aSubPanelObject->get_list_fields () ;
                }

                // don't attempt to access the template_instance property if our subpanel represents a collection, as it won't be there - the sub-sub-panels get this value instead
                if ( ! $this->_aSubPanelObject->isCollection() )
                    $this->_language = $this->_aSubPanelObject->template_instance->module_dir ;

                // Retrieve a copy of the bean for the parent module of this subpanel - so we can find additional fields for the layout
                $subPanelParentModuleName = $this->_aSubPanelObject->get_module_name () ;
                $beanListLower = array_change_key_case ( $GLOBALS [ 'beanList' ] ) ;
                if (! empty ( $subPanelParentModuleName ) && isset ( $beanListLower [ strtolower ( $subPanelParentModuleName ) ] ))
                {
                    $subPanelParentModule = get_module_info ( $subPanelParentModuleName ) ;

                    // Run through the preliminary list, keeping only those fields that are valid to include in a layout
                    foreach ( $subPanelParentModule->field_defs as $key => $def )
                    {
                        $key = strtolower ( $key ) ;

                        if (AbstractMetaDataParser::validField( $def ))
                        {
                        	if ( ! isset ( $def [ 'label' ] ) )
                        		$def [ 'label' ] = $def [ 'name' ] ;
                            $this->_fielddefs [ $key ] = $def ;
                        }
                    }
                }

                $this->_mergeFielddefs ( $this->_fielddefs , $this->_viewdefs ) ;
            }

    }

    function getLanguage ()
    {
        return $this->_language ;
    }

    /*
     * Save a definition that will be used to display a subpanel for $this->_moduleName
     * @param array defs    Layout definition in the same format as received by the constructor
     */
    function deploy ($defs)
    {
        // first sort out the historical record...
        write_array_to_file ( self::HISTORYVARIABLENAME, $this->_viewdefs, $this->historyPathname, 'w', '' ) ;
        $this->_history->append ( $this->historyPathname ) ;

        $this->_viewdefs = $defs ;

        $subpanel = new SubPanel ( $this->_moduleName, 'fab4', $this->_subpanelName , $this->_aSubPanelObject ) ;

        $subpanel->saveSubPanelDefOverride ( $this->_aSubPanelObject, 'list_fields', $defs ) ;

        // now clear the cache so that the results are immediately visible
        MetaDataFiles::clearModuleClientCache($this->_aSubPanelObject->template_instance->module_name, 'view');
        MetaDataFiles::clearModuleClientCache($this->_moduleName, 'layout');

        include_once ('include/TemplateHandler/TemplateHandler.php') ;
        TemplateHandler::clearCache ( $this->_moduleName ) ;

    }

}
