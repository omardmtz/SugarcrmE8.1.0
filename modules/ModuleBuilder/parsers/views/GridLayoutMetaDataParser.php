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

require_once 'modules/ModuleBuilder/parsers/constants.php' ;

class GridLayoutMetaDataParser extends AbstractMetaDataParser implements MetaDataParserInterface
{

    static $variableMap = array (
    	MB_EDITVIEW => 'EditView' ,
    	MB_DETAILVIEW => 'DetailView' ,
    	MB_QUICKCREATE => 'QuickCreate',
    	MB_WIRELESSEDITVIEW => 'EditView' ,
    	MB_WIRELESSDETAILVIEW => 'DetailView' ,
        MB_PORTALRECORDVIEW => array('portal','view','record'),
    	) ;

	protected $FILLER ;

    /**
     * List of fieldset fields. Will be an array once it is set, set initially
     * to a null to indicated fields haven't been checked yet since not all modules
     * have fieldsets in their defs and continually traversing fielddefs gets old.
     * 
     * This is used by the sidecar grid parser but is defined here since it is set
     * by a call in the constructor.
     * 
     * @var array
     */
    protected $fieldsetMemberFields = null;

    /**
     * Field parameters defined in base (standard) view
     *
     * @var array
     */
    protected $baseViewFields = array();

	/**
     * Constructor
     * @param string $view           The view type, that is, editview, searchview etc
     * @param string $moduleName     The name of the module to which this view belongs
     * @param string $packageName    If not empty, the name of the package to which this view belongs
     * @param string $client         The client making the request for this parser
     * @param array  $params         Additional parser parameters
     */
    public function __construct($view, $moduleName, $packageName = '', $client = '', array $params = array())
    {
        $GLOBALS [ 'log' ]->debug ( get_class ( $this ) . "->__construct( {$view} , {$moduleName} , {$packageName} )" ) ;

        // set the client
        $this->client = $client;

        $view = strtolower ( $view ) ;

		$this->FILLER = array ( 'name' => MBConstants::$FILLER['name'] , 'label' => translate ( MBConstants::$FILLER['label'] ) ) ;

        $this->_moduleName = $moduleName ;
        $this->_view = $view ;

        if (empty ( $packageName ))
        {
            $this->implementation = new DeployedMetaDataImplementation($view, $moduleName, $client, $params);
        } else
        {
            $this->implementation = new UndeployedMetaDataImplementation ( $view, $moduleName, $packageName, $client ) ;
        }

        $viewdefs = $this->implementation->getViewdefs () ;
        if (MetaDataFiles::getViewDefVar($view) === null) {
            MetaDataFiles::setViewDefVar($view, $view);
        }

        if (!$this->hasViewVariable($viewdefs, $view)) {
            sugar_die ( get_class ( $this ) . ": incorrect view variable for $view" ) ;
        }

        $viewdefs = $this->getDefsFromArray($viewdefs, $view);
        $this->validateMetaData($viewdefs);
        $this->_viewdefs = $viewdefs ;
        if ($this->getMaxColumns () < 1)
            sugar_die ( get_class ( $this ) . ": maxColumns=" . $this->getMaxColumns () . " - must be greater than 0!" ) ;

        $this->_fielddefs =  $this->implementation->getFielddefs() ;
        $this->_standardizeFieldLabels( $this->_fielddefs );
        // put into our internal format
        $this->_viewdefs['panels'] = $this->_convertFromCanonicalForm($this->_viewdefs['panels']);
        $this->_originalViewDef = $this->getFieldsFromLayout($this->implementation->getOriginalViewdefs ());
        $this->baseViewFields = $this->getFieldsFromLayout($this->implementation->getBaseViewdefs());

        // Setup the fieldset member fields. Used by sidecar.
        $this->setFieldsetMemberFields();
    }

    /**
     * Sets the fields that are part of fieldsets. Since this is used by sidecar
     * and not BWC, it is safe to just set this to an array here.
     */
    protected function setFieldsetMemberFields()
    {
        $this->fieldsetMemberFields = array();
    }

    /**
     * Checks for necessary elements of the metadata array and fails the request
     * if not found
     *
     * @param array $viewdefs The view defs being requested
     * @return void
     */
    public function validateMetaData($viewdefs) {
        if (!isset($viewdefs['templateMeta'])) {
            sugar_die(get_class($this) . ': missing templateMeta section in layout definition (case sensitive)');
        }

        if (!isset($viewdefs['panels'])) {
            sugar_die(get_class($this) . ': missing panels section in layout definition (case sensitive)');
        }
    }

    public function hasViewVariable($viewdefs, $view) {
        $name = MetaDataFiles::getViewDefVar($view);
        return $name && isset($viewdefs[$name]);
    }

    public function getDefsFromArray($viewdefs, $view) {
        return $this->hasViewVariable($viewdefs, $view) ? $viewdefs[MetaDataFiles::getViewDefVar($view)] : array();
    }

    /*
     * Save a draft layout
     */
    function writeWorkingFile ($populate = true)
    {
        if ($populate)
            $this->_populateFromRequest ( $this->_fielddefs ) ;

        $viewdefs = $this->_viewdefs ;
        $viewdefs [ 'panels' ] = $this->_convertToCanonicalForm( $this->_viewdefs [ 'panels' ] , $this->_fielddefs );
        $this->implementation->save(MetaDataFiles::mapPathToArray(MetaDataFiles::getViewDefVar($this->_view),$viewdefs));
    }

    /*
     * Deploy the layout
     * @param boolean $populate If true (default), then update the layout first with new layout information from the $_REQUEST array
     */
    function handleSave ($populate = true, $clearCache = true)
    {
    	$GLOBALS [ 'log' ]->info ( get_class ( $this ) . "->handleSave()" ) ;

        if ($populate)
            $this->_populateFromRequest ( $this->_fielddefs ) ;

        $viewdefs = $this->_viewdefs ;
        $viewdefs [ 'panels' ] = $this->_convertToCanonicalForm ( $this->_viewdefs [ 'panels' ] , $this->_fielddefs ) ;
        $this->implementation->deploy(MetaDataFiles::mapPathToArray(MetaDataFiles::getViewDefVar($this->_view),$viewdefs));
        if ($clearCache) {
            $this->_clearCaches();
        }
    }

    /*
     * Return the layout, padded out with (empty) and (filler) fields ready for display
     */
    function getLayout ()
    {
    	$viewdefs = array () ;
    	$fielddefs = $this->_fielddefs;
    	$fielddefs [ $this->FILLER [ 'name' ] ] = $this->FILLER ;
    	$fielddefs [ MBConstants::$EMPTY [ 'name' ] ] = MBConstants::$EMPTY ;

		foreach ( $this->_viewdefs [ 'panels' ] as $panelID => $panel )
        {
            foreach ( $panel as $rowID => $row )
            {
                foreach ( $row as $colID => $fieldname )
                {
                    // Gets viewdefs from a fieldname value if there is one
                    $defs = $this->getViewDefFromFieldname($fieldname);
                    if ($defs === false) {
                        continue;
                    }

                    $viewdefs[$panelID][$rowID][$colID] = $defs;
                }
            }
        }
        return $viewdefs ;
    }

    /*
    * Return the tab definitions for tab/panel combo
    */
    function getTabDefs ()
    {
      $tabDefs = array();
      $this->setUseTabs( false );
      foreach ( $this->_viewdefs [ 'panels' ] as $panelID => $panel )
      {

        $tabDefs [ strtoupper($panelID) ] = array();

        // panel or tab setting
        if ( isset($this->_viewdefs [ 'templateMeta' ] [ 'tabDefs' ] [ strtoupper($panelID) ] [ 'newTab' ])
        && is_bool($this->_viewdefs [ 'templateMeta' ] [ 'tabDefs' ] [ strtoupper($panelID) ] [ 'newTab' ]))
        {
          $tabDefs [ strtoupper($panelID) ] [ 'newTab' ] = $this->_viewdefs [ 'templateMeta' ] [ 'tabDefs' ] [ strtoupper($panelID) ] [ 'newTab' ];
          if ($tabDefs [ strtoupper($panelID) ] [ 'newTab' ] == true)
              $this->setUseTabs( true );
        }
        else
        {
          $tabDefs [ strtoupper($panelID) ] [ 'newTab' ] = false;
        }

        // collapsed panels
        if ( isset($this->_viewdefs [ 'templateMeta' ] [ 'tabDefs' ] [ strtoupper($panelID) ] [ 'panelDefault' ])
        && $this->_viewdefs [ 'templateMeta' ] [ 'tabDefs' ] [ strtoupper($panelID) ] [ 'panelDefault' ] == 'collapsed' )
        {
          $tabDefs [ strtoupper($panelID) ] [ 'panelDefault' ] = 'collapsed';
        }
        else
        {
          $tabDefs [ strtoupper($panelID) ] [ 'panelDefault' ] = 'expanded';
        }
      }
      return $tabDefs;
    }

    /*
     * Set tab definitions
     */
    function setTabDefs($tabDefs) {
      $this->_viewdefs [ 'templateMeta' ] [ 'tabDefs' ] = $tabDefs;
    }

    function getMaxColumns ()
    {
        if (!empty( $this->_viewdefs) && isset($this->_viewdefs [ 'templateMeta' ] [ 'maxColumns' ]))
		{
			return $this->_viewdefs [ 'templateMeta' ] [ 'maxColumns' ] ;
		}else
		{
			return 2;
		}
    }

    function getAvailableFields ()
    {
        // Obtain the full list of valid fields in this module
    	$availableFields = array () ;
        foreach ( $this->_fielddefs as $key => $def )
        {

            if ( $this->isValidField($key, $def) || isset($this->_originalViewDef[$key]) )
            {
                //If the field original label existing, we should use the original label instead the label in its fielddefs.
            	if(isset($this->_originalViewDef[$key]) && is_array($this->_originalViewDef[$key]) && isset($this->_originalViewDef[$key]['label'])){
                    $availableFields [ $key ] = array ( 'name' => $key , 'label' => $this->_originalViewDef[$key]['label']) ;
                }else{
                    $availableFields [ $key ] = array ( 'name' => $key , 'label' => isset($def [ 'label' ]) ? $def [ 'label' ] : $def['vname'] ) ; // layouts use 'label' not 'vname' for the label entry
                }

                $availableFields[$key]['translatedLabel'] = translate( isset($def [ 'label' ]) ? $def [ 'label' ] : $def['vname'], $this->_moduleName);
                if (isset($this->_originalViewDef[$key]['type']) && $this->_originalViewDef[$key]['type'] == 'fieldset') {
                    $availableFields[$key]['fieldset'] = true;
                
                    if (isset($this->_originalViewDef[$key]['fields'])) {
                        $availableFields[$key]['fieldset_fields'] = $this->getFieldsetFields($this->_originalViewDef[$key]['fields']);
                    }
                }
            }

        }

		// Available fields are those that are in the Model and the original layout definition, but not already shown in the View
        // So, because the formats of the two are different we brute force loop through View and unset the fields we find in a copy of Model
        if (! empty ( $this->_viewdefs ))
        {
            foreach ( $this->_viewdefs [ 'panels' ] as $panel )
            {
                foreach ( $panel as $row )
                {
                    foreach ( $row as $field )
                    {
                        // Remove this field from the available fields array
                        $this->unsetAvailableField($availableFields, $field);
                    }
                }
            }
        }

        // Record view hides the header panel, but the fields in it need to still
        // be removed
        $this->unsetHiddenPanelFields($availableFields);

        //eggsurplus: Bug 10329 - sort on intuitive display labels
        //sort by translatedLabel
        // See cmpLabel() method

        usort($availableFields , array($this, 'cmpLabel'));
        return $availableFields ;
    }

    /**
     * Compares translated label for sorting. Originally, this function was in
     * the getAvailableFields() method but was throwing "cannot redefine function"
     * errors in some cases. Moved to its own method to eliminate that.
     *
     * eggsurplus: Bug 10329 - sort on intuitive display labels
     *
     * @param array $a The first sort side
     * @param array $b The second sort side
     * @return int -1, 0 or 1 based on result of strcmp() function call
     */
    protected function cmpLabel($a, $b) {
        return strcmp($a["translatedLabel"], $b["translatedLabel"]);
    }

    function getPanelDependency ( $panelID )
    {
    	if ( ! isset ( $this->_viewdefs [ 'templateMeta' ][ 'dependency' ] ) && ! isset ( $this->_viewdefs [ 'templateMeta' ][ 'dependency' ] [ $panelID ] ) )
    		return false;

    	return $this->_viewdefs  [ 'templateMeta' ][ 'dependency' ] [ $panelID ] ;
    }

    /*
     * Add a new field to the layout
     * If $panelID is passed in, attempt to add to that panel, otherwise add to the first panel
     * The field is added in place of the first empty (not filler) slot after the last field in the panel; if that row is full, then a new row will be added to the end of the panel
     * and the field added to the start of it.
     * @param array $def Set of properties for the field, in same format as in the viewdefs
     * @param string $panelID Identifier of the panel to add the field to; empty or false if we should use the first panel
     */
    function addField ( $def , $panelID = FALSE)
    {

        if (count ( $this->_viewdefs [ 'panels' ] ) == 0)
        {
            $GLOBALS [ 'log' ]->error ( get_class ( $this ) . "->addField(): _viewdefs empty for module {$this->_moduleName} and view {$this->_view}" ) ;
        }

        // if a panelID was not provided, use the first available panel in the list
        if (! $panelID)
        {
            $panels = array_keys ( $this->_viewdefs [ 'panels' ] ) ;
            list ( $dummy, $panelID ) = each ( $panels ) ;
        }

        if (isset ( $this->_viewdefs [ 'panels' ] [ $panelID ] ))
        {

            $panel = $this->_viewdefs [ 'panels' ] [ $panelID ] ;
            if(!empty($panel)) {
                $lastrow = count ( $panel ) - 1 ; // index starts at 0
                $lastRowDef = $this->_viewdefs [ 'panels' ] [ $panelID ] [ $lastrow ];
            } else {
                $lastrow = 0;
                $lastRowDef = array();
            }
            $maxColumns = $this->getMaxColumns () ;
            for ( $column = 0 ; $column < $maxColumns ; $column ++ )
            {
                if (! isset ( $lastRowDef [ $column ] )
                        || (is_array( $lastRowDef [ $column ]) && $lastRowDef [ $column ][ 'name' ] == '(empty)')
                        || (is_string( $lastRowDef [ $column ]) && $lastRowDef [ $column ] == '(empty)')
                ){
                    break ;
                }
            }

            // if we're on the last column of the last row, start a new row
            if ($column >= $maxColumns)
            {
                $lastrow ++ ;
                $this->_viewdefs [ 'panels' ] [ $panelID ] [ $lastrow ] = array ( ) ;
                $column = 0 ;
            }

            $this->_viewdefs [ 'panels' ] [ $panelID ] [ $lastrow ] [ $column ] = $def [ 'name' ] ;
            // now update the fielddefs
            if (isset($this->_fielddefs [ $def [ 'name' ] ]))
            {
                $this->_fielddefs [ $def [ 'name' ] ] = array_merge ( $this->_fielddefs [ $def [ 'name' ] ] , $def ) ;
            } else
            {
            	$this->_fielddefs [ $def [ 'name' ] ] = $def;
            }
        }
        return true ;
    }

    /*
     * Remove all instances of a field from the layout, and replace by (filler)
     * Filler because we attempt to preserve the customized layout as much as possible - replacing by (empty) would mean that the positions or sizes of adjacent fields may change
     * If the last row of a panel only consists of (filler) after removing the fields, then remove the row also. This undoes the standard addField() scenario;
     * If the fields had been moved around in the layout however then this will not completely undo any addField()
     * @param string $fieldName Name of the field to remove
     * @return boolean True if the field was removed; false otherwise
     */
    function removeField ($fieldName)
    {
        $GLOBALS [ 'log' ]->info ( get_class ( $this ) . "->removeField($fieldName)" ) ;

        $result = false ;
        if (!is_array($this->_viewdefs['panels'])) {
            return $result;
        }
        reset ( $this->_viewdefs ) ;
        $firstPanel = each ( $this->_viewdefs [ 'panels' ] ) ;
        $firstPanelID = $firstPanel [ 'key' ] ;

        foreach ( $this->_viewdefs [ 'panels' ] as $panelID => $panel )
        {
            $lastRowTouched = false ;
            $lastRowID = count ( $this->_viewdefs [ 'panels' ] [ $panelID ] ) - 1 ; // zero offset

            foreach ( $panel as $rowID => $row )
            {

                foreach ( $row as $colID => $field )
                    if ($field == $fieldName)
                    {
                        $lastRowTouched = $rowID ;
                        $this->_viewdefs [ 'panels' ] [ $panelID ] [ $rowID ] [ $colID ] = $this->FILLER [ 'name' ];
                    }

            }

            // if we removed a field from the last row of this panel, tidy up if the last row now consists only of (empty) or (filler)

            if ( $lastRowTouched ==  $lastRowID )
            {
                $lastRow = $this->_viewdefs [ 'panels' ] [ $panelID ] [ $lastRowID ] ; // can't use 'end' for this as we need the key as well as the value...

                $empty = true ;

                foreach ( $lastRow as $colID => $field )
                    $empty &=  $field == MBConstants::$EMPTY ['name' ] || $field == $this->FILLER [ 'name' ]  ;

                if ($empty)
                {
                    unset ( $this->_viewdefs [ 'panels' ] [ $panelID ] [ $lastRowID ] ) ;
                    // if the row was the only one in the panel, and the panel is not the first (default) panel, then remove the panel also
					if ( count ( $this->_viewdefs [ 'panels' ] [ $panelID ] ) == 0 && $panelID != $firstPanelID )
						unset ( $this->_viewdefs [ 'panels' ] [ $panelID ] ) ;
                }

            }

            $result |= ($lastRowTouched !== false ); // explicitly compare to false as row 0 will otherwise evaluate as false
        }

        return $result ;

    }

    function setPanelDependency ( $panelID , $dependency )
    {
    	// only accept dependencies for pre-existing panels
    	if ( ! isset ( $this->_viewdefs [ 'panels' ] [ $panelID ] ) )
    		return false;

    	$this->_viewdefs  [ 'templateMeta' ] [ 'dependency' ] [ $panelID ] = $dependency ;
    	return true ;
    }

    /*
     * Return an integer value for the next unused panel identifier, such that it and any larger numbers are guaranteed to be unused already in the layout
     * Necessary when adding new panels to a layout
     * @return integer First unique panel ID suffix
     */
    function getFirstNewPanelId ()
    {
        $firstNewPanelId = 0 ;
        foreach ( $this->_viewdefs [ 'panels' ] as $panelID => $panel )
        {
            // strip out all but the numerics from the panelID - can't just use a cast as numbers may not be first in the string
            for ( $i = 0, $result = '' ; $i < strlen ( $panelID ) ; $i ++ )
            {
                if (is_numeric ( $panelID [ $i ] ))
                {
                    $result .= $panelID [ $i ] ;
                }
            }

            $firstNewPanelId = max ( ( int ) $result, $firstNewPanelId ) ;
        }
        return $firstNewPanelId + 1 ;
    }

    /*
     * Load the panel layout from the submitted form and update the _viewdefs
     */
    protected function _populateFromRequest ( &$fielddefs )
    {
        $GLOBALS [ 'log' ]->debug ( get_class ( $this ) . "->populateFromRequest()" ) ;
        $i = 1 ;

        // set up the map of panel# (as provided in the _REQUEST) to panel ID (as used in $this->_viewdefs['panels'])
        $i = 1 ;
        foreach ( $this->_viewdefs [ 'panels' ] as $panelID => $panel )
        {
            $panelMap [ $i ++ ] = $panelID ;
        }

        foreach ( $_REQUEST as $key => $displayLabel )
        {
            $components = explode ( '-', $key ) ;
            if ($components [ 0 ] == 'panel' && $components [ 2 ] == 'label')
            {
                $panelMap [ $components [ '1' ] ] = $displayLabel ;
            }
        }

        $this->_viewdefs [ 'panels' ] = array () ; // because the new field properties should replace the old fields, not be merged

        // run through the $_REQUEST twice - first to obtain the fieldnames, the second to update the field properties
        for ( $pass=1 ; $pass<=2 ; $pass++ )
        {
        	foreach ( $_REQUEST as $slot => $value )
        	{
            	$slotComponents = explode ( '-', $slot ) ; // [0] = 'slot', [1] = panel #, [2] = slot #, [3] = property name

            	if ($slotComponents [ 0 ] == 'slot')
            	{
                	$slotNumber = $slotComponents [ '2' ] ;
                	$panelID = $panelMap [ $slotComponents [ '1' ] ] ;
                	$rowID = floor ( $slotNumber / $this->getMaxColumns () ) ;
                	$colID = $slotNumber - ($rowID * $this->getMaxColumns ()) ;
                	$property = $slotComponents [ '3' ] ;

                	//If this field has a custom definition, copy that over
                	if ( $pass == 1 )
                	{
                		if ( $property == 'name' )
                    		$this->_viewdefs [ 'panels' ] [ $panelID ] [ $rowID ] [ $colID ] = $value ;
                	} else
                	{
                		// update fielddefs for this property in the provided position
                		if ( isset ( $this->_viewdefs [ 'panels' ] [ $panelID ] [ $rowID ] [ $colID ] ) )
                		{
                			$fieldname = $this->_viewdefs [ 'panels' ] [ $panelID ] [ $rowID ] [ $colID ] ;
							$fielddefs [ $fieldname ] [ $property ] = $value ;
                		}
                	}
            	}

        	}
        }

        //Set the tab definitions
        $tabDefs = array();
        $this->setUseTabs( false );
        foreach ( $this->_viewdefs [ 'panels' ] as $panelID => $panel )
        {
          // panel or tab setting
          $tabDefs [ strtoupper($panelID) ] = array();
          if ( isset($_REQUEST['tabDefs_'.$panelID.'_newTab']) )
          {
            $tabDefs [ strtoupper($panelID) ] [ 'newTab' ] = ( $_REQUEST['tabDefs_'.$panelID.'_newTab'] == '1' ) ? true : false;
            if ($tabDefs [ strtoupper($panelID) ] [ 'newTab' ] == true)
                $this->setUseTabs( true );
          }
          else
          {
            $tabDefs [ strtoupper($panelID) ] [ 'newTab' ] = false;
          }

          // collapse panel
          if ( isset($_REQUEST['tabDefs_'.$panelID.'_panelDefault']) )
          {
            $tabDefs [ strtoupper($panelID) ] [ 'panelDefault' ] = ( $_REQUEST['tabDefs_'.$panelID.'_panelDefault'] == 'collapsed' ) ? 'collapsed' : 'expanded';
          }
          else
          {
            $tabDefs [ strtoupper($panelID) ] [ 'panelDefault' ] = 'expanded';
          }

        }
        $this->setTabDefs($tabDefs);

    	//bug: 38232 - Set the sync detail and editview settings
        if (isset($_REQUEST['sync_detail_and_edit']))
        {
        	if ($_REQUEST['sync_detail_and_edit'] === false || $_REQUEST['sync_detail_and_edit'] === "false")
            {
        	   $this->setSyncDetailEditViews( false );
            }
            elseif(!empty($_REQUEST['sync_detail_and_edit']))
            {
        	   $this->setSyncDetailEditViews( true );
            }
        }

        $GLOBALS [ 'log' ]->debug ( print_r ( $this->_viewdefs [ 'panels' ], true ) ) ;

    }

    /*  Convert our internal format back to the standard Canonical MetaData layout
     *  First non-(empty) field goes in at column 0; all other (empty)'s removed
     *  Studio required fields are also added to the layout.
     *  Do this AFTER reading in all the $_REQUEST parameters as can't guarantee the order of those, and we need to operate on complete rows
     */
    protected function _convertToCanonicalForm ( $panels , $fielddefs )
    {
        $previousViewDef = $this->getFieldsFromLayout($this->implementation->getViewdefs ());
        $oldDefs = $this->implementation->getViewdefs ();
        $currentFields = $this->getFieldsFromLayout($this->_viewdefs);
        foreach($fielddefs as $field => $def)
        {
        	if (self::fieldIsRequired($def) && !isset($currentFields[$field]))
        	{
                //Use the previous viewdef if this field was on it.
                if (isset($previousViewDef[$field]))
                {
                    $def = $previousViewDef[$field];
                }
                //next see if the field was on the original layout.
                else if (isset ($this->_originalViewDef [ $field ]))
                {
                    $def = $this->_originalViewDef [ $field ] ;
                }
                //Otherwise make up a viewdef for it from field_defs
                else
                {
                    $def =  self::_trimFieldDefs( $def ) ;
                }
                $this->addField($def);
        	}
        }

        foreach ( $panels as $panelID => $panel )
        {
            // remove all (empty)s
            foreach ( $panel as $rowID => $row )
            {
                $startOfRow = true ;
                $offset = 0 ;
                foreach ( $row as $colID => $fieldname )
                {
                    if ($fieldname == MBConstants::$EMPTY[ 'name' ])
                    {
                        // if a leading (empty) then remove (by noting that remaining fields need to be shuffled along)
                        if ($startOfRow)
                        {
                            $offset ++ ;
                        }
                        unset ( $row [ $colID ] ) ;
                    } else
                    {
                        $startOfRow = false ;
                    }
                }

                // reindex to remove leading (empty)s and replace fieldnames by full definition from fielddefs
                $newRow = array ( ) ;
                foreach ( $row as $colID => $fieldname )
                {
                	if ($fieldname == null )
                	   continue;
                    //Backwards compatibility and a safeguard against multiple calls to _convertToCanonicalForm
                    if(is_array($fieldname))
                    {

                    	$newRow [ $colID - $offset ] = $fieldname;
                    	continue;
                    }else if(!isset($fielddefs[$fieldname])){
                       continue;
                     }

                	//Replace (filler) with the empty string
                	if ($fieldname == $this->FILLER[ 'name' ]) {
                        $newRow [ $colID - $offset ] = '' ;
                    }
                    //Use the previous viewdef if this field was on it.
					else if (isset($previousViewDef[$fieldname]))
                	{
                        $newRow[$colID - $offset] = $this->getNewRowItem($previousViewDef[$fieldname], $fielddefs[$fieldname]);
                	}
                    //next see if the field was on the original layout.
                    else if (isset ($this->_originalViewDef [ $fieldname ]))
                    {
                        $newRow[$colID - $offset] = $this->getNewRowItem($this->_originalViewDef[$fieldname], $fielddefs[$fieldname]);
                    }
                	//Otherwise make up a viewdef for it from field_defs
                	else if (isset ($fielddefs [ $fieldname ]))
                	{
                		$newRow [ $colID - $offset ] =  self::_trimFieldDefs( $fielddefs [ $fieldname ] ) ;

                	}
                	//No additional info on this field can be found, jsut use the name;
                	else
                	{
                        $newRow [ $colID - $offset ] = $fieldname;
                	}
	                if ($this->_view == MB_WIRELESSEDITVIEW && !empty($fielddefs [ $fieldname ]['calculated']))
			        {
			            if (is_array($newRow [ $colID - $offset ]))
			            {
			            	$newRow [ $colID - $offset ]['readOnly'] = true;
			            } else
			            {
			            	$newRow [ $colID - $offset ] = array('name' => $newRow [ $colID - $offset ],  'ReadOnly' => true);
			            }
			        }
                }
                $panels [ $panelID ] [ $rowID ] = $newRow ;
            }
        }

        return $panels ;
    }

    /*
     * fixing bug #44428: Studio | Tab Order causes layout errors
     * @param string|array $source it can be a string which contain just a name of field
     *                                  or an array with field attributes including name
     * @param array $fielddef stores field defs from request
     * @return string|array definition of new row item
     */
    function getNewRowItem($source, $fielddef)
    {
        //We should copy over the tabindex if it is set.
        $newRow = array();
        if (isset ($fielddef) && !empty($fielddef['tabindex']))
        {
            if (is_array($source))
            {
                $newRow = $source;
            }
            else
            {
                $newRow['name'] = $source;
            }
            $newRow['tabindex'] = $fielddef['tabindex'];
        }
        else
        {
            $newRow = $source;
        }
        return $newRow;
    }

    /*
     * Convert from the standard MetaData format to our internal format
     * Replace NULL with (filler) and missing entries with (empty)
     */
    protected function _convertFromCanonicalForm($panels)
    {
        if (empty ( $panels ))
            return ;

        // Fix for a flexibility in the format of the panel sections - if only one panel, then we don't have a panel level defined,
		// it goes straight into rows
        // See EditView2 for similar treatment
        if (! empty ( $panels ) && count ( $panels ) > 0)
        {
            $keys = array_keys ( $panels ) ;
            if (is_numeric ( $keys [ 0 ] ))
            {
                $defaultPanel = $panels ;
                unset ( $panels ) ; //blow away current value
                $panels [ 'default' ] = $defaultPanel ;
            }
        }

        $newPanels = array ( ) ;

        // replace '' with (filler)
        foreach ( $panels as $panelID => $panel )
        {
            foreach ( $panel as $rowID => $row )
            {
                $cols = 0;
            	foreach ( $row as $colID => $col )
                {
                    if ( ! empty ( $col ) )
                    {
                        if ( is_string ( $col ))
                        {
                            $fieldname = $col ;
                        } else if (! empty ( $col [ 'name' ] ))
                        {
                            $fieldname = $col [ 'name' ] ;
                        } else if (! empty ( $col [ 'field' ] ))
                        {
                            $fieldname = $col [ 'field' ] ;
                        } else {
                            $GLOBALS['log']->error("Could not find a valid field in " . var_export($col, true));
                            continue;
                        }
                    } else
                    {
                    	$fieldname = $this->FILLER['name'] ;
                    }

                    $newPanels [ $panelID ] [ $rowID ] [ $cols ] = $fieldname ;
                    $cols++;
                }
            }
        }

        // replace missing fields with (empty)
        foreach ( $newPanels as $panelID => $panel )
        {
            $column = 0 ;
            foreach ( $panel as $rowID => $row )
            {
                // pad between fields on a row
                foreach ( $row as $colID => $col )
                {
                    for ( $i = $column + 1 ; $i < $colID ; $i ++ )
                    {
                        $row [ $i ] = MBConstants::$EMPTY ['name'];
                    }
                    $column = $colID ;
                }
                // now pad out to the end of the row
                if (($column + 1) < $this->getMaxColumns ())
                { // last column is maxColumns-1
                    for ( $i = $column + 1 ; $i < $this->getMaxColumns () ; $i ++ )
                    {
                        $row [ $i ] = MBConstants::$EMPTY ['name'] ;
                    }
                }
                ksort ( $row ) ;
                $newPanels [ $panelID ] [ $rowID ] = $row ;
            }
        }

        return $newPanels ;
    }

    /**
     * Gets the panel defs from the viewdef array
     *
     * @param array $viewdef The view def array
     * @return array
     */
    protected function getPanelsFromViewDef($viewdef) {
        if (isset($viewdef['panels'])) {
    		$panels = $viewdef['panels'];
    	} else {
            $defs = MetaDataFiles::mapArrayToPath(MetaDataFiles::getViewDefVar($this->_view), $viewdef);
            $panels = isset($defs['panels']) ? $defs['panels'] : null;
    	}

        return $panels;
    }

    /**
     * Gets the fields from a layout
     *
     * @param array $viewdef The viewdef array
     * @return array
     */
    protected function getFieldsFromLayout($viewdef)
    {
        return $this->getFieldsFromPanels($this->getPanelsFromViewDef($viewdef));
    }

    /**
     * Get fields from panels in layout
     * @param array $panels
     * @return array
     */
    public function getFieldsFromPanels($panels)
    {
        $ret = array();
        if (is_array($panels))
        {
	        foreach ( $panels as $rows) {
	            foreach ($rows as $fields) {
	            	//wireless layouts have one less level of depth
	                if (is_array($fields) && isset($fields['name'])) {
	                	$ret[$fields['name']] = $fields;
	                	continue;
	                }
	                if (!is_array($fields)) {
	                	$ret[$fields] = $fields;
	                	continue;
	                }
	            	foreach ($fields as $field) {
	                    if (is_array($field)) {
	                        if(!empty($field['name'])) {
    	                        $ret[$field['name']] = $field;
	                        } else if(!empty($field['field'])) {
	                            $ret[$field['field']] = $field;
	                        }
	                    } else {
                            $ret[$field] = $field;
                        }
	                }
	            }
	        }
        }
        return $ret;
    }

    protected function fieldIsRequired($def)
    {
    	if (isset($def['studio']))
    	{
    		if (is_array($def['studio']))
    		{
    			if (!empty($def['studio'][$this->_view]) && $def['studio'][$this->_view] == "required")
    			{
    				return true;
    }
    			else if (!empty($def['studio']['required']) && $def['studio']['required'] == true)
    			{
    				return true;
    			}
    		}
    		else if ($def['studio'] == "required" ){
    		  return true;
    		}
    }
        return false;
    }

    public static function _trimFieldDefs(array $def)
	{
        $requiredProps = array(
            'studio' => true,
            'name' => true,
            'label' => true,
            'displayParams' => true,
            'comment' => true,
            'customCode' => true,
            'customLabel' => true,
            'tabindex' => true,
            'hideLabel' => true,
            'readonly' => true,
            'related_fields' => true,
        );
		$ret = array_intersect_key($def, $requiredProps);
        if (!empty($def['vname']) && empty($def['label']))
            $ret['label'] = $def['vname'];
		return $ret;
	}

	public function getUseTabs()
	{
        if (isset($this->_viewdefs  [ 'templateMeta' ]['useTabs']))
           return $this->_viewdefs  [ 'templateMeta' ]['useTabs'];

        return false;
    }

    public function setUseTabs($useTabs){
        $this->_viewdefs  [ 'templateMeta' ]['useTabs'] = $useTabs;
    }

    /**
     * Return whether the Detail & EditView should be in sync.
     */
	public function getSyncDetailEditViews(){
        if (isset($this->_viewdefs  [ 'templateMeta' ]['syncDetailEditViews']))
           return $this->_viewdefs  [ 'templateMeta' ]['syncDetailEditViews'];

        return false;
    }

    /**
     * Sync DetailView & EditView. This should only be set on the EditView
     * @param bool $syncViews
     */
    public function setSyncDetailEditViews($syncDetailEditViews){
        $this->_viewdefs  [ 'templateMeta' ]['syncDetailEditViews'] = $syncDetailEditViews;
    }

    /**
     * Getter function to get the implementation method which is a private variable
     * @return DeployedMetaDataImplementation
     */
    public function getImplementation(){
        return $this->implementation;
    }

    /**
     * Public access to _convertFromCanonicalForm
     * @param  $panels
     * @param  $fielddefs
     * @return array
     */
    public function convertFromCanonicalForm($panels)
    {
        return $this->_convertFromCanonicalForm($panels);
    }

     /**
     * Public access to _convertToCanonicalForm
     * @param  $panels
     * @param  $fielddefs
     * @return array
     */
    public function convertToCanonicalForm ( $panels , $fielddefs )
    {
        return $this->_convertToCanonicalForm ( $panels , $fielddefs );
    }


    /**
     * @return Array list of fields in this module that have the calculated property
     */
    public function getCalculatedFields() {
        $ret = array();
        foreach ($this->_fielddefs as $field => $def)
        {
            if(!empty($def['calculated']) && !empty($def['formula']))
            {
                $ret[] = $field;
            }
        }

        return $ret;
    }

    /**
     * @return Array fields in the given panel
     */
    public function getFieldsInPanel($targetPanel) {
        return iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($this->_viewdefs['panels'][$targetPanel])));
    }

    /**
     * Utility method that allows delegation of validation to child objects
     *
     * @param stirng $key The name of the field to check - used by child validators
     * @param array $def The field defs
     * @return bool
     */
    protected function isValidField($key, array $def)
    {
        return GridLayoutMetaDataParser::validField ( $def, $this->_view, $this->client );
    }

    /**
     * Removes a field from the available field array
     *
     * @param array $availableFields The available fields array
     * @param string $field The field name to remove
     */
    protected function unsetAvailableField(&$availableFields, $field)
    {
        unset($availableFields[$field]);
    }

    /**
     * Gets valid field defs for a field name
     *
     * @param string $fieldname The fieldname to get the defs for
     * @param boolean $withFieldset If true, will look for fieldset fields as well
     * @return array
     */
    protected function getViewDefFromFieldname($fieldname, $withFieldset = true)
    {
        $return = '';
        if (isset($this->_fielddefs[$fieldname])) {
            $return = self::_trimFieldDefs($this->_fielddefs[$fieldname]);
        } else if (isset($this->_originalViewDef[$fieldname]) && is_array($this->_originalViewDef[$fieldname])) {
            $return = self::_trimFieldDefs($this->_originalViewDef[$fieldname]);
        }

        if (empty($return)) {
            $return = array("name" => $fieldname, "label" => $fieldname);
        }

        if ($withFieldset) {
            $return = $this->getFieldsetInfo($return, $fieldname);
        }
        
        return $return;
    }

    /**
    * Checks any hidden panels (panels that should not be editable) and removes
    * any fields from the available fields array that are in the hidden panels.
    *
    * NOTE: At the moment this is a Sidecar parser function only. It is declared
    * here because it is called in getAvailableFields, which is also declared here.
    *
    * @param array $availableFields Current array of available fields
    */
    protected function unsetHiddenPanelFields(&$availableFields)
    {

    }

    /**
     * Gets fields from the fieldset array in a way that resembles how fields are
     * returned to the layouts.
     * 
     * @param array $fields The fieldset fields array
     * @return array
     */
    protected function getFieldsetFields(array $fields)
    {
        $return = array();
        foreach ($fields as $index => $field) {
            if (isset($field['name'])) {
                $return[$index]['name'] = $field['name'];
                
                if (isset($field['label'])) {
                    $return[$index]['label'] = $field['label'];
                } else {
                    // Get the label from the viewdefs or vardefs
                    $defs = $this->getViewDefFromFieldname($field['name'], false);
                    $return[$index]['label'] = $defs['label'];
                }
            }
        }
        return $return;
    }

    /**
     * Gets an array of the fieldset flag and fieldset fields where applicable
     * 
     * @param array $defs The current defs for a field
     * @param string $fieldname The name of the field to get fieldset info for
     * @return array The defs with fieldset info appended to it where applicable
     */
    protected function getFieldsetInfo($defs, $fieldname)
    {
        if (isset($this->_originalViewDef[$fieldname]) 
            && is_array($this->_originalViewDef[$fieldname])
            && isset($this->_originalViewDef[$fieldname]['type'])
            && $this->_originalViewDef[$fieldname]['type'] == 'fieldset') {
            $defs['fieldset'] = true;
            if (isset($this->_originalViewDef[$fieldname]['fields'])) {
                $defs['fieldset_fields'] = $this->getFieldsetFields($this->_originalViewDef[$fieldname]['fields']);
            }
        }
        
        return $defs;
    }
}
