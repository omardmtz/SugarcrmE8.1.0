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
 * This is an Adapter for the new UndeployedRelationships Class to allow ModuleBuilder to use the new class without change
 * As ModuleBuilder is updated, references to this MBRelationship class should be replaced by direct references to UndeployedRelationships
 */

class MBRelationship
{
    
    public $relatableModules = array ( ) ; // required by MBModule
    public $relationships = array ( ) ; // required by view.relationships.php; must be kept in sync with the implementation

    
    /*
     * Constructor
     * @param string $name      The name of this module (not used)
     * @param string $path      The base path of the module directory within the ModuleBuilder package directory
     * @param string $key_name  The Fully Qualified Name for this module - that is, $packageName_$name
     */
    public function __construct($name , $path , $key_name)
    {
        $this->implementation = new UndeployedRelationships ( $path ) ;
        $this->moduleName = $key_name ;
        $this->path = $path ;
        $this->updateRelationshipVariable();
    }

    function findRelatableModules ()
    {
        // do not call findRelatableModules in the constructor as it leads to an infinite loop if the implementation calls getPackage() which loads the packages which loads the module which findsRelatableModules which...
        $this->relatableModules = $this->implementation->findRelatableModules () ;
    }

    /*
     * Originally in 5.0 this method expected $_POST variables keyed in the "old" format - lhs_module, relate, msub, rsub etc
     * At 5.1 this has been changed to the "new" format of lhs_module, rhs_module, lhs_subpanel, rhs_subpanel, label
     * @return AbstractRelationship
     */
    function addFromPost ()
    {
        return $this->implementation->addFromPost () ;
    }

    /*
     * New function to replace the old MBModule subpanel property - now we obtain the 'subpanels' (actually related modules) from the relationships object
     */
    function getRelationshipList ()
    {
        return $this->implementation->getRelationshipList () ;
    }

    function get ($relationshipName)
    {
        return $this->implementation->get ( $relationshipName ) ;
    }

    /* Add a relationship to this set
     * Original MBRelationships could only support one relationship between this module and any other
     * @param array $rel    Relationship definition in the old format (defined by self::oldFormatKeys)
     */
    function add ($rel)
    {
        // convert old format definition to new format
        if (! isset ( $rel [ 'lhs_module' ] ))
            $rel [ 'lhs_module' ] = $this->moduleName ;
        $definition = AbstractRelationships::convertFromOldFormat ( $rel ) ;
        if (! isset ( $definition ['relationship_type']))
            $definition ['relationship_type'] = 'many-to-many';
        // get relationship object from RelationshipFactory
        $relationship = RelationshipFactory::newRelationship ( $definition ) ;
        // add relationship to the set of relationships
        $this->implementation->add ( $relationship ) ;
        $this->updateRelationshipVariable () ;
        return $relationship;
    }

    function delete ($name)
    {
        $this->implementation->delete ( $name ) ;
        $this->updateRelationshipVariable () ;
    }

    function save ()
    {
        $this->implementation->save () ;
    }

    function build ($path)
    {
        $this->implementation->build () ;
    }

    function addInstallDefs (&$installDef)
    {
        $this->implementation->addInstallDefs ( $installDef ) ;
    }

    /*
     * Transitional function to keep the public relationship variable in sync with the implementation master copy
     * We have to do this as various things refer directly to MBRelationship->relationships...
     */
    
    private function updateRelationshipVariable ()
    {
        foreach ( $this->implementation->getRelationshipList () as $relationshipName )
        {
            $rel = $this->implementation->getOldFormat ( $relationshipName ) ;
            $this->relationships [ $relationshipName ] = $rel ;
        }
    }

}

