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
 * Class to manage the metadata for a Many-To-Many Relationship
 * The LHS (One) module will receive a new subpanel for the RHS module
 * The RHS (Many) module will receive a new subpanel for the RHS module
 * The subpanels get their data ('get_subpanel_data') from two link fields (one each) that reference a new Relationship
 * 
 * In OOB modules it's done the same way (e.g. cases_bugs)
 */

class ManyToManyRelationship extends AbstractRelationship
{

    /*
     * Constructor
     * @param array $definition Parameters passed in as array with keys defined in parent::keys
     */
    function __construct ($definition)
    {
        parent::__construct ( $definition ) ;
    }
  
    /*
     * BUILD methods called during the build
     */
    
    /*
     * Construct subpanel definitions
     * The format is that of TO_MODULE => relationship, FROM_MODULE, FROM_MODULES_SUBPANEL, mimicking the format in the layoutdefs.php
     * @return array    An array of subpanel definitions, keyed by module
     */
    function buildSubpanelDefinitions ()
    {        
        $subpanelDefinitions = array ( ) ;
        if (!$this->relationship_only)
        {
            $subpanelDefinitions [ $this->rhs_module ] = $this->getSubpanelDefinition ( $this->relationship_name, $this->lhs_module, $this->lhs_subpanel, $this->getLeftModuleSystemLabel() ) ;
            $subpanelDefinitions [ $this->lhs_module ] = $this->getSubpanelDefinition ( $this->relationship_name, $this->rhs_module, $this->rhs_subpanel, $this->getRightModuleSystemLabel() ) ;
        }
        return $subpanelDefinitions ;
    }

    function buildWirelessSubpanelDefinitions ()
    {

        $subpanelDefinitions = array ( ) ;
        if (!$this->relationship_only)
        {
            $subpanelDefinitions [ $this->rhs_module ] = $this->getWirelessSubpanelDefinition($this->relationship_name, $this->lhs_module, $this->lhs_subpanel, $this->getLeftModuleSystemLabel() ) ;
            $subpanelDefinitions [ $this->lhs_module ] = $this->getWirelessSubpanelDefinition($this->relationship_name, $this->rhs_module, $this->rhs_subpanel, $this->getRightModuleSystemLabel() ) ;
        }
        return $subpanelDefinitions ;
    }

    /*
     * @return array    An array of field definitions, ready for the vardefs, keyed by module
     */
    function buildVardefs ( )
    {
        $vardefs = array ( ) ;
        $vardefs [ $this->rhs_module ] [] = $this->getLinkFieldDefinition ( $this->lhs_module, $this->relationship_name, false, 
            'LBL_' . strtoupper ( $this->relationship_name . '_FROM_' . $this->getLeftModuleSystemLabel() ) . '_TITLE' ) ;
        $vardefs [ $this->lhs_module ] [] = $this->getLinkFieldDefinition ( $this->rhs_module, $this->relationship_name, false, 
            'LBL_' . strtoupper ( $this->relationship_name . '_FROM_' . $this->getRightModuleSystemLabel()  ) . '_TITLE' ) ;
        return $vardefs ;
    }
    
    /*
     * @return array    An array of relationship metadata definitions
     */
    function buildRelationshipMetaData ()
    {
        return array( $this->lhs_module => $this->getRelationshipMetaData ( MB_MANYTOMANY ) ) ;
    }

    public function buildSidecarSubpanelDefinitions()
    {
        return $this->buildSubpanelDefinitions();
    }

    public function buildSidecarMobileSubpanelDefinitions()
    {
        return $this->buildWirelessSubpanelDefinitions();
    }

}
