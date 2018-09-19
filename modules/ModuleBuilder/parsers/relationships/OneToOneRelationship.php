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
 * Class to manage the metadata for a One-To-One Relationship
 * The LHS module will receive a new relate field to point back to the RHS
 * The RHS module will receive a new relate field to point back to the LHS
 * 
 * OOB modules implement One-To-One relationships as:
 * A Relationship of type one-to-one in one modules vardefs.php
 * A single link field in the same vardefs.php with 'relationship'= the relationship name, and 'link-type'='one', 'Module'=other side, and 'source'='non-db'
 * These are not common - examples are in InboundEmail and Schedulers, both pre-5.0 modules
 * InboundEmail:
 *      'created_by_link' => array (
            'name' => 'created_by_link',
            'type' => 'link',
            'relationship' => 'inbound_email_created_by',
            'vname' => 'LBL_CREATED_BY_USER',
            'link_type' => 'one',
            'module' => 'Users',
            'bean_name' => 'User',
            'source' => 'non-db',
        ),

 */

class OneToOneRelationship extends AbstractRelationship
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
     * @return array    An array of relationship metadata definitions
     */
    function buildRelationshipMetaData ()
    {
        return array( $this->lhs_module => $this->getRelationshipMetaData ( MB_ONETOONE ) ) ;
    }

    /* Build a set of Link Field definitions for this relationship
     * @return array    An array of field definitions, ready for the vardefs, keyed by module
     */
    function buildVardefs ( )
    {
        $vardefs = array ( ) ;
        $vardefs [ $this->rhs_module ] [] = $this->getLinkFieldDefinition ( $this->lhs_module, $this->relationship_name , false, 
            'LBL_' . strtoupper ( $this->relationship_name . '_FROM_' . $this->getLeftModuleSystemLabel() ) . '_TITLE' ,
            $this->relationship_only ? false : $this->getIDName( $this->lhs_module )
        ) ;
        $vardefs [ $this->lhs_module ] [] = $this->getLinkFieldDefinition ( $this->rhs_module, $this->relationship_name, false, 
            'LBL_' . strtoupper ( $this->relationship_name . '_FROM_' . $this->getRightModuleSystemLabel()   ) . '_TITLE'  ,
            $this->relationship_only ? false : $this->getIDName( $this->rhs_module )
        ) ;
        
        if (!$this->relationship_only)
        {
            $vardefs [ $this->lhs_module ] [] = $this->getRelateFieldDefinition ( $this->rhs_module, $this->relationship_name, $this->getRightModuleSystemLabel() ) ;
            $vardefs [ $this->rhs_module ] [] = $this->getRelateFieldDefinition ( $this->lhs_module, $this->relationship_name, $this->getLeftModuleSystemLabel() ) ;
            $vardefs [ $this->lhs_module ] [] = $this->getLink2FieldDefinition ( $this->rhs_module, $this->relationship_name , false, 
            'LBL_' . strtoupper ( $this->relationship_name . '_FROM_' . $this->getRightModuleSystemLabel()   ) . '_TITLE' ) ;
            $vardefs [ $this->rhs_module ] [] = $this->getLink2FieldDefinition ( $this->lhs_module, $this->relationship_name , false, 
            'LBL_' . strtoupper ( $this->relationship_name . '_FROM_' . $this->getLeftModuleSystemLabel() ) . '_TITLE' ) ;
        }
        
        return $vardefs ;
    }

    /*
     * Define what fields to add to which modules layouts
     * @return array    An array of module => fieldname
     */
    function buildFieldsToLayouts ()
    {
        if ($this->relationship_only)
            return array () ;
 
        if ($this->lhs_module == $this->rhs_module) // don't add in two fields on recursive relationships
            return array ( $this->lhs_module => $this->getValidDBName($this->relationship_name . "_name") );
        else
            return array (
                $this->lhs_module => $this->getValidDBName($this->relationship_name . "_name") ,
                $this->rhs_module => $this->getValidDBName($this->relationship_name . "_name")
            ) ;
    }

}

?>