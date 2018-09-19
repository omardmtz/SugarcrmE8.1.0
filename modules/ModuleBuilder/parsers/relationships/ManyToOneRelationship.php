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

/*
 * Class to manage the metadata for a many-To-one Relationship
 * Exactly the same as a one-to-many relationship except lhs and rhs modules have been reversed.
 */

class ManyToOneRelationship extends AbstractRelationship
{
	

    /*
     * Constructor
     * @param array $definition Parameters passed in as array defined in parent::$definitionKeys
     * The lhs_module value is for the One side; the rhs_module value is for the Many
     */
    function __construct ($definition) 
    {
        
    	parent::__construct ( $definition ) ;
    	$onetomanyDef = array_merge($definition, array(
	        'rhs_label'    => isset($definition['lhs_label'])    ? $definition['lhs_label']    : null,
	        'lhs_label'    => isset($definition['rhs_label'])    ? $definition['rhs_label']    : null,
	        'lhs_subpanel' => isset($definition['rhs_subpanel']) ? $definition['rhs_subpanel'] : null,
	        'rhs_subpanel' => isset($definition['lhs_subpanel']) ? $definition['lhs_subpanel'] : null,
	        'lhs_module'   => isset($definition['rhs_module'])   ? $definition['rhs_module']   : null,
	        'lhs_table'    => isset($definition['rhs_table'])    ? $definition['rhs_table']    : null,
	        'lhs_key'      => isset($definition['rhs_key'])      ? $definition['rhs_key']      : null,
	        'rhs_module'   => isset($definition['lhs_module'])   ? $definition['lhs_module']   : null,
	        'rhs_table'    => isset($definition['lhs_table'])    ? $definition['lhs_table']    : null,
	        'rhs_key'      => isset($definition['lhs_key'])      ? $definition['lhs_key']      : null,
	        'join_key_lhs' => isset($definition['join_key_rhs']) ? $definition['join_key_rhs'] : null,
	        'join_key_rhs' => isset($definition['join_key_lhs']) ? $definition['join_key_lhs'] : null,
	        'relationship_type' => MB_ONETOMANY,
        ));
        $this->one_to_many = new OneToManyRelationship($onetomanyDef);
    }

    /**
     * BUILD methods called during the build
     * @param bool $update Ignored
     */
    public function buildLabels($update = false)
    {
        return $this->one_to_many->buildLabels();
    }
    
    /*
     * Construct subpanel definitions
     * The format is that of TO_MODULE => relationship, FROM_MODULE, FROM_MODULES_SUBPANEL, mimicking the format in the layoutdefs.php
     * @return array    An array of subpanel definitions, keyed by the module
     */
    function buildSubpanelDefinitions ()
    {        
        return $this->one_to_many->buildSubpanelDefinitions();
    }

    function buildWirelessSubpanelDefinitions ()
    {
        return $this->one_to_many->buildWirelessSubpanelDefinitions();
    }

    /*
     * @return array    An array of field definitions, ready for the vardefs, keyed by module
     */
    function buildVardefs ( )
    {
       return $this->one_to_many->buildVardefs();
    }
    
    /*
     * Define what fields to add to which modules layouts
     * @return array    An array of module => fieldname
     */
    function buildFieldsToLayouts ()
    {
        if ($this->relationship_only)
            return array () ;
 
        return array( $this->lhs_module => $this->getValidDBName($this->relationship_name . "_name") ) ; // this must match the name of the relate field from buildVardefs
    }
       
    /*
     * @return array    An array of relationship metadata definitions
     */
    function buildRelationshipMetaData ()
    {
        return $this->one_to_many->buildRelationshipMetaData();
    }
    
    public function setName ($relationshipName)
    {
        parent::setName($relationshipName);
    	$this->one_to_many->setname($relationshipName);
    }

    public function setReadonly($set = true)
    {
        parent::setReadonly($set);
        $this->one_to_many->setReadonly($set);
    }
    
    public function delete ()
    {
        parent::delete();
    	$this->one_to_many->delete();
    }
    
    public function setRelationship_only ()
    {
        parent::setRelationship_only();
        $this->one_to_many->setRelationship_only();
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
?>